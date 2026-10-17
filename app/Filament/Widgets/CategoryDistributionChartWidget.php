<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\ChartWidget;

class CategoryDistributionChartWidget extends ChartWidget
{
    protected static ?int $sort = -2;

    protected static bool $isLazy = false;

    protected ?string $heading = 'विधागत समाचार वितरण (Category Breakdown)';

    protected ?string $description = 'प्रमुख समाचार विधाहरूमा सामग्रीहरूको अनुपात';

    protected ?string $maxHeight = '260px';

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'md' => 1,
        'xl' => 1,
    ];

    protected function getData(): array
    {
        $categories = Category::withCount('articles')
            ->orderByDesc('articles_count')
            ->limit(7)
            ->get();

        $labels = $categories->pluck('name')->toArray();
        $data = $categories->pluck('articles_count')->toArray();

        $colors = [
            '#0955cf', // Royal Blue
            '#c92137', // Brand Crimson
            '#10b981', // Emerald
            '#f59e0b', // Amber
            '#8b5cf6', // Violet
            '#06b6d4', // Cyan
            '#ec4899', // Pink
        ];

        return [
            'datasets' => [
                [
                    'label' => 'समाचार सङ्ख्या',
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
