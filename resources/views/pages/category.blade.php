{{-- Берём общую рамку сайта: шапка, подвал, стили — всё оттуда --}}
@extends('layouts.app')

{{-- Заголовок вкладки браузера. Если пришло $categoryName — используем его,
     иначе — просто $category (slug вида "mangal", "vorota"). --}}
@section('title', $categoryName ?? $category)

{{-- Содержимое страницы: вставится в @yield('content') главного шаблона --}}
@section('content')

{{-- Шапка страницы: кнопка "На главную" и название категории --}}
<div class="flex items-center gap-4 mb-6">
    <a href="{{ route('home') }}" class="btn btn-ghost btn-sm">
        ← На главную
    </a>
    <h1 class="text-3xl font-bold">{{ $categoryName ?? $category }}</h1>
</div>

{{-- Подключаем Livewire-компонент, который рисует фильтр и список товаров.
     Через :category="$category" передаём slug категории внутрь компонента.
     Двоеточие в начале означает "это PHP-значение, а не просто строка". --}}
<livewire:category-filter :category="$category" />
@endsection
