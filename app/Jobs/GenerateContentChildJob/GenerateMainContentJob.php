<?php

namespace App\Jobs\GenerateContentChildJob;

use Illuminate\Support\Facades\Bus;
use App\Models\CountryOrCityWisePageContent;
use App\Services\AIContentGenerate\AIContentGenerate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GenerateMainContentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $createdBy;
    public $AIModel;
    public $prompt;
    public $location;
    public $whyChooseUsPrompt;
    public $seoMarketInsightPrompt;
    public $transformBusinessPrompt;
    public $caseStudies;
    public $metaKeywordsPrompt;
    public $metaDescriptionPrompt;
    public $industryExpertPrompt;
    public $experiencedPrompt;
    public $projectManagerPrompt;
    public $writersPrompt;
    public $reportingPrompt;
    public $roiPrompt;
    public $faqPrompt;
    public $imageLink;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        $createdBy,
        $AIModel,
        $prompt,
        $location,
        $whyChooseUsPrompt,
        $seoMarketInsightPrompt,
        $transformBusinessPrompt,
        $caseStudies,
        $metaDescriptionPrompt,
        $industryExpertPrompt,
        $experiencedPrompt,
        $projectManagerPrompt,
        $writersPrompt,
        $reportingPrompt,
        $roiPrompt,
        $faqPrompt,
        $imageLink = null,
        )
    {
        $this->createdBy = $createdBy;
        $this->AIModel = $AIModel;
        $this->prompt = $prompt;
        $this->location = $location;
        $this->whyChooseUsPrompt = $whyChooseUsPrompt;
        $this->seoMarketInsightPrompt = $seoMarketInsightPrompt;
        $this->transformBusinessPrompt = $transformBusinessPrompt;
        $this->caseStudies = $caseStudies;
        $this->metaDescriptionPrompt = $metaDescriptionPrompt;
        $this->industryExpertPrompt = $industryExpertPrompt;
        $this->experiencedPrompt = $experiencedPrompt;
        $this->projectManagerPrompt = $projectManagerPrompt;
        $this->writersPrompt = $writersPrompt;
        $this->reportingPrompt = $reportingPrompt;
        $this->roiPrompt = $roiPrompt;
        $this->faqPrompt = $faqPrompt;
        $this->imageLink = $imageLink;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $existingPage = CountryOrCityWisePageContent::where('locations', $this->location)->first();
        if ($existingPage) {
            Log::info("Page for location '{$this->location}' already exists. Skipping creation.");
            return;
        }

        $aiContentGenerate = new AIContentGenerate();
        $mainContent = $aiContentGenerate->generateContentApiCall($this->AIModel, $this->prompt);

        [$section1Content2, $section1Content3] = explode(" || ", $mainContent);

        $countryOrCityPageContent = CountryOrCityWisePageContent::Create(
            [
                'page_title' => $section1Content2,
                'page_url' => Str::slug($section1Content2),
                'section_1_content_2' => $section1Content2,
                'section_1_content_3' => $section1Content3,
                'section_1_content_4' => 'Client Revenue Generated',
                'section_1_content_5' => 'Years of SEO Excellence',
                'section_1_content_6' => 'Verified Client Reviews',
                'section_1_content_7' => 'Client Retention Rate',
                'section_4_content_1' => 'Our SEO Strength',
                'section_6_case_studies' => json_encode($this->caseStudies),
                'section_2_content_1' => "Get Started With {$this->location} SEO Service",
                'locations' => $this->location,
                'link' => env('FRONTEND_URL')  .'/'.  Str::slug($section1Content2),
                'ai_model' => $this->AIModel,
                'section_10_content_1' => $this->imageLink,
                'index' => 2,
                'is_published' => 2,
                'status' => 'Processing',
                'section_11_content_1_faq' => "How VISER X SEO Service Help Businesses Rank in {$this->location}?",
                'section_11_content_2_faq' => "VISER X boosts local rankings in {$this->location} through tailored SEO strategies, combining data-driven insights with proven techniques to drive traffic and grow your business.",
                'created_by' => $this->createdBy,
            ]
        );

        Bus::chain([
            new GenerateWhyChooseUsContentJob($this->AIModel, $countryOrCityPageContent->id, $this->whyChooseUsPrompt),
            new GenerateOurSEOStrengthContent($this->AIModel, $countryOrCityPageContent->id, 'section_4_content_2', $this->industryExpertPrompt),
            new GenerateOurSEOStrengthContent($this->AIModel, $countryOrCityPageContent->id, 'section_4_content_3', $this->experiencedPrompt),
            new GenerateOurSEOStrengthContent($this->AIModel, $countryOrCityPageContent->id, 'section_4_content_4', $this->projectManagerPrompt),
            new GenerateOurSEOStrengthContent($this->AIModel, $countryOrCityPageContent->id, 'section_4_content_5', $this->writersPrompt),
            new GenerateOurSEOStrengthContent($this->AIModel, $countryOrCityPageContent->id, 'section_4_content_6', $this->reportingPrompt),
            new GenerateOurSEOStrengthContent($this->AIModel, $countryOrCityPageContent->id, 'section_4_content_7', $this->roiPrompt),
            new GenerateSeoMarketInshightContentJob($this->AIModel, $countryOrCityPageContent->id, $this->seoMarketInsightPrompt),
            new GenerateTransformBusinessContentJob($this->AIModel, $countryOrCityPageContent->id, $this->transformBusinessPrompt),
            new GenerateSeoContent($this->AIModel, $countryOrCityPageContent->id, $this->metaDescriptionPrompt),
            // new GenerateFAQContentJob($this->AIModel, $countryOrCityPageContent->id, $this->faqPrompt),
            new FinalizeContentGenerationJob($countryOrCityPageContent->id),
        ])->dispatch();
    }
}
