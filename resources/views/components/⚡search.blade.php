<?php

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\Product;

new class extends Component
{
    #[Url(as: 'q')]
    public $query = '';

    public $results = [];
    public $showResults = false;
    public $totalCount = 0;

    public function updatedQuery()
    {
        if (strlen($this->query) >= 2) {
            // Получаем ТОЛЬКО 5 для подсказок
            $this->results = Product::where('is_active', true)
                ->where('name', 'like', "%{$this->query}%")
                ->orWhere('description', 'like', "%{$this->query}%")
                ->limit(10)
                ->get();

            // Считаем ВСЕ результаты
            $this->totalCount = Product::where('is_active', true)
                ->where('name', 'like', "%{$this->query}%")
                ->orWhere('description', 'like', "%{$this->query}%")
                ->count();

            $this->showResults = true;
        } else {
            $this->results = collect();
            $this->totalCount = 0;
            $this->showResults = false;
        }
    }

    public function search()
    {
        if ($this->query !== '') {
            return redirect()->route('search', ['q' => $this->query]);
        }
    }

    public function render()
    {
        return view('components.⚡search');
    }
};
?>

<div class="relative" x-data="{ show: $wire.showResults }"
     @click.away="show = false">

    <form wire:submit.prevent="search" class="flex">
        <input type="text"
               wire:model.live.debounce.300ms="query"
               placeholder="Поиск..."
               class="input input-bordered w-full text-sm h-9 pr-8"
               x-on:focus="show = true"
               x-on:blur="setTimeout(() => show = false, 200)"
               x-on:input="show = true">

        <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2">
            <svg class="h-4 w-4 text-base-content/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>
    </form>

    {{-- Результаты --}}
    @if($showResults && count($results) > 0)
        <div class="absolute top-full left-0 right-0 mt-1 bg-base-100 rounded-lg shadow-xl border z-50"
             x-show="show"
             x-transition>
            @foreach($results as $product)
                <a href="{{ route('product', ['category' => $product->category, 'id' => $product->id]) }}"
                   class="flex items-center gap-2 p-2 hover:bg-base-200 transition-colors"
                   @click="show = false">
                    <img src="{{ asset('storage/' . $product->image) }}"
                         alt="{{ $product->name }}"
                         class="w-8 h-8 object-cover rounded bg-base-200">
                    <div>
                        <div class="text-sm truncate max-w-[150px]">{{ $product->name }}</div>
                        <div class="text-xs text-primary font-bold">{{ number_format($product->price, 0, '.', ' ') }} ₽</div>
                    </div>
                </a>
            @endforeach
            <div class="p-1 border-t">
                <button wire:click="search" class="btn btn-ghost btn-xs w-full text-xs">
                    Все результаты ({{ $totalCount }})
                </button>
            </div>
        </div>
    @endif
</div>
