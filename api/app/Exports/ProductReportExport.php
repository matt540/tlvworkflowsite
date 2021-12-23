<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProductReportExport implements FromArray, WithEvents, WithStyles, WithCustomStartCell, ShouldAutoSize
{
    protected $data;

    public function __construct(array $data, $seller, $filter, $title_rows, $section_data_rows)
    {
        $this->data = $data;
        $this->seller = $seller;
        $this->filter = $filter;
        $this->title_rows = $title_rows;
        $this->section_data_rows = $section_data_rows;
    }

    public function array(): array
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells('A1:H1');
                $event->sheet->setCellValue('A1', 'TLV CLIENT PRODUCT REPORT');
                // $event->sheet->setCellValue('A1', 'TLV Client Product Report');
                // $event->sheet->getDelegate()->getStyle('A1')->getAlignment()->setHorizontal('center');
                
                $event->sheet->getDelegate()->mergeCells('A3:H3');
                $event->sheet->setCellValue('A3', 'Seller Name: ' . $this->seller->getFirstname() . ' ' . $this->seller->getLastname());

                $event->sheet->getDelegate()->mergeCells('A4:H4');
                $event->sheet->setCellValue('A4', 'Dates: ' . $this->filter['start_date_updated'] . ' to ' . $this->filter['end_date_updated']);

                $event->sheet->getDelegate()->getStyle('A3:A4')
                    ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKGREEN);
                
                $event->sheet->getDelegate()->getStyle('A3:A4')
                    ->getFont()
                    ->setBold(true);

                foreach ($this->title_rows as $key => $value) {
                    $event->sheet->getDelegate()->mergeCells('A'.$value.':H'.$value);
                    $event->sheet->getDelegate()->getStyle('A'.$value)                    
                        ->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ],
                            'font' => [
                                'bold' => true
                            ]
                        ]);

                    $event->sheet->getDelegate()->getStyle('A'.($value+1).':H'.($value+1))                    
                        ->applyFromArray([
                            'font' => [
                                'bold' => true
                            ]
                        ]);
                        
// dd('A'.($value+1).':H'.($value+$this->section_data_rows[$key]));
                    $title_row = $value+1;
                    $section_last_row = $title_row + $this->section_data_rows[$key];
                    // dd($section_last_row);
// dd('A'.$title_row.':H'.$section_last_row);
                    $event->sheet->getDelegate()->getStyle('A'.$title_row.':H'.$section_last_row)                    
                        ->applyFromArray([
                            'borders' => [
                                'outline' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '000000'],
                                ],
                            ],
                        ]);
                }
    


            },
                        
        ];
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function styles(Worksheet $sheet)
    {
        $styleArray = [
            'font' => ['bold' => true], 
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ]
        ];

        return [
            1 => [
                'font' => ['bold' => true], 
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ]
            ],
            // 2    => ['font' => ['bold' => true]],
        ];
    }


}
