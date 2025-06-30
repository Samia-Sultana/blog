<?php

namespace App\Jobs\GenerateContentChildJob;

use App\Models\CountryOrCityWisePageContent;
use App\Services\AIContentGenerate\AIContentGenerate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateSeoContent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $AIModel;
    public $contentId;
    public $metaDescriptionPrompt;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($AIModel, $contentId, $metaDescriptionPrompt)
    {
        $this->AIModel = $AIModel;
        $this->contentId = $contentId;
        $this->metaDescriptionPrompt = $metaDescriptionPrompt;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $aiContentGenerate = new AIContentGenerate();

        $metaDescriptionContent = $aiContentGenerate->generateContentApiCall($this->AIModel, $this->metaDescriptionPrompt);
        $countryOrCityPageContent = CountryOrCityWisePageContent::find($this->contentId);

        if ($countryOrCityPageContent) {
            $countryOrCityPageContent->update([
                'meta' => $metaDescriptionContent
            ]);
        }
    }
}
