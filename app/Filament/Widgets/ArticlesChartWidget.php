<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ArticlesChartWidget extends ChartWidget
{
    protected static ?int $sort = -3;

    protected static bool $isLazy = false;

    protected ?string $heading = 'दैनिक समाचार प्रकाशन ट्रेन्ड (Daily Publishing Trend)';

    protected ?string $description = 'विगतका दिनहरूमा प्रकाशित समाचार सामग्रीको उत्पादन विश्लेषण';

    protected ?string $maxHeight = '260px';

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'md' => 1,
        'xl' => 1,
    ];

    public ?string $filter = '7';

    protected function getFilters(): ?array
    {
        return [
            '7' => 'विगत ७ दिन (7 Days)',
            '14' => 'विगत १४ दिन (14 Days)',
            '30' => 'विगत ३० दिन (30 Days)',
        ];
    }

    protected function getData(): array
    {
        $days = (int) ($this->filter ?? 7);
        $labels = [];
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('M d');

            $count = Article::whereDate('published_at', $date->toDateString())
                ->orWhere(function ($query) use ($date) {
                    $query->whereNull('published_at')
                        ->whereDate('created_at', $date->toDateString());
                })
                ->count();

            $data[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'प्रकाशित समाचार (Articles)',
                    'data' => $data,
                    'fill' => 'start',
                    'borderColor' => '#0955cf',
                    'backgroundColor' => 'rgba(9, 85, 207, 0.12)',
                    'tension' => 0.35,
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
