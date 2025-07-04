<?php

namespace App\Exports;

use App\Models\Contact;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ContactExport implements FromCollection, WithHeadings
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
        return Contact::query()
            ->when($this->filters['name'] ?? null, fn($q) => $q->where('name', 'like', '%' . $this->filters['name'] . '%'))
            ->when($this->filters['email'] ?? null, fn($q) => $q->where('email', 'like', '%' . $this->filters['email'] . '%'))
            ->when($this->filters['phone'] ?? null, fn($q) => $q->where('phone', 'like', '%' . $this->filters['phone'] . '%'))
            ->when($this->filters['company_name'] ?? null, fn($q) => $q->where('company', 'like', '%' . $this->filters['company_name'] . '%'))
            ->when($this->filters['from_date'] ?? null, fn($q) => $q->whereDate('created_at', '>=', $this->filters['from_date']))
            ->when($this->filters['to_date'] ?? null, fn($q) => $q->whereDate('created_at', '<=', $this->filters['to_date']))
            ->when($this->filters['status'] ?? null, fn($q) => $q->where('status', $this->filters['status']))
            ->when($this->limit, fn($q) => $q->limit($this->limit))
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    $item->name,
                    $item->phone,
                    $item->email,
                    $item->subject,
                    $item->message,
                    $item->status,
                    $item->created_at->format('Y-m-d H:i:s'),
                    $item->updated_at->format('Y-m-d H:i:s'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Name',
            'Phone',
            'E-mail',
            'Subject',
            'Message',
            'Status',
            'Created At',
            'Updated At',
        ];
    }
}
