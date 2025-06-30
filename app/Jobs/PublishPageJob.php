<?php

namespace App\Jobs;

use App\Models\CountryOrCityWisePageContent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PublishPageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $pageId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($pageId)
    {
        $this->pageId = $pageId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        CountryOrCityWisePageContent::where('id', $this->pageId)->update([
            'is_published' => 1,
            'published_date_time' => now(),
            'updated_at' => now(),
            'index_published_latest_date_time' => now(),
        ]);
    }
}
