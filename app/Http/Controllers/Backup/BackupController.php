<?php

namespace App\Http\Controllers\Backup;

use App\Http\Controllers\Controller;
use App\Services\Backup\BackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use ZipArchive;

class BackupController extends Controller
{
    public function __construct(protected BackupService $backupService)
    {
    }

    public function index()
    {
        try {
            $breadcrumbs = [
                ['link' => "/backup", 'name' => "Backup"], ['name' => "Index"]
            ];

            $backupDirectory = storage_path('app/backups');

            if (!File::exists($backupDirectory)) {
                File::makeDirectory($backupDirectory, 0755, true);
            }

            $backups = File::directories($backupDirectory);

            $backupFolders = array_map(function($folder) {
                return basename($folder);
            }, $backups);

            return view('backups.index', compact('breadcrumbs', 'backupFolders'));
        } catch (\Exception $e) {
            return redirect()->route('backup')->with('error', 'Failed to create backup.');
        }
    }

    public function create()
    {
        try {
            Artisan::call('custom:backup');

            return redirect()->route('backup')->with('success', 'Backup created successfully!');
        } catch (\Exception $e) {
            return redirect()->route('backup')->with('error', 'Failed to create backup.');
        }
    }

    public function downloadBackup($folder)
    {
        try {
            $backupFolderPath = storage_path("app/backups/{$folder}");

            if (!File::exists($backupFolderPath)) {
                return response()->json(['error' => 'Backup folder not found.'], 404);
            }

            $zip = new ZipArchive();
            $zipFileName = "{$folder}.zip";
            $zipFilePath = storage_path("app/backups/{$zipFileName}");

            if ($zip->open($zipFilePath, ZipArchive::CREATE) === true) {
                $this->backupService->addFolderToZip($backupFolderPath, $zip);
                $zip->close();
            } else {
                return response()->json(['error' => 'Failed to create zip file.'], 500);
            }

            return response()->download($zipFilePath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to download the backup.'], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $folderName = $request->input('backup_folder');
            $folderPath = storage_path("app/backups/{$folderName}");

            if (!File::exists($folderPath)) {
                return redirect()->back()->with('error', 'Backup folder not found.');
            }

            File::deleteDirectory($folderPath);
            return redirect()->back()->with('success', 'Backup folder deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong !.');
        }
    }
}
