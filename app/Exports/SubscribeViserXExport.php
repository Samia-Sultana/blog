<?php

namespace App\Exports;

use App\Models\SubscribeViserX;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubscribeViserXExport implements FromCollection, WithHeadings
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
        return SubscribeViserX::query()
                ->when($this->filters['email'] ?? null, fn($q) => $q->where('email', 'like', '%' . $this->filters['email'] . '%'))
                ->when($this->filters['from_date'] ?? null, fn($q) => $q->whereDate('created_at', '>=', $this->filters['from_date']))
                ->when($this->filters['to_date'] ?? null, fn($q) => $q->whereDate('created_at', '<=', $this->filters['to_date']))
                ->when($this->limit, fn($q) => $q->limit($this->limit))
                ->latest()
                ->get()
                ->map(function ($item) {
                    return [
                        $item->email,
                        $item->created_at->format('Y-m-d H:i:s'),
                        $item->updated_at->format('Y-m-d H:i:s'),
                    ];
                });
    }

    public function headings(): array
    {
        return [
            'E-mail',
            'Created At',
            'Updated At',
        ];
    }
}
