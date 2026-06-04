<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BackupFiles extends Command
{
    protected $signature = 'backup:files';

    protected $description = 'Create a backup of the files';

    protected $backupService;

    public function __construct(BackupService $backupService)
    {
        parent::__construct();
        $this->backupService = $backupService;
    }

    public function handle()
    {
        try {
            $filename = $this->backupService->createBackup();
            $this->info('Files backup created successfully: '.$filename);
            Log::info('Files backup created: '.$filename);
        } catch (\Exception $e) {
            $this->error('Failed to create files backup: '.$e->getMessage());
            Log::error('Files backup failed: '.$e->getMessage());
        }
    }
}
