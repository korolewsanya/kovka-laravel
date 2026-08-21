<x-filament-panels::page>
    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow text-center">
        <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">
            🔄 Сброс демо-данных
        </h2>
        <p class="text-gray-500 dark:text-gray-400 mb-6">
            Нажмите кнопку, чтобы сбросить все данные к исходному состоянию.
        </p>
        <button 
            wire:click="resetDemo"
            onclick="return confirm('Внимание! Все изменения будут потеряны. Данные вернутся к исходному состоянию. Продолжить?')"
            class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg text-lg"
        >
            🔄 Сбросить демо-данные
        </button>
    </div>
</x-filament-panels::page>