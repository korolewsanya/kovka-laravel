<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\OrdersChart;
use App\Filament\Widgets\FinancesChart;
use App\Filament\Widgets\ProductsByCategory;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationLabel = 'Дашборд';
    protected static bool $shouldRegisterNavigation = false;

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,      // Карточки сверху
            OrdersChart::class,        // График заказов
            FinancesChart::class,      // График финансов
            ProductsByCategory::class, // Круговая диаграмма
        ];
    }
}