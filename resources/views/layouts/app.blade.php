<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LowPricePhones')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { display: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: {
                        primary: '#00b4d8',
                        accent: '#f59e0b',
                        nav: '#0f172a',
                    }
                }
            }
        }
    </script>
    <style>
        .product-card-hover:hover { transform: translateY(-4px); box-shadow: 0 12px 24px -12px rgb(0 180 216 / 0.2); }
    </style>
    @stack('head')
</head>
<body class="min-h-screen bg-gray-50 flex flex-col">
    @include('layouts.partials.topbar')
    @include('layouts.partials.header')
    @include('layouts.partials.category-nav')
    <main class="flex-1">
        @if(session('success'))
            <div class="bg-green-100 text-green-800 text-sm text-center py-2">{{ session('success') }}</div>
        @endif
        @if(isset($errors) && $errors->any())
            <div class="bg-red-100 text-red-800 text-sm p-4 container mx-auto">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </main>
    @include('layouts.partials.footer')
    @stack('scripts')
</body>
</html>
