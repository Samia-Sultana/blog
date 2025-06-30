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

class GenerateWhyChooseUsContentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $AIModel;
    public $contentId;
    public $whyChooseUsPrompt;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($AIModel, $contentId, $whyChooseUsPrompt)
    {
        $this->AIModel = $AIModel;
        $this->contentId = $contentId;
        $this->whyChooseUsPrompt = $whyChooseUsPrompt;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $aiContentGenerate = new AIContentGenerate();
        $whyChooseUsContent = $aiContentGenerate->generateContentApiCall($this->AIModel, $this->whyChooseUsPrompt);

        [$section3Content1, $section3Content2] = explode(" || ", $whyChooseUsContent);
        $countryOrCityPageContent = CountryOrCityWisePageContent::find($this->contentId);

        if ($countryOrCityPageContent) {
            $countryOrCityPageContent->update([
                'section_3_content_1' => $section3Content1,
                'section_3_content_2' => $section3Content2,
            ]);
        }
    }
}
