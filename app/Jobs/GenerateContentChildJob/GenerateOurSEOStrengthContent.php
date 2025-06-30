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

class GenerateOurSEOStrengthContent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $AIModel;
    public $contentId;
    public $fieldName;
    public $prompt;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($AIModel,$contentId, $fieldName, $prompt)
    {
        $this->AIModel = $AIModel;
        $this->contentId = $contentId;
        $this->fieldName = $fieldName;
        $this->prompt = $prompt;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $aiContentGenerate = new AIContentGenerate();
        $ourStrengthContent = $aiContentGenerate->generateContentApiCall($this->AIModel, $this->prompt);

        $countryOrCityPageContent = CountryOrCityWisePageContent::find($this->contentId);

        if ($countryOrCityPageContent) {
            $countryOrCityPageContent->update([
                $this->fieldName => $ourStrengthContent,
            ]);
        }
    }
}
