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

class GenerateTransformBusinessContentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $AIModel;
    public $contentId;
    public $transformBusinessPrompt;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($AIModel, $contentId, $transformBusinessPrompt)
    {
        $this->AIModel = $AIModel;
        $this->contentId = $contentId;
        $this->transformBusinessPrompt = $transformBusinessPrompt;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $aiContentGenerate = new AIContentGenerate();
        $transformBusinessPrompt = $aiContentGenerate->generateContentApiCall($this->AIModel, $this->transformBusinessPrompt);

        [$section9Content2, $section9Content3] = explode(" || ", $transformBusinessPrompt);
        $countryOrCityPageContent = CountryOrCityWisePageContent::find($this->contentId);

        if ($countryOrCityPageContent) {
            $countryOrCityPageContent->update([
                'section_9_content_2' => $section9Content2,
                'section_9_content_3' => $section9Content3,
            ]);
        }
    }
}
