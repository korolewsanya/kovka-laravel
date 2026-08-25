@extends('layouts.app')

@section('title', 'Результаты поиска: ' . request('q'))

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('home') }}" class="btn btn-ghost btn-sm">
            ← На главную
        </a>
        <h1 class="text-3xl font-bold">
            Результаты поиска: <span class="text-primary">"{{ request('q') }}"</span>
        </h1>
    </div>

    <livewire:search-results :query="request('q')" />
</div>
@endsection
