<?php

namespace App\Http\Controllers;

use App\Exports\ProductWordProposalExport;
use Illuminate\Http\Request;
use App\Repository\RoleRepository as role_repo;
use App\Repository\UserRepository as user_repo;
use App\Repository\OptionRepository as option_repo;
use App\Repository\ProductsApprovedRepository as product_approve_repo;
use App\Repository\ProductsQuotationRepository as product_quotation_repo;
use App\Repository\ProductsRepository as product_repo;
use App\Repository\SellerRepository as seller_repo;
use App\Repository\MailRecordRepository as mail_record_repo;
use App\Http\Requests;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\File;
use Auth;
use View;
// use Excel;
use PHPExcel_Worksheet_Drawing;
use PHPExcel_Style_Fill;
use PDF;
use TCPDF;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller {

    public function __construct(mail_record_repo $mail_record_repo, product_repo $product_repo, seller_repo $seller_repo, product_quotation_repo $product_quotation_repo, product_approve_repo $product_approve_repo, user_repo $user_repo, role_repo $role_repo, option_repo $option_repo) {
        $this->mail_record_repo = $mail_record_repo;
        $this->product_repo = $product_repo;
        $this->user_repo = $user_repo;
        $this->role_repo = $role_repo;
        $this->option_repo = $option_repo;
        $this->product_approve_repo = $product_approve_repo;
        $this->product_quotation_repo = $product_quotation_repo;
        $this->seller_repo = $seller_repo;
    }

    public function export_user(Request $request) {
        $filter = $request->all();

        $file_name = '121_flight_catering_users_' . date('Y-m-d') . '_' . time();

        Excel::create($file_name, function($excel) use($users) {
            $excel->sheet('Sheet 1', function($sheet) use($users) {
                $sheet->setWidth(array(
                    'A' => 25,
                    'B' => 25,
                    'C' => 20,
                    'D' => 35,
                    'E' => 15,
                    'F' => 30,
                    'G' => 20
                ));
                $sheet->mergeCells('A1:G2');

                $sheet->cells('A1:G2', function($cells) {
                    $cells->setAlignment('center');
                });
                $sheet->cells('A3:G3', function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
//                $objDrawing = new PHPExcel_Worksheet_Drawing;
//                $objDrawing->setPath(public_path('../assets/img/otog_logo.jpg')); //your image path
//                $objDrawing->setCoordinates('G1');
//                $objDrawing->setWorksheet($sheet);
//                $objDrawing->setOffsetX(200);

                $sheet->setStyle(array(
                    'font' => array(
                        'name' => 'Calibri',
                        'size' => 12,
                        'bold' => false
                    )
                ));

                $sheet->row(3, array(
                    'contactname', 'companyname', 'username',
                    'email', 'Role',
                    'status', 'phone'
                ));

                $i = 6;
                foreach ($users as $user) {
                    $sheet->row($i, array(
                        $user['contactname'], $user['company_name'], $user['username'],
                        $user['email'],
                        $user['role_name'],
                        $user['status'], $user['phone']
                    ));
                    $i++;
                }
            });
        })->save('xlsx');

        return $file_name . '.xlsx';
    }

    public function exportProducts(Request $request) {
        $filter = $request->all();

//        $filter['start_date'] = date('Y-m-d', strtotime($filter['start_date']));
//        $filter['end_date'] = date('Y-m-d', strtotime($filter['end_date']));

        $data = $this->product_approve_repo->getAllExportProducts($filter);

        $file_name = 'The_Local_Vaults_Products_' . date('Y-m-d') . '_' . time();

        Excel::create($file_name, function($excel) use($data) {
            $excel->sheet('Sheet 1', function($sheet) use($data) {
                $sheet->setWidth(array(
                    'A' => 20,
                    'B' => 20,
                    'C' => 20,
                ));
//                $sheet->mergeCells('A1:J2');
//
//                $sheet->cells('A1:J2', function($cells)
//                {
//                    $cells->setAlignment('center');
//                });
//                $sheet->cells('A3:J3', function($cells)
//                {
//                    $cells->setFontWeight('bold');
//                    $cells->setAlignment('center');
//                });
//                $objDrawing = new PHPExcel_Worksheet_Drawing;
//                $objDrawing->setPath(public_path('../assets/img/otog_logo.jpg')); //your image path
//                $objDrawing->setCoordinates('G1');
//                $objDrawing->setWorksheet($sheet);
//                $objDrawing->setOffsetX(200);

                $sheet->setStyle(array(
                    'font' => array(
                        'name' => 'Calibri',
                        'size' => 12,
                        'bold' => false
                    )
                ));

                $sheet->row(3, array(
                    'Name',
                    'Description',
                    'Price'
                ));

                $i = 6;
                foreach ($data as $details) {
                    $sheet->row($i, array(
                        $details['name'],
                        $details['description'],
                        $details['price'],
                    ));
                    $i++;
                }
            });
        })->save('csv');

        return $file_name . '.csv';
    }

    public function downloadProductWordProposalPopUp($seller, $product_quote) {

//        $details = $request->all();
//        $seller = $this->seller_repo->SellerOfId($details['seller']);
//        $file = 'tlv_word_product_' . time();
//        $filename = public_path() . '/../../Uploads/word/' . $file;


        $seller_name = '';
        if ($seller->getFirstname() != '' && $seller->getLastname() != '') {
            $seller_name = $seller->getFirstname() . $seller->getLastname();
//        $file = $seller->getFirstname().$seller->getLastname();
        } else {
            $seller_name = $seller->getDisplayname();
        }
        $number = $this->mail_record_repo->countOfSellerProposalType($seller->getId());
        $number += 1;
//        $sku_number = $seller->getLastSku();
        if ($number < 100) {
            if ($number < 10) {
                $number = '00' . $number;
            } else {
                $number = '0' . $number;
            }
        }
        $file = $seller_name . '_pricingproposal_' . $number;
        $data = array();
        $data['quote'] = $product_quote;
        $data['seller'] = $seller;
        Excel::create($file, function($excel) use($data) {
            $excel->sheet('Sheet 1', function($sheet) use($data) {
                $seller = $data['seller'];
                $sheet->setWidth(array(
                    'A' => 25,
                    'B' => 25,
                    'C' => 20,
                    'D' => 35,
                ));
                $sheet->mergeCells('A1:D4');

                $sheet->mergeCells('A6:D6');
                $sheet->mergeCells('A7:D7');
                $sheet->mergeCells('A8:D8');
                $sheet->mergeCells('A9:D9');

                $sheet->cells('A1:D4', function($cells) {
                    $cells->setAlignment('center');
                });
                $sheet->cells('A4:D8', function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $total = 7 * count(1) + 11;
                $sheet->cells('A3:A' . $total, function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                $sheet->cells('A6:D9', function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });


                $objDrawing = new PHPExcel_Worksheet_Drawing;
                $objDrawing->setPath(public_path('assets/images/logo.png')); //your image path
                $objDrawing->setHeight(75);
                $objDrawing->setCoordinates('A1');
                $objDrawing->setWorksheet($sheet);
                $objDrawing->setOffsetX(370);

                $sheet->setStyle(array(
                    'font' => array(
                        'name' => 'Calibri',
                        'size' => 12,
                        'bold' => false
                    )
                ));

                $sheet->row(6, array(
                    $seller->getFirstname() . ' ' . $seller->getLastname(),
                ));
                $sheet->row(7, array(
                    $seller->getAddress(),
                ));
                if ($seller->getPhone() != 0) {
                    $sheet->row(8, array(
                        $seller->getPhone(),
                    ));
                } else {
                    $sheet->row(8, array(
                        '',
                    ));
                }
                $sheet->row(9, array(
                    $seller->getEmail(),
                ));


                $i = 11;
                $data['quote'];
//                foreach ($data['details']['products'] as $key => $value)
//                {
//                if ($data['quote']->get['is_send_mail'] == 1)
//                {
                $product_quote = $this->product_quotation_repo->getProductQuotationById($data['quote']->getId());
                $sheet->row($i, array(
                    'SKU',
                    $product_quote['product_id']['sku'],
                ));
                $sheet->row($i + 1, array(
                    'Name',
                    $product_quote['product_id']['name'],
                ));
                $sheet->row($i + 2, array(
                    'Original Retail',
                    '$' . $product_quote['price'],
                ));



                $sheet->getStyle('A' . ($i + 3))->applyFromArray(array(
                    'font' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '00B050')
                    )
                ));
                $sheet->getStyle('B' . ($i + 3))->applyFromArray(array(
                    'font' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '00B050')
                    )
                ));
                $sheet->row($i + 3, array(
                    'Suggested TLV Price',
                    '$' . $product_quote['tlv_suggested_price_max'],
                ));

                $sheet->row($i + 4, array(
                    'Max price',
                    '$' . $product_quote['tlv_suggested_price_max'],
                ));

                $sheet->row($i + 5, array(
                    'Min price',
                    '$' . $product_quote['tlv_suggested_price_min'],
                ));



                $sheet->cells('A' . $i . ':D' . ($i + 5) . '', function($cells) {
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });
                if (isset($product_quote['product_id']['product_pending_images'][0]['name'])) {
                    $offset = 0;
                    foreach ($product_quote['product_id']['product_pending_images'] as $key2 => $value2) {


                        $objDrawing = new PHPExcel_Worksheet_Drawing;
                        $objDrawing->setPath(public_path('../../Uploads/product/' . $value2['name'])); //your image path
                        $objDrawing->setWidthAndHeight(80, 80);
//                                $objDrawing->setResizeProportional(true);
                        $objDrawing->setCoordinates('C' . $i);
                        $objDrawing->setWorksheet($sheet);
                        $objDrawing->setOffsetX($offset);
                        $offset += 100;
                    }
                }
//                $i = $i + 7;
//                }
//                }
            });
        })->save('xlsx');




        $file_size = \File::size(public_path() . '/../storage/exports/' . $file . '.xlsx');
