<?php
declare(strict_types=1);

use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

const TARGET_FIRMA_ID = 1041;
const DEFAULT_DALUX_URL = 'https://fm-api.dalux.com/api/2.0/external/workorders';

bootstrapEnv();

$errors = [];
$messages = [];
$importStats = null;
$nextBookmark = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['import_workorders'])) {
    $bookmark = isset($_POST['bookmark']) ? trim((string) $_POST['bookmark']) : '';
    $bookmark = $bookmark === '' ? null : $bookmark;

    try {
        $pdo = createPdo();
        $response = fetchDaluxWorkorders($bookmark);
        [$importStats, $nextBookmark] = importWorkorders($pdo, $response);

        $messages[] = sprintf(
            'Import completed. API returned %d records, inserted %d customers, inserted %d tasks, skipped %d existing tasks.',
            $importStats['totalRecords'],
            $importStats['insertedCustomers'],
            $importStats['insertedTasks'],
            $importStats['skippedExistingTasks']
        );
    } catch (Throwable $e) {
        $errors[] = $e->getMessage();
    }
}

function bootstrapEnv(): void
{
    $rootDir = dirname(__DIR__);
    $envPath = $rootDir . '/.env';

    if (class_exists(Dotenv::class) && file_exists($envPath)) {
        Dotenv::createImmutable($rootDir)->safeLoad();
    }
}

function envValue(string $key, ?string $default = null): ?string
{
    if (array_key_exists($key, $_ENV)) {
        return (string) $_ENV[$key];
    }

    if (array_key_exists($key, $_SERVER)) {
        return (string) $_SERVER[$key];
    }

    $value = getenv($key);
    if ($value !== false) {
        return (string) $value;
    }

    return $default;
}

function createPdo(): PDO
{
    $connection = envValue('DB_CONNECTION', 'mysql');
    if ($connection !== 'mysql') {
        throw new RuntimeException('Only MySQL DB_CONNECTION is supported by this importer.');
    }

    $host = envValue('DB_HOST', '127.0.0.1');
    $port = envValue('DB_PORT', '3306');
    $database = envValue('DB_DATABASE');
    $username = envValue('DB_USERNAME');
    $password = envValue('DB_PASSWORD', '');

    if ($database === null || $username === null) {
        throw new RuntimeException('Missing DB_DATABASE or DB_USERNAME in environment.');
    }

    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $database);

    return new PDO(
        $dsn,
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
}

function fetchDaluxWorkorders(?string $bookmark): array
{
    $url = envValue('DALUX_WORKORDERS_URL', DEFAULT_DALUX_URL) ?: DEFAULT_DALUX_URL;
    if ($bookmark !== null) {
        $separator = str_contains($url, '?') ? '&' : '?';
        $url .= $separator . 'bookmark=' . rawurlencode($bookmark);
    }

    $token = envValue('DALUX_API_TOKEN');
    $apiKey = envValue('DALUX_API_KEY');
    $customAuthHeader = envValue('DALUX_AUTH_HEADER');

    if ($token === null && $apiKey === null && $customAuthHeader === null) {
        throw new RuntimeException(
            'Dalux credentials are missing. Configure one of DALUX_API_TOKEN, DALUX_API_KEY, or DALUX_AUTH_HEADER in .env.'
        );
    }

    $headers = ['Accept: application/json'];
    if ($customAuthHeader !== null && $customAuthHeader !== '') {
        $headers[] = $customAuthHeader;
    }
    if ($token !== null && $token !== '') {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    if ($apiKey !== null && $apiKey !== '') {
        $headers[] = 'X-Api-Key: ' . $apiKey;
    }

    $ch = curl_init($url);
    if ($ch === false) {
        throw new RuntimeException('Unable to initialize cURL.');
    }

    curl_setopt_array(
        $ch,
        [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_CONNECTTIMEOUT => 10,
        ]
    );

    $body = curl_exec($ch);
    if ($body === false) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new RuntimeException('Dalux API request failed: ' . $error);
    }

    $statusCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($statusCode < 200 || $statusCode >= 300) {
        throw new RuntimeException('Dalux API returned HTTP ' . $statusCode . '. Body: ' . $body);
    }

    $decoded = json_decode($body, true);
    if (!is_array($decoded)) {
        throw new RuntimeException('Dalux API response is not valid JSON.');
    }

    return $decoded;
}

/**
 * @return array{0: array<string, int>, 1: ?string}
 */
