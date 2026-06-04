<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database';

    protected $description = 'Create a backup of the database';

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
            $this->info('Database backup created successfully: '.$filename);
            Log::info('Database backup created: '.$filename);
        } catch (\Exception $e) {
            $this->error('Failed to create database backup: '.$e->getMessage());
            Log::error('Database backup failed: '.$e->getMessage());
        }
    }
}
