<?php

namespace App\Exports;

use App\Models\AIPageContact;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AIPageContactExport implements FromCollection, WithHeadings
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
        return AIPageContact::query()
            ->when($this->filters['name'] ?? null, fn($q) => $q->where('name', 'like', '%' . $this->filters['name'] . '%'))
            ->when($this->filters['email'] ?? null, fn($q) => $q->where('email', 'like', '%' . $this->filters['email'] . '%'))
            ->when($this->filters['contact_number'] ?? null, fn($q) => $q->where('contact_number', 'like', '%' . $this->filters['contact_number'] . '%'))
            ->when($this->filters['location'] ?? null, fn($q) => $q->where('location', $this->filters['location']))
            ->when($this->filters['from_date'] ?? null, fn($q) => $q->whereDate('created_at', '>=', $this->filters['from_date']))
            ->when($this->filters['to_date'] ?? null, fn($q) => $q->whereDate('created_at', '<=', $this->filters['to_date']))
            ->when($this->limit, fn($q) => $q->limit($this->limit))
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    $item->email,
                    $item->name,
                    $item->contact_number,
                    $item->location,
                    $item->website_url,
                    $item->message,
                    $item->created_at->format('Y-m-d H:i:s'),
                    $item->updated_at->format('Y-m-d H:i:s'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'E-mail',
            'Name',
            'Phone',
            'Location',
            'Website Url',
            'Message',
            'Created At',
            'Updated At',
        ];
    }
}
