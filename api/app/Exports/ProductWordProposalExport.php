<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ProductWordProposalExport implements FromArray, ShouldAutoSize, WithEvents, WithStyles, WithHeadings, WithDrawings, WithCustomStartCell, WithColumnWidths
{
    protected $data;
    protected $seller;

    public function __construct(array $data, $seller, $suggested_tlv_price_rows)
    {
        $this->data = $data;
        $this->seller = $seller;
        $this->suggested_tlv_price_rows = $suggested_tlv_price_rows;
    }

    public function headings(): array
    {
        return [
            // [$this->seller->getFirstname() . ' ' . $this->seller->getLastname()],
            // [$this->seller->getAddress()],
            // [$this->seller->getPhone()]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 25,
            'C' => 25,
            'D' => 25,
        ];
    }

    public function startCell(): string
    {
        return 'A11';
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setPath(public_path('../../assets/images/site_logo.png'));
        $drawing->setHeight(75);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(370);

        return $drawing;
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
                // $event->sheet->insertNewRowBefore(11, 1);
                // $event->sheet->setCellValue('A1','Top Triggers Report');
                // $event->sheet->getDelegate()->getParent()->getDefaultStyle()->getFont()->setName('Calibri' );

                // $event->sheet->getColumnDimension('A')->setWidth(25);

                $event->sheet->getDelegate()->mergeCells('A1:D4');
                $event->sheet->getDelegate()->mergeCells('A6:D6');
                $event->sheet->getDelegate()->mergeCells('A7:D7');
                $event->sheet->getDelegate()->mergeCells('A8:D8');
                $event->sheet->getDelegate()->mergeCells('A9:D9');
                $event->sheet->getDelegate()->getStyle('A1:D4')->getAlignment()->setHorizontal('center');

                $event->sheet->setCellValue('A6', $this->seller->getFirstname() . ' ' . $this->seller->getLastname());
                $event->sheet->setCellValue('A7', $this->seller->getAddress());
                $event->sheet->setCellValue('A8', '');
                $event->sheet->setCellValue('A9', $this->seller->getEmail());

                // $event->sheet->getDelegate()->getStyle('A13')
                //     ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKGREEN);
                // $event->sheet->getDelegate()->getStyle('A13')->getFont()->getColor()->setARGB('DD4B39');
                foreach ($this->suggested_tlv_price_rows as $value) {
                    $event->sheet->getDelegate()->getStyle('A'.$value.':D'.($value+4))
                    
                    ->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],
                        ],
                    ]);

                    $event->sheet->getDelegate()->getStyle('A'.($value+2).':B'.($value+2))
                        ->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKGREEN);
                }
            },
                        
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A'  => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'font' => [
                    'bold' => true
                ]
            ]
        ];
    }

}
