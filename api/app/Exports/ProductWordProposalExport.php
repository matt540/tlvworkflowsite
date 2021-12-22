<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class ProductWordProposalExport implements FromArray, ShouldAutoSize, WithEvents
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        // $this->data
        // dd($this->data);
        foreach ($this->data['details']['products'] as $key => $value) {
            // dd($value);
            if ($value['is_send_mail'] == 1) {

            }
        }
        /*
        foreach ($data['details']['products'] as $key => $value) {
            if ($value['is_send_mail'] == 1) {
                $j = $i;
                $product_quote = $this->product_quotation_repo->getProductQuotationById($value['id']);
                $sheet->row($j, array(
                    'SKU',
                    $product_quote['product_id']['sku'],
                ));
                $j++;
                $sheet->row($j, array(
                    'Name',
                    $product_quote['product_id']['name'],
                ));
                $j++;
//                        $sheet->row($j, array(
//                            'Original Retail',
//                            '$' . $product_quote['price'],
//                        ));
//                        $j++;



                $sheet->getStyle('A' . ($j))->applyFromArray(array(
                    'font' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '00B050')
                    )
                ));

                $sheet->getStyle('B' . ($j))->applyFromArray(array(
                    'font' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '00B050')
                    )
                ));
                $sheet->row($j, array(
                    'Suggested TLV Price',
                    '$' . $product_quote['tlv_price'],
                ));
                $j++;

//                        $sheet->row($j, array(
//                            'Max price',
//                            '$' . $product_quote['tlv_suggested_price_max'],
//                        ));
//                        $j++;
//                        $sheet->row($j, array(
//                            'Min price',
//                            '$' . $product_quote['tlv_suggested_price_min'],
//                        ));
//                        $j++;
                if (isset($data['details']['isForClient']) && $data['details']['isForClient'] == true) {
                    
                } else {
                    $sheet->row($j, array(
                        'Internal Note',
                        $product_quote['note'],
                    ));
                    $j++;
                }
                $sheet->row($j, array(
                    'Proposal Date',
                    $product_quote['created_at']->format('m/d/Y'),
                ));



                $sheet->cells('A' . $i . ':D' . ($j) . '', function($cells) {
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });
                if (isset($product_quote['product_id']['product_pending_images'][0]['name'])) {
                    $offset = 0;
//                            foreach ($product_quote['product_id']['product_pending_images'] as $key2 => $value2)
//                            {
//
//
//                                $objDrawing = new PHPExcel_Worksheet_Drawing;
//                                $objDrawing->setPath(public_path('../../Uploads/product/' . $value2['name'])); //your image path
//                                $objDrawing->setWidthAndHeight(80, 80);
////                                $objDrawing->setResizeProportional(true);
//                                $objDrawing->setCoordinates('C' . $i);
//                                $objDrawing->setWorksheet($sheet);
//                                $objDrawing->setOffsetX($offset);
//                                $offset += 100;
//                            }
                }
//                        $i = $i + 8;
                $i = $i + 7;
            }
        }
        */

        return [[1,2,3], [1,2,3]];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // $event->sheet->getDelegate()->getParent()->getDefaultStyle()->getFont()->setName('Calibri' );
                // $event->sheet->getDelegate()->mergeCells('A1:D4');
                // $event->sheet->getDelegate()->mergeCells('A6:D6');
                // $event->sheet->getDelegate()->mergeCells('A7:D7');
                // $event->sheet->getDelegate()->mergeCells('A8:D8');
                // $event->sheet->getDelegate()->mergeCells('A9:D9');
                // $event->sheet->getDelegate()->getStyle('A1:D4')->getAlignment()->setHorizontal('center');
                // $event->sheet->getDelegate()->getStyle('A1:D4')->getAlignment()->setHorizontal('center');
            },
                        
        ];
    }


}
