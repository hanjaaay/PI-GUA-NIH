<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BackupService;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    protected $backupService;

    public function __construct(BackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    public function index()
    {
        $backups = $this->backupService->getBackups();

        return view('admin.backups.index', compact('backups'));
    }

    public function create()
    {
        try {
            $backupPath = $this->backupService->createBackup();

            return redirect()->route('admin.backups.index')
                ->with('success', 'Backup created successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'Failed to create backup: '.$e->getMessage());
        }
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_path' => 'required|string',
        ]);

        try {
            $this->backupService->restoreBackup($request->backup_path);

            return redirect()->route('admin.backups.index')
                ->with('success', 'Backup restored successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'Failed to restore backup: '.$e->getMessage());
        }
    }

    public function download($filename)
    {
        $path = storage_path('app/backups/'.$filename);

        if (! file_exists($path)) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'Backup file not found');
        }

        return response()->download($path);
    }

    public function destroy($filename)
    {
        $path = storage_path('app/backups/'.$filename);

        if (! file_exists($path)) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'Backup file not found');
        }

        try {
            unlink($path);

            return redirect()->route('admin.backups.index')
                ->with('success', 'Backup deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'Failed to delete backup: '.$e->getMessage());
        }
    }
}
