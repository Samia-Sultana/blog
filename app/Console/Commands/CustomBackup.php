<?php

namespace App\Console\Commands;

use App\Services\Backup\BackupService;
use Illuminate\Console\Command;
use ZipArchive;
use Illuminate\Support\Facades\File;

class CustomBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the database and public/images folder into a timestamped folder';

    public function __construct(protected BackupService $backupService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $timestamp = now()->format('Y-m-d_h:i:s_A');
        $backupFolder = storage_path("app/backups/{$timestamp}");

        try {
            File::makeDirectory($backupFolder, 0755, true);

            $this->backupDatabase($backupFolder);
            $this->backupImages($backupFolder);

            $this->info('Backup completed successfully!');
        } catch (\Exception $e) {
            $this->error('Backup failed: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * Backup the database.
     *
     * @param string $backupFolder
     */
    private function backupDatabase($backupFolder)
    {
        $this->info('Backing up the database...');

        $databaseName = env('DB_DATABASE');
        $userName = escapeshellarg(env('DB_USERNAME'));
        $password = escapeshellarg(env('DB_PASSWORD'));
        $host = escapeshellarg(env('DB_HOST'));
        $backupFile = $backupFolder . '/database.sql';

        if (!$databaseName || !$userName || !$password || !$host) {
            $this->error('Database credentials are missing in the .env file.');
            return;
        }

        $command = "mysqldump -u $userName -p$password -h $host $databaseName > $backupFile";

        system($command);
    }

    /**
     * Backup the images folder.
     *
     * @param string $backupFolder
     */
    private function backupImages($backupFolder)
    {
        $this->info('Backing up the public/images folder...');

        $imagesFolder = public_path('images');
        $backupFile = $backupFolder . '/images.zip';

        if (!File::exists($imagesFolder)) {
            $this->error('The public/images folder does not exist.');
            return;
        }

        $zip = new ZipArchive();
        if ($zip->open($backupFile, ZipArchive::CREATE) === true) {
            $this->backupService->addFolderToZip($imagesFolder, $zip);
            $zip->close();
            $this->info("Images backup saved to: $backupFile");
        } else {
            $this->error('Failed to create a zip file for the images backup.');
        }
    }
}
