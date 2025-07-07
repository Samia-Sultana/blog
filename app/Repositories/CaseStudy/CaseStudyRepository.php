<?php

namespace App\Repositories\CaseStudy;

use App\Models\CaseStudy;
use App\Models\ContentCaseStudy;
use App\Models\SeoCaseStudy;
use App\Models\SliderImages;
use App\Models\Solution;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use App\Models\SoftwareCaseStudy;


class CaseStudyRepository
{

    private CaseStudy $model;

    private SeoCaseStudy $seoCaseStudy;
    private Solution $solution;
    private SliderImages $sliderImages;

    private ContentCaseStudy $contentCaseStudy;

    private SoftwareCaseStudy $softwareCaseStudy;




    public function __construct(CaseStudy $model, SeoCaseStudy $seoCaseStudy, Solution $solution, SliderImages $sliderImages, ContentCaseStudy $contentCaseStudy, SoftwareCaseStudy $softwareCaseStudy)
    {
        $this->model = $model;
        $this->seoCaseStudy = $seoCaseStudy;
        $this->solution = $solution;
        $this->sliderImages = $sliderImages;
        $this->contentCaseStudy = $contentCaseStudy;
        $this->softwareCaseStudy = $softwareCaseStudy;
    }


    public function search($searchQuery, $sortBy, $sortOrder)
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

