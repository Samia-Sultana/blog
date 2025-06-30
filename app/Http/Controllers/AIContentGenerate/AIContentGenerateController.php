<?php

namespace App\Http\Controllers\AIContentGenerate;

use App\Http\Controllers\Controller;
use App\Models\CountryOrCityWisePageContent;
use App\Services\AIContentGenerate\AIContentGenerate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AIContentGenerateController extends Controller
{
    public function __construct(public AIContentGenerate $AIContentGenerateService)
    {

    }
    // only for test and use for external api

    public function generateContentForApi(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'ai_model' => 'required|string',
                'country' => 'required|string',
                'city' => 'nullable|string',
            ]);

            $AIContentGenerateService = new AIContentGenerate();
            $country = $request->country;
            $city = $request->city;
            $location = $city ? "{$city}, {$country}" : $country;

            $currentYear = date('Y');
            $previousYear = $currentYear - 1;

            $mainPrompt = "Create SEO content for VISER X with these EXACT specifications:

                FORMAT:
                Best SEO Service Company In {$location} || [Content]

                CONTENT REQUIREMENTS:
                - Begin the content with a rephrased variation of: 'Win the search rankings with VISER X, the leading SEO service company in {$location}'. Preserve the core message but encourage natural wording changes for uniqueness.
                - The part after the || content must be between 240–250 characters.
                - Include variations of the following two phrases (paraphrased naturally, not exact match)::
                * 'expert strategies according to your business needs'
                * 'results you can see locally and globally'
                - Ensure the content clearly conveys the importance of visibility, rankings, and ROI
                - Include the following two exact keyword phrases (no variation allowed): 'SEO company {$location}', 'best SEO services {$location}'
                - Single paragraph, no line breaks
                - Maintain a professional and persuasive tone appropriate for a business audience
                - No special characters except basic punctuation
                - No asterisks, backticks, or other formatting marks

                OUTPUT EXAMPLE FOR $location}:
                Best SEO Service Company In $location} || Content";

            $whyChooseUsPrompt = "Generate a 'Why Choose Us' section for VISER X in {$location} with these EXACT specifications:

                FORMAT:
                Why is VISER X the Best SEO Agency in {$location}? || [Content]

                CONTENT REQUIREMENTS:
                - Open the section with a reworded version of: 'With projected SEO growth rates of' — retain the original meaning but vary the phrasing for uniqueness.
                - Mention a statistic reflecting SEO growth from {$previousYear} to {$currentYear} — can be estimated or industry-based for realistic relevance.
                - Reference strong client results such as a revenue increase — use a natural variation of the idea behind 'quadrupled revenue.'
                - Highlight benefits VISER X delivers: more qualified leads, better brand visibility, and higher conversion rates — all must be included, but wording can vary.
                - Indicate a 90%+ client retention rate to showcase trust and long-term value.
                - Mention recognition by reputable industry sources like Clutch and Sortlist.
                - Emphasize our analytical and performance-driven SEO methods.
                - Address how VISER X drives both local and global SEO success.
                - Use a tone that is 'professional yet convincing' — informative and sales-oriented without sounding aggressive.
                - Must be 2 to 3 brief paragraphs (but presented in one continuous line with proper punctuation no line breaks allowed).
                - Include keywords: 'SEO agency {$location}', 'best SEO company {$location}'
                - Single paragraph, no line breaks
                - Use only standard punctuation such as periods and commas — avoid any special characters.
                - No asterisks, backticks, or other formatting marks
                - Response format: 'Why is VISER X the Best SEO Agency in {$location}? || [Content]'

                OUTPUT EXAMPLE FOR $location}:
                Why is VISER X the Best SEO Agency in {$location}? || With projected SEO growth rates of ";

            $caseStudies = [
                [
                  "heading" => "SaaS SEO Success Story: 15K Organic Traffic Boost with VISER X",
                  "description" => "VISER X, a 360-degree digital marketing agency, helped a SaaS product skyrocket its organic traffic from nearly zero to over 15,000 through data-driven SEO strategies. Starting from September 2021, our expert team implemented tailored keyword targeting, technical optimization, and content strategies—resulting in 25.2K clicks and 1.37M impressions. This case study highlights our commitment to measurable digital growth."
                ],
                [
                  "heading" => "Service Website Growth: 30K+ Organic Impressions Achieved",
                  "description" => "VISER X took charge of this service-based website's SEO journey in July 2024. Through strategic content planning, technical optimization, and search intent mapping, we drove impressive organic growth. The site scaled from minimal visibility to over 30,000 impressions—garnering nearly 25K clicks and a 2.2% CTR. This transformation showcases our ability to deliver consistent, scalable results in competitive service niches."
                ],
                [
                  "heading" => "Law Firm SEO Breakthrough: 900% Organic Traffic Surge",
                  "description" => "In late September 2020, VISER X began partnering with a law agency to revitalize its online search presence. Through consistent SEO improvements — including advanced keyword research, technical SEO fixes, and strategic content optimization — the agency achieved a remarkable 900% increase in organic traffic. Impressions soared from under 1,000 to nearly 12,000 within months, and the site's visibility and ranking positions improved dramatically, positioning the firm as a top player in its local market."
                ],
                [
                  "heading" => "eCommerce SEO Success: 750% Organic Growth Achieved",
                  "description" => "Starting in late April 2022, VISER X partnered with an eCommerce brand to overhaul its SEO strategy. Through meticulous keyword targeting, product page optimization, and technical site enhancements, we drove a 750% boost in organic traffic. Impressions skyrocketed from under 2,000 to over 15,000 daily, while clicks surged to 43K+. This growth positioned the business to capture high-intent searchers, improving both visibility and conversions across competitive categories."
                ]
            ];

            $seoMarketInsightPrompt = "Generate a content section titled 'SEO Market Insights in {$location}' with these EXACT specifications:

                FORMAT:
                SEO Market Insights in {$location} || [Content]

                CONTENT REQUIREMENTS:
                - Begin the section with a natural variation of: 'SEO is reshaping the business landscape in {$location}' — maintain the message but allow for natural rephrasing to enable unique outputs.
                - Include the following 'three statistics' in a smooth, data-backed narrative, change the sentences but the meaning will remain same and where the percentage appears, please use a unique percentage between the range mentioned:
                    1. Local businesses experienced a 70%–80% boost in sales via SEO in recent years.
                    2. Around 28%-48% of consumers now prefer to search and purchase online from home.
                    3. 70%-85% of shoppers check Google before buying something locally.
                - Emphasize Google’s overwhelming '98.17% market share' dominance in {$location} (if {$location} is {$location} or a city within it)
                - Mention that 'VISER X SEO performance' delivered 'over 1000% more traffic' than social media channels — phrasing can vary, but the core message must remain.
                - The tone must be 'persuasive, evidence-based, and professional' — suitable for decision-makers and business clients.
                - Ensure all three of these **keywords** are naturally included:
                    1. 'SEO services in {$location}'
                    2. 'best SEO company {$location}'
                    3. 'search engine visibility'
                - Structure the response in one compact and informative paragraphs. Each paragraph must flow logically and maintain engagement.
                - Structure the section in one clear paragraphs with appropriate punctuation — no line breaks like that \'n\'n.
                - avoid any special formatting symbols like *, /, \'n, or #.
                - No special characters except basic punctuation
                - No asterisks, backticks, or other formatting marks
                - Length: 220–260 characters after `||` for first paragraph; second can go beyond
                ";

            $transformBusinessPrompt = "Generate a content section titled 'Transform Your Business with the Best SEO Service in {$location}' with these EXACT specifications:

                FORMAT:
                Transform Your Business with the Best SEO Service in {$location} || [Content]

                CONTENT REQUIREMENTS:
                - Start with a rephrased variation of: 'VISER X is among the best SEO agencies in {$location}' — retain meaning but use unique phrasing to make each output different.
                - Highlight that VISER X is trusted by global brands and industry leaders — this message must remain, but the wording can be adjusted.
                - Include that we’ve worked with 5000+ clients across 61+ countries — can be reworded for flow, but numbers must remain accurate.
                - Reference well-known clients such as: Stanford University, Macy's, BRAC, BAT, ShareTrip, Apple Gadgets — all must be mentioned (order or phrasing may vary).
                - Mention core services such as search engine reputation management and business profile optimization — include these phrases naturally.
                - Explain the key benefits clients get: improved brand visibility, stronger search engine rankings, and responsive client communication — include all, phrased in varied ways.
                - Include the following exact keyword phrases somewhere in the content:
                    1. 'SEO agency {$location}'
                    2. 'best SEO services {$location}'
                    3. 'trusted SEO partner'
                - Maintain a tone that’s professional, confident, and convincingly persuasive — position VISER X as a high-authority, globally trusted SEO company.
                - Ensure the content is engaging and meets the brand voice, while also being suitable for SEO-focused landing or service pages.
                - Structure the section in one clear paragraphs with appropriate punctuation — no line breaks.
                - Use only standard punctuation (periods, commas).
                - avoid any special formatting symbols like *, /, \'n, or #.
                - No special characters except basic punctuation
                - No asterisks, backticks, or other formatting marks";


            $metaKeywordsPrompt = "Generate ONLY a comma-separated list of SEO meta keywords for VISER X services in {$location} with these exact specifications:
                - Provide ONLY the keywords, nothing else
                - 8-12 relevant keywords/phrases
                - Include location-specific variations
                - Include service variations
                - Strictly comma-separated with no numbers, bullets, or other formatting
                - No introductory text or explanations
                - No special characters except commas
                - Example output: 'SEO services {$location}, best SEO company {$location}, search engine optimization, local SEO services, digital marketing agency {$location}'";

            $metaDescriptionPrompt = "Generate an SEO meta description for VISER X SEO services in {$location} following these strict rules:
                1. Provide ONLY the plain text description, absolutely nothing else
                2. Length must be 155 characters
                3. Must include: 'SEO Services {$location}'
                4. Should include: my company name 'VISER X'
                5. Highlight: increased visibility, higher rankings, better ROI
                6. Include a call-to-action
                7. Write in a way so that my target audience enticed to click it from the search result and I can outrank my top competitors.
                8. No quotes, no parentheses, no character counts
                9. No line breaks or special formatting
                10. Example output: VISER X provides premier SEO services in {$location} to boost your online visibility and rankings. Our expert strategies deliver measurable ROI for your business.

                DO NOT INCLUDE:
                - Quotation marks
                - Character counts
                - Any text outside the description itself
                - Any formatting symbols";

            $industryExpertPrompt = "Generate clean plain-text content for VISER X 'Our SEO Strength' section with these exact requirements:

                1. CONTENT RULES:
                - Start the sentence with a natural variation of: 'VISER X SEO specialists bring' — rewording allowed as long as the message and structure are preserved.
                - Total length must be strictly 30 to 35 words and no more than 200 characters including spaces.
                - You must naturally include the following exact phrases (phrasing cannot change)::
                    1. 'unrivaled expertise'
                    2. 'drive digital growth'
                    3. 'propel your business'
                    4. 'new heights'
                    5. 'digital landscape'
                - The following keywords must also appear naturally:
                    1. 'SEO specialists'
                    2. 'digital growth strategies'
                    3. 'VISER X'
                - The content must communicate: expertise, growth, business results
                    1. the expertise of VISER X team
                    2. how we enable business growth
                    3. and our impact on business results in the digital space
                - Deliver the content as a single paragraph, with no line breaks.
                - Tone should be professional and persuasive — focused on confidence and credibility.
                - No special characters except ,.!?
                - No formatting marks (*,`, etc.)

                2. OUTPUT EXAMPLE:
                VISER X SEO specialists bring unrivaled expertise to drive digital growth. We propel businesses to new heights in the digital landscape through proven growth strategies.

                3. DELIVERY FORMAT:
                Return only the plain text content without any labels, quotes, or formatting. Do not include character counts or notes.";

            $experiencedPrompt = "Generate clean plain-text content for VISER X 'Experienced' section with these exact requirements:

                1. CONTENT RULES:
                - Begin the sentence with a variation of: 'With years of hands-on experience' — keep the meaning but vary the structure slightly for uniqueness.
                - Keep total length strictly within 30–35 words and maximum 200 characters including spaces.
                - You must include all of the following exact phrases:
                    1. SEO service in {$location}
                    2. exceptional results
                    3. business growth
                - Also include these keywords in a natural way:
                    1. 'proven expertise',
                    2. 'VISER X',
                    3. 'SEO strategies'
                - The message must clearly express:
                    1. VISER X years of experience
                    2. A track record of delivering results
                    3. A strong emphasis on client business growth
                - Tone: Confident and results-driven
                - No special characters except ,.!?
                - Format: Single paragraph, no line breaks
                - No special characters except ,.!?
                - No formatting marks (*,`, etc.)

                2. OUTPUT EXAMPLE:
                With years of hands-on experience in SEO service in {$location}, VISER X delivers exceptional results through tailored strategies that drive sustainable business growth and market dominance.";
            $projectManagerPrompt = "Generate clean plain-text content for VISER X 'Dedicated Project Manager' section with these exact requirements:

                1. CONTENT RULES:
                - Start with a natural variation of: 'Experience seamless communication with' — rephrase allowed but meaning intact.
                - Content length must be strictly between 30 and 35 words and not exceed 200 characters including spaces.
                - Must include these exact phrases anywhere in the paragraph:
                    1. 'dedicated SEO Manager'
                    2. 'oversee every aspect'
                    3. 'your satisfaction'
                - Include these keywords naturally:
                    1. 'personalized support',
                    2. 'VISER X',
                    3. 'accountability'
                - Tone should be service-oriented and reassuring.
                - Format: Single paragraph, no line breaks
                - No special characters except ,.!?
                - No formatting marks (*,`, etc.)

                2. OUTPUT EXAMPLE:
                Experience seamless communication with a dedicated SEO Manager from VISER X, overseeing every aspect of your campaign to ensure satisfaction and measurable success.";
            $writersPrompt = "Generate clean plain-text content for VISER X 'Dedicated Writers' section with these exact requirements:

                1. CONTENT RULES:
                - Begin with a natural variation of: 'Content is the key to' — you may rephrase slightly but maintain the core message.
                - Length must be strictly 30 to 35 words and not exceed 200 characters including spaces.
                - Must include these exact phrases somewhere in the paragraph:
                    1. addictive content
                    2. brand popularity
                    3. search engine optimization
                - Also include these keywords naturally:
                    1. 'transparent analytics'
                    2. 'VISER X'
                    3. 'performance tracking'
                - Tone must be creative and authoritative.
                - No line breaks
                - Format: Single paragraph, no line breaks
                - No special characters except ,.!?
                - No formatting marks (*,`, etc.)

                2. OUTPUT EXAMPLE:
                Content is the key to search engine optimization. VISER X writers craft addictive content that boosts brand popularity while aligning with cutting-edge SEO best practices.";
            $reportingPrompt = "Generate clean plain-text content for VISER X 'SEO Reporting' section with these exact requirements:

                1. CONTENT RULES:
                - Start with a natural variation of: 'Stay informed with' — slight rephrasing allowed, core meaning must remain.
                - Length must be strictly 30 to 35 words and not exceed 200 characters including spaces.
                - Must include these exact phrases anywhere in the paragraph:
                    1. keyword rankings
                    2. website health
                    3. data-driven insights
                - Include these keywords naturally:
                    1. 'transparent analytics'
                    2. 'VISER X'
                    3. 'performance tracking'
                - Tone should be technical yet clear and accessible.
                - Format: Single paragraph, no line breaks
                - No special characters except ,.!?
                - No formatting marks (*,`, etc.)

                2. OUTPUT EXAMPLE:
                Stay informed with VISER X transparent analytics tracking keyword rankings, conversions, and website health. Our clinical reports deliver data-driven insights to optimize your digital performance.";
            $roiPrompt = "Generate clean plain-text content for VISER X 'Maximize ROI' section with these exact requirements:

                1. CONTENT RULES:
                - Begin with a natural variation of: 'Unlock the power of' — slight rephrasing allowed but core meaning must remain.
                - Length must be strictly between 30 and 35 words and not exceed 200 characters including spaces.
                - Must include these exact phrases anywhere in the paragraph:
                    1. 'ROI-driven strategies'
                    2. 'business growth'
                    3. 'measurable results'
                - Include these keywords naturally:
                    1. 'profit-focused SEO'
                    2. 'VISER X'
                    3. 'conversion optimization'
                - Tone should be persuasive and focused on metrics.
                - No special characters
                - Format: Single paragraph, no line breaks
                - No special characters except ,.!?
                - No formatting marks (*,`, etc.)

                2. OUTPUT EXAMPLE:
                Unlock the power of ROI-driven strategies with VISER X. We transform business growth into measurable results through profit-focused SEO and conversion optimization techniques.";

            $faqPrompt = "Generate an FAQ section for VISER X in {$location} with these EXACT specifications:

                FORMAT:
                How Are SEO Services Helping Businesses Rank in {$location}? || [Content]

                CONTENT REQUIREMENTS:
                - Begin naturally with a variation of: 'SEO services help businesses in {$location}...' — slight rewording allowed but meaning must stay intact.'
                - Explain main benefits: online visibility, targeted traffic, and conversion growth.
                - Include both local and global SEO aspects.
                - Highlight VISER X’s unique approach using phrases similar to 'tailored strategies using data-driven insights and proven techniques.'
                - Mention outcomes like top rankings, improved domain authority, and sustainable growth.
                - Refer to competitive {$location} markets.
                - Use keywords exactly: 'SEO services {$location}' and 'rank higher in {$location}' within the content.
                - Keep tone professional and concise.
                - Single paragraph, no line breaks
                - No special characters except basic punctuation
                - No asterisks, backticks, or formatting marks
                - Character count must not exceed 200 characters including spaces and punctuation
                - Strict length max 200 chars with spaces
                - Response format: 'How Are SEO Services Helping Businesses Rank in {$location}? || [Content]'

                OUTPUT EXAMPLE:
                How Are SEO Services Helping Businesses Rank in {$location}? || SEO services help businesses in {$location}...";


            $mainContent = $AIContentGenerateService->generateContentApiCall($request->input('ai_model'), $mainPrompt);
            $industryExpertContent = $AIContentGenerateService->generateContentApiCall($request->input('ai_model'), $industryExpertPrompt);
            $experiencedPromptContent = $AIContentGenerateService->generateContentApiCall($request->input('ai_model'), $experiencedPrompt);
            $projectManagerPromptContent = $AIContentGenerateService->generateContentApiCall($request->input('ai_model'), $projectManagerPrompt);
            $writersPromptContent = $AIContentGenerateService->generateContentApiCall($request->input('ai_model'), $writersPrompt);
            $reportingPromptContent = $AIContentGenerateService->generateContentApiCall($request->input('ai_model'), $reportingPrompt);
            $roiPromptContent = $AIContentGenerateService->generateContentApiCall($request->input('ai_model'), $roiPrompt);
            $whyChooseUsContent = $AIContentGenerateService->generateContentApiCall($request->input('ai_model'), $whyChooseUsPrompt);
            $seoMarketInsights = $AIContentGenerateService->generateContentApiCall($request->input('ai_model'), $seoMarketInsightPrompt);
            $transformBusinessContent = $AIContentGenerateService->generateContentApiCall($request->input('ai_model'), $transformBusinessPrompt);
            $faqPromptContent = $AIContentGenerateService->generateContentApiCall($request->input('ai_model'), $faqPrompt);
            $metaDescriptionContent = $AIContentGenerateService->generateContentApiCall($request->input('ai_model'), $metaDescriptionPrompt);


            [$section1Content2, $section1Content3] = explode(" || ", $mainContent);

            [$section3Content1, $section3Content2] = explode(" || ", $whyChooseUsContent);

            [$section8Content2, $section8Content3] = explode(" || ", $seoMarketInsights);

            [$section9Content2, $section9Content3] = explode(" || ", $transformBusinessContent);

            [$section11Content1, $section11Content2] = explode(" || ", $faqPromptContent);


            CountryOrCityWisePageContent::create(
                [
                    'page_title' => $section1Content2,
                    'page_url' => Str::slug($section1Content2),
                    'section_1_content_2' => $section1Content2,
                    'section_1_content_3' => $section1Content3,
                    'section_1_content_4' => 'Client Revenue Generated',
                    'section_1_content_5' => 'Years of SEO Excellence',
                    'section_1_content_6' => 'Verified Client Reviews',
                    'section_1_content_7' => 'Client Retention Rate',
                    'section_3_content_1' => $section3Content1,
                    'section_3_content_2' => $section3Content2,
                    'section_4_content_1' => 'Our SEO Strength',
                    'section_4_content_2' => $industryExpertContent,
                    'section_4_content_3' => $experiencedPromptContent,
                    'section_4_content_4' => $projectManagerPromptContent,
                    'section_4_content_5' => $writersPromptContent,
                    'section_4_content_6' => $reportingPromptContent,
                    'section_4_content_7' => $roiPromptContent,
                    'section_6_case_studies' => json_encode($caseStudies),
                    'section_8_content_2' => $section8Content2,
                    'section_8_content_3' => $section8Content3,
                    'section_9_content_2' => $section9Content2,
                    'section_9_content_3' => $section9Content3,
                    'section_2_content_1' => "Get Started With {$location} SEO Service",
                    "locations" => $location,
                    'meta' => $metaDescriptionContent,
                    'link' => env('FRONTEND_URL') .'/'. Str::slug($section1Content2),
                    'section_11_content_1_faq' => "How VISER X SEO Service Help Businesses Rank in {$location}?",
                    'section_11_content_2_faq' => "VISER X boosts local rankings in {$location} through tailored SEO strategies, combining data-driven insights with proven techniques to drive traffic and grow your business.",
                ]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'content' => [
                    'main_content' => $mainContent,
                    'why_choose_us' => $whyChooseUsContent,
                    'case_studies' => $caseStudies,
                    'seo_market_insights' => $seoMarketInsights,
                    'transform_business' => $transformBusinessContent,
                    // 'meta' => $metaDescriptionContent,
                    'industry_expert' => $industryExpertContent,
                    'experienced' => $experiencedPromptContent,
                    'project_manager' => $projectManagerPromptContent,
                    'writers' => $writersPromptContent,
                    'reporting' => $reportingPromptContent,
                    'roi' => $roiPromptContent,
                    'faq' => $faqPromptContent,
                ],
                'prompts_used' => [
                    'main_prompt' => $mainPrompt,
                    'why_choose_us_prompt' => $whyChooseUsPrompt,
                    'seo_market_insight_prompt' => $seoMarketInsightPrompt,
                    'transform_business_prompt' => $transformBusinessPrompt,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate content: ' . $e->getMessage(),
            ], 500);
        }
    }

    // only for test and use for external api with job

    // public function generateContentForApi(Request $request)
    // {
    //     $request->validate([
    //         'ai_model' => 'required|string',
    //         'country' => 'required|string',
    //         'city' => 'nullable|string',
    //     ]);

    //     try {
    //         $result = $this->AIContentGenerateService->generateAndStoreContent(
    //             Auth::id(),
    //             $request->ai_model,
    //             $request->country,
    //             $request->city
    //         );

    //         return response()->json($result);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to generate content: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }
}
