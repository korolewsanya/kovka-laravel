@extends('layouts.app')

@section('title', $categoryName ?? $category)

@section('content')
<div class="flex items-center gap-4 mb-6">
    <a href="{{ route('home') }}" class="btn btn-ghost btn-sm">
        ← На главную
    </a>
    <h1 class="text-3xl font-bold">{{ $categoryName ?? $category }}</h1>
</div>

{{-- Подключаем Livewire компонент с фильтрацией --}}
<livewire:category-filter :category="$category" />
@endsection