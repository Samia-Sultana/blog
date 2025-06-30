<?php

namespace App\Console\Commands;

use App\Models\Blog;
use App\Models\Post;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically Generate an XML Sitemap';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $blogSiteMap = Sitemap::create();

        Blog::get()->each(function (Blog $blog) use ($blogSiteMap) {

            $lastModified = $blog->updated_at;

            if (empty($blog->slug_url)) {
                $blogSiteMap->add(
                    Url::create("/blog/{$blog->slug}")
                        ->setPriority(0.9)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setLastModificationDate($lastModified)
                );
            } else {
                $blogSiteMap->add(
                    Url::create("/{$blog->slug_url}")
                        ->setPriority(0.9)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setLastModificationDate($lastModified)
                );
            }

        });

        $blogSiteMap->writeToFile(public_path('sitemap.xml'));
    }
}
