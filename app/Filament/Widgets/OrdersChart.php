<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class OrdersChart extends ChartWidget
{
    protected ?string $heading = 'Динамика заказов';

    protected function getData(): array
    {
        $start = now()->startOfMonth();
        $end = now()->endOfMonth();

        $labels = [];
        $data = [];

        for ($date = $start->copy(); $date <= $end; $date->addDay()) {
            $day = $date->format('Y-m-d');
            $labels[] = $date->format('d.m');
            $data[] = Order::whereDate('created_at', $day)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Заказы',
                    'data' => $data,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
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