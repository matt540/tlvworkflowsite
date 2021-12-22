<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
// use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
//, WithStyles
class StorageProductsExport implements FromArray, WithMapping, WithHeadings, ShouldAutoSize, WithEvents
{
    
    protected $products;

    public function __construct(array $products)
    {
        $this->products = $products;
    }

    /*
    public function styles(Worksheet $sheet)
    {
        $styleArray = [
            'font' => ['bold' => true], 
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ]
        ];
        return [
            1    => [
                        'font' => ['bold' => true], 
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                        ]
                    ],
            2    => ['font' => ['bold' => true]],
        ];
    }*/

    public function headings(): array
    {
        return [
            ['TLV Storage Report'],
            [
                'Seller Name',
                'Product Name',
                'SKU',
                'Storage Cost',
                'Storage Start Date'
            ]
        ];
    }

    public function array(): array
    {
        return $this->products;
    }

    public function map($product): array
    {
        return [
            $product['seller_name'],
            $product['name'],
            $product['sku'],
            $product['storage_pricing'],
            $product['storage_date'],
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // $event->sheet->getDelegate()->getParent()->getDefaultStyle()->getFont()->setName('Calibri' );
                $event->sheet->getDelegate()->mergeCells('A1:E1');
                $event->sheet->getDelegate()->getStyle('A1:E2')->getAlignment()->setHorizontal('center');
            },
                        
        ];
    }

    // public function array(): array
    // {
    //     return [
    //         [1, 2, 3],
    //         [4, 5, 6]
    //     ];
    // }
}