//        68 mb
        if ($file_size < 68952992) {
            $stream_opts = [
                "ssl" => [
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ]
            ];
//        $content = file_get_contents(config('app.url') . '/api/../Uploads/word/' . $file . '.xlsx');
            $content = file_get_contents(config('app.url') . 'api/storage/exports/' . $file . '.xlsx', false, stream_context_create($stream_opts));



            \Storage::disk('google')->put($file . '.xlsx', $content);
        }
        //old start
//        $content = file_get_contents(config('app.url') . 'api/storage/exports/' . $file . '.xlsx');
//
//
//        \Storage::disk('google')->put($file . '.xlsx', $content);
        //old stop



        $data_update = array();
        $dir = '/';
        $recursive = false; // Get subdirectories also?
        $file1 = collect(\Storage::disk('google')->listContents($dir, $recursive))
                ->where('type', '=', 'file')
                ->where('filename', '=', pathinfo($file . '.xlsx', PATHINFO_FILENAME))
                ->where('extension', '=', pathinfo($file . '.xlsx', PATHINFO_EXTENSION))
                ->sortBy('timestamp')
                ->last();
        $data_update['last_proposal_file_name_base'] = $file1['path'];


        $data_update['last_proposal_file_name'] = $file . '.xlsx';

        $this->seller_repo->update($seller, $data_update);

        $data2['seller_id'] = $seller;
        $data2['file_name'] = $file . '.xlsx';
        $data2['from_state'] = 'proposal';
        $prepared_data2 = $this->mail_record_repo->prepareData($data2);

        $this->mail_record_repo->create($prepared_data2);

        return $file . '.xlsx';
    }

    public function downloadProductPdfProposal(Request $request) {

        ini_set('memory_limit', '4096M');
        ini_set('max_execution_time', 0);

        $details = $request->all();
        $seller = $this->seller_repo->SellerOfId($details['seller']);

        $seller_name = '';
        if ($seller->getFirstname() != '' && $seller->getLastname() != '') {
            $seller_name = trim($seller->getFirstname()) . trim($seller->getLastname());
        } else {
            $seller_name = trim($seller->getDisplayname());
        }

        $number = $this->mail_record_repo->countOfSellerProposalType($seller->getId());
        $number += 1;
        if ($number < 100) {
            if ($number < 10) {
                $number = '00' . $number;
            } else {
                $number = '0' . $number;
            }
        }
        $seller_name = str_replace(" ", "", $seller_name);

        if (isset($details['isForClient']) && $details['isForClient'] == true) {
            $number = 'client_' . $number;
        }

        $file = $seller_name . '_pricingproposal_' . $number;
        $data = array();

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Smit Vora');
        $pdf->SetTitle('The Local Vault');
        $pdf->SetSubject('Product Details');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        $pdf->SetHeaderData('../../../../../../assets/images/site_logo.png', PDF_HEADER_LOGO_WIDTH, '', '');

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



        $pdf->SetFont('helvetica', '', 10);

        $pdf->AddPage();

        $html = <<<EOF
<!-- EXAMPLE OF CSS STYLE -->
<style>
    .special_td{
      color:#00B050;
          }
    .border_top{
        border-top: 1px;
    }
    .border_left{
        border-left: 1px;
    }
    .border_top{
        border-top: 1px;
    }
    .border_right{
        border-right: 1px;
    }
    .border_bottom{
        border-bottom: 1px;
    }
                
                
</style>            
EOF;
        $html = '';
        $html .= '<table width="100%" cellpadding="2" cellspacing="2">';

        $html .= '<tr>';
        $html .= '  <td align="center" colspan="4">';
        $html .= '<b>' . $seller->getFirstname() . ' ' . $seller->getLastname() . '</b>';
        $html .= '  </td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '  <td align="center" colspan="4">';
        $html .= '<b>' . $seller->getAddress() . '</b>';
        $html .= '  </td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '  <td align="center" colspan="4">';
        if ($seller->getPhone() != 0) {
            $html .= '<b>' . $seller->getPhone() . '</b>';
        }
        $html .= '  </td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '  <td align="center" colspan="4">';
        $html .= '<b>' . $seller->getEmail() . '</b>';
        $html .= '  </td>';
        $html .= '</tr>';


        $html .= '<tr>';
        $html .= '  <td colspan="4">';
        $html .= '  </td>';
        $html .= '</tr>';

        foreach ($details['products'] as $key => $value) {
            if ($value['is_send_mail'] == 1) {
                $product_quote = $this->product_quotation_repo->getProductQuotationById($value['id']);

                $rowspan = 6;
                if (isset($details['isForClient']) && $details['isForClient'] == true) {
                    $rowspan = 5;
                } else {
                    $rowspan = 6;
                }


                $html .= '<tr>';
                $html .= '  <th align="center" style="border-top: 1px solid black;border-left: 1px solid black;">';
                $html .= '  <b>SKU</b>';
                $html .= '  </th>';
                $html .= '  <td style="border-top: 1px solid black;">';
                $html .= $product_quote['product_id']['sku'];
                $html .= '  </td>';

                $html .= '  <td style="border-top: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;" colspan="2" rowspan="' . $rowspan . '">';
                if (isset($product_quote['product_id']['product_pending_images'][0]['name'])) {
                    $offset = 0;
                    foreach ($product_quote['product_id']['product_pending_images'] as $key2 => $value2) {
                        $html .= '<img height="80" width="80" src="' . config('app.url') . 'Uploads/product/thumb/' . $value2['name'] . '">';
                    }
                }
                $html .= '  </td>';
                $html .= '</tr>';


                $html .= '<tr>';
                $html .= '  <th align="center" style="border-left: 1px solid black;">';
                $html .= '  <b>Name</b>';
                $html .= '  </th>';
                $html .= '  <td>';
                $html .= $product_quote['product_id']['name'];
                $html .= '  </td>';
                $html .= '</tr>';

                $html .= '<tr>';
                $html .= '  <th align="center" style="border-left:1px solid black;">';
                $html .= '  <b>TLV price</b>';
                $html .= '  </th>';
                $html .= '  <td>';
                $html .= '$' . $product_quote['tlv_price'];
                $html .= '  </td>';
                $html .= '</tr>';
                
                 $html .= '<tr>';
                $html .= '  <th align="center" style="border-left:1px solid black;">';
                $html .= '  <b>Dropoff by Consignor Required </b>';
                $html .= '  </th>';
                $html .= '  <td>';
                if($product_quote['seller_to_drop_off'] == 1){
                    $html .=   'Yes';
                }else{
                    $html .=   '-';
                }
                
                $html .= '  </td>';
                $html .= '</tr>';

                if (isset($details['isForClient']) && $details['isForClient'] == true) {
                    
                } else {
                    $html .= '<tr>';
                    $html .= '  <th align="center" style="border-left:1px solid black;">';
                    $html .= '  <b>Internal Note</b>';
                    $html .= '  </th>';
                    $html .= '  <td>';
                    $html .= $product_quote['note'];
                    $html .= '  </td>';
                    $html .= '</tr>';
                }

                $html .= '<tr>';
                $html .= '  <th align="center" style="border-left:1px solid black;border-bottom:1px solid black;" >';
                $html .= '  <b>Proposal Date</b>';
                $html .= '  </th>';
                $html .= '  <td style="border-bottom: 1px solid black;">';
                $html .= $product_quote['created_at']->format('m/d/Y');
                $html .= '  </td>';
                $html .= '</tr>';


                $html .= '<tr>';
                $html .= '  <td colspan="4">';
                $html .= '  </td>';
                $html .= '</tr>';
            }
        }
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->lastPage();

        $filename = public_path('storage/exports/' . $file . '.pdf');
        $pdf->output($filename, 'F');

        $stream_opts = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ]
        ];
        
        $content = file_get_contents($filename, false, stream_context_create($stream_opts));
        
        $file_size = \File::size($filename);

        // 68 mb
        if ($file_size < 68952992 && isset($details['isForClient']) && $details['isForClient'] == true) {
            $content = file_get_contents($filename, false, stream_context_create($stream_opts));
        }

        if (isset($details['isForClient']) && $details['isForClient'] == true) {
            $data_update['last_proposal_file_name'] = $file . '.pdf';

            $this->seller_repo->update($seller, $data_update);

            $data2['seller_id'] = $seller;
            $data2['file_name'] = $file . '.pdf';
            $data2['from_state'] = 'proposal';
            $prepared_data2 = $this->mail_record_repo->prepareData($data2);

            $this->mail_record_repo->create($prepared_data2);
        }

        return $file . '.pdf';
    }

    public function downloadProductPdfProposal_Old(Request $request) {
        ini_set('memory_limit', '4096M');
        ini_set('max_execution_time', 0);

        $details = $request->all();
        $seller = $this->seller_repo->SellerOfId($details['seller']);
//        $file = 'tlv_word_product_' . time();
//        $filename = public_path() . '/../../Uploads/word/' . $file;


        $seller_name = '';
        if ($seller->getFirstname() != '' && $seller->getLastname() != '') {
            $seller_name = trim($seller->getFirstname()) . trim($seller->getLastname());
//        $file = $seller->getFirstname().$seller->getLastname();
        } else {
            $seller_name = trim($seller->getDisplayname());
        }
        $number = $this->mail_record_repo->countOfSellerProposalType($seller->getId());
        $number += 1;
//        $sku_number = $seller->getLastSku();
        if ($number < 100) {
            if ($number < 10) {
                $number = '00' . $number;
            } else {
                $number = '0' . $number;
            }
        }
        $seller_name = str_replace(" ", "", $seller_name);

        if (isset($details['isForClient']) && $details['isForClient'] == true) {
            $number = 'client_' . $number;
        }

        $file = $seller_name . '_pricingproposal_' . $number;
        $data = array();
//        $data['details'] = $details;
//        $data['seller'] = $seller;






        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Smit Vora');
        $pdf->SetTitle('The Local Vault');
        $pdf->SetSubject('Product Details');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        $pdf->SetHeaderData('../../../../../../assets/images/site_logo.png', PDF_HEADER_LOGO_WIDTH, '', '');

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



        $pdf->SetFont('helvetica', '', 10);

        $pdf->AddPage();

        $html = <<<EOF
<!-- EXAMPLE OF CSS STYLE -->
<style>
    .special_td{
      color:#00B050;
          }
    .border_top{
        border-top: 1px;
    }
    .border_left{
        border-left: 1px;
    }
    .border_top{
        border-top: 1px;
    }
    .border_right{
        border-right: 1px;
    }
    .border_bottom{
        border-bottom: 1px;
    }
                
                
</style>
                
              
                
EOF;

//        $pdf->writeHTML($html, true, false, true, false, '');

//        $html = '
//<div style="text-align: center; color: #000;">            
//<h3 style="color: black;">' . $seller->getFirstname() . ' ' . $seller->getLastname() . '</h3><br>
//<h4 style="color: black;">' . $seller->getAddress() . '</h4><br>
//<h4 style="color: black;">' . $seller->getPhone() . '</h4><br>
//<h4 style="color: blue;">' . $seller->getEmail() . '</h4><br>
//</div>
//
//<div style="padding: 10px;">
//' . $string . '
//</div>';

        $html = '';
        $html .= '<table width="100%" cellpadding="2" cellspacing="2">';

        $html .= '<tr>';
        $html .= '  <td align="center" colspan="4">';
        $html .= '<b>' . $seller->getFirstname() . ' ' . $seller->getLastname() . '</b>';
        $html .= '  </td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '  <td align="center" colspan="4">';
        $html .= '<b>' . $seller->getAddress() . '</b>';
        $html .= '  </td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '  <td align="center" colspan="4">';
        if ($seller->getPhone() != 0) {
            $html .= '<b>' . $seller->getPhone() . '</b>';
        }
        $html .= '  </td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '  <td align="center" colspan="4">';
        $html .= '<b>' . $seller->getEmail() . '</b>';
        $html .= '  </td>';
        $html .= '</tr>';


        $html .= '<tr>';
        $html .= '  <td colspan="4">';
        $html .= '  </td>';
        $html .= '</tr>';

        foreach ($details['products'] as $key => $value) {
            if ($value['is_send_mail'] == 1) {
                $product_quote = $this->product_quotation_repo->getProductQuotationById($value['id']);

                $rowspan = 6;
                if (isset($details['isForClient']) && $details['isForClient'] == true) {
                    $rowspan = 5;
                } else {
                    $rowspan = 6;
                }


                $html .= '<tr>';
                $html .= '  <th align="center" style="border-top: 1px solid black;border-left: 1px solid black;">';
                $html .= '  <b>SKU</b>';
                $html .= '  </th>';
                $html .= '  <td style="border-top: 1px solid black;">';
                $html .= $product_quote['product_id']['sku'];
                $html .= '  </td>';

                $html .= '  <td style="border-top: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;" colspan="2" rowspan="' . $rowspan . '">';
                if (isset($product_quote['product_id']['product_pending_images'][0]['name'])) {
                    $offset = 0;
                    foreach ($product_quote['product_id']['product_pending_images'] as $key2 => $value2) {
//                        $imageLocation = public_path('../../Uploads/product/' . $value2['name']);
//                        $image = base64_encode(file_get_contents($imageLocation));
//                        $exts = explode(".", $imageLocation);
//                        $ext = $exts[(count($exts) - 1)];
//                        $html .= '<img height="80" width="80" src="data:image/' . $ext . ';base64,' . $image . '">';
//                        if (@getimagesize(config('app.url') . 'Uploads/product/thumb/' . $value2['name']))
//                        {
//                            $html .= '<img height="80" width="80" src="' . config('app.url') . 'Uploads/product/thumb/' . $value2['name'] . '">';
//                        $html .= '<img height="80" width="80" >';
//                        }
                        $html .= '<img height="80" width="80" src="' . config('app.url') . 'Uploads/product/thumb/' . $value2['name'] . '">';
                    }
                }
                $html .= '  </td>';
                $html .= '</tr>';


                $html .= '<tr>';
                $html .= '  <th align="center" style="border-left: 1px solid black;">';
                $html .= '  <b>Name</b>';
                $html .= '  </th>';
                $html .= '  <td>';
                $html .= $product_quote['product_id']['name'];
                $html .= '  </td>';
                $html .= '</tr>';

//                $html .= '<tr>';
//                $html .= '  <th align="center" style="color:#00B050;border-left:1px solid black;">';
//                $html .= '  <b>Suggested TLV Price</b>';
//                $html .= '  </th>';
//                $html .= '  <td style="color:#00B050;">';
//                $html .= '$' . $product_quote['tlv_suggested_price_max'];
//                $html .= '  </td>';
//                $html .= '</tr>';
//                $html .= '<tr>';
//                $html .= '  <th align="center" style="border-left:1px solid black;">';
//                $html .= '  <b>Max price</b>';
//                $html .= '  </th>';
//                $html .= '  <td>';
//                $html .= '$' . $product_quote['tlv_suggested_price_max'];
//                $html .= '  </td>';
//                $html .= '</tr>';

                $html .= '<tr>';
                $html .= '  <th align="center" style="border-left:1px solid black;">';
                $html .= '  <b>TLV price</b>';
                $html .= '  </th>';
                $html .= '  <td>';
                $html .= '$' . $product_quote['tlv_price'];
                $html .= '  </td>';
                $html .= '</tr>';
                
                 $html .= '<tr>';
                $html .= '  <th align="center" style="border-left:1px solid black;">';
                $html .= '  <b>Dropoff by Consignor Required </b>';
                $html .= '  </th>';
                $html .= '  <td>';
                if($product_quote['seller_to_drop_off'] == 1){
                    $html .=   'Yes';
                }else{
                    $html .=   '-';
                }
                
                $html .= '  </td>';
                $html .= '</tr>';

                if (isset($details['isForClient']) && $details['isForClient'] == true) {
                    
                } else {
                    $html .= '<tr>';
                    $html .= '  <th align="center" style="border-left:1px solid black;">';
                    $html .= '  <b>Internal Note</b>';
                    $html .= '  </th>';
                    $html .= '  <td>';
                    $html .= $product_quote['note'];
                    $html .= '  </td>';
                    $html .= '</tr>';
                }

                $html .= '<tr>';
                $html .= '  <th align="center" style="border-left:1px solid black;border-bottom:1px solid black;" >';
                $html .= '  <b>Proposal Date</b>';
                $html .= '  </th>';
                $html .= '  <td style="border-bottom: 1px solid black;">';
                $html .= $product_quote['created_at']->format('m/d/Y');
                $html .= '  </td>';
                $html .= '</tr>';


                $html .= '<tr>';
                $html .= '  <td colspan="4">';
                $html .= '  </td>';
                $html .= '</tr>';
            }
        }
        $html .= '</table>';



        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->lastPage();

//        $file = 'tlv_pdf_' . time();
//        $filename = public_path() . '/../../Uploads/pdf/' . $file . '.pdf';
        $filename = public_path() . '/../../api/storage/exports/' . $file . '.pdf';
        $pdf->output($filename, 'F');

        $stream_opts = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ]
        ];

        $content = file_get_contents(config('app.url') . 'api/storage/exports/' . $file . '.pdf', false, stream_context_create($stream_opts));






        $file_size = \File::size(public_path() . '/../storage/exports/' . $file . '.pdf');


