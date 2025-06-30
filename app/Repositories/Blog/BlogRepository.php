<?php

namespace App\Repositories\Blog;

use App\Models\Blog;
use App\Models\User;
use App\Models\Author;
use App\Models\Content;
use App\Models\PostLink;
use App\Models\PostScript;
use Illuminate\Support\Str;
use App\Models\BlogCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BlogRepository
{

    private Blog $model;
    private BlogCategory $blogCategory;
    private Author $author;
    private Content $content;
    private PostLink $postLink;
    private PostScript $postScript;

    public function __construct(Blog $model, BlogCategory $blogCategory, Author $author, Content $content, PostLink $postLink, PostScript $postScript)
    {
        $this->model = $model;
        $this->blogCategory = $blogCategory;
        $this->author = $author;
        $this->content = $content;
        $this->postLink = $postLink;
        $this->postScript = $postScript;
    }


    /**
     * @return array
     */

    public function index()
    {

        try {
            $data = $this->model->latest('created_at')->paginate(30);
            return $data;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function search($searchQuery, $sortBy, $sortOrder)
    {

        try {
            if (is_numeric($searchQuery)) {
                $data = $this->model->where('id', $searchQuery)
                    ->orderBy($sortBy, $sortOrder)
                    ->paginate(30);
            } else {
                $data = $this->model->where('title', 'like', '%' . $searchQuery . '%')
                    ->orWhere('slug', 'like', '%' . $searchQuery . '%')
                    ->orderBy($sortBy, $sortOrder)
                    ->paginate(30);
            }

            return $data;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function blogCategory()
    {

        try {
            $blogCategory = $this->blogCategory->all();
            return $blogCategory;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function author()
    {

        try {
            $authors = $this->author->all();
            return $authors;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    /**
     * @param $validated
     * @return bool
     */

    public function storeBlog(array $validated, $request)
    {
        try {
            $newBlog = null;

            DB::transaction(function () use ($validated, $request, &$newBlog) {
                if ($request->hasFile('feature_picture')) {
                    $image = $request->file('feature_picture');
                    $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                    Storage::putFileAs('public/blogs/images', $image, $filename);
                    $validated['featured_image'] = 'storage/blogs/images/' . $filename;
                }

                $this->model->create($validated);

                //category sync
                $blog = $this->model->latest('created_at')->first();
                $newBlog = $blog;
                $blog->blogCategories()->sync($validated['blog_category']);
            });


            return $newBlog;
        } catch (\Exception $e) {
            info($e->getMessage());
            error_log($e->getMessage());
            return false;
        }
    }

    public function storeContent($request)
    {
        try {

            $contentTitles = $request->input('contentTitle');
            $descriptions = $request->input('description');
            $blogId = $request->input('blog_id');
            $sequence = $request->input('sequence');

            foreach ($contentTitles as $key => $title) {

                Content::create([
                    'title' => $title,
                    'description' => $descriptions[$key],
                    'sequence' => $sequence[$key],
                    'blog_id' => $blogId
                ]);
            }

            return $blogId;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function blogUpdate($id)
    {

        try {
            $data = $this->findById($id);
            return $data;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function updateContentView($id)
    {
        try {
            $data = $this->content->where('blog_id', $id)->get();
            return $data;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function storeSeo($request)
    {
        try {

            DB::transaction(function () use ($request) {

                $blog = $this->findById($request->input('blog_id'));

                $blog->update([
                    'index_status' => $request->input('index_status') == '1' ? 1 : 2,
                    'meta_title' => $request->input('meta_title'),
                    'meta_description' => $request->input('meta_description')
                ]);

                $postKeys = $request->input('post_key');
                $postValues = $request->input('post_value');


                foreach ($postKeys as $index => $postKey) {
                    if ($postKey != null && $postValues[$index] != null) {
                        $this->postLink->create([
                            'key' => $postKey,
                            'value' => $postValues[$index],
                            'blog_id' => $request->input('blog_id')
                        ]);
                    }
                }

                $type = $request->input('type');
                $script = $request->input('script');

                foreach ($type as $items => $type) {
                    if ($type != null && $script[$items] != null) {
                        $this->postScript->create([
                            'type' => $type,
                            'script' => $script[$items],
                            'blog_id' => $request->input('blog_id')
                        ]);
                    }
                }
            });

            return true;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function updateContentUpdate($request)
    {
        try {
            $data = $this->model->latest('created_at')->first();
            $data->update($request->all());
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

    public function publish($data){
        try {
            DB::transaction(function () use ($data) {

                $this->model->where('id', $data['blog_id'])->update(['ispublished' => $data['ispublished'] == 'true' ? 1 : 0]);

            });

            return true;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }

    }
    public function destroy($id)
    {

        try {
            DB::transaction(function () use ($id) {
                $this->findById($id)->forceDelete();
                $this->content->where('blog_id', $id)->delete();
                $this->postLink->where('blog_id', $id)->delete();
                $this->postScript->where('blog_id', $id)->delete();
            });

            return true;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * @param $id
     * @return mixed
     */

    public function findById($id)
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
     * @return mixed
     */
    public function view($id)
    {

        try {
            $data = $this->model->with('postLinks', 'postScripts', 'authors', 'blogCategories', 'contents')->where('id', $id)->first();
            return $data;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }


    /**
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {

        try {
            $data = $this->findById($id);
            return $data;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    /**
     * @param $validated
     * @return bool
     */

    public function update($validated, $request)
    {
        try {

            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['title'], '-');
            }

            $data = $this->findById($validated['blog_id']);

            if ($request->hasFile('feature_picture')) {

                $image = $request->file('feature_picture');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/blogs/images', $image, $filename);
                $validated['featured_image'] = 'storage/blogs/images/' . $filename;


                $existingImagePath = $data->featured_image;
                $existingImagePath = public_path($existingImagePath);

                if ($existingImagePath && File::exists($existingImagePath)) {
                    File::delete($existingImagePath);
                }
            }

            $data->update($validated);
            $data->blogCategories()->sync($validated['blog_category']);

            return $data->id;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            info($e);
            return false;
        }
    }

    public function updateContent($request)
    {
        try {
            $blogId = $request->input('blog_id');

            DB::transaction(function () use ($request, $blogId) {
                $this->content->where('blog_id', $blogId)->delete();

                $contentTitles = $request->input('contentTitle');
                $sequence = $request->input('sequence');
                $descriptions = $request->input('description');

                foreach ($contentTitles as $key => $title) {
                     Content::create([
                        'title' => $title,
                        'description' => $descriptions[$key],
                        'sequence' =>$sequence[$key],
                        'blog_id' => $blogId
                    ]);
                }

                //update blog updated_at
                $blog = Blog::where('id', $blogId)->first();
                $blog->touch();
            });

            return $blogId;
        } catch (\Exception $e) {
            info($e);
            return false;
        }
    }

    public function updateSeoView($id)
    {
        try {
            $data = $this->model->where('id', $id)->with(['postLinks', 'postScripts'])->get();

            return $data;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }


    public function updateSeo($request)
    {
        return DB::transaction(function () use ($request) {
            try {
                $blog = $this->findById($request->input('blog_id'));

                $blog->update([
                    'index_status' => $request->input('index_status') == '1' ? 1 : 2,
                    'meta_title' => $request->input('meta_title'),
                    'meta_description' => $request->input('meta_description')
                ]);

                $blog->touch();

                $postKeys = $request->input('post_key');
                $postValues = $request->input('post_value');

                $this->postLink->where('blog_id', $blog->id)->delete();

                foreach ($postKeys as $index => $postKey) {
                    $this->postLink->create([
                        'key' => $postKey,
                        'value' => $postValues[$index],
                        'blog_id' => $request->input('blog_id')
                    ]);
                }

                $type = $request->input('type');
                $script = $request->input('script');

                $this->postScript->where('blog_id', $blog->id)->delete();

                foreach ($type as $items => $type) {
                    $this->postScript->create([
                        'type' => $type,
                        'script' => $script[$items],
                        'blog_id' => $request->input('blog_id')
                    ]);
                }

                return true;
            } catch (\Exception $e) {
                error_log($e->getMessage());
                return [];
            }
        });
    }

    public function postLink($id)
    {
        try {
            $data = $this->postLink->where('blog_id', $id)->get();
            return $data;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function postScript($id)
    {
        try {
            $data = $this->postScript->where('blog_id', $id)->get();
            return $data;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }


    /**
     * @param $validated
     * @return array
     */

    public function apiIndex($category, $search)
    {
        try {
            if ($category) {
                $blogs = $this->model->with('blogCategories', 'authors', 'contents')->whereHas('blogCategories', function ($query) use ($category) {
                    $query->where('name', $category);
                })
                ->where('ispublished', 1)
                ->when($search, function ($query) use ($search) {
                    $query->where('title', 'LIKE', '%' . $search . '%');
                })
                ->orderBy('published_at', 'desc')
                ->paginate(6);
            } else {
                $blogs = $this->model->with('blogCategories', 'authors','contents')
                ->orderBy('published_at', 'desc')
                ->where('ispublished', 1)
                ->when($search, function ($query) use ($search) {
                    $query->where('title', 'LIKE', '%' . $search . '%');
                })
                ->paginate(6);
            }
            $categories = $this->blogCategory->all();
            return [
                'status' => 'success',
                'message' => 'Blogs fetched successfully.',
                'blogs' => $blogs,
                'categories' => $categories
            ];
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'blogs' => [],
                'categories' => []
            ];
        }
    }

    public function apiShow($category = null, $slug)
    {
        try {
            return $this->model->with('postLinks', 'postScripts', 'authors', 'blogCategories', 'contents')->where('slug', $slug)->whereHas(
                'blogCategories',
                function ($query) use ($category) {
                    $query->where('name', $category);
                }
            )->first();
        } catch (\Exception $e) {
            error_log($e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => '',
            ];
        }
    }

    public function apiAllBlogSlugs()
    {
        try {
            $data = $this->model->select('slug')->get();

            return $data;
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }
}
