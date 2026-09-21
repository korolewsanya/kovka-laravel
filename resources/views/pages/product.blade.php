{{-- Берём общую рамку сайта: шапка, подвал, стили — всё оттуда --}}
@extends('layouts.app')

{{-- Заголовок вкладки браузера = название товара --}}
@section('title', $product->name)

{{-- Содержимое страницы: вставится в @yield('content') главного шаблона --}}
@section('content')
{{-- Раскладка из двух колонок на больших экранах: слева фото, справа инфо + форма --}}
<div class="grid md:grid-cols-2 gap-8">

{{-- ЛЕВАЯ КОЛОНКА: фото товара --}}
    <div class="card bg-base-100 shadow-xl">
        <figure class="h-[400px] bg-base-200 p-4">
            {{-- Если у товара есть картинка — показываем её --}}
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
    </div>

    {{-- ПРАВАЯ КОЛОНКА: название, размеры, цена и форма заказа --}}
    <div class="card bg-base-100 shadow-xl p-6">

        {{-- Название товара --}}
        <h1 class="text-3xl font-bold">{{ $product->name }}</h1>

        {{-- Горизонтальная линия-разделитель --}}
        <div class="divider"></div>

        {{-- Размеры. Каждую строку показываем ТОЛЬКО если поле заполнено --}}
        <div class="space-y-3">
            @if($product->length)
                <div class="flex justify-between">
                    <span class="font-semibold">Длина</span>
                    <span>{{ $product->length }}</span>
                </div>
            @endif
            @if($product->width)
                <div class="flex justify-between">
                    <span class="font-semibold">Ширина</span>
                    <span>{{ $product->width }}</span>
                </div>
            @endif
            @if($product->height)
                <div class="flex justify-between">
                    <span class="font-semibold">Высота</span>
                    <span>{{ $product->height }}</span>
                </div>
            @endif
        </div>

        <div class="divider"></div>

        <div class="text-3xl font-bold text-primary text-center">
            {{ number_format($product->price, 0, '.', ' ') }} ₽
        </div>

        <div class="divider"></div>

        {{-- ФОРМА ЗАКАЗА. Отправляется POST-запросом на маршрут 'order.store' --}}
        <form action="{{ route('order.store') }}" method="POST" class="space-y-4">
            {{-- Защитный токен безопасности --}}
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Ваше имя *</span>
                </label>
                <input type="text" name="name" class="input input-bordered" required>
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Телефон *</span>
                </label>
                <input type="tel" name="tel" class="input input-bordered" placeholder="+7 (900) 131-64-18" required>
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Email</span>
                </label>
                <input type="email" name="email" class="input input-bordered">
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Комментарий</span>
                </label>
                <textarea name="coment" class="textarea textarea-bordered" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-full text-lg">
                Отправить заказ
            </button>
        </form>

        <div class="mt-4 text-center">
            <a href="{{ route('category', $category) }}" class="btn btn-ghost btn-sm">
                ← Вернуться к списку
            </a>
        </div>
    </div>
</div>
@endsection