//        68 mb
        if ($file_size < 68952992 && isset($details['isForClient']) && $details['isForClient'] == true) {


//        $content = file_get_contents(config('app.url') . '/api/../Uploads/word/' . $file . '.xlsx');
            $content = file_get_contents(config('app.url') . 'api/storage/exports/' . $file . '.pdf', false, stream_context_create($stream_opts));



//            \Storage::disk('google')->put($file . '.pdf', $content);
        }


        if (isset($details['isForClient']) && $details['isForClient'] == true) {
            $data_update['last_proposal_file_name'] = $file . '.pdf';

            $this->seller_repo->update($seller, $data_update);

            $data2['seller_id'] = $seller;
            $data2['file_name'] = $file . '.pdf';
            $data2['from_state'] = 'proposal';
            $prepared_data2 = $this->mail_record_repo->prepareData($data2);

            $this->mail_record_repo->create($prepared_data2);
        }




        return $file . '.pdf';
    }

    public function downloadProductPdfProposalProductForReview($details) {

        ini_set('memory_limit', '4096M');
        ini_set('max_execution_time', 0);

        $seller = $this->seller_repo->SellerOfId($details['seller']);
//        $file = 'tlv_word_product_' . time();
//        $filename = public_path() . '/../../Uploads/word/' . $file;


        $seller_name = '';
        if ($seller->getFirstname() != '' && $seller->getLastname() != '') {
            $seller_name = trim($seller->getFirstname()) . trim($seller->getLastname());
//        $file = $seller->getFirstname().$seller->getLastname();
        } else {
            $seller_name = trim($seller->getDisplayname());
        }
        $number = $this->mail_record_repo->countOfSellerProposalType($seller->getId());
        $number += 1;
//        $sku_number = $seller->getLastSku();
        if ($number < 100) {
            if ($number < 10) {
                $number = '00' . $number;
            } else {
                $number = '0' . $number;
            }
        }
        $seller_name = str_replace(" ", "", $seller_name);

        if (isset($details['isForClient']) && $details['isForClient'] == true) {
            $number = 'client_' . $number;
        }

        $file = $seller_name . '_pricingproposal_' . $number;
        $data = array();
//        $data['details'] = $details;
//        $data['seller'] = $seller;






        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Smit Vora');
        $pdf->SetTitle('The Local Vault');
        $pdf->SetSubject('Product Details');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        $pdf->SetHeaderData('../../../../../../assets/images/site_logo.png', PDF_HEADER_LOGO_WIDTH, '', '');

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



        $pdf->SetFont('helvetica', '', 10);

        $pdf->AddPage();

        $html = <<<EOF
<!-- EXAMPLE OF CSS STYLE -->
<style>
    .special_td{
      color:#00B050;
          }
    .border_top{
        border-top: 1px;
    }
    .border_left{
        border-left: 1px;
    }
    .border_top{
        border-top: 1px;
    }
    .border_right{
        border-right: 1px;
    }
    .border_bottom{
        border-bottom: 1px;
    }
                
                
</style>
                
              
                
EOF;

        $pdf->writeHTML($html, true, false, true, false, '');

//        $html = '
//<div style="text-align: center; color: #000;">            
//<h3 style="color: black;">' . $seller->getFirstname() . ' ' . $seller->getLastname() . '</h3><br>
//<h4 style="color: black;">' . $seller->getAddress() . '</h4><br>
//<h4 style="color: black;">' . $seller->getPhone() . '</h4><br>
//<h4 style="color: blue;">' . $seller->getEmail() . '</h4><br>
//</div>
//
//<div style="padding: 10px;">
//' . $string . '
//</div>';

        $html = '';
        $html .= '<table width="100%" cellpadding="2" cellspacing="2">';

        $html .= '<tr>';
        $html .= '  <td align="center" colspan="4">';
        $html .= '<b>' . $seller->getFirstname() . ' ' . $seller->getLastname() . '</b>';
        $html .= '  </td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '  <td align="center" colspan="4">';
        $html .= '<b>' . $seller->getAddress() . '</b>';
        $html .= '  </td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '  <td align="center" colspan="4">';
        if ($seller->getPhone() != 0) {
            $html .= '<b>' . $seller->getPhone() . '</b>';
        }
        $html .= '  </td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '  <td align="center" colspan="4">';
        $html .= '<b>' . $seller->getEmail() . '</b>';
        $html .= '  </td>';
        $html .= '</tr>';


        $html .= '<tr>';
        $html .= '  <td colspan="4">';
        $html .= '  </td>';
        $html .= '</tr>';

        foreach ($details['products'] as $key => $value) {
//            if ($value['is_send_mail'] == 1)
//            {
            $product_quote = $this->product_quotation_repo->getProductQuotationById($value['id']);

            $rowspan = 6;
            if (isset($details['isForClient']) && $details['isForClient'] == true) {
                $rowspan = 5;
            } else {
                $rowspan = 6;
            }


            $html .= '<tr>';
            $html .= '  <th align="center" style="border-top: 1px solid black;border-left: 1px solid black;">';
            $html .= '  <b>SKU</b>';
            $html .= '  </th>';
            $html .= '  <td style="border-top: 1px solid black;">';
            $html .= $product_quote['product_id']['sku'];
            $html .= '  </td>';

            $html .= '  <td style="border-top: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;" colspan="2" rowspan="' . $rowspan . '">';
            if (isset($product_quote['product_id']['product_pending_images'][0]['name'])) {
                $offset = 0;
                foreach ($product_quote['product_id']['product_pending_images'] as $key2 => $value2) {
//                        $imageLocation = public_path('../../Uploads/product/' . $value2['name']);
//                        $image = base64_encode(file_get_contents($imageLocation));
//                        $exts = explode(".", $imageLocation);
//                        $ext = $exts[(count($exts) - 1)];
//                        $html .= '<img height="80" width="80" src="data:image/' . $ext . ';base64,' . $image . '">';
//                        if (@getimagesize(config('app.url') . 'Uploads/product/thumb/' . $value2['name']))
//                        {
//                            $html .= '<img height="80" width="80" src="' . config('app.url') . 'Uploads/product/thumb/' . $value2['name'] . '">';
                    $html .= '<img height="80" width="80" >';
//                        }
                }
            }
            $html .= '  </td>';
            $html .= '</tr>';


            $html .= '<tr>';
            $html .= '  <th align="center" style="border-left: 1px solid black;">';
            $html .= '  <b>Name</b>';
            $html .= '  </th>';
            $html .= '  <td>';
            $html .= $product_quote['product_id']['name'];
            $html .= '  </td>';
            $html .= '</tr>';

//                $html .= '<tr>';
//                $html .= '  <th align="center" style="color:#00B050;border-left:1px solid black;">';
//                $html .= '  <b>Suggested TLV Price</b>';
//                $html .= '  </th>';
//                $html .= '  <td style="color:#00B050;">';
//                $html .= '$' . $product_quote['tlv_suggested_price_max'];
//                $html .= '  </td>';
//                $html .= '</tr>';

            $html .= '<tr>';
            $html .= '  <th align="center" style="border-left:1px solid black;">';
            $html .= '  <b>Max price</b>';
            $html .= '  </th>';
            $html .= '  <td>';
            $html .= '$' . $product_quote['tlv_suggested_price_max'];
            $html .= '  </td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '  <th align="center" style="border-left:1px solid black;">';
            $html .= '  <b>Min price</b>';
            $html .= '  </th>';
            $html .= '  <td>';
            $html .= '$' . $product_quote['tlv_suggested_price_min'];
            $html .= '  </td>';
            $html .= '</tr>';

            if (isset($details['isForClient']) && $details['isForClient'] == true) {
                
            } else {
                $html .= '<tr>';
                $html .= '  <th align="center" style="border-left:1px solid black;">';
                $html .= '  <b>Internal Note</b>';
                $html .= '  </th>';
                $html .= '  <td>';
                $html .= $product_quote['note'];
                $html .= '  </td>';
                $html .= '</tr>';
            }

            $html .= '<tr>';
            $html .= '  <th align="center" style="border-left:1px solid black;border-bottom:1px solid black;" >';
            $html .= '  <b>Proposal Date</b>';
            $html .= '  </th>';
            $html .= '  <td style="border-bottom: 1px solid black;">';
            $html .= $product_quote['created_at']->format('m/d/Y');
            $html .= '  </td>';
            $html .= '</tr>';


            $html .= '<tr>';
            $html .= '  <td colspan="4">';
            $html .= '  </td>';
            $html .= '</tr>';
        }
//        }
        $html .= '</table>';



        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->lastPage();

//        $file = 'tlv_pdf_' . time();
//        $filename = public_path() . '/../../Uploads/pdf/' . $file . '.pdf';
        $filename = public_path() . '/../../api/storage/exports/' . $file . '.pdf';
        $pdf->output($filename, 'F');

        $stream_opts = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ]
        ];

        $content = file_get_contents(config('app.url') . 'api/storage/exports/' . $file . '.pdf', false, stream_context_create($stream_opts));






        $file_size = \File::size(public_path() . '/../storage/exports/' . $file . '.pdf');


