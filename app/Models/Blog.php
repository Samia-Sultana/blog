<?php

namespace App\Models;

use App\Traits\CreatedBy;
use App\Traits\DeletedBy;
use App\Traits\UpdatedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    use HasFactory, CreatedBy, UpdatedBy, DeletedBy, SoftDeletes;

    protected $fillable = [
        // blog features
        'title',
        'slug',
        'featured_image',
        'author_id',
        'published_at',

        // seo features
        'index_status',
        'meta_title',
        'meta_description',

        'ispublished',

        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // append featured_image_url attribute
    protected $appends = ['featured_image_url'];

    public function getFeaturedImageUrlAttribute()
    {
        return $this->featured_image ? asset($this->featured_image) : null;
    }

    public function blogCategories()
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_category_blog', 'blog_id', 'blog_category_id');
    }

    public function authors()
    {
        return $this->belongsTo(Author::class, 'author_id', 'id');
    }

    public function postLinks()
    {
        return $this->hasMany(PostLink::class, 'blog_id', 'id');
    }

    public function postScripts()
    {
        return $this->hasMany(PostScript::class, 'blog_id', 'id');
    }

    public function contents()
    {
        return $this->hasMany(Content::class, 'blog_id', 'id')->orderBy('sequence', 'asc');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
