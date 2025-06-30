<?php

namespace App\Transformers;

use App\Models\BlogCategory;
use League\Fractal\TransformerAbstract;

class BlogCategoryTransformer extends TransformerAbstract
{
    public function transform(BlogCategory $blogCategory)
    {

        return [
            'id' => $blogCategory->id,
            'name' => $blogCategory->name,
            'created_at' => $blogCategory->created_at,
            'updated_at' => $blogCategory->updated_at,
        ];
    }
}