//        68 mb
        if ($file_size < 68952992 && isset($details['isForClient']) && $details['isForClient'] == true) {


//        $content = file_get_contents(config('app.url') . '/api/../Uploads/word/' . $file . '.xlsx');
            $content = file_get_contents(config('app.url') . 'api/storage/exports/' . $file . '.pdf', false, stream_context_create($stream_opts));



//            \Storage::disk('google')->put($file . '.pdf', $content);
        }


        if (isset($details['isForClient']) && $details['isForClient'] == true) {
            $data_update['last_proposal_file_name'] = $file . '.pdf';

            $this->seller_repo->update($seller, $data_update);

            $data2['seller_id'] = $seller;
            $data2['file_name'] = $file . '.pdf';
            $data2['from_state'] = 'proposal';
            $prepared_data2 = $this->mail_record_repo->prepareData($data2);

            $this->mail_record_repo->create($prepared_data2);
        }




        return $file . '.pdf';
    }

    public function downloadProductWordProposal(Request $request) {
        ini_set('max_execution_time', 900);
        ini_set('memory_limit', '4096M');

        $details = $request->all();

        $seller = $this->seller_repo->SellerOfId($details['seller']);

        $seller_name = '';
        if ($seller->getFirstname() != '' && $seller->getLastname() != '') {
            $seller_name = trim($seller->getFirstname()) . trim($seller->getLastname());
        } else {
            $seller_name = trim($seller->getDisplayname());
        }

        $number = $this->mail_record_repo->countOfSellerProposalType($seller->getId());
        $number += 1;

        if ($number < 100) {
            if ($number < 10) {
                $number = '00' . $number;
            } else {
                $number = '0' . $number;
            }
        }
        $seller_name = str_replace(" ", "", $seller_name);

        if (isset($details['isForClient']) && $details['isForClient'] == true) {
            $number = 'client_' . $number;
        }

        $file_name = $seller_name . '_pricingproposal_' . $number . '.xlsx';
        $file = 'public/exports/'.$file_name;

        $data = array();
        $data['details'] = $details;
        $data['seller'] = $seller;

        $export_data = [];
        $suggested_tlv_price_rows = [];
        $count = 11;
        foreach ($data['details']['products'] as $key => $value) {
            
            if ($value['is_send_mail'] == 1) {
                $product_quote = $this->product_quotation_repo->getProductQuotationById($value['id']);


                $row = [
                    [
                        'SKU',
                        $product_quote['product_id']['sku'],
                    ],
                    [
                        'Name',
                        $product_quote['product_id']['name'],
                    ],
                    [
                        'Suggested TLV Price',
                        '$' . $product_quote['tlv_price'],
                    ],
                    [
                        'Internal Note',
                        $product_quote['note'],
                    ],
                    [
                        'Proposal Date',
                        $product_quote['created_at']->format('m/d/Y'),
                    ],
                ];

                $export_data = array_merge($export_data, $row);

                array_push($export_data, [[]]);
                array_push($export_data, [[]]);

                if ($key != 0) {
                    $count = $count + 7;
                }

                // $count = $count + 2;
                array_push($suggested_tlv_price_rows, $count);

                // array_push($export_data, );
                // dd($product_quote);
            }
        }

        // $file_name = 'demo.xlsx';
        // $file = 'public/exports/'.$file_name;
        
        // $export = new ProductWordProposalExport($data);
        $export = new ProductWordProposalExport($export_data, $data['seller'], $suggested_tlv_price_rows);

        ob_end_clean();
        ob_start(); // and this
        // Excel::store(new StorageProductsExport, $file);
        Excel::store($export, $file);
        
        $path = asset('api/storage/exports/'.$file_name);
        return $path;
        

// dd($data);
        // return Excel::download(new ProductWordProposalExport, 'users.xlsx');
        // $details = $request->all();
        // dd($details);
        // $seller = $this->seller_repo->SellerOfId($details['seller']);
        // dd($seller);
    }

    public function downloadProductWordProposal_Old(Request $request) {
        ini_set('max_execution_time', 900);
        ini_set('memory_limit', '4096M');

        $details = $request->all();

        $seller = $this->seller_repo->SellerOfId($details['seller']);
//        $file = 'tlv_word_product_' . time();
//        $filename = public_path() . '/../../Uploads/word/' . $file;


        $seller_name = '';
        if ($seller->getFirstname() != '' && $seller->getLastname() != '') {
            $seller_name = trim($seller->getFirstname()) . trim($seller->getLastname());
//        $file = $seller->getFirstname().$seller->getLastname();
        } else {
            $seller_name = trim($seller->getDisplayname());
        }
        $number = $this->mail_record_repo->countOfSellerProposalType($seller->getId());
        $number += 1;
//        $sku_number = $seller->getLastSku();
        if ($number < 100) {
            if ($number < 10) {
                $number = '00' . $number;
            } else {
                $number = '0' . $number;
            }
        }
        $seller_name = str_replace(" ", "", $seller_name);

        if (isset($details['isForClient']) && $details['isForClient'] == true) {
            $number = 'client_' . $number;
        }

        $file = $seller_name . '_pricingproposal_' . $number;
        $data = array();
        $data['details'] = $details;
        $data['seller'] = $seller;
        Excel::create($file, function($excel) use($data) {
            $excel->sheet('Sheet 1', function($sheet) use($data) {
                $seller = $data['seller'];
                $sheet->setWidth(array(
                    'A' => 25,
                    'B' => 25,
                    'C' => 20,
                    'D' => 35,
                ));
                $sheet->mergeCells('A1:D4');

                $sheet->mergeCells('A6:D6');
                $sheet->mergeCells('A7:D7');
                $sheet->mergeCells('A8:D8');
                $sheet->mergeCells('A9:D9');

                $sheet->cells('A1:D4', function($cells) {
                    $cells->setAlignment('center');
                });
                $sheet->cells('A4:D8', function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $total = 7 * count($data['details']['products']) + 11;
                $sheet->cells('A3:A' . $total, function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                $sheet->cells('A6:D9', function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });


                $objDrawing = new PHPExcel_Worksheet_Drawing;
                $objDrawing->setPath(public_path('assets/images/logo.png')); //your image path
                $objDrawing->setHeight(75);
                $objDrawing->setCoordinates('A1');
                $objDrawing->setWorksheet($sheet);
                $objDrawing->setOffsetX(370);

                $sheet->setStyle(array(
                    'font' => array(
                        'name' => 'Calibri',
                        'size' => 12,
                        'bold' => false
                    )
                ));

                $sheet->row(6, array(
                    $seller->getFirstname() . ' ' . $seller->getLastname(),
                ));
                $sheet->row(7, array(
                    $seller->getAddress(),
                ));
                if ($seller->getPhone() != 0) {
                    $sheet->row(8, array(
                        $seller->getPhone(),
                    ));
                } else {
                    $sheet->row(8, array(
                        '',
                    ));
                }
                $sheet->row(9, array(
                    $seller->getEmail(),
                ));


                $i = 11;
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
            });
        })->save('xlsx');
        $stream_opts = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ]
        ];
