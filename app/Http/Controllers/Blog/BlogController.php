<?php

namespace App\Http\Controllers\Blog;

use App\Transformers\BlogTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Blog\BlogRepository;
use App\Http\Requests\Blog\StoreBlogRequest;
use App\Http\Requests\Blog\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\CountryOrCityWisePageContent;
use App\Models\SeoServiceInAllCountry;
use Carbon\Carbon;
use League\Fractal\Resource\Item;
use League\Fractal\Manager;

class BlogController extends Controller
{
    private BlogRepository $repository;

    public function __construct(BlogRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $breadcrumbs = [
            ['link' => "/blog", 'name' => "Blog"], ['name' => "Index"]
        ];

        $data = $this->repository->index();

        $searchQuery = '';

        return view('blog.blog.index', compact('data', 'breadcrumbs', 'searchQuery'));
    }

    public function search(Request $request)
    {
        $breadcrumbs = [
            ['link' => "/blog", 'name' => "Blog"], ['name' => "Index"]
        ];
        $searchQuery = $request->input('query');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $data = $this->repository->search($searchQuery, $sortBy, $sortOrder);

        return view('blog.blog.index', compact('data', 'breadcrumbs', 'searchQuery'));
    }

    public function createBlogView()
    {
        $breadcrumbs = [
            ['link' => "/blog", 'name' => "Blog"], ['name' => "Create"]
        ];

        $blogCategoryData = $this->repository->blogCategory();
        $authorData = $this->repository->author();

        return view('blog.blog.create-blog', compact('breadcrumbs', 'blogCategoryData', 'authorData'));
    }

    public function updateContentView($id)
    {
        $breadcrumbs = [
            ['link' => "/blog", 'name' => "Blog"], ['name' => "Update Content"]
        ];

        $contentData = $this->repository->updateContentView($id);

        return view('blog.blog.update-content', compact('breadcrumbs', 'contentData'));
    }



    public function storeBlog(StoreBlogRequest $request)
    {
        $validated = $request->validated();
        $data = $this->repository->storeBlog($validated, $request);

        $blogId = $data ?  $data['id']:  redirect()->route('blog')->with('error', 'Blog failed created.');

        if ($data) {
            return redirect('/blog/update/' . $blogId);
        } else {
            return redirect()->route('blog')->with('error', 'Blog failed created.');
        }
    }

    public function createContentView(Request $id)
    {

        $breadcrumbs = [
            ['link' => "/blog", 'name' => "Blog"], ['name' => "Update"]
        ];

        return view('blog.blog.create-content');
    }

    public function storeContent(Request $request)
    {
        $request->validate([
            'sequence.*.value' => 'unique'
        ]);
        $data = $this->repository->storeContent($request);

        if ($data) {
            return redirect('/back');
        } else {
            return redirect()->route('blog')->with('error', 'Blog failed created.');
        }
    }

    public function createSeoView($id)
    {
        $breadcrumbs = [
            ['link' => "/blog", 'name' => "Blog"], ['name' => "SEO"]
        ];

        return view('blog.blog.create-seo', compact('breadcrumbs'));
    }

    public function storeSeo(Request $request)
    {
        $data = $this->repository->storeSeo($request);

        if ($data) {
            return redirect()->route('blog')->with('success', 'Blog successfully created.');
        } else {
            return redirect()->route('blog')->with('error', 'Blog failed created.');
        }
    }

    public function view($id)
    {
        $breadcrumbs = [
            ['link' => "/blog", 'name' => "Blog"], ['name' => "View"]
        ];
        $data = $this->repository->view($id);

        return view('blog.blog.view', compact('data', 'breadcrumbs'));
    }

    public function edit($id)
    {
        $breadcrumbs = [
            ['link' => "/blog", 'name' => "Blog"], ['name' => "Edit"]
        ];

        $data = $this->repository->edit($id);
        $blogCategoryData = $this->repository->blogCategory();
        $authorData = $this->repository->author();

        return view('blog.blog.edit', compact('data', 'breadcrumbs', 'blogCategoryData', 'authorData'));
    }

    public function update(UpdateBlogRequest $request)
    {
        $validated = $request->validated();

        $blog_id = $validated['blog_id'];

        $data = $this->repository->update($validated,  $request);

        if ($data) {
            return redirect('/blog/content/update/' . $data);
        } else {
            return redirect()->route('blog')->with('error', 'Blog failed updated.');
        }
    }

