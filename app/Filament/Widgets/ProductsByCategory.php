<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\ChartWidget;

class ProductsByCategory extends ChartWidget
{
    protected ?string $heading = 'Товары по категориям';  //убрали static

    protected function getData(): array
    {
        $categories = [
            'vorota' => 'Ворота',
            'zabor' => 'Заборы',
            'mangal' => 'Мангалы',
            'kozirek' => 'Козырьки',
            'lavo4ki' => 'Лавочки',
            'ogradki' => 'Оградки',
            'reshetki' => 'Решетки',
            'mebel' => 'Мебель',
            'melo4i' => 'Полезные мелочи',
            'other' => 'Другое',
        ];

        $data = [];
        $labels = [];

        foreach ($categories as $key => $label) {
            $count = Product::where('category', $key)->count();
            if ($count > 0) {
                $data[] = $count;
                $labels[] = $label;
            }
        }

        $colors = [
            '#f59e0b', '#ef4444', '#22c55e', '#3b82f6',
            '#8b5cf6', '#ec4899', '#14b8a6', '#f97316',
            '#6366f1', '#6b7280'
        ];

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}