<?php

namespace App\Http\Controllers;

use App\Repositories\SeoService\SeoServiceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SeoServiceInAllCountryController extends Controller
{
    private SeoServiceRepository $repository;

    public function __construct(SeoServiceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function view_seo_service()
    {
        return $this->repository->all();
    }

    public function view_seo_service_single($slug)
    {
        return $this->repository->single($slug);
    }

    public function view(Request $request)
    {
        $breadcrumbs = [
            ['link' => "/seo-service", 'name' => "Seo service"],
            ['name' => "Index"]
        ];
        $searchQuery = $request->input('query');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $data = $this->repository->index($searchQuery, $sortBy, $sortOrder);
        return view('seoService.index', compact('data', 'breadcrumbs', 'searchQuery'));
    }

    public function create()
    {
        $breadcrumbs = [
            ['link' => "/seo-service", 'name' => "Seo Service"],
            ['name' => "Index"]
        ];
        return view('seoService.create', compact('breadcrumbs'));
    }

    public function store(Request $request)
    {
        $data = $this->prepareData($request);

        $this->repository->storeData($data);

        return redirect()->route('seo-service')->with('success', 'Data successfully created.');
    }

    public function edit($id)
    {
        $breadcrumbs = [
            ['link' => "/seo-service", 'name' => "Seo Service"],
            ['name' => "Index"]
        ];

        $old = $this->repository->edit($id);

        return view('seoService.edit', compact('old', 'breadcrumbs'));
    }

    public function update(Request $request, $id)
    {
        $data = $this->prepareData($request, $id);

        $this->repository->update($data, $id);

        return redirect()->route('seo-service')->with('success', 'Data successfully updated.');
    }

    public function delete($id)
    {
        $this->repository->delete($id);

        return redirect()->route('seo-service')->with('success', 'Data successfully deleted.');
    }

    public function publish(Request $request)
    {
        $this->repository->seo_service_publish($request->all());

        return redirect()->route('seo-service')->with('success', 'Data successfully published.');
    }

    private function prepareData(Request $request, $id = null)
    {
        $data = $request->except('_token');

        // Convert checkbox inputs to 1/0
        foreach (['section_1st_is_active', 'section_2nd_is_active', 'section_3rd_is_active', 'case_stydy_section_is_active'] as $field) {
            $data[$field] = $request->has($field) ? 1 : 0;
        }

        // Handle file uploads
        foreach ([
            'image_1st_section',
            '2nd_section_image',
            'section_3rd_image',
            'case_study_1st_image',
            'case_study_2nd_image'
        ] as $imageField) {
            if ($request->hasFile($imageField)) {
                $data[$imageField] = $this->uploadImage($request->file($imageField));
            } elseif ($id !== null) {
                // Retain existing image if not uploaded
                $existingData = $this->repository->edit($id);
                $data[$imageField] = $existingData[$imageField] ?? null;
            }
        }

        return $data;
    }

    private function uploadImage($image)
    {
        $filename = uniqid() . '.' . $image->getClientOriginalExtension();
        Storage::putFileAs('public/seoService', $image, $filename);
        return 'storage/seoService/' . $filename;
    }
}
