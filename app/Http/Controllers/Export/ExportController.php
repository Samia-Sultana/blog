<?php

namespace App\Http\Controllers\Export;

use App\Exports\AIPageContactExport;
use App\Exports\AIPageExport;
use App\Exports\CompanyDeckExport;
use App\Exports\ContactExport;
use App\Exports\JobApplicationExport;
use App\Exports\SubscribeViserXExport;
use App\Http\Controllers\Controller;
use App\Jobs\ExportModelToExcel;
use App\Models\AIPageContact;
use App\Models\Application;
use App\Models\CompanyDeck;
use App\Models\Contact;
use App\Models\CountryOrCityWisePageContent;
use App\Models\SubscribeViserX;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExportController extends Controller
{
    public function index()
    {
        $breadcrumbs = [
            ['link' => "/export", 'name' => "Generate Execl"], ['name' => "Index"]
        ];
        $modelFolders = Storage::disk('public')->directories('exports');

        $exports = [];

        foreach ($modelFolders as $folder) {
            $modelName = basename($folder);
            $files = Storage::disk('public')->files($folder);

            $modelExports = collect($files)
                ->filter(function ($file) {
                    return pathinfo($file, PATHINFO_EXTENSION) === 'xlsx';
                })
                ->map(function ($file) use ($modelName) {
                    return [
                        'model' => $modelName,
                        'filename' => basename($file),
                        'created_at' => date('Y-m-d H:i:s', Storage::disk('public')->lastModified($file)),
                        'download_url' => route('export.download', [
                            'model' => $modelName,
                            'filename' => basename($file)
                        ]),
                        'delete_url' => route('export.delete', [
                            'model' => $modelName,
                            'filename' => basename($file)
                        ])
                    ];
                })
                ->sortByDesc('created_at')
                ->values()
                ->toArray();

            $exports[$modelName] = $modelExports;
        }

        return view('export.index', compact('exports','breadcrumbs'));
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'model_name' => 'required|string'
        ]);
        $modelName = $validated['model_name'];
        $fileName = "{$modelName}_export_" . now()->format('Y_m_d_H_i_s') . '.xlsx';

        $exportClasses = [
            class_basename(new Contact()) => ContactExport::class,
            class_basename(new AIPageContact()) => AIPageContactExport::class,
            class_basename(new SubscribeViserX()) => SubscribeViserXExport::class,
            class_basename(new CompanyDeck()) => CompanyDeckExport::class,
            class_basename(new CountryOrCityWisePageContent()) => AIPageExport::class,
            class_basename(new Application()) => JobApplicationExport::class,
        ];

        if (!array_key_exists($modelName, $exportClasses)) {
            return response()->json([
                'error' => 'Unsupported model for export'
            ], 400);
        }

        ExportModelToExcel::dispatch($fileName, $exportClasses[$modelName], $modelName);
        return redirect()->back()->with('success', 'Execl is generating in the background wait a bit');
    }

    public function downloadExport($model, $filename)
    {
        $path = "exports/{$model}/{$filename}";

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download($path);
    }

    public function deleteExport($model, $filename)
    {
        $path = "exports/{$model}/{$filename}";

        if (!Storage::disk('public')->exists($path)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        Storage::disk('public')->delete($path);

        return response()->json(['message' => 'File deleted successfully']);
    }
}
