<?php

namespace App\Repositories\SeoService;

use App\Models\SeoServiceInAllCountry;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SeoServiceRepository
{

    private SeoServiceInAllCountry $model;


    public function __construct(SeoServiceInAllCountry $model)
    {
        $this->model = $model;;
    }

    public function all()
    {
        try {
            return   $this->model->orderBy('id', 'desc')
            ->where('is_active', 1)->get();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function single($slug)
    {
        try {
            return   $this->model->where(['slug'=> $slug, 'is_published' => 1])->first();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function index($searchQuery, $sortBy, $sortOrder)
    {

        try {
            $data = $this->model->where('slug', 'like', '%' . $searchQuery . '%')
            ->orderBy($sortBy, $sortOrder)
            ->paginate(30);
            return $data;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function storeData($data)
    {
        try {

            SeoServiceInAllCountry::create($data);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function edit($id)
    {

        try {
            return SeoServiceInAllCountry::find($id);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function update($data, $id)
    {

        try {
            $obj = SeoServiceInAllCountry::find($id);
            $obj->update($data);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function delete($id)
    {

        try {
            $obj = SeoServiceInAllCountry::find($id);
            $obj->delete();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function seo_service_publish($data)
    {


        try {
            $v =  $data['ispublished'] == 'true' ? 1 : 0;
            SeoServiceInAllCountry::find($data['id'])->update(['is_published' => $v]);

            return true;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
