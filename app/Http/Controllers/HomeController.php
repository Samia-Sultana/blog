<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\CaseStudy;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private CaseStudy $model;
    private Blog $blog;

  public function __construct(CaseStudy $model, Blog $blog)
    {
        $this->model = $model;
        $this->blog = $blog;

    }
   public function index()
    {
    $caseStudies = $this->model->orderBy('id', 'desc')
                ->where('ispublished', 1)
                ->get();

    $blogs = $this->blog
    ->where('ispublished', 1)
    ->orderByDesc('id')
    ->limit(2)
    ->get();

    return view('index', compact('caseStudies', 'blogs'));
    }

}
