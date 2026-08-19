<!DOCTYPE html>
<html lang="ru" data-theme="cupcake">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Кованые изделия')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen flex flex-col bg-base-200">
    @include('components.header')
    
    <main class="flex-grow container mx-auto px-4 py-8">
        @yield('content')
    </main>
    
    @include('components.footer')

    @livewireScripts    
</body>
</html>