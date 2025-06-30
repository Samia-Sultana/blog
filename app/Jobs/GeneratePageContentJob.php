<?php

namespace App\Jobs;

use App\Services\AIContentGenerate\AIContentGenerate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GeneratePageContentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $createdBy;
    public $AIModel;
    public $country;
    public $city;
    public $imageLink;

    public function __construct($createdBy, $AIModel, $country, $city = null, $imageLink = null)
    {
        $this->createdBy = $createdBy;
        $this->AIModel = $AIModel;
        $this->country = $country;
        $this->city = $city;
        $this->imageLink = $imageLink;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $aiGenerateContent = new AIContentGenerate();
        $aiGenerateContent->generateAndStoreContent($this->createdBy, $this->AIModel, $this->country, $this->city, $this->imageLink);
    }
}
