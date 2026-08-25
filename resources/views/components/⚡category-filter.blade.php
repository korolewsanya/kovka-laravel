<?php

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\Product;

new class extends Component
{
    #[Url(as: 'price_min')]
    public $priceMin = '';

    #[Url(as: 'price_max')]
    public $priceMax = '';

    #[Url(as: 'length_min')]
    public $lengthMin = '';

    #[Url(as: 'length_max')]
    public $lengthMax = '';

    #[Url(as: 'width_min')]
    public $widthMin = '';

    #[Url(as: 'width_max')]
    public $widthMax = '';

    #[Url(as: 'height_min')]
    public $heightMin = '';

    #[Url(as: 'height_max')]
    public $heightMax = '';

    public $category;
    public $categoryName;

    public function mount($category)
    {
        $categoryNames = [
            'mangal' => 'Мангалы',
            'lavo4ki' => 'Лавочки',
            'kozirek' => 'Козырьки',
            'ogradki' => 'Оградки',
            'zabor' => 'Заборы',
            'vorota' => 'Ворота',
            'mebel' => 'Мебель',
            'reshetki' => 'Решетки',
            'melo4i' => 'Полезные мелочи',
        ];

        if (!isset($categoryNames[$category])) {
            abort(404, 'Категория не найдена');
        }

        $this->category = $category;
        $this->categoryName = $categoryNames[$category];
    }

    public function render()
    {
        $query = Product::where('category', $this->category);

        // Цена
        if ($this->priceMin !== '' && is_numeric($this->priceMin)) {
            $query->where('price', '>=', (float) $this->priceMin);
        }
        if ($this->priceMax !== '' && is_numeric($this->priceMax)) {
            $query->where('price', '<=', (float) $this->priceMax);
        }

        // Длина - только если поле заполнено
        if ($this->lengthMin !== '' && is_numeric($this->lengthMin)) {
            $query->whereRaw('CAST(REPLACE(REPLACE(length, " мм", ""), "мм", "") AS DECIMAL) >= ?', [(float) $this->lengthMin]);
        }
        if ($this->lengthMax !== '' && is_numeric($this->lengthMax)) {
            $query->whereRaw('CAST(REPLACE(REPLACE(length, " мм", ""), "мм", "") AS DECIMAL) <= ?', [(float) $this->lengthMax]);
        }

        // Ширина - только если поле заполнено
        if ($this->widthMin !== '' && is_numeric($this->widthMin)) {
            $query->whereRaw('CAST(REPLACE(REPLACE(width, " мм", ""), "мм", "") AS DECIMAL) >= ?', [(float) $this->widthMin]);
        }
        if ($this->widthMax !== '' && is_numeric($this->widthMax)) {
            $query->whereRaw('CAST(REPLACE(REPLACE(width, " мм", ""), "мм", "") AS DECIMAL) <= ?', [(float) $this->widthMax]);
        }

        // Высота - только если поле заполнено
        if ($this->heightMin !== '' && is_numeric($this->heightMin)) {
            $query->whereRaw('CAST(REPLACE(REPLACE(height, " мм", ""), "мм", "") AS DECIMAL) >= ?', [(float) $this->heightMin]);
        }
        if ($this->heightMax !== '' && is_numeric($this->heightMax)) {
            $query->whereRaw('CAST(REPLACE(REPLACE(height, " мм", ""), "мм", "") AS DECIMAL) <= ?', [(float) $this->heightMax]);
        }

        $products = $query->get();

        return view('components.⚡category-filter', [
            'products' => $products
        ]);
    }

    public function resetFilters()
    {
        $this->reset(['priceMin', 'priceMax', 'lengthMin', 'lengthMax', 
                      'widthMin', 'widthMax', 'heightMin', 'heightMax']);
    }
};
?>

<div>
    <div class="flex flex-col lg:flex-row gap-6">
        {{-- Фильтр --}}
        <div class="lg:w-72 shrink-0">
            <div class="card bg-base-100 shadow-xl sticky top-4">
                <div class="card-body p-4">
                    <h2 class="card-title text-lg">Фильтр</h2>
                    
                    {{-- Цена --}}
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Цена, ₽</span>
                        </label>
                        <div class="flex gap-2">
                            <input type="number" 
                                   wire:model.live.debounce.300ms="priceMin"
                                   placeholder="от" 
                                   class="input input-bordered input-sm w-full">
                            <input type="number" 
                                   wire:model.live.debounce.300ms="priceMax"
                                   placeholder="до" 
                                   class="input input-bordered input-sm w-full">
                        </div>
                    </div>

                    {{-- Длина --}}
                    <div class="form-control mt-4">
                        <label class="label">
                            <span class="label-text">Длина, мм</span>
                        </label>
                        <div class="flex gap-2">
                            <input type="number" 
                                   wire:model.live.debounce.300ms="lengthMin"
                                   placeholder="от" 
                                   class="input input-bordered input-sm w-full">
                            <input type="number" 
                                   wire:model.live.debounce.300ms="lengthMax"
                                   placeholder="до" 
                                   class="input input-bordered input-sm w-full">
                        </div>
                    </div>

                    {{-- Ширина --}}
                    <div class="form-control mt-4">
                        <label class="label">
                            <span class="label-text">Ширина, мм</span>
                        </label>
                        <div class="flex gap-2">
                            <input type="number" 
                                   wire:model.live.debounce.300ms="widthMin"
                                   placeholder="от" 
                                   class="input input-bordered input-sm w-full">
                            <input type="number" 
                                   wire:model.live.debounce.300ms="widthMax"
                                   placeholder="до" 
                                   class="input input-bordered input-sm w-full">
                        </div>
                    </div>

                    {{-- Высота --}}
                    <div class="form-control mt-4">
                        <label class="label">
                            <span class="label-text">Высота, мм</span>
                        </label>
                        <div class="flex gap-2">
                            <input type="number" 
                                   wire:model.live.debounce.300ms="heightMin"
                                   placeholder="от" 
                                   class="input input-bordered input-sm w-full">
                            <input type="number" 
                                   wire:model.live.debounce.300ms="heightMax"
                                   placeholder="до" 
                                   class="input input-bordered input-sm w-full">
                        </div>
                    </div>

                    <div class="flex gap-2 mt-4">
                        <button wire:click="resetFilters" class="btn btn-ghost btn-sm flex-1">
                            Сбросить все
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Товары --}}
        <div class="flex-1">
            <div class="text-sm mb-4 text-base-content/70">
                Найдено товаров: {{ $products->count() }}
            </div>

            @if($products->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <a href="{{ route('product', ['category' => $category, 'id' => $product->id]) }}" 
                           class="card bg-base-100 shadow-xl hover:shadow-2xl transition-shadow">
                            <figure class="h-56 bg-base-200 p-2 relative">
                                @if($product->is_custom)
                                    <span class="absolute top-2 right-2 badge badge-primary">На заказ</span>
                                @endif
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
                                @if($product->length || $product->width || $product->height)
                                    <div class="text-sm text-base-content/60">
                                        {{ $product->length ? $product->length : '' }}
                                        {{ $product->width ? '× '.$product->width : '' }}
                                        {{ $product->height ? '× '.$product->height : '' }}
                                    </div>
                                @endif
                                <p class="text-xl font-bold text-primary">
                                    {{ number_format($product->price, 0, '.', ' ') }} ₽
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">
                    <span>Товаров не найдено</span>
                </div>
            @endif
        </div>
    </div>
</div>