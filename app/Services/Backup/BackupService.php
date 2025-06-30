<?php

namespace App\Services\Backup;

use Illuminate\Support\Facades\File;
use ZipArchive;

class BackupService
{
    /**
     * Recursively add a folder to the zip file.
     *
     * @param string $folder
     * @param ZipArchive $zip
     * @param string $baseFolder
     */
    public function addFolderToZip($folder, ZipArchive $zip, $baseFolder = '')
    {
        $relativePath = $baseFolder ? $baseFolder . '/' . basename($folder) : basename($folder);

        if (is_dir($folder)) {
            $zip->addEmptyDir($relativePath);

            foreach (File::files($folder) as $file) {
                $this->addFolderToZip($file, $zip, $relativePath);
            }

            foreach (File::directories($folder) as $subfolder) {
                $this->addFolderToZip($subfolder, $zip, $relativePath);
            }
        } elseif (is_file($folder)) {
            $zip->addFile($folder, $relativePath);
        }
    }
}
