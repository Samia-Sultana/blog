<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanExportFolder extends Command
{
    protected $signature = 'clean:exports';
    protected $description = 'Clean the exports folder weekly';

    public function handle()
    {
        $this->info('Starting export folder cleanup...');

        // Get all files and directories in the exports folder
        $contents = Storage::disk('public')->allFiles('exports');

        if (empty($contents)) {
            $this->info('No files found in exports folder.');
            return;
        }

        $deletedCount = 0;

        foreach ($contents as $file) {
            try {
                Storage::disk('public')->delete($file);
                $deletedCount++;
                $this->line("Deleted: {$file}");
            } catch (\Exception $e) {
                $this->error("Failed to delete {$file}: " . $e->getMessage());
            }
        }

        // Also delete empty directories
        $directories = Storage::disk('public')->allDirectories('exports');
        foreach ($directories as $dir) {
            if (empty(Storage::disk('public')->files($dir)) &&
                empty(Storage::disk('public')->directories($dir))) {
                Storage::disk('public')->deleteDirectory($dir);
                $this->line("Deleted empty directory: {$dir}");
            }
        }

        $this->info("Cleanup complete. Deleted {$deletedCount} files.");

    }
}
