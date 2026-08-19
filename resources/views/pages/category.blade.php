@extends('layouts.app')

@section('title', $categoryName ?? $category)

@section('content')
<div class="flex items-center gap-4 mb-6">
    <a href="{{ route('home') }}" class="btn btn-ghost btn-sm">
        ← На главную
    </a>
    <h1 class="text-3xl font-bold">{{ $categoryName ?? $category }}</h1>
</div>

@if(isset($products) && $products->count())
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($products as $product)
            <a href="{{ route('product', ['category' => $category, 'id' => $product->id]) }}" 
               class="card bg-base-100 shadow-xl hover:shadow-2xl transition-shadow">
                <figure class="h-56 bg-base-200 p-2">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-contain">
                    @else
                        <div class="flex items-center justify-center w-full h-full text-base-300">
                            Нет фото
                        </div>
                    @endif
                </figure>
                <div class="card-body p-4">
                    <h2 class="card-title text-base">{{ $product->name }}</h2>
                    <p class="text-xl font-bold text-primary">{{ number_format($product->price, 0, '.', ' ') }} ₽</p>
                </div>
            </a>
        @endforeach
    </div>
@else
    <div class="alert alert-info">
        <span>Товаров в этой категории пока нет</span>
    </div>
@endif
@endsection