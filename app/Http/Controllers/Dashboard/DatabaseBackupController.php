<?php

namespace App\Http\Controllers\Dashboard;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use App\Mail\BackupNotification;

class DatabaseBackupController extends Controller
{
    protected $backupPath = 'app/POS';

    public function index()
    {
        try {
            $path = storage_path($this->backupPath);
            
            // Create directory if it doesn't exist
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }

            return view('database.index', [
                'files' => File::allFiles($path)
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error loading backup files: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            // Run the database backup
            $output = Artisan::call('backup:run');

            // Check if backup was successful
            if ($output !== 0) {
                throw new Exception('Backup command failed');
            }

            // Get the latest backup file
            $filePath = storage_path($this->backupPath);
            $files = File::allFiles($filePath);
            
            if (empty($files)) {
                throw new Exception('No backup files found');
            }

            $latestFile = collect($files)
                ->sortByDesc(function($file) {
                    return $file->getCTime();
                })
                ->first();

            $fileName = $latestFile->getFileName();

            // Delete old backups (keep only last 5)
            $this->cleanOldBackups();

            // Send email notification
            Mail::to('nellas.aurie@dnsc.edu.ph')->send(new BackupNotification($fileName));

            return redirect()
                ->route('backup.index')
                ->with('success', 'Database backup created successfully: ' . $fileName);

        } catch (Exception $e) {
            return redirect()
                ->route('backup.index')
                ->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function download(String $getFileName)
    {
        try {
            $path = storage_path($this->backupPath . '/' . $getFileName);
            
            if (!File::exists($path)) {
                throw new Exception('Backup file not found');
            }

            return response()->download($path);

        } catch (Exception $e) {
            return redirect()
                ->route('backup.index')
                ->with('error', 'Download failed: ' . $e->getMessage());
        }
    }

    public function delete(String $getFileName)
    {
        try {
            $deleted = Storage::delete('POS/' . $getFileName);
            
            if (!$deleted) {
                throw new Exception('Could not delete backup file');
            }

            return redirect()
                ->route('backup.index')
                ->with('success', 'Backup deleted successfully');

        } catch (Exception $e) {
            return redirect()
                ->route('backup.index')
                ->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }

    protected function cleanOldBackups()
    {
        try {
            $files = collect(File::allFiles(storage_path($this->backupPath)))
                ->sortByDesc(function($file) {
                    return $file->getCTime();
                });

            // Keep only the 5 most recent backups
            if ($files->count() > 5) {
                $files->slice(5)->each(function($file) {
                    File::delete($file->getPathname());
                });
            }
        } catch (Exception $e) {
            Log::error('Failed to clean old backups: ' . $e->getMessage());
        }
    }
}