    public function publish_blog(Request $request)
    {

        $id = $request->blog_id;


        $data = $this->repository->publish($request->all());

        // dd($data);

        if ($data) {
            return response()->json(['success' => true, 'message' => 'Blog successfully published.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Blog failed published.']);
        }
    }

    public function updateContent(Request $request)
    {
        $data = $this->repository->updateContent($request);

        if ($data) {
            return redirect('/blog/seo/update/' . $data);
        } else {
            return redirect()->route('blog')->with('error', 'Blog failed updated.');
        }
    }

    public function updateSeoView($id)
    {
        $breadcrumbs = [
            ['link' => "/blog", 'name' => "Blog"], ['name' => "SEO"]
        ];

        $data = $this->repository->updateSeoView($id);

        $postLinkData = $this->repository->postLink($id);
        $postScriptData = $this->repository->postScript($id);

        return view('blog.blog.update-seo', compact('data', 'breadcrumbs', 'postLinkData', 'postScriptData'));
    }

    public function updateSeo(Request $request)
    {
        $data = $this->repository->updateSeo($request);

        if ($data) {
            return redirect()->route('blog')->with('success', 'Blog successfully updated.');
        } else {
            return redirect()->route('blog')->with('error', 'Blog failed updated.');
        }
    }

    public function destroy(Request $request)
    {
        $id = $request->blog_id;

        $data = $this->repository->destroy($id);

        if ($data) {
            return redirect()->route('blog')->with('success', 'Blog successfully deleted.');
        } else {
            return redirect()->route('blog')->with('error', 'Blog failed deleted.');
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex(Request $request)
    {
        $category = $request['category'];
        $search = $request->input('search');
        $data = $this->repository->apiIndex($category, $search);


        //return response()->json($data);

        return view('blogs', ['blogs' => $data]);

    }

    function slugify($str)
    {
        // Remove leading and trailing whitespace
        $str = trim($str);

        // Convert to lowercase
        $str = strtolower($str);

        // Replace non-alphanumeric characters (except spaces and hyphens) with a single space
        $str = preg_replace('/[^a-z0-9 -]/', '', $str);

        // Replace consecutive spaces or hyphens with a single hyphen
        $str = preg_replace('/\s+/', '-', $str);
        $str = preg_replace('/-+/', '-', $str);

        // Trim any leading or trailing hyphens
        $str = trim($str, '-');

        return $str;
    }

    public function apiShow($category, $slug)
    {
        try {
            $category = str_replace('-', ' ', $category);
            $category = ucwords($category);
            $data = $this->repository->apiShow($category, $slug);

            if (empty($data)) {
                return null;
            }

            $blog = [
                    'title' => $data->title ?? null,
                    'author' => $data->authors->name ?? null,
                    'published_at' => $data->published_at ?? null,
                    'featured_image_url' => !empty($data->featured_image) ? asset($data->featured_image) : null,
                    'categories' => $data->blogCategories->map(function ($category) {
                        return [
                            'id' => $category->id,
                            'title' => $category->name,
                        ];
                    })->toArray(),
                    'contents' => $data->contents->map(function ($content) {
                        return [
                            'id' => $content->id,
                            'title' => $content->title,
                            'description' => $content->description,
                        ];
                    })->toArray(),
                ];


            return view('blog-single-fullwidth', compact('blog'));
        }catch (\Exception $e) {
            // Log the error message
            \Log::error('Error fetching blog data: ' . $e->getMessage());

            // Return a 404 response or a custom error view
            return response()->json(['error' => 'Blog not found'], 404);
        }
    }

    public function apiAllBlogSlugs()
    {
        $data = $this->repository->apiAllBlogSlugs();

        return response()->json($data);
    }

    // public function blogSearch()
    // {
    //     $blogs = Blog::all();
    //     return response()->json($blogs);
    // }

    //subscription

    public function subscription(Request $request)
    {
        info($request->all());
    }

    public function apiSiteMap()
    {
        $frontendUrl = env('FRONTEND_URL');

        $blogs = Blog::where('ispublished', 1)->get();

        $data = [
            [
                'loc' => $frontendUrl . '/',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '1'
            ],
            [
                'loc' => $frontendUrl . '/services',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/software',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/software/erp',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/software/ecommerce',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/software/project-management',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/software/crm',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/software/hr-management',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/software/accounting-finance',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/software/payroll-management',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/software/mobile-app',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/web-development',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/web-development/website-development',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/web-development/ecommerce-website-development',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/web-development/website-speed-optimization',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/web-development/website-maintenance',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/digital-marketing/seo',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/digital-marketing/seo/seo-services',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/digital-marketing/seo/ecommerce-seo',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/digital-marketing/seo/local-seo',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/digital-marketing/seo/guest-post',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/digital-marketing/seo/seo-audit',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/digital-marketing/seo/google-business-profile-optimization',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/digital-marketing/seo/app-store-optimization',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/digital-marketing',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/digital-marketing/media-buying',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/digital-marketing/social-media-management',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/digital-marketing/online-reputation-management',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/digital-marketing/media-buying/facebook-ads-management',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/digital-marketing/media-buying/google-ads-management',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/digital-marketing/media-buying/youtube-ads-management',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/digital-marketing/media-buying/linkedIn-ads-management',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/creative-content',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/creative-content/content-writing',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/creative-content/video-production',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/creative-content/social-media-content-creation',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/creative-design',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/creative-design/ui-ux-design',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/creative-design/graphic-design',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/services/creative-design/motion-graphic',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/industries',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/industries/software',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/industries/education',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/industries/finance',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/industries/ecommerce',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/industries/banking',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/industries/real-state',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/industries/legal',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/industries/travel',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/blog',
                'lastmod' => '2024-04-07',
                'changefreq' => 'weekly',
                'priority' => '0.7'
            ],
            [
                'loc' => $frontendUrl . '/about',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/media',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/career',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/life-at-viserx',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/contact-us',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/privacy-policy',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/testimonials',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/refund-policy',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/cookie-policy',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/seo-service-company-in-bangladesh',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/seo-services-in-dubai',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            [
                'loc' => $frontendUrl . '/tools',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/backlink-checker',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],

            [
                'loc' => $frontendUrl . '/tools/article-rewriter',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/plagiarism-checker',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/backlink-maker',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/meta-tag-generator',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/keyword-position-checker',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/robots-txt-generator',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/xml-sitemap-generator',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
             [
                'loc' => $frontendUrl . '/tools/word-counter',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],

            [
                'loc' => $frontendUrl . '/tools/online-ping-website-tool',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/link-analyzer-tool',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],

            [
                'loc' => $frontendUrl . '/tools/keyword-density-checker',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/google-malware-checker',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],

            [
                'loc' => $frontendUrl . '/tools/domain-age-checker',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/whois-checker',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/domain-into-ip',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/url-rewriting-tool',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/url-encoder-decoder',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/server-status-checker',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/webpage-screen-resolution-simulator',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/page-size-checker',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/blacklist-lookup',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/suspicious-domain-checker',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/link-price-calculator',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/domain-hosting-checker',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/get-source-code-of-webpage',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/google-index-checker',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/website-links-count-checker',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/class-c-ip-checker',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/online-md5-generator',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/page-speed-checker',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/code-to-text-ratio-checker',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/find-dns-records',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/what-is-my-browser',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/email-privacy',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/google-cache-checker',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/broken-links-finder',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/spider-simulator',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/keywords-suggestion-tool',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            [
                'loc' => $frontendUrl . '/tools/meta-tags-analyzer',
                'lastmod' => '2024-04-07',
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ]


        ];

        foreach ($blogs as $blog) {

            $category = strtolower($blog->blogCategories[0]->name);
            $category = str_replace(' ', '-', $category);
            $category = preg_replace('/[^a-z0-9\-]/', '', $category);
            $category = preg_replace('/-+/', '-', $category);
            $category = trim($category, '-');


            $data[] = [
                'loc' => $frontendUrl . '/blog/' . $category . '/' . $blog->slug,
                'lastmod' => Carbon::parse($blog->updated_at)->format('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => '0.7'
            ];
        };

        $seoAllCountries = SeoServiceInAllCountry::where('is_published', 1)->get();

        foreach ($seoAllCountries as $country) {
            $data[] = [
                'loc' => $frontendUrl . '/seo/' . $country->slug,
                'lastmod' => Carbon::parse($country->updated_at)->format('Y-m-d'),
                'changefreq' => 'monthly',
                'priority' => '0.7'
            ];
        };

        $aiGeneratePages = CountryOrCityWisePageContent::where('is_published', 1)->where('index', 1)->get();

        foreach ($aiGeneratePages as $page) {
            $data[] = [
                'loc' => $frontendUrl .'/'. $page->page_url,
                'lastmod' => Carbon::parse($page->updated_at)->format('Y-m-d'),
                'changefreq' => 'monthly',
                'priority' => '0.8'
            ];
        };

        usort($data, function ($a, $b) {
            return strtotime($b['lastmod']) <=> strtotime($a['lastmod']);
        });

        return response()->json($data);
    }
}
