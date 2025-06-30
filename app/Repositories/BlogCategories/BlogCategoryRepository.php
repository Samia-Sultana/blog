<?php

namespace App\Repositories\BlogCategories;

use App\Models\BlogCategory;

class BlogCategoryRepository
{

    private BlogCategory $model;

    public function __construct(BlogCategory $model)
    {
        $this->model = $model;
    }

    /**
     * @return array
     */

    public function index($sortBy, $sortOrder)
    {

        try {
            $data = $this->model->orderBy($sortBy, $sortOrder)->paginate(10);
            return $data;
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     * @param $validated
     * @return bool
     */

    public function store($validated): bool
    {

        try {
            $this->model->create($validated);
            return true;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    /**
     * @param $id
     * @return bool
     */

    public function destroy($id): bool
    {

        try {
            $data = $this->findById($id);
            $data->delete();
            return true;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * @param $id
     * @return BlogCategory
     */

    public function findById($id): BlogCategory
    {

        try {
            $data = $this->model->findOrFail($id);
            return $data;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    /**
     * @param $id
     * @return BlogCategory
     */

     public function apiIndex()
     {

         try {
             $data = $this->model->latest('created_at')->get();
             return $data;
         } catch (\Exception $e) {
             error_log($e->getMessage());
             return [];
         }
     }

     public function apiShow($id)
     {

         try {
             $data = $this->model->where('id', $id)->with('blog')->get();
             return $data;
         } catch (\Exception $e) {
             error_log($e->getMessage());
             return [];
         }
     }
}
