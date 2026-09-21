<!DOCTYPE html>
{{-- Это Главный шаблон сайта. Все остальные страницы "вставляются" внутрь него. --}}
<html lang="ru" data-theme="cupcake">
<head>
    <meta charset="UTF-8">
    {{-- Чтобы сайт нормально выглядел на телефонах и планшетах --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Заголовок вкладки браузера. Если страница не задала свой — будет "Кованые изделия" --}}
    <title>@yield('title', 'Кованые изделия')</title>
    {{-- Подключаем CSS и JS, собранные Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Стили, которые нужны Livewire для работы --}}
    @livewireStyles
</head>
<body class="min-h-screen flex flex-col bg-base-200">
    @include('components.header')

    <main class="flex-grow container mx-auto px-4 py-8">
        {{-- сюда вставится основное содержимое из других.blade.php ...@section('content')--}}
        @yield('content')
    </main>

    @include('components.footer')

    {{-- Скрипты Livewire (нужны для "живых" компонентов) --}}
    @livewireScripts
</body>
</html>
