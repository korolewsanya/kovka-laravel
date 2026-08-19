<?php

use Livewire\Component;

new class extends Component
{
    public function render()
    {
        $categories = [
            'mangal' => ['name' => 'Мангалы', 'image' => 'Мангал_обработано.png'],
            'lavo4ki' => ['name' => 'Лавочки', 'image' => 'Лавочки.jpg'],
            'kozirek' => ['name' => 'Козырьки', 'image' => 'Козырек.png'],
            'ogradki' => ['name' => 'Оградки', 'image' => 'Оградки.png'],
            'zabor' => ['name' => 'Заборы', 'image' => 'Забор1.jpg'],
            'vorota' => ['name' => 'Ворота', 'image' => 'Ворота.png'],
            'mebel' => ['name' => 'Мебель', 'image' => 'Кованная мебель_обработано.png'],
            'reshetki' => ['name' => 'Решетки', 'image' => 'Решетки на окна_обработано.png'],
            'melo4i' => ['name' => 'Полезные мелочи', 'image' => 'Полезные мелочи.png'],
        ];

        return view('components.⚡home-page', ['categories' => $categories]);
    }
};
?>

<div>
    <!-- Hero-блок с 3 картинками во всю ширину -->
    <div class="hero bg-base-200 rounded-2xl p-0.5 mb-0.5">
        <div class="hero-content flex-col w-full p-0">
            <!-- 3 картинки в ряд на всю ширину (только на десктопе) -->
            <div class="hidden md:grid grid-cols-3 gap-2 w-full">
                <img src="{{ asset('storage/products/Набор кованных элементов.png') }}" 
                     alt="Ворота" 
                     class="w-full h-32 object-contain bg-base-100">
                <img src="{{ asset('storage/products/Надпись.png') }}" 
                     alt="Мангалы" 
                     class="w-full h-32 object-contain bg-base-100">
                <img src="{{ asset('storage/products/Кованные изделия.png') }}" 
                     alt="Мебель" 
                     class="w-full h-32 object-contain bg-base-100">
            </div>
            
            <!-- Текст снизу -->
            <div class="text-center py-6 px-4 w-full">
                <p class="py-6 text-lg max-w-2xl mx-auto">Изготовление на заказ. Индивидуальный подход. Качество ручной работы.</p>
            </div>
        </div>
    </div>

    <!-- Категории -->
    <div id="catalog" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($categories as $slug => $cat)
            <a href="{{ route('category', $slug) }}" 
               class="card bg-base-100 shadow-xl hover:shadow-2xl transition-shadow duration-300 hover:-translate-y-1">
                <figure class="h-48 bg-base-200">
                    <img src="{{ asset('storage/products/' . $cat['image']) }}" 
                         alt="{{ $cat['name'] }}" 
                         class="w-full h-full object-cover"
                         loading="lazy">
                </figure>
                <div class="card-body items-center text-center p-4">
                    <h2 class="card-title text-base">{{ $cat['name'] }}</h2>
                </div>
            </a>
        @endforeach
    </div>

    <!-- Индивидуальный заказ -->
    <div class="text-center mt-12 p-8 bg-primary/10 rounded-2xl">
        <h2 class="text-3xl font-bold text-primary">Сделаем по индивидуальному заказу</h2>
        <p class="text-lg mt-2 text-base-content/70">Любые размеры, формы и дизайн по вашему желанию</p>
        <a href="tel:+79001316418" class="btn btn-primary mt-4">Связаться с нами</a>
    </div>
</div>