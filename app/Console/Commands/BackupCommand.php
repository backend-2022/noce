<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use App\Models\Backup;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BackupCommand extends Command
{

    protected $signature = 'backup:all';

    protected $description = 'Run the database backup and store backup details in the database.';

    public function handle(): void
    {
        $this->info('Starting full project backup...');

        // Run Laravel backup command (includes files + database)
        Artisan::call('backup:run');

        $disk = Storage::disk('local');

        // Find the latest backup file
        $backupFolder = trim(config('backup.backup.name', 'backup'), '/');
        $backupFolder = $backupFolder === '' ? 'backup' : $backupFolder;
        $backupFiles = $disk->files($backupFolder);

        if (empty($backupFiles)) {
            $this->error('No backup file found!');
            return;
        }

        // Get the newest backup file (latest by modification time)
        $latestBackup = collect($backupFiles)
            ->mapWithKeys(fn($file) => [$disk->lastModified($file) => $file])
            ->sortKeys()
            ->last(); // Get the most recent backup file

        if (!$latestBackup) {
            $this->error('No valid backup file found.');
            return;
        }

        // Rename backup file to use underscore between date and time
        $latestBackup = $this->renameBackupFile($latestBackup);

        // Reorganize the backup zip file
        $this->reorganizeBackup($latestBackup);

        // Store the relative path instead of the full storage path
        // $latestBackup is now something like: backup-YYYY-MM-DD_HH-MM-SS.zip
        $zipName = basename($latestBackup);
        $storageDir = trim(config('backup.backup.storage_directory', Backup::$STORAGE_DIR), '/');
        $storageDir = $storageDir === '' ? Backup::$STORAGE_DIR : $storageDir;

        if (!$disk->exists($storageDir)) {
            $disk->makeDirectory($storageDir);
        }

        $storageRelativePath = "{$storageDir}/{$zipName}";

        // Check if this backup was already saved in the database
        if (Backup::where('path', $storageRelativePath)->exists()) {
            $this->info('Backup already saved. Skipping duplicate entry.');
            return;
        }

        // Get keep_backups setting BEFORE copying the new backup
        $keepBackups = setting('keep_backups', '0');

        // If keep_backups is 0, remove all old backups BEFORE copying the new one
        if ($keepBackups === '0') {
            $this->info('keep_backups is disabled. Removing all old backups...');

            // Get all existing backups
            $oldBackups = Backup::all();

            // Delete old backup files from storage directory
            foreach ($oldBackups as $oldBackup) {
                if ($disk->exists($oldBackup->path)) {
                    $disk->delete($oldBackup->path);
                    $this->info("Deleted old backup file: {$oldBackup->path}");
                }
            }

            // Delete all files from the original backup folder (except the current one we just created)
            $allBackupFiles = $disk->files($backupFolder);
            foreach ($allBackupFiles as $file) {
                // Don't delete the latest backup we just created
                if ($file !== $latestBackup) {
                    $disk->delete($file);
                    $this->info("Deleted old backup file from backup folder: {$file}");
                }
            }

            // Delete all files from storage directory (except the new backup we're about to copy)
            $allStorageFiles = $disk->files($storageDir);
            foreach ($allStorageFiles as $file) {
                // Don't delete the file if it has the same name as the new backup
                if ($file !== $storageRelativePath) {
                    $disk->delete($file);
                    $this->info("Deleted old backup file from storage folder: {$file}");
                }
            }

            // Delete all old backup records from database
            Backup::query()->delete();
            $this->info('All old backup records removed from database.');
        }

        // Now copy the new backup to storage directory
        // Use PHP's native copy to avoid filesystem cache issues after deletion
        $sourcePath = $disk->path($latestBackup);
        $destinationDir = $disk->path($storageDir);
        $destinationPath = $destinationDir . '/' . $zipName;

        // Ensure the directory exists
        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        copy($sourcePath, $destinationPath);
        $this->info("Backup copied to {$storageRelativePath}");

        $relativePath = $storageRelativePath;

        // Store the new backup in the database
        Backup::create([
            'name' => basename($relativePath),
            'path' => $relativePath,
            'created_at' => Carbon::now(),
        ]);

        $this->info("Backup completed and saved to the database: $relativePath");
    }

    private function renameBackupFile(string $backupPath): string
    {
        $disk = Storage::disk('local');
        $filename = basename($backupPath);

        // Accept both "YYYY-MM-DD-HH-MM-SS.zip" and "backup-YYYY-MM-DD-HH-MM-SS.zip"
        if (preg_match('/^(backup-)?(\d{4}-\d{2}-\d{2})-(\d{2}-\d{2}-\d{2})\.zip$/', $filename, $matches)) {
            $prefix = $matches[1] ?: 'backup-'; // default to "backup-" if no prefix
            $date = $matches[2];                // 2025-11-24
            $time = $matches[3];                // 15-37-38

            // New format: backup-2025-11-24_15-37-38.zip
            $newFilename = "{$prefix}{$date}_{$time}.zip";

            // If name is already correct, no need to rename
            if ($newFilename === $filename) {
                return $backupPath;
            }

            $directory = dirname($backupPath);
            $newPath = $directory . '/' . $newFilename;

            // Rename file
            $disk->move($backupPath, $newPath);
            $this->info("Renamed backup file to {$newFilename}");

            return $newPath;
        }

        // If it doesn't match expected pattern, just return original path
        return $backupPath;
    }


    private function reorganizeBackup(string $backupPath): void
    {
        $fullPath = Storage::disk('local')->path($backupPath);
        $tempPath = $fullPath . '.temp';
        $tempExtractDir = sys_get_temp_dir() . '/backup_reorganize_' . uniqid();

        // Extract timestamp from filename (e.g., backup-2025-11-24-12-35-31)
        $filename = basename($backupPath, '.zip');
        $timestamp = str_replace('backup-', '', $filename);
        $appName = strtolower(str_replace(' ', '-', config('app.name', 'noce')));
        $projectFolder = "backup-{$appName}-{$timestamp}";

        $zip = new \ZipArchive();
        $newZip = new \ZipArchive();

        try {
            if ($zip->open($fullPath) === true && $newZip->open($tempPath, \ZipArchive::CREATE) === true) {
                // Extract all files to temporary directory
                if (!is_dir($tempExtractDir)) {
                    mkdir($tempExtractDir, 0755, true);
                }

                $zip->extractTo($tempExtractDir);

                // Process each file and add to new zip
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $filename = $zip->getNameIndex($i);

                    // Skip directories
                    if (substr($filename, -1) === '/') {
                        continue;
                    }

                    // Determine new filename
                    if (str_starts_with($filename, 'db-dumps/')) {
                        $newFilename = $filename;
                    } else {
                        $newFilename = $projectFolder . '/' . $filename;
                    }

                    // Get the extracted file path
                    $extractedFilePath = $tempExtractDir . '/' . $filename;

                    // Use addFile() instead of addFromString() for memory efficiency
                    if (file_exists($extractedFilePath)) {
                        $newZip->addFile($extractedFilePath, $newFilename);
                    }
                }

                $zip->close();
                $newZip->close();

                // Replace original with reorganized backup
                unlink($fullPath);
                rename($tempPath, $fullPath);

                $this->info('Backup structure reorganized successfully.');
            }
        } finally {
            // Clean up temporary extraction directory
            if (is_dir($tempExtractDir)) {
                $this->removeDirectory($tempExtractDir);
            }
        }
    }

    /**
     * Recursively remove a directory and its contents
     */
    private function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->removeDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }
}