function importWorkorders(PDO $pdo, array $response): array
{
    $items = $response['items'] ?? null;
    if (!is_array($items)) {
        throw new RuntimeException('Dalux response does not contain an "items" array.');
    }

    $nextBookmark = null;
    if (isset($response['metadata']['nextBookmark'])) {
        $nextBookmark = (string) $response['metadata']['nextBookmark'];
    }

    $selectCustomerStmt = $pdo->prepare(
        'SELECT `fldKundeID` FROM `tbl_kunder` WHERE `fldFirmaID` = :firmaId AND `fldEmail` = :email LIMIT 1'
    );
    $insertCustomerStmt = $pdo->prepare(
        'INSERT INTO `tbl_kunder` (`fldFirmaID`, `fldNavn`, `fldEmail`) VALUES (:firmaId, :name, :email)'
    );

    $selectTaskStmt = $pdo->prepare(
        'SELECT 1 FROM `tbl_hard_tjenester_opgave` WHERE `fldFirmaID` = :firmaId AND `fldTaskerID` = :taskId LIMIT 1'
    );
    $insertTaskStmt = $pdo->prepare(
        'INSERT INTO `tbl_hard_tjenester_opgave` (`fldFirmaID`, `fldTaskerID`, `fldKundeID`, `fldKommentar`, `description`)
         VALUES (:firmaId, :taskId, NULL, :comment, :description)'
    );

    $stats = [
        'totalRecords' => count($items),
        'insertedCustomers' => 0,
        'insertedTasks' => 0,
        'skippedExistingTasks' => 0,
    ];

    $pdo->beginTransaction();

    try {
        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }

            $data = $item['data'] ?? null;
            if (!is_array($data)) {
                continue;
            }

            $email = trim((string) ($data['responsibleUserEmail'] ?? ''));
            if ($email === '') {
                $email = trim((string) ($data['contactPersonEmail'] ?? ''));
            }

            if ($email !== '') {
                $selectCustomerStmt->execute(
                    [
                        ':firmaId' => TARGET_FIRMA_ID,
                        ':email' => $email,
                    ]
                );

                if (!$selectCustomerStmt->fetch()) {
                    $insertCustomerStmt->execute(
                        [
                            ':firmaId' => TARGET_FIRMA_ID,
                            ':name' => $email,
                            ':email' => $email,
                        ]
                    );
                    $stats['insertedCustomers']++;
                }
            }

            $workOrderId = trim((string) ($data['workOrderId'] ?? ''));
            if ($workOrderId === '') {
                continue;
            }

            $selectTaskStmt->execute(
                [
                    ':firmaId' => TARGET_FIRMA_ID,
                    ':taskId' => $workOrderId,
                ]
            );

            if ($selectTaskStmt->fetch()) {
                $stats['skippedExistingTasks']++;
                continue;
            }

            $insertTaskStmt->execute(
                [
                    ':firmaId' => TARGET_FIRMA_ID,
                    ':taskId' => $workOrderId,
                    ':comment' => '',
                    ':description' => (string) ($data['description'] ?? ''),
                ]
            );
            $stats['insertedTasks']++;
        }

        $pdo->commit();
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        throw $e;
    }

    return [$stats, $nextBookmark];
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hard Tjenester Opgave Import</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; }
        .container { max-width: 860px; }
        .card { border: 1px solid #ddd; border-radius: 8px; padding: 16px; margin-bottom: 16px; }
        .btn { background: #0052cc; color: #fff; border: 0; padding: 10px 14px; border-radius: 6px; cursor: pointer; }
        .btn:hover { background: #003f9f; }
        .error { color: #b00020; margin: 0 0 8px; }
        .ok { color: #006b3c; margin: 0 0 8px; }
        code { background: #f4f4f4; padding: 2px 5px; border-radius: 4px; }
        ul { margin-top: 8px; }
    </style>
</head>
<body>
<div class="container">
    <h1>Dalux Work Order Import</h1>

    <div class="card">
        <p>This page imports work orders from <code><?= htmlspecialchars(DEFAULT_DALUX_URL, ENT_QUOTES, 'UTF-8') ?></code>.</p>
        <ul>
            <li>Creates customer in <code>tbl_kunder</code> with <code>fldFirmaID=1041</code>, <code>fldNavn=email</code>, <code>fldEmail=email</code>.</li>
            <li>Creates task in <code>tbl_hard_tjenester_opgave</code> with <code>fldFirmaID=1041</code>, <code>fldTaskerID=workOrderId</code>, <code>fldKundeID=NULL</code>, <code>fldKommentar=''</code>, <code>description=API description</code>.</li>
            <li>If API returns <code>nextBookmark</code>, click import again to load more.</li>
        </ul>
    </div>

    <?php foreach ($errors as $error): ?>
        <p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endforeach; ?>

    <?php foreach ($messages as $message): ?>
        <p class="ok"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endforeach; ?>

    <div class="card">
        <form method="post">
            <input type="hidden" name="bookmark" value="<?= htmlspecialchars($nextBookmark ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <button class="btn" type="submit" name="import_workorders" value="1">
                <?= $nextBookmark !== null ? 'Import next page (bookmark ' . htmlspecialchars($nextBookmark, ENT_QUOTES, 'UTF-8') . ')' : 'Import first page (100 records)' ?>
            </button>
        </form>
    </div>

    <?php if (is_array($importStats)): ?>
        <div class="card">
            <h3>Last import summary</h3>
            <ul>
                <li>API records: <?= (int) $importStats['totalRecords'] ?></li>
                <li>Inserted customers: <?= (int) $importStats['insertedCustomers'] ?></li>
                <li>Inserted tasks: <?= (int) $importStats['insertedTasks'] ?></li>
                <li>Skipped existing tasks: <?= (int) $importStats['skippedExistingTasks'] ?></li>
                <li>Next bookmark: <?= $nextBookmark !== null ? htmlspecialchars($nextBookmark, ENT_QUOTES, 'UTF-8') : 'none' ?></li>
            </ul>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
