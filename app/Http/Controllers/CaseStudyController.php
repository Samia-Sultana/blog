<?php

namespace App\Http\Controllers;

use App\Repositories\CaseStudy\CaseStudyRepository;
use Illuminate\Http\Request;

class CaseStudyController extends Controller
{

    private CaseStudyRepository $repository;

    private $categories = ['SEO', 'Content Writing', 'Software Development'];

    private function redirectRouteConditionWise($category,$id,$update = false){

        // dd($category,$id,$update);
        switch ($category) {
            case 'SEO':
                return $update ? redirect()->route('case.seo.edit', $id) : redirect()->route('case.seo', $id);
                break;
            case 'Content Writing':
                return $update ? redirect()->route('case.content.edit', $id) : redirect()->route('case.content', $id);
                break;
            case 'Software Development':
                return $update ? redirect()->route('case.software.edit', $id) : redirect()->route('case.software', $id);
                break;
            default:
                return redirect()->route('case-study');
        }
    }

    public function __construct(CaseStudyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function view(Request $request)
    {
        $breadcrumbs = [
            ['link' => "/case-study", 'name' => "case-study"], ['name' => "Index"]
        ];
        $searchQuery = $request->input('query');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $data = $this->repository->search($searchQuery, $sortBy, $sortOrder);

        return view('CaseStudy.index', compact('data', 'breadcrumbs', 'searchQuery'));
    }


    public function apiview(Request $request)
    {
        $category = $request['category'];
        $data = $this->repository->apiview($category);
        return view('casestudies', compact('data'));
    }
    public function apiShow($category, $slug)
    {
        $data = $this->repository->apiShow($category, $slug);
        return view('casestudy-details', compact('data'));
    }

    public function create()
    {
        $breadcrumbs = [
            ['link' => "/case-study", 'name' => "Case study"], ['name' => "Index"]
        ];
        $category = $this->categories;
        return view('CaseStudy.create', compact('breadcrumbs', 'category'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'required|unique:case_studies,slug',
            'sort_desc' => 'required',
            'category' => 'required',
            'featured_image' => 'required',
            'challenge' => 'required',
            'solution' => 'required',
            'results' => 'required',

        ]);
        $data = $this->repository->store($request);
        return redirect('/case-study/home');
    }

    public function edit($id)
    {
        $old = $this->repository->edit($id);
        $breadcrumbs = [
            ['link' => "/case-study", 'name' => "Case study"], ['name' => "Index"]
        ];
        $category = $this->categories;
        return view('CaseStudy.update', compact('old', 'category', 'breadcrumbs'));
    }

    public function update(Request $request, $id)
    {
        $data = $this->repository->update($request, $id);

        if ($data) {
            return redirect('/case-study/home');
        }else{
            return redirect()->route('case-study');
        }


    }

    public function destroy(Request $request)
    {

        $this->repository->destroy($request->id);
        return redirect()->route('case-study');
    }

    public function publish(Request $request)
    {
        $this->repository->publish($request->all());
        return redirect()->route('case-study');
    }
}
