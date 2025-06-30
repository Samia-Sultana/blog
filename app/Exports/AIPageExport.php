<?php

namespace App\Exports;

use App\Models\CountryOrCityWisePageContent;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AIPageExport implements FromCollection, WithHeadings
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
        return CountryOrCityWisePageContent::query()
            ->when($this->filters['page_title'] ?? null, fn($q) => $q->where('page_title', 'like', '%' . $this->filters['page_title'] . '%'))
            ->when($this->filters['query'] ?? null, fn($q) => $q->where('locations', 'like', '%' . $this->filters['query'] . '%'))
            ->when($this->filters['index'] ?? null, fn($q) => $q->where('index', $this->filters['index']))
            ->when($this->filters['published'] ?? null, fn($q) => $q->where('is_published', $this->filters['published']))
            ->when($this->filters['from_date'] ?? null, fn($q) => $q->whereDate('created_at', '>=', $this->filters['from_date']))
            ->when($this->filters['to_date'] ?? null, fn($q) => $q->whereDate('created_at', '<=', $this->filters['to_date']))
            ->when($this->limit, fn($q) => $q->limit($this->limit))
            ->latest()
            ->get()
            ->map(function ($item, $index) {
                return [
                    $index + 1,
                    $item->page_title,
                    $item->page_url ? 'https://viserx.com/'. $item->page_url : null,
                    $item->locations,
                    $item->published_date_time
                        ? \Carbon\Carbon::parse($item->published_date_time)->format('Y-m-d H:i:s')
                        : null,
                    $item->index_date_time
                        ? \Carbon\Carbon::parse($item->index_date_time)->format('Y-m-d H:i:s')
                        : null,
                    $item->updated_at->format('Y-m-d H:i:s'),
                    $item->section_1_content_1
                        ? url('storage/' . $item->section_1_content_1)
                        : ($item->section_10_content_1
                            ? url('storage/' . $item->section_10_content_1)
                            : null),
                ];
            });
    }


    public function headings(): array
    {
        return [
            'SR No',
            'Page Title',
            'Page URL',
            'Location',
            'Published Date',
            'Index Request Date',
            'Last Modified Date',
            'Image',
        ];
    }

}
