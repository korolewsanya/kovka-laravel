<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\Finance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array  // ← нет static
    {
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalIncome = Finance::sum('income');
        $totalExpense = Finance::sum('expense');
        $profit = $totalIncome - $totalExpense;

        return [
            Stat::make('Всего заказов', $totalOrders)
                ->description('За все время')
                ->icon('heroicon-o-shopping-cart')
                ->color('success'),

            Stat::make('Всего товаров', $totalProducts)
                ->description('В каталоге')
                ->icon('heroicon-o-shopping-bag')
                ->color('info'),

            Stat::make('Доходы', number_format($totalIncome, 0, '.', ' ') . ' ₽')
                ->description('Всего доходов')
                ->icon('heroicon-o-arrow-up-circle')
                ->color('success'),

            Stat::make('Расходы', number_format($totalExpense, 0, '.', ' ') . ' ₽')
                ->description('Всего расходов')
                ->icon('heroicon-o-arrow-down-circle')
                ->color('danger'),

            Stat::make('Прибыль', number_format($profit, 0, '.', ' ') . ' ₽')
                ->description($profit >= 0 ? 'Прибыль' : 'Убыток')
                ->icon('heroicon-o-banknotes')
                ->color($profit >= 0 ? 'success' : 'danger'),
        ];
    }
}