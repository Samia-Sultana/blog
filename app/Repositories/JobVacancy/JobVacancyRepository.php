<?php

namespace App\Repositories\JobVacancy;

use App\Models\JobVacancy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class JobVacancyRepository
{

    private JobVacancy $model;


    public function __construct(JobVacancy $model)
    {
        $this->model = $model;;
    }

    public function jobView()
    {
        try {
            return   $this->model->with('positionLabel')->orderBy('id', 'desc')
            ->where('is_active', 1)->get();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function jobViewSingle($slug)
    {
        try {
            return   $this->model->with('positionLabel:id,name')->where(['slug'=> $slug, 'is_active' => 1])->first();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function index($searchQuery, $sortBy, $sortOrder)
    {

        try {
            $data = $this->model->where('title', 'like', '%' . $searchQuery . '%')
                ->orWhere('slug', 'like', '%' . $searchQuery . '%')
                ->orderBy($sortBy, $sortOrder)
                ->paginate(30);
            return $data;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function store($data,$imagePath)
    {

        try {

            $data['educations'] = json_encode($data['educations']);
            $data['experiences'] = json_encode($data['experiences']);

            $data['employment_statuses'] = json_encode($data['employment_statuses']);
            $data['responsibilities'] = json_encode($data['responsibilities']);
            $data['requirements'] = json_encode($data['requirements']);
            $data['benefits'] = json_encode($data['benefits']);
            $data['image'] = $imagePath;


            JobVacancy::create($data);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function edit($id)
    {

        try {
            return JobVacancy::find($id);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function update($data,$imagePath, $id)
    {

        try {

            $data['educations'] = json_encode($data['educations']);
            $data['experiences'] = json_encode($data['experiences']);
            $data['employment_statuses'] = json_encode($data['employment_statuses']);
            $data['responsibilities'] = json_encode($data['responsibilities']);
            $data['requirements'] = json_encode($data['requirements']);
            $data['benefits'] = json_encode($data['benefits']);

            if ($imagePath) {
                $data['image'] = $imagePath;
            }



            $obj = JobVacancy::find($id);
            $obj->update($data);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function delete($id)
    {

        try {
            $obj = JobVacancy::find($id);
            $obj->delete();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function job_publish($data)
    {
        try {
            DB::transaction(function () use ($data) {

                $this->model->where('id', $data['job_id'])->update(['is_active' => $data['ispublished'] == 'true' ? 1 : 0]);

            });

            return true;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
