<?php

namespace App\Http\Controllers;

use App\Repositories\CaseStudy\CaseStudyRepository;
use Illuminate\Http\Request;

class SoftwareCaseStudyController extends Controller
{
    private CaseStudyRepository $repository;

    public function __construct(CaseStudyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create($id)
    {
        $breadcrumbs = [
            ['link' => "/case-study", 'name' => "Case study"], ['name' => "Index"]
        ];

        return view('CaseStudy.category.software',compact('breadcrumbs','id'));
    }

    public function store(Request $request, $id)
    {
        $this->repository->softwareStore($request, $id);
        return redirect()->route('case-study');

    }

    public function edit($id)
    {


        $old = $this->repository->softwareEdit($id);

        $breadcrumbs = [
            ['link' => "/case-study", 'name' => "Case study"], ['name' => "Index"]
        ];
        if (is_null($old)) {
            return redirect()->route('case.software', $id);
        }
        return view('CaseStudy.category.softwareUpdate', compact('old', 'breadcrumbs'));
    }

    public function update(Request $request, $id)
    {
        $this->repository->softwareUpdate($request, $id);
        return redirect()->route('case-study');
    }

    public function view_for_api($slug)
    {
        $case =$this->repository->searchBySlug($slug);
        $data =$this->repository->softwareEdit($case->id);

        function slugify($str) {
            $str = trim($str);
            $str = mb_strtolower($str, 'UTF-8');
            $str = preg_replace('/[^a-z0-9 -]/', '', $str);
            $str = preg_replace('/\s+/', '-', $str);
            $str = preg_replace('/-+/', '-', $str);

            return $str;
        }




        $frontendUrl = env('FRONTEND_URL');

        if (empty($data)) {
            return null;
        }

        if ($data->index_status == 1) {
            $indexStatus = "index";
        } else {
            $indexStatus = "no-index";
        }


        $frontendUrl = env('FRONTEND_URL');

        $fixedLink = [];
        $fixedLink[] = [
            'key' => 'canonical',
            'value' => $frontendUrl . '/case-studies/software-case-studies/' . slugify($case->category) . '/' . $case->slug.'/'. $case->id,
        ];



        $fixedScript = [];
        $fixedScript[] = [
            'type' => "application/ld+json",
            'script' => json_encode([
                "@context" => "https://schema.org",
                "@type" => "CaseStudyPosting",
                "mainEntityOfPage" => [
                    "@type" => "WebPage",
                    "@id" => ''
                ],
                "headline" => $data->title,
                "description" => $data->meta_description,
                "image" => (!empty($data->featured_image) ? asset($data->featured_image) : null),
                "author" => [
                    "@type" => "Person",
                    "name" => "VISER X",
                    "url" => null,
                ],
                "publisher" => [
                    "@type" => "Organization",
                    "name" => "VISER X",
                ],
                "datePublished" => $data->updated_at,
                "dateModified" => $data->updated_at,
            ])
        ];




        return response()->json([
            'seo' => [
                'title' => $data->meta_title ? str_replace("%currentyear%", date("Y"), $data->meta_title) : null,
                'description' => $data->meta_description ?? null,
                'robots' => $indexStatus,
                'openGraph' => [
                    'type' => "website",
                    'locale' => "en_IE",
                    'url' => $frontendUrl . '/case-studies/software-case-studies/' . slugify($case->category) . '/' . $case->slug.'/'. $case->id,
                    'site_name' => "VISER X",
                    'image' => [
                        'url' => !empty($data->featured_image) ? asset($data->featured_image) : null,
                        'width' => 800,
                        'height' => 600,
                        'alt' => "Case Study Post",
                    ],
                ],
                'links' => $fixedLink,
                'scripts' => $fixedScript,

            ],
            'case_study' => $data,
            'category' => $case->category


        ], 200);
    }
}
