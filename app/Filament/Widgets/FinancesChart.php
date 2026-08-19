<?php

namespace App\Filament\Widgets;

use App\Models\Finance;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class FinancesChart extends ChartWidget
{
    protected ?string $heading = 'Доходы и расходы';

    protected function getData(): array
    {
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();

        $days = [];
        $incomeData = [];
        $expenseData = [];
        $labels = [];

        for ($date = $start->copy(); $date <= $end; $date->addDay()) {
            $day = $date->format('Y-m-d');
            $labels[] = $date->format('d.m');

            $incomeData[] = Finance::whereDate('created_at', $day)->sum('income');
            $expenseData[] = Finance::whereDate('created_at', $day)->sum('expense');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Доходы',
                    'data' => $incomeData,
                    'borderColor' => '#22c55e',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                ],
                [
                    'label' => 'Расходы',
                    'data' => $expenseData,
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}