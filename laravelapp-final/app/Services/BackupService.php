<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class BackupService
{
    protected $backupPath;

    protected $maxBackups;

    public function __construct()
    {
        $this->backupPath = storage_path('app/backups');
        $this->maxBackups = config('backup.max_backups', 5);
    }

    public function createBackup()
    {
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $backupName = "backup_{$timestamp}";
        $backupPath = "{$this->backupPath}/{$backupName}";

        // Create backup directory
        if (! File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        // Backup database
        $this->backupDatabase($backupPath);

        // Backup files
        $this->backupFiles($backupPath);

        // Create zip archive
        $zipPath = $this->createZipArchive($backupPath, $backupName);

        // Clean up temporary files
        File::deleteDirectory($backupPath);

        // Clean old backups
        $this->cleanOldBackups();

        return $zipPath;
    }

    protected function backupDatabase($backupPath)
    {
        $filename = "{$backupPath}/database.sql";
        $command = sprintf(
            'mysqldump -u%s -p%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            $filename
        );

        exec($command);
    }

    protected function backupFiles($backupPath)
    {
        // Backup storage files
        $storagePath = storage_path('app/public');
        if (File::exists($storagePath)) {
            File::copyDirectory($storagePath, "{$backupPath}/storage");
        }

        // Backup uploads
        $uploadsPath = public_path('uploads');
        if (File::exists($uploadsPath)) {
            File::copyDirectory($uploadsPath, "{$backupPath}/uploads");
        }
    }

    protected function createZipArchive($backupPath, $backupName)
    {
        $zip = new ZipArchive;
        $zipPath = "{$this->backupPath}/{$backupName}.zip";

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $files = File::allFiles($backupPath);

            foreach ($files as $file) {
                $relativePath = $file->getRelativePathname();
                $zip->addFile($file->getPathname(), $relativePath);
            }

            $zip->close();

            return $zipPath;
        }

        throw new \Exception('Failed to create zip archive');
    }

    protected function cleanOldBackups()
    {
        $files = collect(File::files($this->backupPath))
            ->filter(function ($file) {
                return $file->getExtension() === 'zip';
            })
            ->sortByDesc(function ($file) {
                return $file->getMTime();
            });

        if ($files->count() > $this->maxBackups) {
            $files->skip($this->maxBackups)->each(function ($file) {
                File::delete($file->getPathname());
            });
        }
    }

    public function restoreBackup($backupPath)
    {
        if (! File::exists($backupPath)) {
            throw new \Exception('Backup file not found');
        }

        $tempPath = storage_path('app/temp_restore');
        if (File::exists($tempPath)) {
            File::deleteDirectory($tempPath);
        }
        File::makeDirectory($tempPath);

        // Extract zip file
        $zip = new ZipArchive;
        if ($zip->open($backupPath) === true) {
            $zip->extractTo($tempPath);
            $zip->close();
        } else {
            throw new \Exception('Failed to extract backup file');
        }

        // Restore database
        $this->restoreDatabase($tempPath);

        // Restore files
        $this->restoreFiles($tempPath);

        // Clean up
        File::deleteDirectory($tempPath);
    }

    protected function restoreDatabase($tempPath)
    {
        $sqlFile = "{$tempPath}/database.sql";
        if (File::exists($sqlFile)) {
            $command = sprintf(
                'mysql -u%s -p%s %s < %s',
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.database'),
                $sqlFile
            );

            exec($command);
        }
    }

    protected function restoreFiles($tempPath)
    {
        // Restore storage files
        $storagePath = storage_path('app/public');
        if (File::exists("{$tempPath}/storage")) {
            if (File::exists($storagePath)) {
                File::deleteDirectory($storagePath);
            }
            File::copyDirectory("{$tempPath}/storage", $storagePath);
        }

        // Restore uploads
        $uploadsPath = public_path('uploads');
        if (File::exists("{$tempPath}/uploads")) {
            if (File::exists($uploadsPath)) {
                File::deleteDirectory($uploadsPath);
            }
            File::copyDirectory("{$tempPath}/uploads", $uploadsPath);
        }
    }

    public function getBackups()
    {
        return collect(File::files($this->backupPath))
            ->filter(function ($file) {
                return $file->getExtension() === 'zip';
            })
            ->map(function ($file) {
                return [
                    'name' => $file->getFilename(),
                    'size' => $file->getSize(),
                    'created_at' => Carbon::createFromTimestamp($file->getMTime()),
                    'path' => $file->getPathname(),
                ];
            })
            ->sortByDesc('created_at')
            ->values();
    }
}
