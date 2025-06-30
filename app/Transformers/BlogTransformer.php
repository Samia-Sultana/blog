<?php

namespace App\Transformers;

use App\Models\Blog;
use League\Fractal\TransformerAbstract;

class BlogTransformer extends TransformerAbstract
{

    protected array $availableIncludes = ['blogCategories'];

    public function transform(Blog $blog)
    {
        return [
            'id' => $blog->id,
            'title' => $blog->title,
            'feature_picture' => asset($blog->feature_picture),
            'slug' => $blog->slug,
            'slug_url' => $blog->slug_url,
            'read_time' => $blog->read_time,
            'author_id' => $blog->author_id,
            'author' => $blog->author,
            'description' => $blog->description,
            'index_status' => $blog->index_status,
            'canonical_url' => $blog->canonical_url,
            'meta_title' => $blog->meta_title,
            'meta_description' => $blog->meta_description,
            'meta_url' => $blog->meta_url,
            'meta_publish_date' => $blog->meta_publish_date,
            'schema_markup' => $blog->schema_markup,
            'custom_code' => $blog->custom_code,
            'created_at' => $blog->created_at,
            'updated_at' => $blog->updated_at,
            'blog_categories' => $blog->blogCategories,

        ];
    }

    public function includeBlogCategories(Blog $blog)
    {
        return $this->collection($blog->blogCategories, new BlogCategoryTransformer());
    }
}