//        $content = file_get_contents(config('app.url') . '/api/../Uploads/word/' . $file . '.xlsx');
        $content = file_get_contents(config('app.url') . 'api/storage/exports/' . $file . '.xlsx', false, stream_context_create($stream_opts));


        $file_size = \File::size(public_path() . '/../storage/exports/' . $file . '.xlsx');


//        68 mb
        if ($file_size < 68952992 && isset($details['isForClient']) && $details['isForClient'] == true) {
            $stream_opts = [
                "ssl" => [
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ]
            ];

//        $content = file_get_contents(config('app.url') . '/api/../Uploads/word/' . $file . '.xlsx');
            $content = file_get_contents(config('app.url') . 'api/storage/exports/' . $file . '.xlsx', false, stream_context_create($stream_opts));



//            \Storage::disk('google')->put($file . '.xlsx', $content);
        }
//        \Storage::disk('google')->put($file . '.xlsx', $content);
//        $config['settings']['mimeType'] = 'application/vnd.google-apps.spreadsheet';
//        \Storage::disk('google')->getAdapter()->write($file . '.xlsx', $content, $config);
//        $data_update = array();
//        $dir = '/';
//        $recursive = false; // Get subdirectories also?
//        $file1 = collect(\Storage::disk('google')->listContents($dir, $recursive))
//                ->where('type', '=', 'file')
//                ->where('filename', '=', pathinfo($file . '.xlsx', PATHINFO_FILENAME))
//                ->where('extension', '=', pathinfo($file . '.xlsx', PATHINFO_EXTENSION))
//                ->sortBy('timestamp')
//                ->last();
//        $data_update['last_proposal_file_name_base'] = $file1['path'];


        if (isset($details['isForClient']) && $details['isForClient'] == true) {
            $data_update['last_proposal_file_name'] = $file . '.xlsx';

            $this->seller_repo->update($seller, $data_update);

            $data2['seller_id'] = $seller;
            $data2['file_name'] = $file . '.xlsx';
            $data2['from_state'] = 'proposal';
            $prepared_data2 = $this->mail_record_repo->prepareData($data2);

            $this->mail_record_repo->create($prepared_data2);
        } else {
            
        }



        return $file . '.xlsx';










//        $details = $request->all();
//
//        $seller = $this->seller_repo->SellerOfId($details['seller']);
//
//
//        $phpWord = new \PhpOffice\PhpWord\PhpWord();
//        $section = $phpWord->addSection();
//        $header = $section->addHeader();
//        $header->addImage(config('app.url') . 'assets/images/site_logo.png'
//                , array('width' => 200,
//            'height' => 100,
//            'align' => 'center',
//            'marginLeft' => 100,
//            'marginTop' => 200));
//
//        $section->addText('');
//        $section->addText('');
//        $phpWord->addTitleStyle(1, array('name' => 'Tahoma', 'size' => 15, 'color' => 'Black', 'bold' => true)); //h1
//        $defaultStyle = new \PhpOffice\PhpWord\Style\Font();
//        $defaultStyle->setSize(15);
//        $defaultStyle->setName('Tahoma');
//        $phpWord->addFontStyle(
//                'defaultStyleSpecial', array('name' => 'Tahoma', 'size' => 10, 'color' => '669900',)
//        );
////seller
//        $tabs = '                            ';
//        $section->addTitle($tabs . $seller->getFirstname() . ' ' . $seller->getLastname(), 1);
//        $section->addTitle($tabs . $seller->getAddress(), 1);
//        if ($seller->getPhone() != 0)
//        {
//            $section->addTitle($tabs . $seller->getPhone(), 1);
//        }
//        else
//        {
//            $section->addTitle($tabs . '', 1);
//        }
//        $section->addTitle($tabs . $seller->getEmail(), 1);
//
//
//        $section->addText('', [$defaultStyle]);
//
//
//
//
//
//
//        foreach ($details['products'] as $key => $value)
//        {
//            if ($value['is_send_mail'] == 1)
//            {
//                $product = $this->product_quotation_repo->getProductQuotationById($value['id']);
//                $section->addText('', [$defaultStyle]);
//                $section->addText($product['product_id']['sku'], [$defaultStyle]);
//                $section->addText($product['product_id']['name'], [$defaultStyle]);
//                $section->addText('Original Retail $ ' . $product['price'], [$defaultStyle]);
//                $section->addText('Suggested TLV Price $' . $product['tlv_suggested_price_max'], 'defaultStyleSpecial');
//
//                if (isset($product['product_id']['product_pending_images'][0]['name']))
//                {
//                    $section->addImage('../Uploads/product/thumb/' . $product['product_id']['product_pending_images'][0]['name']
//                            , array('width' => 200,
//                        'height' => 100,
//                        'marginLeft' => 100,
//                        'marginTop' => 200));
//                    
//                }
//            }
//        }
//        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
//        $file = 'tlv_word_' . time();
//        $filename = public_path() . '/../../Uploads/word/' . $file . '.docx';
//        $objWriter->save($filename);
//        return $file . '.docx';
    }

    public function downloadProductWordProposalProductForReview($details) {
        ini_set('max_execution_time', 900);
        ini_set('memory_limit', '4096M');

        $seller = $this->seller_repo->SellerOfId($details['seller']);
//        $file = 'tlv_word_product_' . time();
//        $filename = public_path() . '/../../Uploads/word/' . $file;


        $seller_name = '';
        if ($seller->getFirstname() != '' && $seller->getLastname() != '') {
            $seller_name = trim($seller->getFirstname()) . trim($seller->getLastname());
//        $file = $seller->getFirstname().$seller->getLastname();
        } else {
            $seller_name = trim($seller->getDisplayname());
        }
        $number = $this->mail_record_repo->countOfSellerProposalType($seller->getId());
        $number += 1;
//        $sku_number = $seller->getLastSku();
        if ($number < 100) {
            if ($number < 10) {
                $number = '00' . $number;
            } else {
                $number = '0' . $number;
            }
        }
        $seller_name = str_replace(" ", "", $seller_name);

        if (isset($details['isForClient']) && $details['isForClient'] == true) {
            $number = 'client_' . $number;
        }

        $file = $seller_name . '_pricingproposal_' . $number;
        $data = array();
        $data['details'] = $details;
        $data['seller'] = $seller;
        Excel::create($file, function($excel) use($data) {
            $excel->sheet('Sheet 1', function($sheet) use($data) {
                $seller = $data['seller'];
                $sheet->setWidth(array(
                    'A' => 25,
                    'B' => 25,
                    'C' => 20,
                    'D' => 35,
                ));
                $sheet->mergeCells('A1:D4');

                $sheet->mergeCells('A6:D6');
                $sheet->mergeCells('A7:D7');
                $sheet->mergeCells('A8:D8');
                $sheet->mergeCells('A9:D9');

                $sheet->cells('A1:D4', function($cells) {
                    $cells->setAlignment('center');
                });
                $sheet->cells('A4:D8', function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $total = 7 * count($data['details']['products']) + 11;
                $sheet->cells('A3:A' . $total, function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                $sheet->cells('A6:D9', function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });


                $objDrawing = new PHPExcel_Worksheet_Drawing;
                $objDrawing->setPath(public_path('assets/images/logo.png')); //your image path
                $objDrawing->setHeight(75);
                $objDrawing->setCoordinates('A1');
                $objDrawing->setWorksheet($sheet);
                $objDrawing->setOffsetX(370);

                $sheet->setStyle(array(
                    'font' => array(
                        'name' => 'Calibri',
                        'size' => 12,
                        'bold' => false
                    )
                ));

                $sheet->row(6, array(
                    $seller->getFirstname() . ' ' . $seller->getLastname(),
                ));
                $sheet->row(7, array(
                    $seller->getAddress(),
                ));
                if ($seller->getPhone() != 0) {
                    $sheet->row(8, array(
                        $seller->getPhone(),
                    ));
                } else {
                    $sheet->row(8, array(
                        '',
                    ));
                }
                $sheet->row(9, array(
                    $seller->getEmail(),
                ));


                $i = 11;
                foreach ($data['details']['products'] as $key => $value) {
//                    if ($value['is_send_mail'] == 1)
//                    {
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
                        '$' . $product_quote['price'],
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
//                    }
                }
            });
        })->save('xlsx');
        $stream_opts = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ]
        ];
