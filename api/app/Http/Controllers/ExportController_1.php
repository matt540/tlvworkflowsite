<?php

namespace App\Http\Controllers;

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
use Auth;
use View;
use Excel;
use PHPExcel_Worksheet_Drawing;
use PDF;
use TCPDF;

class ExportController_1 extends Controller
{

    public function __construct(mail_record_repo $mail_record_repo, product_repo $product_repo, seller_repo $seller_repo, product_quotation_repo $product_quotation_repo, product_approve_repo $product_approve_repo, user_repo $user_repo, role_repo $role_repo, option_repo $option_repo)
    {
        $this->mail_record_repo = $mail_record_repo;
        $this->product_repo = $product_repo;
        $this->user_repo = $user_repo;
        $this->role_repo = $role_repo;
        $this->option_repo = $option_repo;
        $this->product_approve_repo = $product_approve_repo;
        $this->product_quotation_repo = $product_quotation_repo;
        $this->seller_repo = $seller_repo;
    }

    public function export_user(Request $request)
    {
        $filter = $request->all();
        $users = $this->user_repo->getAllUsersForExport($filter);

        $file_name = '121_flight_catering_users_' . date('Y-m-d') . '_' . time();

        Excel::create($file_name, function($excel) use($users)
        {
            $excel->sheet('Sheet 1', function($sheet) use($users)
            {
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

                $sheet->cells('A1:G2', function($cells)
                {
                    $cells->setAlignment('center');
                });
                $sheet->cells('A3:G3', function($cells)
                {
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
                foreach ($users as $user)
                {
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

    public function exportProducts(Request $request)
    {
        $filter = $request->all();

//        $filter['start_date'] = date('Y-m-d', strtotime($filter['start_date']));
//        $filter['end_date'] = date('Y-m-d', strtotime($filter['end_date']));

        $data = $this->product_approve_repo->getAllExportProducts($filter);

        $file_name = 'The_Local_Vaults_Products_' . date('Y-m-d') . '_' . time();

        Excel::create($file_name, function($excel) use($data)
        {
            $excel->sheet('Sheet 1', function($sheet) use($data)
            {
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
                foreach ($data as $details)
                {
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

    public function downloadProductWordProposal(Request $request)
    {

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
        if ($seller->getPhone() != 0)
        {
            $section->addTitle($tabs . $seller->getPhone(), 1);
        }
        else
        {
            $section->addTitle($tabs . '', 1);
        }
        $section->addTitle($tabs . $seller->getEmail(), 1);


        $section->addText('', [$defaultStyle]);






        foreach ($details['products'] as $key => $value)
        {
            if ($value['is_send_mail'] == 1)
            {
                $product = $this->product_quotation_repo->getProductQuotationById($value['id']);
                $section->addText('', [$defaultStyle]);
                $section->addText($product['product_id']['sku'], [$defaultStyle]);
                $section->addText($product['product_id']['name'], [$defaultStyle]);
                $section->addText('Original Retail $ ' . $product['price'], [$defaultStyle]);
                $section->addText('Suggested TLV Price $' . $product['tlv_suggested_price_max'], 'defaultStyleSpecial');

                if (isset($product['product_id']['product_pending_images'][0]['name']))
                {
                    $section->addImage('../Uploads/product/thumb/' . $product['product_id']['product_pending_images'][0]['name']
                            , array('width' => 200,
                        'height' => 100,
                        'marginLeft' => 100,
                        'marginTop' => 200));
//                $string = $string . '<img src="../Uploads/product/thumb/' . $product['product_id']['product_pending_images'][0]['name'] . '" /><br><br>';
                }
            }
        }
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $file = 'tlv_word_' . time();
        $filename = public_path() . '/../../Uploads/word/' . $file . '.docx';
        $objWriter->save($filename);
        return $file . '.docx';
    }

    public function downloadProductWord(Request $request)
    {

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
        if ($seller->getPhone() != 0)
        {
            $section->addTitle($tabs . $seller->getPhone(), 1);
        }
        else
        {
            $section->addTitle($tabs . '', 1);
        }
//        $section->addTitle($tabs . $seller->getPhone(), 1);
        $section->addTitle($tabs . $seller->getEmail(), 1);


        $section->addText('', [$defaultStyle]);






        foreach ($details['products'] as $key => $value)
        {
            if ($value['product_status_id'] == 7)
            {
                $product = $this->product_repo->getProductById($value['product_id']);
                $section->addText('', [$defaultStyle]);
                $section->addText($product['sku'], [$defaultStyle]);
                $section->addText($product['name'], [$defaultStyle]);
                $section->addText('Original Retail $ ' . $product['price'], [$defaultStyle]);
                $section->addText('Suggested TLV Price $' . $product['tlv_suggested_price_max'], 'defaultStyleSpecial');

                if (isset($product['product_pending_images'][0]['name']))
                {
                    $section->addImage('../Uploads/product/thumb/' . $product['product_pending_images'][0]['name']
                            , array('width' => 200,
                        'height' => 100,
                        'marginLeft' => 100,
                        'marginTop' => 200));
//                $string = $string . '<img src="../Uploads/product/thumb/' . $product['product_id']['product_pending_images'][0]['name'] . '" /><br><br>';
                }
            }
        }




        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007',true);
        $file = 'tlv_word_product_' . time();
        $filename = public_path() . '/../../Uploads/word/' . $file . '.docx';
        $objWriter->save($filename);

//        header("Content-Description: File Transfer");
//        header('Content-Disposition: attachment; filename="' . $file . '.docx"');
//        header('Content-Type: application/msword'  );
//        header('Content-Transfer-Encoding: binary');
//        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
//        header('Expires: 0');

        $content = file_get_contents(config('app.url') . '/api/../Uploads/word/' . $file . '.docx');
        \Storage::disk('google')->put($file . '.docx', $content);


        $data_update = array();
        $data_update['last_product_file_name'] = $file . '.docx';

        $this->seller_repo->update($seller, $data_update);

        return $file . '.docx';
    }

    public function downloadProductPdf(Request $request)
    {
        $details = $request->all();

        $seller = $this->seller_repo->SellerOfId($details['seller']);

        $temp_string = '';
        $string = '';

        foreach ($details['products'] as $key => $value)
        {
            $product = $this->product_quotation_repo->getProductQuotationById($value['id']);
            $string = $string . '<div style="margin-top: 30px;">';
            $string = $string . '<h4 style="color: black; font-weight: 700;">' . $product['product_id']['sku'] . '</h4><br>';
            $string = $string . '<h4 style="color: black; font-weight: 300;">' . $product['product_id']['name'] . '</h4><br>';
            $string = $string . '<h4 style="color: black; font-weight: 300;">Original Retail $' . $product['price'] . '</h4><br>';
            $string = $string . '<h4 style="color: #00ff00; font-weight: 300;">Suggested TLV Price $' . $product['tlv_suggested_price_max'] . '</h4><br>';

            if (isset($product['product_id']['product_pending_images'][0]['name']))
            {
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

        if (@file_exists(dirname(__FILE__) . '/lang/eng.php'))
        {
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

}
