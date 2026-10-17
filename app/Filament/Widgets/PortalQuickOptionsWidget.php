<?php

namespace App\Filament\Widgets;

use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PortalQuickOptionsWidget extends Widget
{
    protected static ?int $sort = 2;

    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    protected string $view = 'filament.widgets.portal-quick-options';

    public function clearSystemCache(): void
    {
        try {
            Artisan::call('optimize:clear');
            Cache::flush();

            Notification::make()
                ->title('सफलतापूर्वक क्यास खाली भयो!')
                ->body('एप्लिकेसन, रुट र कन्फिग क्यास सफा गरियो।')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('त्रुटि')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function clearViewCache(): void
    {
        try {
            Artisan::call('view:clear');

            Notification::make()
                ->title('भ्यु क्यास खाली भयो!')
                ->body('कम्पाइल गरिएका ब्लेड भ्युहरू सफलतापूर्वक रिसेट गरियो।')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('त्रुटि')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function getSystemInfo(): array
    {
        $dbConnected = true;
        try {
            DB::connection()->getPdo();
        } catch (\Throwable $e) {
            $dbConnected = false;
        }

        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'db_status' => $dbConnected ? 'सक्रिय (Connected)' : 'निष्क्रिय (Disconnected)',
            'cache_driver' => config('cache.default', 'file'),
            'nepali_date' => nepali_date(),
        ];
    }
}