//        $content = file_get_contents(config('app.url') . '/api/../Uploads/word/' . $file . '.xlsx');
        $content = file_get_contents(config('app.url') . 'api/storage/exports/' . $file . '.xlsx', false, stream_context_create($stream_opts));


        $file_size = \File::size(public_path() . '/../storage/exports/' . $file . '.xlsx');


//        68 mb
        if ($file_size < 68952992 && isset($details['isForClient']) && $details['isForClient'] == true) {
            $stream_opts = [
                "ssl" => [
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ]
            ];

//        $content = file_get_contents(config('app.url') . '/api/../Uploads/word/' . $file . '.xlsx');
            $content = file_get_contents(config('app.url') . 'api/storage/exports/' . $file . '.xlsx', false, stream_context_create($stream_opts));



//            \Storage::disk('google')->put($file . '.xlsx', $content);
        }
//        \Storage::disk('google')->put($file . '.xlsx', $content);
//        $config['settings']['mimeType'] = 'application/vnd.google-apps.spreadsheet';
//        \Storage::disk('google')->getAdapter()->write($file . '.xlsx', $content, $config);
//        $data_update = array();
//        $dir = '/';
//        $recursive = false; // Get subdirectories also?
//        $file1 = collect(\Storage::disk('google')->listContents($dir, $recursive))
//                ->where('type', '=', 'file')
//                ->where('filename', '=', pathinfo($file . '.xlsx', PATHINFO_FILENAME))
//                ->where('extension', '=', pathinfo($file . '.xlsx', PATHINFO_EXTENSION))
//                ->sortBy('timestamp')
//                ->last();
//        $data_update['last_proposal_file_name_base'] = $file1['path'];


        if (isset($details['isForClient']) && $details['isForClient'] == true) {
            $data_update['last_proposal_file_name'] = $file . '.xlsx';

            $this->seller_repo->update($seller, $data_update);

            $data2['seller_id'] = $seller;
            $data2['file_name'] = $file . '.xlsx';
            $data2['from_state'] = 'proposal';
            $prepared_data2 = $this->mail_record_repo->prepareData($data2);

            $this->mail_record_repo->create($prepared_data2);
        } else {
            
        }



        return $file . '.xlsx';










//        $details = $request->all();
//
//        $seller = $this->seller_repo->SellerOfId($details['seller']);
//
//
//        $phpWord = new \PhpOffice\PhpWord\PhpWord();
//        $section = $phpWord->addSection();
//        $header = $section->addHeader();
//        $header->addImage(config('app.url') . 'assets/images/site_logo.png'
//                , array('width' => 200,
//            'height' => 100,
//            'align' => 'center',
//            'marginLeft' => 100,
//            'marginTop' => 200));
//
//        $section->addText('');
//        $section->addText('');
//        $phpWord->addTitleStyle(1, array('name' => 'Tahoma', 'size' => 15, 'color' => 'Black', 'bold' => true)); //h1
//        $defaultStyle = new \PhpOffice\PhpWord\Style\Font();
//        $defaultStyle->setSize(15);
//        $defaultStyle->setName('Tahoma');
//        $phpWord->addFontStyle(
//                'defaultStyleSpecial', array('name' => 'Tahoma', 'size' => 10, 'color' => '669900',)
//        );
////seller
//        $tabs = '                            ';
//        $section->addTitle($tabs . $seller->getFirstname() . ' ' . $seller->getLastname(), 1);
//        $section->addTitle($tabs . $seller->getAddress(), 1);
//        if ($seller->getPhone() != 0)
//        {
//            $section->addTitle($tabs . $seller->getPhone(), 1);
//        }
//        else
//        {
//            $section->addTitle($tabs . '', 1);
//        }
//        $section->addTitle($tabs . $seller->getEmail(), 1);
//
//
//        $section->addText('', [$defaultStyle]);
//
//
//
//
//
//
//        foreach ($details['products'] as $key => $value)
//        {
//            if ($value['is_send_mail'] == 1)
//            {
//                $product = $this->product_quotation_repo->getProductQuotationById($value['id']);
//                $section->addText('', [$defaultStyle]);
//                $section->addText($product['product_id']['sku'], [$defaultStyle]);
//                $section->addText($product['product_id']['name'], [$defaultStyle]);
//                $section->addText('Original Retail $ ' . $product['price'], [$defaultStyle]);
//                $section->addText('Suggested TLV Price $' . $product['tlv_suggested_price_max'], 'defaultStyleSpecial');
//
//                if (isset($product['product_id']['product_pending_images'][0]['name']))
//                {
//                    $section->addImage('../Uploads/product/thumb/' . $product['product_id']['product_pending_images'][0]['name']
//                            , array('width' => 200,
//                        'height' => 100,
//                        'marginLeft' => 100,
//                        'marginTop' => 200));
//                    
//                }
//            }
//        }
//        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
//        $file = 'tlv_word_' . time();
//        $filename = public_path() . '/../../Uploads/word/' . $file . '.docx';
//        $objWriter->save($filename);
//        return $file . '.docx';
    }

    public function read_file_docx($filename) {

        $striped_content = '';

        $content = '';

        if (!$filename || !file_exists($filename))
            return false;

        $zip = zip_open($filename);

        if (!$zip || is_numeric($zip))
            return false;

        while ($zip_entry = zip_read($zip)) {

            if (zip_entry_open($zip, $zip_entry) == FALSE)
                continue;

            if (zip_entry_name($zip_entry) != "word/document.xml")
                continue;

            $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

            zip_entry_close($zip_entry);
        }// end while

        zip_close($zip);

//echo $content;
//echo "<hr>";
//file_put_contents('1.xml', $content);

        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);

        $content = str_replace('</w:r></w:p>', "\r\n", $content);

        $striped_content = strip_tags($content);

        return $striped_content;
    }

    public function downloadProductWord(Request $request) {
        $details = $request->all();
        $seller = $this->seller_repo->SellerOfId($details['seller']);
//        $file = 'tlv_word_product_' . time();
        $seller_name = '';
        if ($seller->getFirstname() != '' && $seller->getLastname() != '') {
            $seller_name = $seller->getFirstname() . $seller->getLastname();
//        $file = $seller->getFirstname().$seller->getLastname();
        } else {
            $seller_name = $seller->getDisplayname();
        }
//        $sku_number = $seller->getLastSku();
        $number = $this->mail_record_repo->countOfSellerProductForReviewType($seller->getId());
        $number += 1;

        if ($number < 100) {
            if ($number < 10) {
                $number = '00' . $number;
            } else {
                $number = '0' . $number;
            }
        }
        $file = $seller_name . '_pricingproposal_' . $number;
//        $filename = public_path() . '/../../Uploads/word/' . $file;
        $data = array();
        $data['details'] = $details;
        $data['seller'] = $seller;
        //store to storage exports folder
        Excel::create($file, function($excel) use($data) {
            $excel->sheet('Sheet 1', function($sheet) use($data) {
                $seller = $data['seller'];
                $sheet->setWidth(array(
                    'A' => 25,
                    'B' => 25,
                    'C' => 20,
                    'D' => 35,
                ));
                $sheet->mergeCells('A1:D4');

//                $sheet->mergeCells('A5:D5');
                $sheet->mergeCells('A6:D6');
                $sheet->mergeCells('A7:D7');
                $sheet->mergeCells('A8:D8');
                $sheet->mergeCells('A9:D9');

                $sheet->cells('A1:D4', function($cells) {
                    $cells->setAlignment('center');
                });
                $sheet->cells('A4:D8', function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $total = 7 * count($data['details']['products']) + 11;
                $sheet->cells('A3:A' . $total, function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $sheet->cells('A6:D9', function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $objDrawing = new PHPExcel_Worksheet_Drawing;
                $objDrawing->setPath(public_path('assets/images/logo.png')); //your image path
                $objDrawing->setHeight(75);
                $objDrawing->setCoordinates('A1');
                $objDrawing->setWorksheet($sheet);
                $objDrawing->setOffsetX(340);

                $sheet->setStyle(array(
                    'font' => array(
                        'name' => 'Calibri',
                        'size' => 12,
                        'bold' => false
                    )
                ));

//                $sheet->cells('A5:C9', function($cells)
//                {
//                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
//                });

                $sheet->row(6, array(
                    $seller->getFirstname() . ' ' . $seller->getLastname(),
                ));
                $sheet->row(7, array(
                    $seller->getAddress(),
                ));
                if ($seller->getPhone() != 0) {
                    $sheet->row(8, array(
                        $seller->getPhone(),
                    ));
                } else {
                    $sheet->row(8, array(
                        '',
                    ));
                }
                $sheet->row(9, array(
                    $seller->getEmail(),
                ));


                $i = 11;
                foreach ($data['details']['products'] as $key => $value) {
                    if ($value['product_status_id'] == 7) {

                        $product = $this->product_repo->getProductById($value['product_id']);
                        $sheet->row($i, array(
                            'SKU',
                            $product['sku'],
                        ));
                        $sheet->row($i + 1, array(
                            'Name',
                            $product['name'],
                        ));
                        $sheet->row($i + 2, array(
                            'Original Retail',
                            '$' . $product['price'],
                        ));


                        $sheet->getStyle('A' . ($i + 3))->applyFromArray(array(
                            'font' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => '00B050')
                            )
                        ));
                        $sheet->getStyle('B' . ($i + 3))->applyFromArray(array(
                            'font' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => '00B050')
                            )
                        ));


                        $sheet->row($i + 3, array(
                            'Suggested TLV Price',
                            '$' . $product['tlv_suggested_price_max'],
                        ));



                        $sheet->row($i + 4, array(
                            'Max price',
                            '$' . $product['tlv_suggested_price_max'],
                        ));

                        $sheet->row($i + 5, array(
                            'Min price',
                            '$' . $product['tlv_suggested_price_min'],
                        ));



                        $sheet->cells('A' . $i . ':D' . ($i + 5) . '', function($cells) {
                            $cells->setBorder('thin', 'thin', 'thin', 'thin');
                        });
                        if (isset($product['product_pending_images'][0]['name'])) {
                            $offset = 0;
                            foreach ($product['product_pending_images'] as $key2 => $value2) {


                                $objDrawing = new PHPExcel_Worksheet_Drawing;
                                $objDrawing->setPath(public_path('../../Uploads/product/' . $value2['name'])); //your image path
                                $objDrawing->setWidthAndHeight(80, 80);
//                                $objDrawing->setResizeProportional(true);
                                $objDrawing->setCoordinates('C' . $i);
                                $objDrawing->setWorksheet($sheet);
                                $objDrawing->setOffsetX($offset);
                                $offset += 100;
                            }
                        }
                        $i = $i + 7;
                    }
                }
            });
        })->save('xlsx');
