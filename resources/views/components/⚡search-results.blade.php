<?php

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\Product;

new class extends Component
{
    #[Url]
    public $query = '';

    public function mount($query)
    {
        $this->query = $query;
    }

    public function render()
    {
        $products = Product::where('is_active', true)
            ->where(function($q) {
                $q->where('name', 'like', "%{$this->query}%")
                  ->orWhere('description', 'like', "%{$this->query}%")
                  ->orWhere('category', 'like', "%{$this->query}%");
            })
            ->get();

        return view('components.⚡search-results', [
            'products' => $products,
            'count' => $products->count()
        ]);
    }
};
?>

<div>
    <div class="text-sm mb-4 text-base-content/70">
        Найдено товаров: {{ $count }}
    </div>

    @if($count > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                <a href="{{ route('product', ['category' => $product->category, 'id' => $product->id]) }}" 
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
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </figure>
                    <div class="card-body p-4">
                        <h2 class="card-title text-base">{{ $product->name }}</h2>
                        <div class="text-sm text-base-content/60">
                            <span class="badge badge-ghost badge-xs">{{ $product->category }}</span>
                        </div>
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
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>По запросу "{{ $query }}" ничего не найдено</span>
        </div>
    @endif
</div>