<?php

namespace App\Exports;

use App\Enums\ApplicationTypeEnum;
use App\Models\Application;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JobApplicationExport implements FromCollection, WithHeadings
{
    protected $filters;
    protected $limit;

    public function __construct(array $filters = [], ?int $limit = null)
    {
        $this->filters = $filters;
        $this->limit = $limit;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Application::with(['positionLabel', 'statusLabel', 'deparmentLabel'])
            ->when($this->filters['name'] ?? null, fn($q) => $q->where('name', 'like', '%' . $this->filters['name'] . '%'))
            ->when($this->filters['email'] ?? null, fn($q) => $q->where('email', 'like', '%' . $this->filters['email'] . '%'))
            ->when($this->filters['phone'] ?? null, fn($q) => $q->where('phone', 'like', '%' . $this->filters['phone'] . '%'))
            ->when($this->filters['position_label'] ?? null, fn($q) => $q->where('position_id', $this->filters['position_label']))
            ->when($this->filters['status_label'] ?? null, fn($q) => $q->where('label_status_id', $this->filters['status_label']))
            ->when($this->filters['department_label'] ?? null, fn($q) => $q->where('department_id', $this->filters['department_label']))
            ->when($this->filters['application_type'] ?? null, fn($q) => $q->where('application_type', $this->filters['application_type']))
            ->when($this->filters['from_date'] ?? null, fn($q) => $q->whereDate('created_at', '>=', $this->filters['from_date']))
            ->when($this->filters['to_date'] ?? null, fn($q) => $q->whereDate('created_at', '<=', $this->filters['to_date']))
            ->when($this->limit, fn($q) => $q->limit($this->limit))
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    $item->name,
                    $item->email,
                    $item->contact,
                    $item->location,
                    $item->expected_salary,
                    $item->experience,
                    $item->cv_file ? url($item->cv_file) : null,
                    optional($item->statusLabel)->name,
                    optional($item->positionLabel)->name,
                    optional($item->deparmentLabel)->name,
                    $item->applying_position,
                    ApplicationTypeEnum::tryFrom($item->application_type)
                    ? ApplicationTypeEnum::getString(ApplicationTypeEnum::from($item->application_type))
                    : 'N/A',
                    $item->created_at->format('Y-m-d H:i:s'),
                    $item->updated_at->format('Y-m-d H:i:s'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Contact',
            'Location',
            'Expected Salary',
            'Experience',
            'CV File',
            'Status Label',
            'Position Label',
            'Department Label',
            'Applying Position',
            'Application Type',
            'Created At',
            'Updated At',
        ];
    }
}
