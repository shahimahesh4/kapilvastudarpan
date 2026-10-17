<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = '';

    public function getHeading(): string | Htmlable | null
    {
        return null;
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    public function getPageClasses(): array
    {
        return ['kd-compact-dashboard-page'];
    }
}
