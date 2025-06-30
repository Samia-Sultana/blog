<?php

namespace App\Http\Controllers;

use App\Exports\CompanyDeckExport;
use App\Mail\CompanyDeckConfirmationMail;
use App\Mail\ViserXMail;
use App\Models\CompanyDeck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class CompanyDeckController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'contact_number' => 'required|string|max:20',
                'website_url' => 'nullable|url',
                'month_marketing_budget' => 'nullable|numeric',
                'industry' => 'nullable|string|max:255',
            ]);

            $companyDeck = CompanyDeck::create([
                'name' => $request->name,
                'contact_number' => $request->contact_number,
                'email' => $request->email,
                'website_url' => $request->website_url,
                'month_marketing_budget' => $request->month_marketing_budget,
                'industry' => $request->industry,
            ]);


            $pdfUrl = asset('pdf/VISER_X_Company_Profile.pdf');
            Mail::to($companyDeck->email)->send(new CompanyDeckConfirmationMail($companyDeck->email, $pdfUrl, $companyDeck->name));

            $adminMailBody = $this->companyDeckMailBodyForAdmin($companyDeck);

            $ccList = config('viserxMailConfigList');

            Mail::to(env('CONTACT_EMAIL_BD'))->cc($ccList)->send(new ViserXMail($adminMailBody, 'VISER X | New Request for Company Deck'));

            return response()->json(['message' => 'Email sent successfully!'], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred. Please try again.'. $e->getMessage()], 500);
        }
    }


    private function companyDeckMailBodyForAdmin($companyDeck)
    {
        $submittedAt = now()->format('F d, Y, h:i A');
        return <<<HTML
        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0px -5px 14px 5px rgba(0, 0, 0, 0.1);">
            <tr>
                <td align="center" style="background-color: #f4f8fc; padding: 20px;">
                    <h1 style="font-size: 24px; font-weight: bold; margin: 0;"><span style="font-weight: bold; color: #007bff;">VISER</span> X</h1>
                    <p style="margin: 5px 0 0 0; font-size: 16px; color: #666;">New Company Deck Request</p>
                </td>
            </tr>
            <tr>
                <td style="padding: 20px 40px; color: #333333; line-height: 1.6;">
                    <h5 style="margin: 15px 0; font-size: 14px; font-weight: bold;">Hello,</h5>
                    <p style="margin: 15px 0; font-size: 14px;">
                        You've received a new company deck submission. Here are the details:
                    </p>
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8f9fa; border-radius: 5px; padding: 15px; margin: 20px 0; border: 1px solid black">
                        <tr><td style="font-weight: bold; padding: 8px 0;">Name:</td><td>{$companyDeck->name}</td></tr>
                        <tr><td style="font-weight: bold; padding: 8px 0;">Email:</td><td>{$companyDeck->email}</td></tr>
                        <tr><td style="font-weight: bold; padding: 8px 0;">Phone:</td><td>{$companyDeck->contact_number}</td></tr>
                        <tr><td style="font-weight: bold; padding: 8px 0;">Website:</td><td>{$companyDeck->website_url}</td></tr>
                        <tr><td style="font-weight: bold; padding: 8px 0;">Budget:</td><td>{$companyDeck->month_marketing_budget}</td></tr>
                        <tr><td style="font-weight: bold; padding: 8px 0;">Industry:</td><td>{$companyDeck->industry}</td></tr>
                        <tr><td style="font-weight: bold; padding: 8px 0;">Submitted At:</td><td>{$submittedAt}</td></tr>
                    </table>
                    <p style="margin: 15px 0; font-size: 14px;">You can view this submission in the admin panel, please <a href="https://api.viserx.com/company-deck" target="_blank">click here.</a></p>
                </td>
            </tr>
        </table>
        HTML;
    }

    public function index(Request $request)
    {
        $breadcrumbs = [
            ['link' => "/company-deck", 'name' => "Company Deck"], ['name' => "Index"]
        ];

        $name = $request->input('name');
        $email = $request->input('email');
        $contactNumber = $request->input('contact_number');
        $industry = $request->input('industry');
        $startDate = $request->input('from_date');
        $endDate = $request->input('to_date');
        $perPage = $request->input('per_page', 10);
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        $data = CompanyDeck::query()
                    ->when($name, function ($q) use ($name) {
                        $q->where('name', 'like', "%{$name}%");
                    })
                    ->when($email, function ($q) use ($email) {
                        $q->where('email', 'like', "%{$email}%");
                    })
                    ->when($contactNumber, function ($q) use ($contactNumber) {
                        $q->where('contact_number', 'like', "%{$contactNumber}%");
                    })
                    ->when($industry, function ($q) use ($industry) {
                        $q->where('industry', 'like', "%{$industry}%");
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
        return view('companyDeck.index', compact('data', 'breadcrumbs', 'perPage'));
    }

    public function export(Request $request)
    {
        $filters = $request->only(['name', 'email', 'contact_number', 'industry', 'from_date', 'to_date', 'per_page']);

        return Excel::download(new CompanyDeckExport($filters, $request->per_page ?? 10), 'company_deck.xlsx');
    }
}
