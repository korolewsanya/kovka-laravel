<header class="navbar bg-base-100 shadow-lg sticky top-0 z-50 px-1 sm:px-4">
    {{-- Логотип слева --}}
    <div class="navbar-start w-1/4 max-sm:w-[33%]">
        <a href="/" class="flex items-center gap-1 sm:gap-2">
            <span class="text-xl sm:text-2xl font-bold text-primary">Ковка</span>
        </a>
    </div>

    {{-- Поиск по центру --}}
    <div class="navbar-center w-1/2 flex justify-center max-sm:w-[33%]">
        <div class="w-full max-w-xs max-sm:max-w-45">
            <livewire:search />
        </div>
    </div>

    {{-- Телефон справа --}}
    <div class="navbar-end w-1/4 flex justify-end max-sm:w-[33%]">
        <a href="tel:+79934456418" class="btn btn-primary btn-sm whitespace-nowrap px-1.5 sm:px-3 text-[11px] sm:text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
            <span class="hidden sm:inline">+7 993 445-64-18</span>
            <span class="sm:hidden">Звонок</span>
        </a>
    </div>
</header>
