<?php

namespace App\Http\Controllers\StatusLabel;

use App\Enums\StatusLabelEnum;
use App\Http\Controllers\Controller;
use App\Models\StatusLabel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StatusLabelController extends Controller
{
    public function __construct(private StatusLabel $statusLabel)
    {

    }

    public function index(Request $request)
    {
        $breadcrumbs = [
            ['link' => "/status-label", 'name' => "Status Label"]
        ];

        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $statusLabels = $this->statusLabel
        ->when(request('type'), function ($query) {
            $query->where('type', request('type'));
        })
        ->orderBy($sortBy, $sortOrder)
        ->paginate(10)
        ->appends($request->except('page'));
        return view('statusLabel.index', compact('breadcrumbs', 'statusLabels'));
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|unique:status_labels|max:255',
            'type' => 'required',
            'text_color' => 'required|string',
            'background_color' => 'required|string',
        ]);
        StatusLabel::create($validatedData);

        Session::flash('success', 'Status Label successfully created.');

        return redirect()->back();
    }

    public function edit($id)
    {
        $statusLabelData = StatusLabel::findOrFail($id);

        return response()->json($statusLabelData);
    }

    public function update(Request $request, $id)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required',
            'text_color' => 'required|string',
            'background_color' => 'required|string',
        ]);

        $statusLabel = StatusLabel::find($id);

        $statusLabel->update($validatedData);

        Session::flash('success', 'Status Label successfully updated.');

        return redirect()->back();
    }

    public function delete(Request $request)
    {

        $statusLabelId = $request->input('status_label_id');
        $statusLabel = StatusLabel::findOrFail($statusLabelId);

        $statusLabel->delete();

        Session::flash('success', 'Status Label successfully deleted.');

        return redirect()->back();
    }

    public function getDepartments()
    {
        return StatusLabel::where('type', StatusLabelEnum::DEPARTMENT->value)
            ->orderBy('updated_at', 'desc')
            ->get(['id', 'name']);
    }

    public function getPositions()
    {
        return StatusLabel::where('type', StatusLabelEnum::POSITION->value)
            ->orderBy('updated_at', 'desc')
            ->get(['id', 'name']);
    }

}
