<?php

namespace App\Http\Controllers;

use App\Enums\StatusLabelEnum;
use App\Models\JobVacancy;
use App\Models\StatusLabel;
use App\Repositories\JobVacancy\JobVacancyRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class JobVacancyController extends Controller
{
    private JobVacancyRepository $repository;

    public function __construct(JobVacancyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function view_job()  {
        return $this->repository->jobView();
    }

    public function view_job_single($id)  {
        return $this->repository->jobViewSingle($id);
    }
    public function view(Request $request)
    {
        $breadcrumbs = [
            ['link' => "/job-portal", 'name' => "Job portal"], ['name' => "Index"]
        ];
        $searchQuery = $request->input('query');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $data = $this->repository->index($searchQuery, $sortBy, $sortOrder);
        return view('jobPortal.index', compact('data', 'breadcrumbs', 'searchQuery'));
    }

    public function create()
    {
        $breadcrumbs = [
            ['link' => "/job-portal", 'name' => "Job portal"], ['name' => "Index"]
        ];

        $old = new \stdClass();
        $old->employment_statuses = json_encode([
            'Full-time',
            '9:00 AM to 6:00 PM',
            'Sunday to Thursday (Weekly 2 days off)',
            'Working From Office'
        ]);

        $old->benefits = json_encode([
            "Weekly Two holiday (Friday & Saturday)",
            "Lunch facilities: Partially Subsidized",
            "Festival bonus: 2 (yearly)",
            "Evening Snacks",
            "12 days casual leaves per year",
            "6 days sick leaves per year",
            "Paternity leave, maternity leave, and bereavement leave as per company policy",
            "Government holidays as per company policy",
            "Un-utilized leave encashment at the year-end",
            "Training and learning materials to improve skills",
            "Gaming Facility (PS4)",
            "Excellent working environment",
            "Yearly retreat",
            "Yearly Salary Review"
        ]);

        $old->about_company = "Hossain Litigation & Law is a global information technology, software development and digital marketing agency. We have a team of 40+ energetic and talented team members successfully providing software development, web application development and digital marketing services worldwide.";

        $old->address ="Dhaka";

        $positionLabels = StatusLabel::where('type', StatusLabelEnum::POSITION->value)->get();

        return view('jobPortal.create', compact('breadcrumbs', 'old', 'positionLabels'));
    }

    public function store(Request $request)
    {
        $request->except('_token');

        DB::beginTransaction();
        try {
            $imagePath = '';

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                Storage::putFileAs('public/jobportal', $image, $filename);
                $imagePath = 'storage/jobportal/' . $filename;
            }

            $this->repository->store($request->all(),$imagePath);

            DB::commit();
            return redirect()->route('job-portal')->with('success', 'Job successfully created.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Optional: Log the exception
            Log::error('Job creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->withInput()->with('error', 'Failed to create job. Please try again.');
        }
    }

    public function edit($id)
    {

        $breadcrumbs = [
            ['link' => "/job-portal", 'name' => "Job portal"], ['name' => "Index"]
        ];


        $old = $this->repository->edit($id);

        $positionLabels = StatusLabel::where('type', StatusLabelEnum::POSITION->value)->get();

        return view('jobPortal.edit', compact('old', 'breadcrumbs', 'positionLabels'));
    }

    public function update(Request $request, $id)
    {
        $request->except('_token');

        $imagePath = '';
        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            Storage::putFileAs('public/jobportal', $image, $filename);
            $imagePath = 'storage/jobportal/' . $filename;
        }

        $this->repository->update($request->all(),$imagePath, $id);

        return redirect()->route('job-portal')->with('success', 'Job successfully created.');
    }

    public function delete($id)
    {
        $this->repository->delete($id);

        return redirect()->route('job-portal')->with('success', 'Job successfully deleted.');
    }

    public function job_publish(Request $request)
    {
        $this->repository->job_publish($request->all());
        return redirect()->route('job-portal')->with('success', 'Job successfully published.');
    }

    public function copy($id)
    {
        $originalJob = JobVacancy::findOrFail($id);

        $newJob = $originalJob->replicate();
        $newJob->created_at = now();
        $newJob->updated_at = now();
        $newJob->save();

        return response()->json([
            'message' => 'Job copied successfully',
            'data' => $newJob
        ]);
    }

}
