<?php

namespace App\Http\Controllers\Blog;

use League\Fractal\Manager;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use League\Fractal\Resource\Item;
use App\Http\Controllers\Controller;
use League\Fractal\Resource\Collection;
use App\Transformers\BlogCategoryTransformer;
use App\Repositories\BlogCategories\BlogCategoryRepository;
use App\Http\Requests\BlogCategory\StoreBlogCategoryRequest;

class BlogCategoryController extends Controller
{
    private BlogCategoryRepository $repository;

    public function __construct(BlogCategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $breadcrumbs = [
            ['link' => "/blog-category", 'name' => "Blog Category"], ['name' => "Index"]
        ];


        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $data = $this->repository->index($sortBy, $sortOrder);

        return view('blog.blog-category.index', compact('data', 'breadcrumbs'));


    }

    public function store(StoreBlogCategoryRequest $request)
    {
        $validated = $request->validated();

        $data = $this->repository->store($validated);

        if($data){
            return redirect()->route('blog-category')->with('success', 'Blog Category successfully created.');
        }else{
            return redirect()->route('blog-category')->with('error', 'Blog Category failed created.');
        }

    }

    public function destroy(Request $request)
    {
        $id = $request->blog_category_id;

        $data = $this->repository->destroy($id);

        if($data){
            return redirect()->route('blog-category')->with('success', 'Blog Category successfully deleted.');
        }else{
            return redirect()->route('blog-category')->with('error', 'Blog Category failed deleted.');
        }

    }

    public function apiIndex()
    {
        $data = $this->repository->apiIndex();

        return response()->json($data);

    }

    public function apiShow($id)
    {
        $data = $this->repository->apiShow($id);

        return response()->json($data);
    }


}
