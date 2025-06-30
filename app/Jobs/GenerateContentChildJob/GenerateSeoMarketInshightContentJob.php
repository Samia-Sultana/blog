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

class GenerateSeoMarketInshightContentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $AIModel;
    public $contentId;
    public $seoMarketInsightPrompt;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($AIModel, $contentId, $seoMarketInsightPrompt)
    {
        $this->AIModel = $AIModel;
        $this->contentId = $contentId;
        $this->seoMarketInsightPrompt = $seoMarketInsightPrompt;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $aiContentGenerate = new AIContentGenerate();
        $seoMarketInsightPrompt = $aiContentGenerate->generateContentApiCall($this->AIModel, $this->seoMarketInsightPrompt);

        [$section8Content2, $section8Content3] = explode(" || ", $seoMarketInsightPrompt);
        $countryOrCityPageContent = CountryOrCityWisePageContent::find($this->contentId);

        if ($countryOrCityPageContent) {
            $countryOrCityPageContent->update([
                'section_8_content_2' => $section8Content2,
                'section_8_content_3' => $section8Content3,
            ]);
        }
    }
}
