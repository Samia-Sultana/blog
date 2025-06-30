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

class GenerateFAQContentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $AIModel;
    public $contentId;
    public $faqPrompt;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($AIModel, $contentId, $faqPrompt)
    {
        $this->AIModel = $AIModel;
        $this->contentId = $contentId;
        $this->faqPrompt = $faqPrompt;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $aiContentGenerate = new AIContentGenerate();
        $faqPromptContent = $aiContentGenerate->generateContentApiCall($this->AIModel, $this->faqPrompt);

        [$section9Content2, $section9Content3] = explode(" || ", $faqPromptContent);
        $countryOrCityPageContent = CountryOrCityWisePageContent::find($this->contentId);

        if ($countryOrCityPageContent) {
            $countryOrCityPageContent->update([
                'section_11_content_1_faq' => $section9Content2,
                'section_11_content_2_faq' => $section9Content3,
            ]);
        }
    }
}
