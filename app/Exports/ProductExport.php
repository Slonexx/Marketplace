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
                    $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(25);
                    $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(15);
                    $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(15);
                    $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(15);
                    $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(15);
                    $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(15);
                    $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(15);
                    $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(15);
                    $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(15);
            },
        ];
    }

    public function headings(): array
    {
        return [
            'SKU',
            'model',
            'brand',
            'price',
            'PP1',
            'PP2',
            'PP3',
            'PP4',
            'PP5',
            'preorder'
        ];
    }

}
