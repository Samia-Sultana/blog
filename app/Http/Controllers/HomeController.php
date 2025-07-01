<?php

namespace App\Http\Controllers;

use App\Models\CaseStudy;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private CaseStudy $model;

  public function __construct(CaseStudy $model)
    {
        $this->model = $model;

    }
   public function index()
    {
    $caseStudies = $this->model->orderBy('id', 'desc')
                ->where('ispublished', 1)
                ->get();
    return view('index', compact('caseStudies'));
    }

}
