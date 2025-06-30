<?php

namespace App\Http\Controllers\Contact;

use App\Models\Contact;
use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Mail\ViserXMail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Exports\ContactExport;
use Maatwebsite\Excel\Facades\Excel;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'email' => 'nullable|email|max:255',
                'company' => 'nullable|string|max:255',
                'subject' => 'nullable|string|max:255',
                'subsubject' => 'nullable|string|max:255',
                'message' => 'nullable|string|max:255',
            ]);

            $ip = $request->ip();
            $response = Http::get("http://ip-api.com/json/$ip?fields=country");
            $country = $response->json()['country'] ?? null;

            $contact = new Contact();
            $contact->name = $request->name;
            $contact->phone = $request->phone;
            $contact->email = $request->email;
            $contact->company = $request->company;
            $contact->subject = $request->subject;
            $contact->subsubject = $request->subsubject;
            $contact->message = $request->message;
            $contact->country = $country;
            $contact->save();

            Mail::to($contact->email)->send(new ContactMail($contact, "Thanks For Your Request of Consultation: VISER X - {$contact->subject}"));
            $ccList = config('viserxMailConfigList');
            $data = $this->contactUsMailBodyForAdmin($contact, $ip);
            if ($country == 'Bangladesh') {
                Mail::to(env('CONTACT_EMAIL_BD'))->cc($ccList)->send(new ViserXMail($data, "Request For Consultation: VISER X - {$contact->subject}"));
            }else{
                Mail::to(env('CONTACT_EMAIL'))->cc($ccList)->send(new ViserXMail($data, "Request For Consultation: VISER X - {$contact->subject}"));
            }

            return response()->json(['message' => 'Contact created successfully'], 201);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json($e->getMessage(), 500);
        }
    }

    private function contactUsMailBodyForAdmin($data, $ip)
    {
        $submittedAt = now()->format('F d, Y, h:i A');
        return <<<HTML
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0px -5px 14px 5px rgba(0, 0, 0, 0.1);">
            <tr>
                <td align="center" style="background-color: #f4f8fc; padding: 20px;">
                    <h1 style="font-size: 24px; font-weight: bold; margin: 0;"><span style="font-weight: bold; color: #007bff;">VISER</span> X</h1>
                    <p style="margin: 5px 0 0 0; font-size: 16px; color: #666;">Empowering Digital Presence</p>
                </td>
            </tr>
            <tr>
                <td style="padding: 20px 40px; color: #333333; line-height: 1.6;">
                    <h5 style="margin: 15px 0; font-size: 14px; font-weight: bold;">Hello,</h5>
                    <p style="margin: 15px 0; font-size: 14px;">
                        A new request for consulation has been submitted from {$ip} ($data->country):
                    </p>
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8f9fa; border-radius: 5px; padding: 15px; margin: 20px 0; border: 1px solid black">
                        <tr><td style="font-weight: bold; padding: 8px 0;">Name:</td><td>{$data->name}</td></tr>
                        <tr><td style="font-weight: bold; padding: 8px 0;">Email:</td><td>{$data->email}</td></tr>
                        <tr><td style="font-weight: bold; padding: 8px 0;">Phone:</td><td>{$data->phone}</td></tr>
                        <tr><td style="font-weight: bold; padding: 8px 0;">Company:</td><td>{$data->company}</td></tr>
                        <tr><td style="font-weight: bold; padding: 8px 0;">Subject:</td><td>{$data->subject}</td></tr>
                        <tr><td style="font-weight: bold; padding: 8px 0;">Sub-Subject:</td><td>{$data->subsubject}</td></tr>
                        <tr><td style="font-weight: bold; padding: 8px 0;">Message:</td><td>{$data->message}</td></tr>
                        <tr><td style="font-weight: bold; padding: 8px 0;">Submitted At:</td><td>{$submittedAt}</td></tr>
                    </table>
                    <p style="margin: 15px 0; font-size: 14px;">You can view this submission in the admin panel, please <a href="https://api.viserx.com/contacts" target="_blank">click here.</a></p>
                    <p style="margin: 15px 0; font-size: 8px;">This email contains confidential information belonging to VISER X and intended only for the recipient. If you are not the intended recipient, please delete it and notify the sender. Unauthorized use is prohibited.</p>
                </td>
            </tr>
        </table>
        HTML;
    }

    public function index(Request $request)
    {
        $breadcrumbs = [
            ['link' => "/contacts", 'name' => "Contacts"], ['name' => "Index"]
        ];

        $name = $request->input('name');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $companyName = $request->input('company_name');
        $startDate = $request->input('from_date');
        $endDate = $request->input('to_date');
        $status = $request->input('status');
        $perPage = $request->input('per_page', 10);
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        $data = Contact::query()
                    ->when($name, function ($q) use ($name) {
                        $q->where('name', 'like', "%{$name}%");
                    })
                    ->when($email, function ($q) use ($email) {
                        $q->where('email', 'like', "%{$email}%");
                    })
                    ->when($phone, function ($q) use ($phone) {
                        $q->where('phone', 'like', "%{$phone}%");
                    })
                    ->when($companyName, function ($q) use ($companyName) {
                        $q->where('company', 'like', "%{$companyName}%");
                    })
                    ->when($startDate, function ($q) use ($startDate) {
                        $q->whereDate('created_at', '>=', $startDate);
                    })
                    ->when($endDate, function ($q) use ($endDate) {
                        $q->whereDate('created_at', '<=', $endDate);
                    })
                    ->when($status, function ($q) use ($status) {
                        $q->where('status', $status);
                    })
                    ->orderBy($sortBy, $sortOrder)
                    ->paginate($perPage)
                    ->appends($request->query());
        return view('contacts.index', compact('data', 'breadcrumbs', 'perPage'));
    }

    public function show($id)
    {
        try {
            $breadcrumbs = [
                ['link' => "/contacts", 'name' => "Contacts Details"], ['name' => "Show"]
            ];
            $contact = Contact::findOrFail($id);
            return view('contacts.show', compact('contact', 'breadcrumbs'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Failed to update status.');
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => ['required'],
            ]);

            $contact = Contact::findOrFail($id);

            $contact->status = $request->status;
            $contact->save();

            return redirect()->back()->with('success', 'Status updated successfully.');

        } catch (\Throwable $th) {
            Log::error('Error updating contact status: ' . $th->getMessage());

            return redirect()->back()->with('error', 'Failed to update status.');
        }
    }

    public function export(Request $request)
    {
        $filters = $request->only(['name', 'email', 'phone', 'company_name', 'from_date', 'to_date', 'status', 'per_page']);

        return Excel::download(new ContactExport($filters, $request->per_page ?? 10), 'contacts.xlsx');
    }
}
