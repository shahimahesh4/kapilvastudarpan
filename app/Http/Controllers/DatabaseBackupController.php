<?php

namespace App\Http\Controllers;

use App\Services\DatabaseBackupService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DatabaseBackupController extends Controller
{
    public function __construct(
        protected DatabaseBackupService $backupService
    ) {}

    /**
     * Export and download database.sql for administrators.
     */
    public function export(Request $request): StreamedResponse
    {
        $user = $request->user();

        // Security check: Strictly ONLY Super Admin can download the full database backup
        if (! $user || ! $user->isSuperAdmin()) {
            abort(403, 'Unauthorized access to database export. Only Super Admin can download database backups.');
        }

        // Determine filename
        $nameOption = $request->query('filename', 'database.sql');
        if ($nameOption === 'timestamped') {
            $filename = 'kapilvastudarpan_database_' . now()->format('Y_m_d_His') . '.sql';
        } else {
            $filename = 'database.sql';
        }

        return $this->backupService->downloadBackup($filename);
    }
}