//        $content = file_get_contents(config('app.url') . '/api/../Uploads/word/' . $file . '.xlsx');
        $stream_opts = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ]
        ];
        $content = file_get_contents(config('app.url') . 'api/storage/exports/' . $file . '.xlsx', false, stream_context_create($stream_opts));


        \Storage::disk('google')->put($file . '.xlsx', $content);



        $data_update = array();
        $dir = '/';
        $recursive = false; // Get subdirectories also?
        $file1 = collect(\Storage::disk('google')->listContents($dir, $recursive))
                ->where('type', '=', 'file')
                ->where('filename', '=', pathinfo($file . '.xlsx', PATHINFO_FILENAME))
                ->where('extension', '=', pathinfo($file . '.xlsx', PATHINFO_EXTENSION))
                ->sortBy('timestamp')
                ->last();
        $data_update['last_product_file_name_base'] = $file1['path'];


        $data_update['last_product_file_name'] = $file . '.xlsx';

        $this->seller_repo->update($seller, $data_update);
        return $file . '.xlsx';
    }

    public function downloadProductWord2(Request $request) {

        $details = $request->all();

        $seller = $this->seller_repo->SellerOfId($details['seller']);


        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $header = $section->addHeader();
        $header->addImage(config('app.url') . 'assets/images/site_logo.png'
                , array('width' => 200,
            'height' => 100,
            'align' => 'center',
            'marginLeft' => 100,
            'marginTop' => 200));

        $section->addText('');
        $section->addText('');
        $phpWord->addTitleStyle(1, array('name' => 'Tahoma', 'size' => 15, 'color' => 'Black', 'bold' => true)); //h1
        $defaultStyle = new \PhpOffice\PhpWord\Style\Font();
        $defaultStyle->setSize(15);
        $defaultStyle->setName('Tahoma');
//        $defaultStyleSpecial = new \PhpOffice\PhpWord\Style\Font();
//        $defaultStyleSpecial->setSize(15);
//        $defaultStyleSpecial->setName('Tahoma');
//        $defaultStyleSpecial->setColor('gray');
        $phpWord->addFontStyle(
                'defaultStyleSpecial', array('name' => 'Tahoma', 'size' => 10, 'color' => '669900',)
        );
//seller
        $tabs = '                            ';
        $section->addTitle($tabs . $seller->getFirstname() . ' ' . $seller->getLastname(), 1);
        $section->addTitle($tabs . $seller->getAddress(), 1);
        if ($seller->getPhone() != 0) {
            $section->addTitle($tabs . $seller->getPhone(), 1);
        } else {
            $section->addTitle($tabs . '', 1);
        }
//        $section->addTitle($tabs . $seller->getPhone(), 1);
        $section->addTitle($tabs . $seller->getEmail(), 1);


        $section->addText('', [$defaultStyle]);






        foreach ($details['products'] as $key => $value) {
            if ($value['product_status_id'] == 7) {
                $product = $this->product_repo->getProductById($value['product_id']);
                $section->addText('', [$defaultStyle]);
                $section->addText($product['sku'], [$defaultStyle]);
                $section->addText($product['name'], [$defaultStyle]);
                $section->addText('Original Retail $ ' . $product['price'], [$defaultStyle]);
                $section->addText('Suggested TLV Price $' . $product['tlv_suggested_price_max'], 'defaultStyleSpecial');

                if (isset($product['product_pending_images'][0]['name'])) {
                    $section->addImage('../Uploads/product/thumb/' . $product['product_pending_images'][0]['name']
                            , array('width' => 200,
                        'height' => 100,
                        'marginLeft' => 100,
                        'marginTop' => 200));
//                $string = $string . '<img src="../Uploads/product/thumb/' . $product['product_id']['product_pending_images'][0]['name'] . '" /><br><br>';
                }
            }
        }




//        $objWriter2 = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007',true);
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $file = 'tlv_word_product_' . time();
        $filename = public_path() . '/../../Uploads/word/' . $file . '.docx';
//        header("Content-Description: File Transfer");
//        header("Content-Disposition: attachment; filename=\".$file.'.docx' \"");
//        header('Content-Type: application/octet-stream');
//        header('Content-Transfer-Encoding: binary');
//        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
//        header('Expires: 0');

        $objWriter->save($filename);

//        echo "<pre>";
//        print_r($content);
//        die;
//        header('Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
//        header('Content-Disposition: attachment; filename="' . $file . '.docx"');
//        $file_content=readfile($filename);
//        header('Content-Disposition: inline');
//        header('Content-Type: appication/msword'  );
//        $content1 = readfile(config('app.url') . '/api/../Uploads/word/' . $file . '.docx'); 
//        $objReader = \PhpOffice\PhpWord\IOFactory::createReader('Word2007');
//        $content1 = $objReader->load(config('app.url') . '/api/../Uploads/word/' . $file . '.docx');
//        $content1 = file_get_contents(config('app.url') . '/api/../Uploads/word/' . $file . '.docx');
//        $content = $this->readDocFile(config('app.url') . '/api/../Uploads/word/' . $file . '.docx');
//        $content1 = \PhpOffice\PhpWord\IOFactory::load(config('app.url') . '/api/../Uploads/word/' . $file . '.docx');
//        header('Content-Disposition: inline');
//        header('Content-Type: appication/vnd.openxmlformats-officedocument.wordprocessingml.document');
//        $file1= fopen(config('app.url') . '/api/../Uploads/word/' . $file . '.docx','r');
//        fclose($file1);

        $stream_opts = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ]
        ];
        $content = file_get_contents(config('app.url') . '/api/../Uploads/word/' . $file . '.docx', false, stream_context_create($stream_opts));


        \Storage::disk('google')->put($file . '.docx', $content);
//        \Storage::disk('google')->putFileAs('', new File(asset('public/assets/tlv_word_product_1501313584.docx')), $file.'.docx');
//        move_uploaded_file(config('app.url') . '/api/../Uploads/word/' . $file . '.docx', 'https://drive.google.com/drive/u/2/folders/0B_Q14BariG0KQlNVek80ampPOU0');
        $data_update = array();
        $data_update['last_product_file_name'] = $file . '.docx';

        $this->seller_repo->update($seller, $data_update);

        return $file . '.docx';
    }

