<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExport implements FromArray,WithEvents,WithHeadings
{

    protected $products;

    public function __construct(array $products)
    {
        $this->products = $products;
    }

    public function array(): array
    {
        return $this->products;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                    $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(15);
                    $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(10);
            },
        ];
    }

    public function headings(): array
    {
        return [
            'Name',
            'Cost',
        ];
    }

}
