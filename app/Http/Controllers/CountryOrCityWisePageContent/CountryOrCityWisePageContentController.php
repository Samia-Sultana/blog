<?php

namespace App\Http\Controllers\CountryOrCityWisePageContent;

use App\Exports\AIPageContactExport;
use App\Exports\AIPageExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\CountryOrCityWisePageContentRequest;
use App\Jobs\GeneratePageContentJob;
use App\Jobs\PublishPageJob;
use App\Mail\AIPageContactMail;
use App\Mail\ContactMail;
use App\Mail\ViserXMail;
use App\Models\AIPageContact;
use App\Models\CountryOrCityWisePageContent;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CountryOrCityWisePageContentController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = [
            ['link' => "/ai-seo-pages", 'name' => "AI SEO Page"],
            ['name' => "Index"]
        ];

        $pageTitileQuery = $request->input('title_query');
        $searchQuery = $request->input('query');
        $indexPublishedLatestDateTime = $request->input('last_published_index_date');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $index = $request->input('index');
        $published = $request->input('published');

        $perPage = $request->input('per_page', 100);
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        $data = CountryOrCityWisePageContent::with(['createdBy', 'updatedBy'])
            ->when($pageTitileQuery, function ($q) use ($pageTitileQuery) {
                $q->where('page_title', 'like', "%{$pageTitileQuery}%");
            })
            ->when($searchQuery, function ($q) use ($searchQuery) {
                $q->where('locations', 'like', "%{$searchQuery}%");
            })
            ->when($startDate, function ($q) use ($startDate) {
                $q->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($q) use ($endDate) {
                $q->whereDate('created_at', '<=', $endDate);
            })
            ->when($index, function ($q) use ($index) {
                $q->where('index', $index);
            })
            ->when($published, function ($q) use ($published) {
                $q->where('is_published', $published);
            })
             ->when($indexPublishedLatestDateTime, function ($q) use ($indexPublishedLatestDateTime) {
                $q->whereDate('index_published_latest_date_time', $indexPublishedLatestDateTime);
            })
            ->orderBy($sortBy, $sortOrder)
            ->paginate($perPage)
            ->appends($request->except('page')); // Keep filters when paginating

            $dhakaTime = now()->timezone('Asia/Dhaka');
            $defaultDatetime = $dhakaTime->format('Y-m-d\TH:i');
        return view('CountryOrCityWisePageContent.index', compact(
            'data',
            'breadcrumbs',
            'perPage',
            'defaultDatetime',
        ));
    }



    public function create()
    {
        $breadcrumbs = [
            ['link' => "/pages", 'name' => "Page"], ['name' => "Create"]
        ];

        return view('CountryOrCityWisePageContent.create', compact('breadcrumbs'));
    }

    public function store(CountryOrCityWisePageContentRequest $request)
    {
        DB::beginTransaction();

        try {
            $requestData = $request->all();

            if ($request->hasFile('section_1_content_1')) {
                $file = $request->file('section_1_content_1');
                $filePath = $file->store('pages/'.$requestData['page_url'], 'public');
                $requestData['section_1_content_1'] = $filePath;
            }

            if ($request->hasFile('section_8_content_1')) {
                $file = $request->file('section_8_content_1');
                $filePath = $file->store('pages/'.$requestData['page_url'], 'public');
                $requestData['section_8_content_1'] = $filePath;
            }

            if ($request->hasFile('section_9_content_1')) {
                $file = $request->file('section_9_content_1');
                $filePath = $file->store('pages/'.$requestData['page_url'], 'public');
                $requestData['section_9_content_1'] = $filePath;
            }

            $caseStudies = [];
            if ($request->has('section_6_slider_heading')) {
                foreach ($request->section_6_slider_heading as $index => $heading) {
                    $caseStudy = [
                        'heading' => $heading,
                        'description' => $request->section_6_slider_description[$index] ?? null,
                        'image' => null
                    ];

                    // Handle image upload if exists for this case study
                    if ($request->hasFile('section_6_slider_image.'.$index)) {
                        $file = $request->file('section_6_slider_image')[$index];
                        $filePath = $file->store('pages/'.$requestData['page_url'].'/case-studies', 'public');
                        $caseStudy['image'] = $filePath;
                    }

                    $caseStudies[] = $caseStudy;
                }
            }

            $requestData['section_6_case_studies'] = !empty($caseStudies) ? json_encode($caseStudies) : null;

            $requestData['created_by'] = Auth::id();

            CountryOrCityWisePageContent::create($requestData);
            DB::commit();
            return redirect()->route('ai-seo-pages')->with('success', 'Content successfully created.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'An error occurred while saving the content. Please try again later.');
        }
    }

    public function edit($id)
    {
        try {
            $breadcrumbs = [
                ['link' => "/pages", 'name' => "Page"], ['name' => "Edit"]
            ];

            $page = CountryOrCityWisePageContent::find($id);
            return view('CountryOrCityWisePageContent.edit', compact('breadcrumbs', 'page'));
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'An error occurred while saving the content. Please try again later.');
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $page = CountryOrCityWisePageContent::findOrFail($id);

            $requestData = $request->all();

            if ($request->hasFile('section_1_content_1')) {
                if ($page->section_1_content_1 && Storage::exists('public/' . $page->section_1_content_1)) {
                    Storage::delete('public/' . $page->section_1_content_1);
                }
                $file = $request->file('section_1_content_1');
                $filePath = $file->store('pages/'.$requestData['page_url'], 'public');
                $requestData['section_1_content_1'] = $filePath;
            }

            if ($request->hasFile('section_8_content_1')) {
                if ($page->section_8_content_1 && Storage::exists('public/' . $page->section_8_content_1)) {
                    Storage::delete('public/' . $page->section_8_content_1);
                }
                $file = $request->file('section_8_content_1');
                $filePath = $file->store('pages/'.$requestData['page_url'], 'public');
                $requestData['section_8_content_1'] = $filePath;
            }

            if ($request->hasFile('section_9_content_1')) {
                if ($page->section_9_content_1 && Storage::exists('public/' . $page->section_9_content_1)) {
                    Storage::delete('public/' . $page->section_9_content_1);
                }
                $file = $request->file('section_9_content_1');
                $filePath = $file->store('pages/'.$requestData['page_url'], 'public');
                $requestData['section_9_content_1'] = $filePath;
            }

            $caseStudies = [];
            $existingCaseStudies = json_decode($page->section_6_case_studies, true) ?? [];

            if ($request->has('section_6_slider_heading')) {
                foreach ($request->section_6_slider_heading as $index => $heading) {
                    $caseStudy = [
                        'heading' => $heading,
                        'description' => $request->section_6_slider_description[$index] ?? null,
                        'image' => $existingCaseStudies[$index]['image'] ?? null
                    ];

                    // Handle new image upload if exists for this case study
                    if ($request->hasFile('section_6_slider_image.'.$index)) {
                        $file = $request->file('section_6_slider_image')[$index];
                        $filePath = $file->store('pages/'.$requestData['page_url'].'/case-studies', 'public');
                        // Delete old image if exists
                        if (!empty($existingCaseStudies[$index]['image'])) {
                            Storage::disk('public')->delete($existingCaseStudies[$index]['image']);
                        }
                        $caseStudy['image'] = $filePath;
                    }

                    $caseStudies[] = $caseStudy;
                }
            }

            $requestData['section_6_case_studies'] = !empty($caseStudies) ? json_encode($caseStudies) : null;

            $requestData['updated_by'] = Auth::id();

            $page->update($requestData);

            DB::commit();

            return redirect()->route('ai-seo-pages')->with('success', 'Content successfully updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'An error occurred while updating the content. Please try again later.');
        }
    }

    public function delete($id)
    {
        try {
            $page = CountryOrCityWisePageContent::find($id);

            if ($page) {
                if (Storage::exists('public/' . $page->section_1_content_1)) {
                    Storage::delete('public/' . $page->section_1_content_1);
                }
                if (Storage::exists('public/' . $page->section_8_content_1)) {
                    Storage::delete('public/' . $page->section_8_content_1);
                }
                if (Storage::exists('public/' . $page->section_9_content_1)) {
                    Storage::delete('public/' . $page->section_9_content_1);
                }

                $page->delete();

                return redirect()->route('ai-seo-pages')->with('success', 'Page content deleted successfully.');
            }

            return redirect()->route('ai-seo-pages')->with('error', 'Page content not found.');
        } catch (\Exception $e) {
            return redirect()->route('ai-seo-pages')->with('error', 'An error occurred while deleting the page content. Please try again later.');
        }
    }

    public function getBySlug($slug)
    {
        try {
            $page = CountryOrCityWisePageContent::where('page_url', $slug)->where('is_published', 1)->firstOrFail();
            $page['script'] = json_decode($page->script, true);
            $page['section_6_case_studies'] = json_decode($page->section_6_case_studies, true);
            return response()->json($page);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Page not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching the page.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function uploadCountryCity(Request $request)
    {
        $request->validate([
            'ai_model' => 'required|string',
            'file' => 'required|file|mimes:xlsx'
        ]);

        $collection = Excel::toCollection(null, $request->file('file'));

        $rows = $collection->first();

        foreach ($rows as $row) {
            $country = $row[0] ?? null;
            $city = $row[1] ?? null;
            $imageLink = $row[2] ?? null;
            if ($country) {
                GeneratePageContentJob::dispatch(Auth::id(), $request->ai_model, $country, $city, $imageLink);
            }
        }

        return back()->with('success', 'Country/City data uploaded and processing started.');
    }

    public function bulkDelete(Request $request)
    {
        try {
            if (!is_array($request->ids) || empty($request->ids)) {
                return response()->json(['message' => 'No pages selected.'], 422);
            }
            CountryOrCityWisePageContent::whereIn('id', $request->ids)->delete();

            return response()->json(['message' => 'Pages deleted successfully.'], 200);
        } catch (Exception $e) {

            return response()->json([
                'message' => 'An error occurred while deleting pages.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function bulkSetIndex(Request $request)
    {
        try {
            if (!is_array($request->ids) || empty($request->ids)) {
                return response()->json(['message' => 'No pages selected.'], 422);
            }

            CountryOrCityWisePageContent::whereIn('id', $request->ids)
                                        ->update([
                                            'index' => $request->index,
                                            'updated_by' => Auth::id(),
                                            'index_date_time' => now(),
                                            'index_published_latest_date_time' => now(),
                                        ]);

            return response()->json(['message' => 'Pages updated successfully.'], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating pages.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function bulkSetbulkSetPublished(Request $request)
    {
        try {
            if (!is_array($request->ids) || empty($request->ids)) {
                return response()->json(['message' => 'No pages selected.'], 422);
            }

            CountryOrCityWisePageContent::whereIn('id', $request->ids)
                        ->update([
                            'is_published' => $request->published,
                            'updated_by' => Auth::id(),
                            'published_date_time' => now(),
                            'index_published_latest_date_time' => now(),
                        ]);

            return response()->json(['message' => 'Pages updated successfully.'], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating pages.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function togglePublish($id)
    {
        $page = CountryOrCityWisePageContent::findOrFail($id);
        $page->is_published = ($page->is_published == 1) ? 2 : 1;
        $page->updated_by = Auth::id();
        $page->published_date_time = now();
        $page->index_published_latest_date_time = now();
        $page->save();

        return back()->with('success', 'Page publication status updated.');
    }


    public function bulkPublishSchedule(Request $request)
    {
        try {
            $request->validate([
                'page_ids' => 'required|string',
                'publish_datetime' => 'required|date|after_or_equal:now',
                'interval' => 'nullable|integer|min:1'
            ]);
            $ids = explode(',', $request->page_ids);
            $baseTime = Carbon::parse($request->publish_datetime);
            $interval = $request->interval ?? 0;

            foreach ($ids as $index => $id) {
                $scheduleTime = $interval > 0 ? $baseTime->copy()->addMinutes($index * $interval) : $baseTime;

                CountryOrCityWisePageContent::where('id', $id)->update([
                    'updated_by' => Auth::id(),
                    'published_date_time_by_job' => $scheduleTime,
                ]);

                PublishPageJob::dispatch($id)->delay($scheduleTime);
            }

            return back()->with('success', 'Pages scheduled for publish.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function getScheduleGraphData(Request $request)
    {
        $isFiltered = $request->filled('start_date') && $request->filled('end_date');

        $startDate = $isFiltered
            ? Carbon::parse($request->start_date)->startOfDay()
            : now();

        $endDate = $isFiltered
            ? Carbon::parse($request->end_date)->endOfDay()
            : null;

        if ($isFiltered) {
            $data = $this->getScheduledJobs($startDate, $endDate);
        } else {
            $cacheKey = 'schedule_graph_data_all_upcoming';

            $data = Cache::rememberForever($cacheKey, function () use ($startDate) {
                return $this->getScheduledJobs($startDate);
            });
        }

        return response()->json(['jobs' => $data]);
    }

    private function getScheduledJobs(Carbon $startDate, ?Carbon $endDate = null)
    {
        return CountryOrCityWisePageContent::query()
            ->whereNotNull('published_date_time_by_job')
            ->when($startDate, fn($q) => $q->where('published_date_time_by_job', '>=', $startDate))
            ->when($endDate, fn($q) => $q->where('published_date_time_by_job', '<=', $endDate))
            ->orderBy('published_date_time_by_job')
            ->get()
            ->map(function ($job) {
                return [
                    'id' => $job->id,
                    'title' => $job->page_title ?? 'Untitled Page',
                    'time' => $job->published_date_time_by_job,
                    'timestamp' => Carbon::parse($job->published_date_time_by_job)->format('d M Y h:i A'),
                ];
            });
    }

    public function clearScheduleGraphCache()
    {
        $cacheKey = 'schedule_graph_data_all_upcoming';
        $cleared = Cache::forget($cacheKey);

        return response()->json([
            'status' => $cleared,
            'message' => $cleared
                ? 'Schedule graph cache cleared successfully.'
                : 'Failed to clear schedule graph cache or cache key did not exist.',
        ]);
    }

    public function getIndexGraphData(Request $request)
    {
        $isFiltered = $request->filled('start_date') && $request->filled('end_date');

        $startDate = $isFiltered
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::now()->subDays(30)->startOfDay();

        $endDate = $isFiltered
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::now()->endOfDay();

        if ($isFiltered) {
            $data = $this->getIndexJobs($startDate, $endDate);
        } else {
            $cacheKey = 'index_graph_data_last_30_days';

            $data = Cache::rememberForever($cacheKey, function () use ($startDate, $endDate) {
                return $this->getIndexJobs($startDate, $endDate);
            });
        }

        return response()->json(['jobs' => $data]);
    }

    private function getIndexJobs(Carbon $startDate, Carbon $endDate)
    {
        return CountryOrCityWisePageContent::query()
            ->whereNotNull('index_published_latest_date_time')
            ->whereBetween('index_published_latest_date_time', [$startDate, $endDate])
            ->orderBy('index_published_latest_date_time')
            ->get()
            ->map(function ($job) {
                return [
                    'id' => $job->id,
                    'title' => $job->page_title ?? 'Untitled Page',
                    'time' => $job->index_published_latest_date_time,
                    'timestamp' => Carbon::parse($job->index_published_latest_date_time)->format('d M Y h:i A'),
                ];
            });
    }

    public function clearIndexGraphCache()
    {
        $cacheKey = 'index_graph_data_last_30_days';
        $cleared = Cache::forget($cacheKey);

        return response()->json([
            'status' => $cleared,
            'message' => $cleared
                ? 'Schedule graph cache cleared successfully.'
                : 'Failed to clear schedule graph cache or cache key did not exist.',
        ]);
    }


    public function contact(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'contact_number' => 'required|string|max:20',
                'website_url' => 'nullable|url',
                'message' => 'nullable|string|max:255',
                'location' => 'nullable|string|max:255'
            ]);

            $ip = $request->ip();
            $response = Http::get("http://ip-api.com/json/$ip?fields=country");
            $country = $response->json()['country'] ?? null;
            $city = $response->json()['city'] ?? null;

            $contact = AIPageContact::create([
                'name' => $request->name,
                'email' => $request->email,
                'contact_number' => $request->contact_number,
                'website_url' => $request->website_url,
                'message' => $request->message,
                'location' => $request->location,
            ]);

            Mail::to($contact->email)->send(new AIPageContactMail($contact, "Thanks For Your Request of Consultation: VISER X - {$contact->location} SEO Service"));

            $adminMailBody = $this->aiPageContactFromMailBodyForAdmin($contact, $ip, $country, $city);

            $ccList = config('viserxMailConfigList');
            $ccList[] = 'sabbirahammed.shuvo@viserx.net';
            Mail::to(env('CONTACT_EMAIL'))->cc($ccList)->send(new ViserXMail(
                                                                            $adminMailBody,
                                                                            "Request For Consultation: VISER X - {$contact->location} SEO Service",
                                                                        ));

            return response()->json(['message' => 'Submited successfully!'], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred. Please try again.'. $e->getMessage()], 500);
        }
    }

    private function aiPageContactFromMailBodyForAdmin($data, $ip, $country, $city)
    {
        $submittedAt = now()->format('jS F, Y');
        return <<<HTML
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0px -5px 14px 5px rgba(0, 0, 0, 0.1);">
            <tr>
                <td align="center" style="background-color: #f4f8fc; padding: 20px;">
                    <h1 style="font-size: 24px; font-weight: bold; margin: 0;"><span style="font-weight: bold; color: #007bff;">VISER</span> X</h1>
                    <p style="margin: 5px 0 0 0; font-size: 16px; color: #666;">{$data->location} SEO Service Contact Form Submission</p>
                </td>
            </tr>
            <tr>
                <td style="padding: 20px 40px; color: #333333; line-height: 1.6;">
                    <p style="margin: 15px 0; font-size: 14px;">
                        A form has been submitted for {$data->location} SEO Service on {$submittedAt} from {$ip} - {$city} {$country}
                    </p>
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8f9fa; border-radius: 5px; padding: 15px; margin: 20px 0; border: 1px solid black">
                        <tr><td style="font-weight: bold; padding: 8px 0;">Name:</td><td>{$data->name}</td></tr>
                        <tr><td style="font-weight: bold; padding: 8px 0;">Email:</td><td>{$data->email}</td></tr>
                        <tr><td style="font-weight: bold; padding: 8px 0;">Phone:</td><td>{$data->contact_number}</td></tr>
                        <tr><td style="font-weight: bold; padding: 8px 0;">Website:</td><td>{$data->website_url}</td></tr>
                        <tr><td style="font-weight: bold; padding: 8px 0;">Message:</td><td>{$data->message}</td></tr>
                    </table>
                    <p style="margin: 15px 0; font-size: 14px;">You can view this submission in the admin panel, please <a href="https://api.viserx.com/ai-page-contacts" target="_blank">click here.</a></p>
                    <p style="margin: 15px 0; font-size: 8px;">This email contains confidential information belonging to VISER X and intended only for the recipient. If you are not the intended recipient, please delete it and notify the sender. Unauthorized use is prohibited.</p>
                </td>
            </tr>

        </table>
        HTML;
    }


    public function aiContactIndex(Request $request)
    {
        $breadcrumbs = [
            ['link' => "/ai-page-contacts", 'name' => "Ai Page Contacts"], ['name' => "Index"]
        ];

        $name = $request->input('name');
        $email = $request->input('email');
        $location = $request->input('location');
        $contactNumber = $request->input('contact_number');
        $startDate = $request->input('from_date');
        $endDate = $request->input('to_date');
        $perPage = $request->input('per_page', 10);


        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        $data = AIPageContact::query()
                    ->when($name, function ($q) use ($name) {
                        $q->where('name', 'like', "%{$name}%");
                    })
                    ->when($email, function ($q) use ($email) {
                        $q->where('email', 'like', "%{$email}%");
                    })
                    ->when($location, function ($q) use ($location) {
                        $q->where('location', 'like', "%{$location}%");
                    })
                    ->when($contactNumber, function ($q) use ($contactNumber) {
                        $q->where('contact_number', 'like', "%{$contactNumber}%");
                    })
                    ->when($startDate, function ($q) use ($startDate) {
                        $q->whereDate('created_at', '>=', $startDate);
                    })
                    ->when($endDate, function ($q) use ($endDate) {
                        $q->whereDate('created_at', '<=', $endDate);
                    })
                    ->orderBy($sortBy, $sortOrder)
                    ->paginate($perPage)
                    ->appends($request->query());
        return view('ai-contact.index', compact('data', 'breadcrumbs', 'perPage'));
    }

    public function export(Request $request)
    {
        $filters = $request->only(['name', 'email', 'contact_number', 'location', 'from_date', 'to_date', 'per_page']);

        return Excel::download(new AIPageContactExport($filters, $request->per_page ?? 10), 'ai_contact.xlsx');
    }

    public function pageExport(Request $request)
    {
        $filters = $request->only(['title_query', 'query', 'start_date', 'end_date', 'index', 'per_page', 'published']);

        return Excel::download(new AIPageExport($filters, $request->per_page ?? 10), 'ai_page.xlsx');
    }
}
