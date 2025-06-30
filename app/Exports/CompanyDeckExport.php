<?php

namespace App\Exports;

use App\Models\CompanyDeck;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CompanyDeckExport implements FromCollection, WithHeadings
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
        return CompanyDeck::query()
            ->when($this->filters['name'] ?? null, fn($q) => $q->where('name', 'like', '%' . $this->filters['name'] . '%'))
            ->when($this->filters['email'] ?? null, fn($q) => $q->where('email', 'like', '%' . $this->filters['email'] . '%'))
            ->when($this->filters['contact_number'] ?? null, fn($q) => $q->where('contact_number', 'like', '%' . $this->filters['phone'] . '%'))
            ->when($this->filters['industry'] ?? null, fn($q) => $q->where('industry', $this->filters['industry']))
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
                    $item->industry,
                    $item->month_marketing_budget,
                    $item->website_url,
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
            'Industry',
            'Month Marketing Budget',
            'Website Url',
            'Created At',
            'Updated At',
        ];
    }
}
