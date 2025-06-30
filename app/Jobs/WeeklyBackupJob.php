<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class WeeklyBackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Artisan::call('custom:backup');

        $backupPath = storage_path('app/backups');
        $files = File::directories($backupPath);

        foreach ($files as $file) {
            $folderName = basename($file);
            $folderDate = Carbon::createFromFormat('Y-m-d_h:i:s_A', $folderName);

            if ($folderDate->lt(Carbon::now()->subDays(7))) {
                File::deleteDirectory($file);
            }
        }
    }
}
