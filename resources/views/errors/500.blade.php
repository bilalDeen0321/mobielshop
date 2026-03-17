<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Server Error - {{ config('app.name') }}</title>
    <style>
        body { font-family: system-ui, sans-serif; background:#f5f5f5; margin:0;
               display:flex; align-items:center; justify-content:center; min-height:100vh; }
        .box { background:#fff; padding:2rem; border-radius:8px; max-width:480px;
               text-align:center; box-shadow:0 1px 3px rgba(0,0,0,.1); }
        h1 { margin:0 0 .5rem; font-size:1.5rem; color:#333; }
        p { margin:0 0 1.5rem; color:#666; }
        a { color:#4db3a5; text-decoration:none; }
        a:hover { text-decoration:underline; }
    </style>
</head>
<body>
    <div class="box">
        <h1>Something went wrong</h1>
        <p>We’re sorry, the server encountered an error. Please try again later.</p>
        <a href="{{ url('/') }}">Return to home</a>
    </div>
</body>
</html>