    public function apiview($category)
    {

        try {
            if ($category) {
                $caseStudies = $this->model->where('category', $category)
                ->where('ispublished', 1)
                ->orderBy('id', 'desc')
                ->paginate(6);
            } else {
                $caseStudies = $this->model->orderBy('id', 'desc')
                ->where('ispublished', 1)
                ->paginate(6);
            }
            $categories = [
                'Civil Litigation',
        'Commercial Litigation',
        'Construction Litigation',
        'Contractual Disputes',
        'Defamation',
        'Immigration Law',
        'Mortgage Defense',
        'Real Estate Litigation'
            ];
            return [
                'status' => 'success',
                'message' => 'Case Studies fetched successfully.',
                'data' => $caseStudies,
                'categories' => $categories
            ];
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => [],
                'categories' => []
            ];
        }
    }

    public function apiShow($category, $slug)
    {
        try {
            $data = $this->model->where('category', $category)
                ->where('slug', $slug)
                ->first();

            if (!$data) {
                return [];
            }

            return [
                'status' => 'success',
                'message' => 'Case Study fetched successfully.',
                'data' => $data,
                'related_case_studies' => $this->model->where('category', $category)
                    ->where('slug', '!=', $slug)
                    ->where('ispublished', 1)
                    ->orderBy('id', 'desc')
                    ->take(3)
                    ->get()
            ];
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }


    private function checkPageName($name){

        if($name == 'SEO'){
            $pageName = 'seo-case-studies';
        }

        if($name == 'Content Writing'){
            $pageName = 'content-writing-case-studies';
        }

        if($name == 'Software Development'){
            $pageName = 'software-case-studies';
        }

        return $pageName;
    }

    private function imageRemoveFromDir($path){

        $existingImagePath = public_path($path);

        if ($existingImagePath && File::exists($existingImagePath)) {
            File::delete($existingImagePath);
        }
    }

    public function store($request)
    {
        try {
            if ($request->hasFile('featured_image')) {
                $image = $request->file('featured_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/images', $image, $filename);
                $imagePath = 'storage/CaseStudy/images/' . $filename;
            }

            $v = $request->all();
            $v['page_name'] =  $v['category'];
            $v['featured_image'] = $imagePath;

            $data = $this->model->create($v);
            return $data;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }



    public function edit($id)
    {
        try {
            $data = $this->model->find($id);
            return $data;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function searchBySlug($slug)
    {
        try {
            $data = $this->model->where('slug', $slug)->first();
            return $data;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }


    public function update($request, $id)
    {
        try {
            $data = $this->model->find($id);
            $v = $request->all();

            if ($request->hasFile('featured_image')) {
                $image = $request->file('featured_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/images', $image, $filename);
                $imagePath = 'storage/CaseStudy/images/' . $filename;
                $v['featured_image'] =  $imagePath;

                $this->imageRemoveFromDir($data->featured_image);
            }

            $v['page_name'] =  $v['category'];

            $data->update($v);
            return $data;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }


    public function destroy($id)
    {
        try {
            $data = $this->model->find($id);
            $data->delete();
            return $data;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function publish($v)
    {
        try {
            $data = $this->model->find($v['id']);
            $data->ispublished = $v['ispublished'];
            $data->update();
            return $data;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }


    public function seoStore($request, $id){



        try {
             if ($request->hasFile('featured_image')) {
                $image = $request->file('featured_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/seo', $image, $filename);
                $featured_image = 'storage/CaseStudy/seo/' . $filename;
            }

            if ($request->hasFile('sec_1_image')) {
                $image = $request->file('sec_1_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/seo', $image, $filename);
                $sec_1_image = 'storage/CaseStudy/seo/' . $filename;
            }
            if ($request->hasFile('sec_2_image')) {
                $image = $request->file('sec_2_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/seo', $image, $filename);
                $sec_2_image = 'storage/CaseStudy/seo/' . $filename;
            }
            if ($request->hasFile('sec_3_image')) {
                $image = $request->file('sec_3_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/seo', $image, $filename);
                $sec_3_image = 'storage/CaseStudy/seo/' . $filename;
            }

            if ($request->hasFile('sec_ranking_image')) {
                $image = $request->file('sec_ranking_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/seo', $image, $filename);
                $sec_ranking_image = 'storage/CaseStudy/seo/' . $filename;
            }

            $v = $request->all();
            $v['case_study_id'] =  $id;
            $v['featured_image'] = $featured_image ?? '';
            $v['sec_1_image'] = $sec_1_image ?? '';
            $v['sec_2_image'] = $sec_2_image ?? '';
            $v['sec_3_image'] = $sec_3_image ?? '';
            $v['sec_ranking_image'] = $sec_ranking_image ?? '';

            $data = $this->seoCaseStudy->create($v);

            if (!$data) {
               return $data = [];
            }

            $this->seoSolutionadd($request, $data->id);
            $this->seoSliderImageAdd($request, $data->id);

            return $data;

        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    private function seoSolutionadd($request,$id){
        $solution_name = $request->input('solution_name');
        $solution_title = $request->input('solution_title');
        $solution_description = $request->input('solution_description');
        $solution_sequence = $request->input('solution_sequence');
        $solution_images = $request->file('solution_image');

        foreach ($solution_name as $key => $name) {
            $solution_image_path = '';

            if (isset($solution_images[$key]) && $solution_images[$key] != null) {
                $image = $solution_images[$key];
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/CaseStudy/seo/solution', $filename);
                $solution_image_path = 'storage/CaseStudy/seo/solution/' . $filename;
            }

            $this->solution->create([
                'seo_case_study_id' => $id,
                'name' => $name,
                'title' => $solution_title[$key],
                'description' => $solution_description[$key],
                'sequence' => $solution_sequence[$key],
                'image' => $solution_image_path,
            ]);
        }
    }

    private function seoSliderImageAdd($request,$id){

        $slider_image = $request->file('slider_image');

        foreach ($slider_image as $key => $name) {
            $solution_image_path = '';

            if (isset($slider_image[$key]) && $slider_image[$key] != null) {
                $image = $slider_image[$key];
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/CaseStudy/seo/slider', $filename);
                $solution_image_path = 'storage/CaseStudy/seo/slider/' . $filename;
            }

            $this->sliderImages->create([
                'seo_case_study_id' => $id,
                'image' => $solution_image_path,
                'image_alt' => $request->input('image_alt')[$key],
            ]);
        }

    }


    private function seoSolutionUpdate($request,$id){


        $solution_id = $request->input('solution_id');
        $solution_name = $request->input('solution_name');
        $solution_title = $request->input('solution_title');
        $solution_description = $request->input('solution_description');
        $solution_sequence = $request->input('solution_sequence');
        $solution_images = $request->file('solution_image');



        foreach ($solution_name as $key => $name) {


          $x = isset($solution_id[$key]) ? $this->solution->where('id', $solution_id[$key])->first() : new Solution();
          $x->name = $name;
          $x->seo_case_study_id = $id;
          $x->title = $solution_title[$key];
          $x->description = $solution_description[$key];
          $x->sequence = $solution_sequence[$key];
          if (isset($solution_images[$key]) && $solution_images[$key] != null){

            $image = $solution_images[$key];
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/CaseStudy/seo/solution', $filename);
            $solution_image_path = 'storage/CaseStudy/seo/solution/' . $filename;

            $x->image = $solution_image_path;
          }

          $x->save();

        }
    }

    private function seoSliderImageUpdate($request,$id){



        $image_id = $request->input('slider_image_id');
        $slider_image = $request->file('slider_image');
        $image_alt = $request->input('image_alt');



        foreach ($image_alt as $key => $name) {


          $x = isset($image_id[$key]) ?$this->sliderImages->where('id', $image_id[$key])->first(): new SliderImages();


          if (isset($slider_image[$key]) && $slider_image[$key] != null){

            $image = $slider_image[$key];
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/CaseStudy/seo/solution', $filename);
            $solution_image_path = 'storage/CaseStudy/seo/solution/' . $filename;

            $x->image = $solution_image_path;
            $x->seo_case_study_id = $id;
            $x->image_alt = $name;

          }

          $x->save();

        }
    }



    public function seoEdit($id)
    {
        try {
            $data = $this->seoCaseStudy->where('case_study_id', $id)->with('solution','slider')->first();
            return $data;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function seoUpdate( $request, $id)
    {
        try {

            $data = $this->seoCaseStudy->find($id);

            $v = $request->all();


            if ($request->hasFile('featured_image')) {

                $image = $request->file('featured_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/seo', $image, $filename);
                $v['featured_image'] = 'storage/CaseStudy/seo/' . $filename;

              $this->imageRemoveFromDir($data->featured_image);

            }

            if ($request->hasFile('sec_1_image')) {
                $image = $request->file('sec_1_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/seo', $image, $filename);
                $v['sec_1_image'] = 'storage/CaseStudy/seo/' . $filename;

              $this->imageRemoveFromDir($data->sec_1_image);
            }
            if ($request->hasFile('sec_2_image')) {
                $image = $request->file('sec_2_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/seo', $image, $filename);
                $v['sec_2_image'] = 'storage/CaseStudy/seo/' . $filename;

              $this->imageRemoveFromDir($data->sec_2_image);
            }
            if ($request->hasFile('sec_3_image')) {
                $image = $request->file('sec_3_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/seo', $image, $filename);
                $v['sec_3_image'] = 'storage/CaseStudy/seo/' . $filename;

                $this->imageRemoveFromDir($data->sec_3_image);
            }

            if ($request->hasFile('sec_ranking_image')) {
                $image = $request->file('sec_ranking_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/seo', $image, $filename);
                $v['sec_ranking_image'] = 'storage/CaseStudy/seo/' . $filename;

              $this->imageRemoveFromDir($data->sec_ranking_image);
            }

            $solution_id = $request->input('solution_id');

            if (isset($solution_id)) {
                $this->seoSolutionUpdate($request, $id);
            } else {
                $this->seoSolutionAdd($request, $id);
            }

            $image_id = $request->input('slider_image_id');



            if (isset($image_id)) {
                $this->seoSliderImageUpdate($request, $id);
            }else{
                $this->seoSliderImageadd($request,$id);
            }




            $data->update($v);


            return $data;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }



    public function contentStore($request, $id){

        try {
             if ($request->hasFile('featured_image')) {
                $image = $request->file('featured_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/content', $image, $filename);
                $featured_image = 'storage/CaseStudy/content/' . $filename;
            }

            if ($request->hasFile('problem_image')) {
                $image = $request->file('problem_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/content', $image, $filename);
                $problem_image = 'storage/CaseStudy/content/' . $filename;
            }
            if ($request->hasFile('challenge_2_image')) {
                $image = $request->file('challenge_2_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/content', $image, $filename);
                $challenge_2_image = 'storage/CaseStudy/content/' . $filename;
            }
            if ($request->hasFile('result_image')) {
                $image = $request->file('result_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/content', $image, $filename);
                $result_image = 'storage/CaseStudy/content/' . $filename;
            }


            $v = $request->all();
            $v['case_study_id'] =  $id;
            $v['featured_image'] = $featured_image ?? '';
            $v['problem_image'] = $problem_image ?? '';
            $v['challenge_2_image'] = $challenge_2_image ?? '';
            $v['sec_3_image'] = $sec_3_image ?? '';
            $v['result_image'] = $result_image ?? '';

            $data = $this->contentCaseStudy->create($v);

            if (!$data) {
               return $data = [];
            }

            return $data;

        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }


    public function contentEdit($id)
    {
        try {
            $data = $this->contentCaseStudy->where('case_study_id', $id)->first();
            return $data;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }


    public function contentUpdate( $request, $id)
    {
        try {

            $data = $this->contentCaseStudy->find($id);

            $v = $request->all();


            if ($request->hasFile('featured_image')) {

                $image = $request->file('featured_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/seo', $image, $filename);
                $v['featured_image'] = 'storage/CaseStudy/seo/' . $filename;

                $this->imageRemoveFromDir($data->featured_image);

            }

            if ($request->hasFile('problem_image')) {
                $image = $request->file('problem_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/seo', $image, $filename);
                $v['problem_image'] = 'storage/CaseStudy/seo/' . $filename;

                $this->imageRemoveFromDir($data->problem_image);
            }
            if ($request->hasFile('challenge_2_image')) {
                $image = $request->file('challenge_2_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/seo', $image, $filename);
                $v['challenge_2_image'] = 'storage/CaseStudy/seo/' . $filename;

                $this->imageRemoveFromDir($data->challenge_2_image);
            }
            if ($request->hasFile('result_image')) {
                $image = $request->file('result_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/seo', $image, $filename);
                $v['result_image'] = 'storage/CaseStudy/seo/' . $filename;

                $this->imageRemoveFromDir($data->result_image);
            }

            $data->update($v);


            return $data;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }


    public function softwareStore($request, $id){

        try {
             if ($request->hasFile('featured_image')) {
                $image = $request->file('featured_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/software', $image, $filename);
                $featured_image = 'storage/CaseStudy/software/' . $filename;
            }

            if ($request->hasFile('middle_1st_image')) {
                $image = $request->file('middle_1st_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/software', $image, $filename);
                $middle_1st_image = 'storage/CaseStudy/software/' . $filename;
            }

            if ($request->hasFile('middle_2nd_image')) {
                $image = $request->file('middle_2nd_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/software', $image, $filename);
                $middle_2nd_image = 'storage/CaseStudy/software/' . $filename;
            }

            if ($request->hasFile('middle_3rd_image')) {
                $image = $request->file('middle_3rd_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/software', $image, $filename);
                $middle_3rd_image = 'storage/CaseStudy/software/' . $filename;
            }

            if ($request->hasFile('middle_4th_image')) {
                $image = $request->file('middle_4th_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/software', $image, $filename);
                $middle_4th_image = 'storage/CaseStudy/software/' . $filename;
            }





            if ($request->hasFile('bottom_1st_image')) {
                $image = $request->file('bottom_1st_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/software', $image, $filename);
                $bottom_1st_image = 'storage/CaseStudy/software/' . $filename;
            }

            if ($request->hasFile('bottom_2nd_image')) {
                $image = $request->file('bottom_2nd_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/software', $image, $filename);
                $bottom_2nd_image = 'storage/CaseStudy/software/' . $filename;
            }

            if ($request->hasFile('bottom_3rd_image')) {
                $image = $request->file('bottom_3rd_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/software', $image, $filename);
                $bottom_3rd_image = 'storage/CaseStudy/software/' . $filename;
            }
            if ($request->hasFile('bottom_4th_image')) {
                $image = $request->file('bottom_4th_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/software', $image, $filename);
                $bottom_4th_image = 'storage/CaseStudy/software/' . $filename;
            }
            if ($request->hasFile('bottom_5th_image')) {
                $image = $request->file('bottom_5th_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/software', $image, $filename);
                $bottom_5th_image = 'storage/CaseStudy/software/' . $filename;
            }
            if ($request->hasFile('bottom_6th_image')) {
                $image = $request->file('bottom_6th_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/software', $image, $filename);
                $bottom_6th_image = 'storage/CaseStudy/software/' . $filename;
            }

            $v = $request->all();

            $v['case_study_id'] =  $id;

            $v['featured_image'] = $featured_image ?? '';

            $v['middle_1st_image'] = $middle_1st_image ?? '';

            $v['middle_2nd_image'] = $middle_2nd_image ?? '';

            $v['middle_3rd_image'] = $middle_3rd_image ?? '';

            $v['middle_4th_image'] = $middle_4th_image ?? '';

            $v['bottom_1st_image'] = $bottom_1st_image ?? '';

            $v['bottom_2nd_image'] = $bottom_2nd_image ?? '';

            $v['bottom_3rd_image'] = $bottom_3rd_image ?? '';

            $v['bottom_4th_image'] = $bottom_4th_image ?? '';

            $v['bottom_5th_image'] = $bottom_5th_image ?? '';

            $v['bottom_6th_image'] = $bottom_6th_image ?? '';



            $data = $this->softwareCaseStudy->create($v);



            if (!$data) {
               return $data = [];
            }

            return $data;

        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function softwareEdit($id)
    {
        try {
            $data = $this->softwareCaseStudy->where('case_study_id', $id)->first();
            return $data;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }


    public function softwareUpdate($request, $id){

        try {
            $data = $this->softwareCaseStudy->find($id);

            $v = $request->all();

             if ($request->hasFile('featured_image')) {
                $image = $request->file('featured_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/software', $image, $filename);
                $v['featured_image'] = 'storage/CaseStudy/software/' . $filename;

               $this->imageRemoveFromDir($data->featured_image);


            }

            if ($request->hasFile('middle_1st_image')) {
                $image = $request->file('middle_1st_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/software', $image, $filename);
                $v['middle_1st_image'] = 'storage/CaseStudy/software/' . $filename;

                $this->imageRemoveFromDir($data->middle_1st_image);
            }

            if ($request->hasFile('middle_2nd_image')) {
                $image = $request->file('middle_2nd_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/software', $image, $filename);
                $v['middle_2nd_image'] = 'storage/CaseStudy/software/' . $filename;

                $this->imageRemoveFromDir($data->middle_2nd_image);
            }

            if ($request->hasFile('middle_3rd_image')) {
                $image = $request->file('middle_3rd_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/software', $image, $filename);
                $v['middle_3rd_image'] = 'storage/CaseStudy/software/' . $filename;

                $this->imageRemoveFromDir($data->middle_3rd_image);
            }

            if ($request->hasFile('middle_4th_image')) {
                $image = $request->file('middle_4th_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/software', $image, $filename);
                $v['middle_4th_image'] = 'storage/CaseStudy/software/' . $filename;

                $this->imageRemoveFromDir($data->middle_4th_image);
            }





            if ($request->hasFile('bottom_1st_image')) {
                $image = $request->file('bottom_1st_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/software', $image, $filename);
                $v['bottom_1st_image'] = 'storage/CaseStudy/software/' . $filename;

                $this->imageRemoveFromDir($data->bottom_1st_image);
            }

            if ($request->hasFile('bottom_2nd_image')) {
                $image = $request->file('bottom_2nd_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/software', $image, $filename);
                $v['bottom_2nd_image'] = 'storage/CaseStudy/software/' . $filename;

                $this->imageRemoveFromDir($data->bottom_2nd_image);
            }

            if ($request->hasFile('bottom_3rd_image')) {
                $image = $request->file('bottom_3rd_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/software', $image, $filename);
                $v['bottom_3rd_image'] = 'storage/CaseStudy/software/' . $filename;

                $this->imageRemoveFromDir($data->bottom_3rd_image);
            }
            if ($request->hasFile('bottom_4th_image')) {
                $image = $request->file('bottom_4th_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/software', $image, $filename);
                $v['bottom_4th_image'] = 'storage/CaseStudy/software/' . $filename;

                $this->imageRemoveFromDir($data->bottom_4th_image);
            }
            if ($request->hasFile('bottom_5th_image')) {
                $image = $request->file('bottom_5th_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/software', $image, $filename);
                $v['bottom_5th_image'] = 'storage/CaseStudy/software/' . $filename;

                $this->imageRemoveFromDir($data->bottom_5th_image);
            }
            if ($request->hasFile('bottom_6th_image')) {
                $image = $request->file('bottom_6th_image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/CaseStudy/software', $image, $filename);
                $v['bottom_6th_image'] = 'storage/CaseStudy/software/' . $filename;

                $this->imageRemoveFromDir($data->bottom_6th_image);
            }

            $data->update($v);

            if (!$data) {
               return $data = [];
            }

            return $data;

        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }




}
