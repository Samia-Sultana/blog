<?php

namespace App\Http\Controllers\JobApplication;

use App\Enums\StatusLabelEnum;
use App\Exports\JobApplicationExport;
use App\Http\Controllers\Controller;
use App\Mail\ApplicationResponseMail;
use App\Mail\ApplicationResponseMailForOutside;
use App\Mail\ViserXMail;
use App\Models\Application;
use App\Models\StatusLabel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class JobApplicationController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = [
            ['link' => "/job/application", 'name' => "Application"]
        ];

        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $perPage = $request->input('per_page', 10);

        $positionLabels = StatusLabel::where('type', StatusLabelEnum::POSITION->value)->get();
        $statusLabels = StatusLabel::where('type', StatusLabelEnum::STATUS->value)->get();
        $departmentLabels = StatusLabel::where('type', StatusLabelEnum::DEPARTMENT->value)->get();

        $applicationQuery = Application::with(['positionLabel', 'statusLabel', 'deparmentLabel']);

        // Apply position filter if selected
        if ($request->has('position_label') && $request->position_label != '') {
            $applicationQuery->where('position_id', $request->position_label);
        }

        // Apply status filter if selected
        if ($request->has('status_label') && $request->status_label != '') {
            $applicationQuery->where('label_status_id', $request->status_label);
        }

        if ($request->has('department_label') && $request->department_label != '') {
            $applicationQuery->where('department_id', $request->department_label);
        }

        if ($request->has('name') && $request->name != '') {
            $applicationQuery->where('name', 'like', '%' . $request->name . '%');
        }

        // Apply email filter
        if ($request->has('email') && $request->email != '') {
            $applicationQuery->where('email', 'like', '%' . $request->email . '%');
        }

        // Apply date range filter
        if ($request->has('from_date') && $request->from_date != '') {
            $applicationQuery->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date != '') {
            $applicationQuery->whereDate('created_at', '<=', $request->to_date);
        }

        if ($request->has('phone') && $request->phone != '') {
            $applicationQuery->where('contact', 'like', '%' . $request->phone . '%');
        }
        if ($request->has('application_type') && $request->application_type != '') {
            $applicationQuery->where('application_type', $request->application_type);
        }

        $applications = $applicationQuery
                        ->orderBy($sortBy, $sortOrder)
                        ->paginate($perPage)
                        ->appends($request->query());

        return view('application.index', compact('applications', 'breadcrumbs', 'positionLabels', 'statusLabels', 'departmentLabels', 'perPage'));
    }

    public function storeApplication(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validation rules
            $validator = Validator::make($request->all(), [
                'name'             => 'required|string|max:255',
                'email'            => 'required|email|max:255',
                'contact'          => 'required|string|max:20',
                'location'         => 'required|string|max:255',
                'position_id'      => [
                                    'nullable',
                                    'integer',
                                    function ($attribute, $value, $fail) {
                                        $exists = StatusLabel::where('id', $value)
                                            ->where('type', StatusLabelEnum::POSITION->value)
                                            ->exists();

                                        if (! $exists) {
                                            $fail('The selected position is invalid.');
                                        }
                                    }
                                ],
                'department_id'      => [
                                    'nullable',
                                    'integer',
                                    function ($attribute, $value, $fail) {
                                        $exists = StatusLabel::where('id', $value)
                                            ->where('type', StatusLabelEnum::DEPARTMENT->value)
                                            ->exists();

                                        if (! $exists) {
                                            $fail('The selected department is invalid.');
                                        }
                                    }
                                ],
                'expected_salary'  => 'required|string|max:255',
                'experience'       => 'required|string|max:255',
                'cv_file'          => 'required|file|max:2048',
                'label_status_id'  => 'nullable|exists:status_labels,id',
                'applying_position' => 'nullable',
                'application_type' => 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Handle file upload
            $validatedData = $validator->validated();

            if ($request->hasFile('cv_file')) {
                $file = $request->file('cv_file');
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/cv_files', $filename);
                $validatedData['cv_file'] = 'storage/cv_files/' . $filename;
            }

            $application = Application::create($validatedData);

            $adminMailBody = $this->cvSubmiteMailBodyForAdmin($application);

            if ($application->application_type == \App\Enums\ApplicationTypeEnum::IN_SIDE->value) {
                $positionName = StatusLabel::where('id', $application->position_id)->first();
                Mail::to($application->email)->send(new ApplicationResponseMail($application->name, $positionName->name));
                Mail::to(env('CONTACT_EMAIL_HR'))->send(new ViserXMail($adminMailBody, "{$application->name} Submitted CV for {$positionName->name}"));
            } else if ($application->application_type == \App\Enums\ApplicationTypeEnum::OUT_SIDE->value){
                $departmentName = StatusLabel::where('id', $application->department_id)->first();
                Mail::to($application->email)->send(new ApplicationResponseMailForOutside($application->name));
                Mail::to(env('CONTACT_EMAIL_HR'))->send(new ViserXMail($adminMailBody, "{$application->name} Submitted CV for {$departmentName->name}"));
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Application submitted successfully.',
                'data' => $application,
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while submitting the application.',
                'error' => $e->getMessage(), // Consider logging this instead in production
            ], 500);
        }
    }

    public function view($id)
    {
        $application = Application::with(['positionLabel', 'statusLabel'])->findOrFail($id);
        $breadcrumbs = [
            ['link' => "/job/application", 'name' => "Applications"],
            ['name' => "View Application"]
        ];
        $statuses = StatusLabel::where('type', StatusLabelEnum::STATUS->value)->get();

        return view('application.view', compact('application', 'breadcrumbs', 'statuses'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_id' => 'required|exists:status_labels,id',
        ]);

        $application = Application::findOrFail($id);
        $application->label_status_id = $request->status_id;
        $application->save();

        return redirect()->back()->with('success', 'Application status updated successfully!');
    }

    private function cvSubmiteMailBodyForAdmin($data)
    {
        $submittedAt = now()->format('jS F, Y');
        $position = $data->position_id ? StatusLabel::find($data->position_id)->name : 'Not specified';
        $cvUrl = url($data->cv_file);

        $positionRow = '';
        if ($data->position_id) {
            $position = StatusLabel::find($data->position_id)->name;
            $positionRow = <<<HTML
            <tr>
                <td style="font-weight: bold;  vertical-align: top;">Position:</td>
                <td style="">{$position}</td>
            </tr>
            HTML;
        }

        $departmentRow = '';
        if ($data->department_id) {
            $department = StatusLabel::find($data->department_id)->name;
            $departmentRow = <<<HTML
            <tr>
                <td style="font-weight: bold;  vertical-align: top;">Applying Position:</td>
                <td style="">{$data->applying_position}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;  vertical-align: top;">Department:</td>
                <td style="">{$department}</td>
            </tr>
            HTML;
        }

        return <<<HTML
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0px -5px 14px 5px rgba(0, 0, 0, 0.1);">
            <tr>
                <td align="center" style="background-color: #f4f8fc; padding: 20px;">
                    <h1 style="font-size: 24px; font-weight: bold; margin: 0;"><span style="font-weight: bold; color: #007bff;">VISER</span> X</h1>
                </td>
            </tr>
            <tr>
                <td style="padding: 20px 40px; color: #333333; line-height: 1.6;">
                    <h5 style="margin: 15px 0; font-size: 14px; font-weight: bold;">Dear HR Team,</h5>
                    <p style="margin: 15px 0; font-size: 14px;">
                        A new job application has been submitted through the VISER X career portal at {$submittedAt}. Please find the details below:
                    </p>

                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8f9fa; border-radius: 5px; padding: 15px; margin: 20px 0; border: 1px solid #e0e0e0;">
                        <tr>
                            <td width="30%" style="font-weight: bold;  vertical-align: top;">Full Name:</td>
                            <td style="">{$data->name}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;  vertical-align: top;">E-mail:</td>
                            <td style="">{$data->email}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;  vertical-align: top;">Contact No:</td>
                            <td style="">{$data->contact}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;  vertical-align: top;">Location:</td>
                            <td style="">{$data->location}</td>
                        </tr>
                        {$positionRow}
                        {$departmentRow}
                        <tr>
                            <td style="font-weight: bold;  vertical-align: top;">Expected Salary (BDT):</td>
                            <td style="">{$data->expected_salary}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;  vertical-align: top;">Experience:</td>
                            <td style="">{$data->experience}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold;  vertical-align: top;">CV Attachment:</td>
                            <td style="">
                                <a href="{$cvUrl}" target="_blank" style="color: #007bff; text-decoration: underline;">Download CV</a>
                            </td>
                        </tr>
                    </table>

                    <p style="margin: 15px 0; font-size: 14px;">
                        Please log this entry in your applicant tracking system and proceed with the recruitment process as appropriate. <br>
                        <strong>Regards</strong>
                    </p>
                </td>
            </tr>
        </table>
        HTML;
    }

    public function export(Request $request)
    {
        $filters = $request->only(['position_label', 'status_label', 'department_label', 'name', 'email', 'from_date', 'to_date', 'phone' ,'application_type', 'per_page']);

        return Excel::download(new JobApplicationExport($filters, $request->per_page ?? 10), 'job-application.xlsx');
    }
}
