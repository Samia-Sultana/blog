<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Blog\BlogController;
use App\Http\Controllers\Blog\BlogCategoryController;


Route::middleware(['auth',  'module:blog', 'module:blog-category'])->group(function () {

    // blog category resource route
    Route::get('/blog-category', [BlogCategoryController::class, 'index'])->name('blog-category');
    Route::post('/blog-category', [BlogCategoryController::class, 'store'])->name('create-blog-category');
    Route::get('/blog-category/{id}/edit', [BlogCategoryController::class, 'edit'])->name('edit-blog-category');
    // Route::put('/blog-category/{id}', [BlogCategoryController::class, 'update'])->name('update-user');
    Route::delete('/blog-category', [BlogCategoryController::class, 'destroy'])->name('delete-blog-category');


    // blog routes
    Route::get('/blog', [BlogController::class, 'search'])->name('blog'); //blog list
    Route::get('/blog/create', [BlogController::class, 'createBlogView'])->name('create-blog'); //blog create view
    Route::post('/blog/post', [BlogController::class, 'storeBlog'])->name('store-blog'); //blog create
    Route::match(['put', 'get'], '/blog/update/{id}', [BlogController::class, 'createContentView'])->name('update-blog'); //content view
    Route::post('/blog/content', [BlogController::class, 'storeContent'])->name('create-content'); //blog content create
    Route::match(['put', 'get'], '/blog/seo/{id}', [BlogController::class, 'createSeoView'])->name('seo-blog'); //seo view
    Route::post('/blog/seo', [BlogController::class, 'storeSeo'])->name('store-seo'); //seo create

    Route::get('/blog/{id}/edit', [BlogController::class, 'edit'])->name('edit-blog'); //blog edit view
    Route::put('/blog/{id}', [BlogController::class, 'update'])->name('update-blog'); //update blog

    Route::post('/blog/publish', [BlogController::class, 'publish_blog'])->name('publish-blog'); //update blog


    Route::match(['put', 'get'], '/blog/content/update/{id}', [BlogController::class, 'updateContentView'])->name('update-blog-content'); //update content view
    Route::post('/blog/content/update', [BlogController::class, 'updateContent'])->name('update-content'); //blog content update
    Route::match(['put', 'get'], '/blog/seo/update/{id}', [BlogController::class, 'updateSeoView'])->name('seo-blog-update'); //seo update view
    Route::post('/blog/seo/update', [BlogController::class, 'updateSeo'])->name('update-seo'); //seo update


Route::get('/back', function () { return redirect('/blog'); })->name('redirect-blog');
    //search
    // Route::post('/blog', [BlogController::class, 'search'])->name('search.blog'); //blog list





    // Route::get('/blog/seo', [BlogController::class, 'updateSeoView'])->name('update-seo');
    Route::delete('/blog', [BlogController::class, 'destroy'])->name('delete-blog');
    Route::get('/blog/{id}/view', [BlogController::class, 'view'])->name('view-blog');
});
