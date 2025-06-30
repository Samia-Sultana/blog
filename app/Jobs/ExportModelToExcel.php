<?php

namespace App\Jobs;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ExportModelToExcel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $fileName;
    protected $exportClass;
    protected $modelName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fileName, $exportClass, $modelName)
    {
        $this->fileName = $fileName;
        $this->exportClass = $exportClass;
        $this->modelName = $modelName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $folderPath = "exports/{$this->modelName}";

            if (!Storage::disk('public')->exists($folderPath)) {
                Storage::disk('public')->makeDirectory($folderPath);
            }

            $fullPath = "{$folderPath}/{$this->fileName}";

            $export = new $this->exportClass();
            Excel::store($export, $fullPath, 'public');

        } catch (\Exception $e) {
            throw $e;
        }
    }
}
