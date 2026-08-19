@extends('layouts.app')

@section('title', 'Заказ оформлен')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="card bg-base-100 shadow-xl text-center p-8">
        <div class="flex justify-center mb-6">
            <div class="w-24 h-24 bg-success rounded-full flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>

        <h1 class="text-3xl font-bold">Заказ оформлен!</h1>
        <p class="text-lg text-base-content/70 mt-2">Спасибо за ваш заказ!</p>

        <div class="divider">Детали заказа</div>

        <div class="text-left space-y-2">
            <div class="flex justify-between">
                <span class="font-semibold">Изделие</span>
                <span>{{ $order->product->name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold">Стоимость</span>
                <span>{{ number_format($order->price, 0, '.', ' ') }} ₽</span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold">Клиент</span>
                <span>{{ $order->customer_name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold">Телефон</span>
                <span>{{ $order->customer_phone }}</span>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('home') }}" class="btn btn-primary w-full">
                Вернуться на главную
            </a>
        </div>
    </div>
</div>
@endsection