//    public function readDocFile($file){
//         $content = \PhpOffice\PhpWord\IOFactory::load($file, 'Word2007');
//         return $content;
//    }



    public function downloadProductPdf(Request $request) {
        $details = $request->all();

        $seller = $this->seller_repo->SellerOfId($details['seller']);

        $temp_string = '';
        $string = '';

        foreach ($details['products'] as $key => $value) {
            $product = $this->product_quotation_repo->getProductQuotationById($value['id']);
            $string = $string . '<div style="margin-top: 30px;">';
            $string = $string . '<h4 style="color: black; font-weight: 700;">' . $product['product_id']['sku'] . '</h4><br>';
            $string = $string . '<h4 style="color: black; font-weight: 300;">' . $product['product_id']['name'] . '</h4><br>';
            $string = $string . '<h4 style="color: black; font-weight: 300;">Original Retail $' . $product['price'] . '</h4><br>';
            $string = $string . '<h4 style="color: #00ff00; font-weight: 300;">Suggested TLV Price $' . $product['tlv_suggested_price_max'] . '</h4><br>';

            if (isset($product['product_id']['product_pending_images'][0]['name'])) {
                $string = $string . '<img src="../Uploads/product/thumb/' . $product['product_id']['product_pending_images'][0]['name'] . '" /><br><br>';
            }

            $string = $string . '</div>';
        }
//        foreach ($details['products'] as $key => $value)
//        {
//            $product = $this->product_quotation_repo->getProductQuotationById($value['id']);
//
//            $temp_string = $temp_string . '<tr>';
//            $temp_string = $temp_string . '<td>' . $product['product_id']['sku'] . '</td>';
//            $temp_string = $temp_string . '<td>' . $product['product_id']['sku'] . '</td>';
//            $temp_string = $temp_string . '<td>' . $product['product_id']['name'] . '</td>';
//            $temp_string = $temp_string . '<td>' . $product['price'] . '</td>';
//            $temp_string = $temp_string . '<td>' . $product['tlv_suggested_price_min'] . '</td>';
//            $temp_string = $temp_string . '<td>' . $product['tlv_suggested_price_max'] . '</td>';
//
//            $temp_string = $temp_string . '</tr>';
//        }

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Vaibhav Raychura');
        $pdf->SetTitle('The Local Vault');
        $pdf->SetSubject('Product Details');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        $pdf->SetHeaderData('../../../../../../assets/images/site_logo.png', PDF_HEADER_LOGO_WIDTH, '', '');

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        $pdf->SetFont('helvetica', '', 10);

        $pdf->AddPage();

        $temp = 'Hello!';
        $html = <<<EOF
<!-- EXAMPLE OF CSS STYLE -->
<style>
    h1 {
        color: teal;
        font-family: times;
        font-size: 24pt;
        text-decoration: none;
    }
    h2 {
        color: orange;
        font-family: times;
        font-size: 18pt;
        text-decoration: none;
    }
    p.first {
        color: #003300;
        font-family: helvetica;
        font-size: 12pt;
    }
    p.first span {
        color: #006600;
        font-style: italic;
    }
    p#second {
        color: rgb(00,63,127);
        font-family: times;
        font-size: 12pt;
        text-align: justify;
    }
    p#second > span {
        background-color: #FFFFAA;
    }
    table{
        color: #003300;
        font-family: helvetica;
        font-size: 8pt;
        border-left: 3px solid red;
        border-right: 3px solid #FF00FF;
        border-top: 3px solid green;
        border-bottom: 3px solid blue;
        background-color: #ccffcc;
    }
    tr {
        padding: 5px;
    }
    td {
        border: 2px solid blue;
        background-color: #ffffee;
    }
    td.second {
        border: 2px dashed green;
    }
    div.test {
        color: #CC0000;
        background-color: #FFFF66;
        font-family: helvetica;
        font-size: 10pt;
        border-style: solid solid solid solid;
        border-width: 2px 2px 2px 2px;
        border-color: green #FF00FF blue red;
        text-align: center;
    }
    .lowercase {
        text-transform: lowercase;
    }
    .uppercase {
        text-transform: uppercase;
    }
    .capitalize {
        text-transform: capitalize;
    }
</style>
                
              
                
EOF;

        $pdf->writeHTML($html, true, false, true, false, '');

        $html = '
<div style="text-align: center; color: #000;">            
<h3 style="color: black;">' . $seller->getFirstname() . ' ' . $seller->getLastname() . '</h3><br>
<h4 style="color: black;">' . $seller->getAddress() . '</h4><br>
<h4 style="color: black;">' . $seller->getPhone() . '</h4><br>
<h4 style="color: blue;">' . $seller->getEmail() . '</h4><br>
</div>

<div style="padding: 10px;">
' . $string . '
</div>';

        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->lastPage();

        $file = 'tlv_pdf_' . time();

        $filename = public_path() . '/../../Uploads/pdf/' . $file . '.pdf';
        $pdf->output($filename, 'F');

        return $file . '.pdf';
    }

    public function downloadStrageProductPdfProposal(Request $request) {

        ini_set('memory_limit', '4096M');
        ini_set('max_execution_time', 0);

        $details = $request->all();
        $seller = $this->seller_repo->SellerOfId($details['seller']);

        $seller_name = '';
        if ($seller->getFirstname() != '' && $seller->getLastname() != '') {
            $seller_name = trim($seller->getFirstname()) . trim($seller->getLastname());
        } else {
            $seller_name = trim($seller->getDisplayname());
        }

        $number = $this->mail_record_repo->countOfSellerProposalType($seller->getId());
        $number += 1;

        if ($number < 100) {
            if ($number < 10) {
                $number = '00' . $number;
            } else {
                $number = '0' . $number;
            }
        }

        $seller_name = str_replace(" ", "", $seller_name);

        if (isset($details['isForClient']) && $details['isForClient'] == true) {
            $number = 'client_' . $number;
        }

        $file = $seller_name . '_storageroposal_' . $number;
        $data = array();

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('The Local Vault');
        $pdf->SetSubject('Product Details');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
        $pdf->SetHeaderData('../../../../../../assets/images/site_logo.png', PDF_HEADER_LOGO_WIDTH, '', '');
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->AddPage();

        $html = <<<EOF
<style>
    .special_td{
      color:#00B050;
          }
    .border_top{
        border-top: 1px;
    }
    .border_left{
        border-left: 1px;
    }
    .border_top{
        border-top: 1px;
    }
    .border_right{
        border-right: 1px;
    }
    .border_bottom{
        border-bottom: 1px;
    }         
</style>       
                
EOF;

        $pdf->writeHTML($html, true, false, true, false, '');

        $html = '';
        $html .= '<table width="100%" cellpadding="2" cellspacing="2">';

        $html .= '<tr>';
        $html .= '  <td align="center" colspan="4">';
        $html .= '<b>' . $seller->getFirstname() . ' ' . $seller->getLastname() . '</b>';
        $html .= '  </td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '  <td align="center" colspan="4">';
        $html .= '<b>' . $seller->getAddress() . '</b>';
        $html .= '  </td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '  <td align="center" colspan="4">';
        if ($seller->getPhone() != 0) {
            $html .= '<b>' . $seller->getPhone() . '</b>';
        }
        $html .= '  </td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '  <td align="center" colspan="4">';
        $html .= '<b>' . $seller->getEmail() . '</b>';
        $html .= '  </td>';
        $html .= '</tr>';


        $html .= '<tr>';
        $html .= '  <td colspan="4">';
        $html .= '  </td>';
        $html .= '</tr>';

        foreach ($details['product_status'] as $key => $value) {
            $product_quote = $this->product_quotation_repo->getProductQuotationById($value['product_quotation_id']);

            $rowspan = 5;

            $html .= '<tr>';
            $html .= '  <th align="center" style="border-top: 1px solid black;border-left: 1px solid black;">';
            $html .= '  <b>SKU</b>';
            $html .= '  </th>';
            $html .= '  <td style="border-top: 1px solid black;">';
            $html .= $product_quote['product_id']['sku'];
            $html .= '  </td>';

            $html .= '  <td style="border-top: 1px solid black;border-right: 1px solid black;border-bottom: 1px solid black;" colspan="2" rowspan="' . $rowspan . '">';
            if (isset($product_quote['product_id']['product_pending_images'][0]['name'])) {
                $offset = 0;
                foreach ($product_quote['product_id']['product_pending_images'] as $key2 => $value2) {
                    $html .= '<img height="80" width="80" src="' . config('app.url') . 'Uploads/product/thumb/' . $value2['name'] . '">';
                }
            }
            $html .= '  </td>';
            $html .= '</tr>';


            $html .= '<tr>';
            $html .= '  <th align="center" style="border-left: 1px solid black;">';
            $html .= '  <b>Name</b>';
            $html .= '  </th>';
            $html .= '  <td>';
            $html .= $product_quote['product_id']['name'];
            $html .= '  </td>';
            $html .= '</tr>';


            $html .= '<tr>';
            $html .= '  <th align="center" style="border-left:1px solid black;">';
            $html .= '  <b>TLV price</b>';
            $html .= '  </th>';
            $html .= '  <td>';
            $html .= '$' . $product_quote['tlv_price'];
            $html .= '  </td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '  <th align="center" style="border-left:1px solid black;">';
            $html .= '  <b>Storage Fee</b>';
            $html .= '  </th>';
            $html .= '  <td>';
            $html .= '$' . $product_quote['storage_pricing'];
            $html .= '  </td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '  <th align="center" style="border-left:1px solid black;border-bottom:1px solid black;" >';
            $html .= '  <b>Proposal Date</b>';
            $html .= '  </th>';
            $html .= '  <td style="border-bottom: 1px solid black;">';
            $html .= $product_quote['created_at']->format('m/d/Y');
            $html .= '  </td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '  <td colspan="4">';
            $html .= '  </td>';
            $html .= '</tr>';
        }
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->lastPage();

        $filename = public_path() . '/../../api/storage/exports/' . $file . '.pdf';
        $pdf->output($filename, 'F');

        $stream_opts = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ]
        ];

        $content = file_get_contents(config('app.url') . 'api/storage/exports/' . $file . '.pdf', false, stream_context_create($stream_opts));

        $file_size = \File::size(public_path() . '/../storage/exports/' . $file . '.pdf');

        //        68 mb
        if ($file_size < 68952992 && isset($details['isForClient']) && $details['isForClient'] == true) {
            $content = file_get_contents(config('app.url') . 'api/storage/exports/' . $file . '.pdf', false, stream_context_create($stream_opts));
        }

        if (isset($details['isForClient']) && $details['isForClient'] == true) {
            $data_update['last_proposal_file_name'] = $file . '.pdf';

            $this->seller_repo->update($seller, $data_update);

            $data2['seller_id'] = $seller;
            $data2['file_name'] = $file . '.pdf';
            $data2['from_state'] = 'proposal';
            $prepared_data2 = $this->mail_record_repo->prepareData($data2);

            $this->mail_record_repo->create($prepared_data2);
        }

        return $file . '.pdf';
    }

}
