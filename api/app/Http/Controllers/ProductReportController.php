<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Entities\Users;
use App\Entities\Role;
use App\Exports\ProductReportExport;
use App\Exports\StorageProductsExport;
use App\Exports\UsersExport;
use App\Repository\UserRepository as user_repo;
use App\Repository\RoleRepository as role_repo;
use App\Repository\OptionRepository as option_repo;
use App\Repository\ProductsRepository as product_repo;
use App\Repository\SellRepository as sell_repo;
use App\Repository\SubCategoryRepository as sub_category_repo;
use App\Repository\EmailTemplateRepository as email_template_repo;
use App\Repository\ProductsQuotationRepository as product_quote_repo;
use App\Repository\ImagesRepository as image_repo;
use App\Repository\CategoryRepository as cat_repo;
use App\Repository\MailRecordRepository as mail_record_repo;
use App\Repository\SellerRepository as seller_repo;
use App\Repository\ProductStorageAgreementRepository as product_storage_agreement_repo;
use Auth;
use Google_Service_Drive;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;
// use Excel;
use PHPExcel_Worksheet_Drawing;
use PHPExcel_Style_Fill;
use TCPDF;
use Maatwebsite\Excel\Facades\Excel;

class ProductReportController extends Controller {

    public function __construct(product_storage_agreement_repo $product_storage_agreement_repo, seller_repo $seller_repo, Google_Service_Drive $Google_Service_Drive, mail_record_repo $mail_record_repo, cat_repo $cat_repo, image_repo $image_repo, product_quote_repo $product_quote_repo, email_template_repo $email_template_repo, option_repo $option_repo, sub_category_repo $sub_category_repo, sell_repo $sell_repo, product_repo $product_repo, user_repo $user_repo, role_repo $role_repo) {

        $this->Google_Service_Drive = $Google_Service_Drive;

        $this->product_repo = $product_repo;

        $this->mail_record_repo = $mail_record_repo;

        $this->user_repo = $user_repo;

        $this->role_repo = $role_repo;

        $this->sell_repo = $sell_repo;

        $this->option_repo = $option_repo;

        $this->sub_category_repo = $sub_category_repo;

        $this->email_template_repo = $email_template_repo;

        $this->product_quote_repo = $product_quote_repo;

        $this->image_repo = $image_repo;

        $this->cat_repo = $cat_repo;

        $this->seller_repo = $seller_repo;

        $this->product_storage_agreement_repo = $product_storage_agreement_repo;
    }

    public function getProductReportFinal(Request $request) {

        $filter = $request->all();

        return $this->product_repo->getAllProductsReport($filter);
    }

    public function getStorageAgreementReport(Request $request) {
        $filter = $request->all();
        $filter['pass_from'] = 'report';
        $filter['id'] = $filter['seller_id'];

        if ($filter['state'] == 'all') {

            //for product 
            $users_data_total = $this->product_storage_agreement_repo->getAllStorageAgreements($filter);
            $data['data'] = $users_data_total['data'];
            $data['recordsTotal'] = $users_data_total['total'];
            $data['recordsFiltered'] = $this->product_storage_agreement_repo->getAllStorageAgreementsTotal($filter);
            $data_final['product'] = $data;
            $data_final['seller'] = $this->seller_repo->SellerOfId($filter['seller_id']);
            $data_final['filter'] = $filter;

            if (isset($filter['is_excel_generate']) && $filter['is_excel_generate']) {

                $file = 'Storage_Report_detail_' . time();

                $data = array();

                Excel::create($file, function($excel) use($data_final) {

                    $excel->sheet('Sheet 1', function($sheet) use($data_final) {

                        $i = 1;

                        $products = $data_final['product'];


                        $sheet->setWidth(array(
                            'A' => 25,
                            'B' => 15,
                            'C' => 15,
                            'D' => 15,
                            'E' => 15,
                            'F' => 15,
                            'G' => 15,
                            'H' => 15,
                        ));



                        $sheet->setStyle(array(
                            'font' => array(
                                'name' => 'Calibri',
                                'size' => 12,
                                'bold' => false
                            )
                        ));

                        $sheet->mergeCells('A1:H1');



                        $sheet->mergeCells('A' . $i . ':H' . $i);

                        $sheet->cells('A' . $i . ':H' . $i, function($cells) {

                            $cells->setFontWeight('bold');

                            $cells->setAlignment('center');
                        });



                        $sheet->row($i, array(
                            'TLV Client Product Report',
                        ));

                        $i = $i + 2;


                        $sheet->mergeCells('A' . $i . ':H' . $i);


                        $sheet->row($i, array(
                            'Seller Name: ' . $data_final['seller']->getFirstname() . ' ' . $data_final['seller']->getLastname(),
                        ));

//                        $i = $i + 1;

                        if (isset($data_final['filter']['start_date_updated']) && $data_final['filter']['end_date_updated']) {

                            $i = $i + 1;

                            $sheet->mergeCells('A' . $i . ':H' . $i);

                            $sheet->row($i, array(
                                'Dates: ' . $data_final['filter']['start_date_updated'] . ' to ' . $data_final['filter']['end_date_updated'],
                            ));

                            $i = $i + 2;
                        } else {

                            $i = $i + 2;
                        }


                        $sheet->mergeCells('A' . $i . ':H' . $i);

                        $sheet->cells('A' . $i . ':H' . $i, function($cells) {

                            $cells->setFontWeight('bold');

                            $cells->setAlignment('center');
                        });

                        $sheet->cells('A' . ($i + 1) . ':H' . ($i + $products['recordsTotal'] + 1) . '', function($cells) {

                            $cells->setBorder('thin', 'thin', 'thin', 'thin');
                        });



                        $sheet->row($i, array(
                            'Storage Products Total :' . $products['recordsTotal'],
                        ));

                        $i = $i + 1;

                        $sheet->row($i, array(
                            'Product Name',
                            'Internal Note',
                            'Estimated Price',
                            'Storage Price',
                            'Quantity',
                            'Created Date',
                            'Status'
                        ));

                        foreach ($products['data'] as $key => $value) {

                            $i++;

                            $sheet->row($i, array(
                                $value['name'],
                                $value['note'],
                                $value['price'],
                                $value['storage_pricing'],
                                $value['quantity'],
                                $value['created_at']->format('Y-m-d'),
                                $value['status_value'],
                            ));
                        }


                        $i = $i + 3;
                        $sheet->mergeCells('A' . $i . ':H' . $i);

                        $sheet->cells('A' . $i . ':H' . $i, function($cells) {

                            $cells->setFontWeight('bold');

                            $cells->setAlignment('center');
                        });

                        $i = $i + 2;
                    });
                })->save('xlsx');

                return $file . '.xlsx';
            }
        }

        return response()->json($data_final, 200);
    }

    public function getProductReport(Request $request) {
        $filter = $request->all();

        $filter['pass_from'] = 'report';
        $filter['id'] = $filter['seller_id'];

        if ($filter['state'] == 'all') {
            
            //for product for Review
            $users_data_total = $this->product_repo->getProductsReport($filter);
            $data['data'] = $users_data_total['data'];
            $data['recordsTotal'] = $users_data_total['total'];
            $data['recordsFiltered'] = $this->product_repo->getProductsTotalReport($filter);
            $data_final['product'] = $data;

            //for Proposal
            $users_data_total = $this->product_quote_repo->getProductQuotationsReport($filter);
            $data['data'] = $users_data_total['data'];
            $data['recordsTotal'] = $users_data_total['total'];
            $data['recordsFiltered'] = $this->product_quote_repo->getProductQuotationsTotalReport($filter);
            $data_final['proposal'] = $data;

            //for product for Production
            
            $users_data_total = $this->product_quote_repo->getProductForProductionsReport($filter);
            $data['data'] = $users_data_total['data'];
            $data['recordsTotal'] = $users_data_total['total'];
            $data['recordsFiltered'] = $this->product_quote_repo->getProductForProductionsTotalReport($filter);
            $data_final['product_for_production'] = $data;

            //for product for Pricing 
            $users_data_total = $this->product_quote_repo->getProductReportForPricings($filter);
            $data['data'] = $users_data_total['data'];
            $data['recordsTotal'] = $users_data_total['total'];
            $data['recordsFiltered'] = $this->product_quote_repo->getProductReportForPricingsTotal($filter);
            $data_final['product_for_pricing'] = $data;

            //for product for Awaiting Contract
            $users_data_total = $this->product_quote_repo->getProductReportForAwaitingContract($filter);
            $data['data'] = $users_data_total['data'];
            $data['recordsTotal'] = $users_data_total['total'];
            $data['recordsFiltered'] = $this->product_quote_repo->getProductReportForAwaitingContractTotal($filter);
            $data_final['awaiting_contract'] = $data;

            //for approvalproducts
            $users_data_total = $this->product_quote_repo->getProductQuotationsFinalReport($filter);
            $data['data'] = $users_data_total['data'];
            $data['recordsTotal'] = $users_data_total['total'];
            $data['recordsFiltered'] = $this->product_quote_repo->getProductQuotationsFinalTotalReport($filter);
            $data_final['approvalproducts'] = $data;

            //for approved
            $users_data_total = $this->product_quote_repo->getProductQuotationsFinalSyncedReport($filter);
            $data['data'] = $users_data_total['data'];
            $data['recordsTotal'] = $users_data_total['total'];
            $data['recordsFiltered'] = $this->product_quote_repo->getProductQuotationsFinalSyncedTotalReport($filter);
            $data_final['approved'] = $data;

            $data_final['seller'] = $this->seller_repo->SellerOfId($filter['seller_id']);

            $data_final['filter'] = $filter;
            
            if (isset($filter['is_excel_generate']) && $filter['is_excel_generate']) {
                $file_name = 'Report_detail_' . time()  . '.xlsx';
                $file = 'public/exports/'.$file_name;
                
                // dd($data_final);
                $products = $data_final['product'];
                $porposals = $data_final['proposal'];
                $product_for_production = $data_final['product_for_production'];
                $product_for_awaiting_contract = $data_final['awaiting_contract'];
                $product_for_pricing = $data_final['product_for_pricing'];
                $approvalproducts = $data_final['approvalproducts'];
                $approved = $data_final['approved'];

                $export_data = [];
                $title_rows_count = 6;
                $title_rows = [6];
                $section_data_rows = [];

                // START Products For Review
                $products_for_reviews_data = [];
                $title_rows_count++;
                
                if (isset($products['data']) && !empty($products['data'])) {
                    foreach ($products['data'] as $key => $value) {
                        array_push($products_for_reviews_data, [
                            $value['name'],
                            $value['sku'],
                            $value['note'],
                            $value['price'],
                            $value['quantity'],
                            $value['created_at']->format('Y-m-d'),
                            $value['status_value'],
                        ]);
                    }
                    $title_rows_count = $title_rows_count + count($products['data']);
                }
                
                $export_data = array_merge($export_data, [
                    ['PRODUCTS FOR REVIEW TOTAL :' . $products['recordsTotal']],
                    ['Product Name', 'Product SKU', 'Internal Note', 'Estimated Price', 'Quantity', 'Created Date', 'Status'],
                    $products_for_reviews_data,
                    [[], []]
                ]);
                array_push($section_data_rows, count($products['data']));
                // END Products For Review

                // START Products For Awaiting Contract
                $products_for_awaiting_contract_data = [];

                $title_rows_count+=3;
                array_push($title_rows, $title_rows_count);
                $title_rows_count++;

                if (isset($product_for_awaiting_contract['data']) && !empty($product_for_awaiting_contract['data'])) {
                    foreach ($product_for_awaiting_contract['data'] as $key => $value) {
                        array_push($products_for_awaiting_contract_data, [
                            $value['name'],
                            $value['sku'],
                            $value['note'],
                            $value['price'],
                            $value['quantity'],
                            $value['quote_created_at']->format('Y-m-d'),
                            $value['status_value'],
                        ]);
                    }
                    $title_rows_count = $title_rows_count + count($product_for_awaiting_contract['data']);
                }
                $export_data = array_merge($export_data, [
                    ['PRODUCTS FOR AWAITING CONTRACT TOTAL :' . $product_for_awaiting_contract['recordsTotal']],
                    ['Product Name', 'Product SKU', 'Internal Note', 'Estimated Price', 'Quantity', 'Created Date', 'Status'],
                    $products_for_awaiting_contract_data,
                    [[], []]
                ]);
                array_push($section_data_rows, count($product_for_awaiting_contract['data']));
                // END Products For Awaiting Contract

                // START For Production
                $production_data = [];

                $title_rows_count+=3;
                array_push($title_rows, $title_rows_count);
                $title_rows_count++;

                if (isset($product_for_production['data']) && !empty($product_for_production['data'])) {
                    foreach ($product_for_production['data'] as $key => $value) {
                        array_push($production_data, [
                            $value['name'],
                            $value['sku'],
                            $value['note'],
                            $value['price'],
                            $value['quantity'],
                            $value['quote_created_at']->format('Y-m-d'),
                            $value['is_send_mail'] == 2 ? 'Rejected' : 'Pending',
                        ]);
                        $title_rows_count = $title_rows_count + count($product_for_production['data']);
                    }
                }
                $export_data = array_merge($export_data, [
                    ['FOR PRODUCTION TOTAL :' . $product_for_production['recordsTotal']],
                    ['Product Name', 'Product SKU', 'Internal Note', 'Estimated Price', 'Quantity', 'Created Date', 'Status'],
                    $production_data,
                    [[], []]
                ]);
                array_push($section_data_rows, count($product_for_production['data']));
                // END For Production

                // START Products For Pricing
                $products_for_pricing_data = [];

                $title_rows_count+=3;
                array_push($title_rows, $title_rows_count);
                $title_rows_count++;

                if (isset($product_for_pricing['data']) && !empty($product_for_pricing['data'])) {
                    foreach ($product_for_pricing['data'] as $key => $value) {
                        array_push($products_for_pricing_data, [
                            $value['name'],
                            $value['sku'],
                            $value['note'],
                            $value['price'],
                            $value['quantity'],
                            $value['quote_created_at']->format('Y-m-d'),
                            $value['status_value'],
                        ]);
                    }
                    $title_rows_count = $title_rows_count + count($product_for_pricing['data']);
                }
                $export_data = array_merge($export_data, [
                    ['PRODUCTIONS FOR PRICING TOTAL :' . $product_for_pricing['recordsTotal']],
                    ['Product Name', 'Product SKU', 'Internal Note', 'Estimated Price', 'Quantity', 'Created Date', 'Status'],
                    $products_for_pricing_data,
                    [[], []]
                ]);
                array_push($section_data_rows, count($product_for_pricing['data']));
                // END Products For Pricing
                
                // START Approval Products
                $approval_products_data = [];

                $title_rows_count+=3;
                array_push($title_rows, $title_rows_count);
                $title_rows_count++;

                if (isset($approvalproducts['data']) && !empty($approvalproducts['data'])) {
                    foreach ($approvalproducts['data'] as $key => $value) {
                        array_push($approval_products_data, [
                            $value['name'],
                            $value['sku'],
                            $value['note'],
                            $value['price'],
                            $value['quantity'],
                            $value['approved_created_at']->format('Y-m-d'),
                            $value['status_id'] == 19 ? 'Rejected' : 'Pending',
                        ]);
                    }
                    $title_rows_count = $title_rows_count + count($approvalproducts['data']);
                }
                $export_data = array_merge($export_data, [
                    ['APPROVAL TOTAL :' . $approvalproducts['recordsTotal']],
                    ['Product Name', 'Product SKU', 'Internal Note', 'Estimated Price', 'Quantity', 'Created Date', 'Status'],
                    $approval_products_data,
                    [[], []]
                ]);
                array_push($section_data_rows, count($approvalproducts['data']));
                // END Approval

                // START Synced
                $synced_products_data = [];

                $title_rows_count+=3;
                array_push($title_rows, $title_rows_count);
                $title_rows_count++;

                if (isset($approved['data']) && !empty($approved['data'])) {
                    foreach ($approved['data'] as $key => $value) {
                        array_push($synced_products_data, [
                            $value['name'],
                            $value['sku'],
                            $value['note'],
                            $value['price'],
                            $value['quantity'],
                            $value['updated_at']->format('Y-m-d'),
                            $value['status_id'] == 18 ? 'Synced' : '---',
                        ]);
                    }
                    $title_rows_count = $title_rows_count + count($approved['data']);
                }
                $export_data = array_merge($export_data, [
                    ['SYNCED TOTAL :' . $approved['recordsTotal']],
                    ['Product Name', 'Product SKU', 'Internal Note', 'Estimated Price', 'Quantity', 'Created Date', 'Status'],
                    $synced_products_data,
                    [[], []]
                ]);
                array_push($section_data_rows, count($approved['data']));
                // END Synced

                // $data = [[1,2,3], [4,5,6]];

                $export = new ProductReportExport($export_data, $data_final['seller'], $data_final['filter'], $title_rows, $section_data_rows);

                ob_end_clean();
                ob_start();
                Excel::store($export, $file);
                
                $path = asset('api/storage/exports/'.$file_name);
                return $path;
            }
        } else {
            if ($filter['state'] == 'product') {
                $filter['id'] = $filter['seller_id'];
                $users_data_total = $this->product_repo->getProducts($filter);
                $data['data'] = $users_data_total['data'];
                $data['recordsTotal'] = $users_data_total['total'];
                $data['recordsFiltered'] = $this->product_repo->getProductsTotal($filter);
            } else if ($filter['state'] == 'proposal') {
                $filter['id'] = $filter['seller_id'];
                $users_data_total = $this->product_quote_repo->getProductQuotations($filter);
                $data['data'] = $users_data_total['data'];
                $data['recordsTotal'] = $users_data_total['total'];
                $data['recordsFiltered'] = $this->product_quote_repo->getProductQuotationsTotal($filter);
            } else if ($filter['state'] == 'product_for_production') {
                $filter['id'] = $filter['seller_id'];
                $users_data_total = $this->product_quote_repo->getProductForProductions($filter);
                $data['data'] = $users_data_total['data'];
                $data['recordsTotal'] = $users_data_total['total'];
                $data['recordsFiltered'] = $this->product_quote_repo->getProductForProductionsTotal($filter);
            } else if ($filter['state'] == 'copyright') {
                $filter['id'] = $filter['seller_id'];
                $users_data_total = $this->product_quote_repo->getCopyrights($filter);
                $data['data'] = $users_data_total['data'];
                $data['recordsTotal'] = $users_data_total['total'];
                $data['recordsFiltered'] = $this->product_quote_repo->getCopyrightsTotal($filter);
            } else if ($filter['state'] == 'approvalproducts') {
                $filter['id'] = $filter['seller_id'];
                $users_data_total = $this->product_quote_repo->getProductQuotationsFinal($filter);
                $data['data'] = $users_data_total['data'];
                $data['recordsTotal'] = $users_data_total['total'];
                $data['recordsFiltered'] = $this->product_quote_repo->getProductQuotationsFinalTotal($filter);
            } else if ($filter['state'] == 'approved') {
                $filter['id'] = $filter['seller_id'];
                $users_data_total = $this->product_quote_repo->getProductQuotationsFinalSynced($filter);
                $data['data'] = $users_data_total['data'];
                $data['recordsTotal'] = $users_data_total['total'];
                $data['recordsFiltered'] = $this->product_quote_repo->getProductQuotationsFinalSyncedTotal($filter);
            }

            $data_final[$filter['state']] = $data;
        }

        return response()->json($data_final, 200);
    }



    public function getProductReport_Old(Request $request) {



        $filter = $request->all();



        $filter['pass_from'] = 'report';

//        $data['draw'] = $filter['draw'];

        $filter['id'] = $filter['seller_id'];



        if ($filter['state'] == 'all') {







            //for product for Review

            $users_data_total = $this->product_repo->getProductsReport($filter);

            $data['data'] = $users_data_total['data'];

            $data['recordsTotal'] = $users_data_total['total'];

            $data['recordsFiltered'] = $this->product_repo->getProductsTotalReport($filter);

            $data_final['product'] = $data;







            //for Proposal

            $users_data_total = $this->product_quote_repo->getProductQuotationsReport($filter);
            $data['data'] = $users_data_total['data'];
            $data['recordsTotal'] = $users_data_total['total'];
            $data['recordsFiltered'] = $this->product_quote_repo->getProductQuotationsTotalReport($filter);
            $data_final['proposal'] = $data;

            //for product for Production
            $users_data_total = $this->product_quote_repo->getProductForProductionsReport($filter);
            $data['data'] = $users_data_total['data'];
            $data['recordsTotal'] = $users_data_total['total'];
            $data['recordsFiltered'] = $this->product_quote_repo->getProductForProductionsTotalReport($filter);
            $data_final['product_for_production'] = $data;

            //for product for Pricing 
            $users_data_total = $this->product_quote_repo->getProductReportForPricings($filter);
            $data['data'] = $users_data_total['data'];
            $data['recordsTotal'] = $users_data_total['total'];
            $data['recordsFiltered'] = $this->product_quote_repo->getProductReportForPricingsTotal($filter);
            $data_final['product_for_pricing'] = $data;

            //for product for Awaiting Contract
            $users_data_total = $this->product_quote_repo->getProductReportForAwaitingContract($filter);
            $data['data'] = $users_data_total['data'];
            $data['recordsTotal'] = $users_data_total['total'];
            $data['recordsFiltered'] = $this->product_quote_repo->getProductReportForAwaitingContractTotal($filter);
            $data_final['awaiting_contract'] = $data;





//            //for Copyright
//            $users_data_total = $this->product_quote_repo->getCopyrightsReport($filter);
//            $data['data'] = $users_data_total['data'];
//
//            $data['recordsTotal'] = $users_data_total['total'];
//            $data['recordsFiltered'] = $this->product_quote_repo->getCopyrightsTotalReport($filter);
//
//            $data_final['copyright'] = $data;
            //for approvalproducts

            $users_data_total = $this->product_quote_repo->getProductQuotationsFinalReport($filter);

            $data['data'] = $users_data_total['data'];



            $data['recordsTotal'] = $users_data_total['total'];

            $data['recordsFiltered'] = $this->product_quote_repo->getProductQuotationsFinalTotalReport($filter);



            $data_final['approvalproducts'] = $data;





            //for approved

            $users_data_total = $this->product_quote_repo->getProductQuotationsFinalSyncedReport($filter);

            $data['data'] = $users_data_total['data'];



            $data['recordsTotal'] = $users_data_total['total'];

            $data['recordsFiltered'] = $this->product_quote_repo->getProductQuotationsFinalSyncedTotalReport($filter);



            $data_final['approved'] = $data;



            $data_final['seller'] = $this->seller_repo->SellerOfId($filter['seller_id']);

            $data_final['filter'] = $filter;





            if (isset($filter['is_excel_generate']) && $filter['is_excel_generate']) {



                $file = 'Report_detail_' . time();

                $data = array();

                Excel::create($file, function($excel) use($data_final) {

                    $excel->sheet('Sheet 1', function($sheet) use($data_final) {

                        $i = 1;

                        $products = $data_final['product'];

                        $porposals = $data_final['proposal'];

                        $product_for_production = $data_final['product_for_production'];

                        $product_for_awaiting_contract = $data_final['awaiting_contract'];

                        $product_for_pricing = $data_final['product_for_pricing'];

                        $approvalproducts = $data_final['approvalproducts'];

                        $approved = $data_final['approved'];



                        $sheet->setWidth(array(
                            'A' => 25,
                            'B' => 15,
                            'C' => 15,
                            'D' => 15,
                            'E' => 15,
                            'F' => 15,
                            'G' => 15,
                            'H' => 15,
                        ));



                        $sheet->setStyle(array(
                            'font' => array(
                                'name' => 'Calibri',
                                'size' => 12,
                                'bold' => false
                            )
                        ));

                        $sheet->mergeCells('A1:H1');



                        $sheet->mergeCells('A' . $i . ':H' . $i);

                        $sheet->cells('A' . $i . ':H' . $i, function($cells) {

                            $cells->setFontWeight('bold');

                            $cells->setAlignment('center');
                        });



                        $sheet->row($i, array(
                            'TLV Client Product Report',
                        ));

                        $i = $i + 2;







                        $sheet->mergeCells('A' . $i . ':H' . $i);

//                        $sheet->cells('A' . $i . ':G' . $i, function($cells)
//                        {
//                            $cells->setFontWeight('bold');
//                            $cells->setAlignment('center');
//                        });



                        $sheet->row($i, array(
                            'Seller Name: ' . $data_final['seller']->getFirstname() . ' ' . $data_final['seller']->getLastname(),
                        ));

//                        $i = $i + 1;

                        if (isset($data_final['filter']['start_date_updated']) && $data_final['filter']['end_date_updated']) {

                            $i = $i + 1;

                            $sheet->mergeCells('A' . $i . ':H' . $i);

                            $sheet->row($i, array(
                                'Dates: ' . $data_final['filter']['start_date_updated'] . ' to ' . $data_final['filter']['end_date_updated'],
                            ));

                            $i = $i + 2;
                        } else {

                            $i = $i + 2;
                        }





//
//                        $sheet->mergeCells('A6:D6');
//                        $sheet->mergeCells('A7:D7');
//                        $sheet->mergeCells('A8:D8');
//                        $sheet->mergeCells('A9:D9');
//                        $sheet->cells('A1:D4', function($cells)
//                        {
//                            $cells->setAlignment('center');
//                        });
//                        $sheet->cells('A4:D8', function($cells)
//                        {
//                            $cells->setFontWeight('bold');
//                            $cells->setAlignment('center');
//                        });
//                        $total = 7 * count(1) + 11;
//                        $sheet->cells('A3:A' . $total, function($cells)
//                        {
//                            $cells->setFontWeight('bold');
//                            $cells->setAlignment('center');
//                        });
//                        $sheet->cells('A6:D9', function($cells)
//                        {
//                            $cells->setFontWeight('bold');
//                            $cells->setAlignment('center');
//                        });
//                        $objDrawing = new PHPExcel_Worksheet_Drawing;
//                        $objDrawing->setPath(public_path('assets/images/logo.png')); //your image path
//                        $objDrawing->setHeight(75);
//                        $objDrawing->setCoordinates('A1');
//                        $objDrawing->setWorksheet($sheet);
//                        $objDrawing->setOffsetX(400);
//                        $sheet->row(6, array(
//                            $seller->getFirstname() . ' ' . $seller->getLastname(),
//                        ));
//                        $sheet->row(7, array(
//                            $seller->getAddress(),
//                        ));
//                        if ($seller->getPhone() != 0)
//                        {
//                            $sheet->row(8, array(
//                                $seller->getPhone(),
//                            ));
//                        }
//                        else
//                        {
//                            $sheet->row(8, array(
//                                '',
//                            ));
//                        }
//                        $sheet->row(9, array(
//                            $seller->getEmail(),
//                        ));

                        $sheet->mergeCells('A' . $i . ':H' . $i);

                        $sheet->cells('A' . $i . ':H' . $i, function($cells) {

                            $cells->setFontWeight('bold');

                            $cells->setAlignment('center');
                        });

                        $sheet->cells('A' . ($i + 1) . ':H' . ($i + $products['recordsTotal'] + 1) . '', function($cells) {

                            $cells->setBorder('thin', 'thin', 'thin', 'thin');
                        });



                        $sheet->row($i, array(
                            'Products For Review Total :' . $products['recordsTotal'],
                        ));

                        $i = $i + 1;

                        $sheet->row($i, array(
                            'Product Name',
                            'Product SKU',
                            'Internal Note',
                            'Estimated Price',
                            'Quantity',
                            'Created Date',
                            'Status'
                        ));

                        foreach ($products['data'] as $key => $value) {

                            $i++;

                            $sheet->row($i, array(
                                $value['name'],
                                $value['sku'],
                                $value['note'],
                                $value['price'],
                                $value['quantity'],
                                $value['created_at']->format('Y-m-d'),
                                $value['status_value'],
                            ));
                        }


                        $i = $i + 3;
                        $sheet->mergeCells('A' . $i . ':H' . $i);

                        $sheet->cells('A' . $i . ':H' . $i, function($cells) {

                            $cells->setFontWeight('bold');

                            $cells->setAlignment('center');
                        });

                        $sheet->cells('A' . ($i + 1) . ':H' . ($i + $product_for_awaiting_contract['recordsTotal'] + 1) . '', function($cells) {

                            $cells->setBorder('thin', 'thin', 'thin', 'thin');
                        });



                        $sheet->row($i, array(
                            'Products For Awaiting Contract Total :' . $product_for_awaiting_contract['recordsTotal'],
                        ));

                        $i = $i + 1;

                        $sheet->row($i, array(
                            'Product Name',
                            'Product SKU',
                            'Internal Note',
                            'Estimated Price',
                            'Quantity',
                            'Created Date',
                            'Status'
                        ));

                        foreach ($product_for_awaiting_contract['data'] as $key => $value) {

                            $i++;

                            $sheet->row($i, array(
                                $value['name'],
                                $value['sku'],
                                $value['note'],
                                $value['price'],
                                $value['quantity'],
                                $value['quote_created_at']->format('Y-m-d'),
                                $value['status_value'],
                            ));
                        }













                        $i = $i + 3;

                        $sheet->mergeCells('A' . $i . ':H' . $i);

                        $sheet->cells('A' . $i . ':H' . $i, function($cells) {

                            $cells->setFontWeight('bold');

                            $cells->setAlignment('center');
                        });

                        $sheet->cells('A' . ($i + 1) . ':H' . ($i + $product_for_production['recordsTotal'] + 1) . '', function($cells) {

                            $cells->setBorder('thin', 'thin', 'thin', 'thin');
                        });

                        $sheet->row($i, array(
//                            'Proposals / For Production Total :' . ($porposals['recordsTotal']+$product_for_production['recordsTotal']),

                            'For Production Total :' . ( $product_for_production['recordsTotal']),
                        ));

                        $i += 1;



                        $sheet->row($i, array(
                            'Product Name',
                            'Product SKU',
                            'Internal Note',
                            'Estimated Price',
                            'Quantity',
                            'Created Date',
                            'Status'
                        ));

                        foreach ($product_for_production['data'] as $key => $value) {

                            $i++;

                            $sheet->row($i, array(
                                $value['name'],
                                $value['sku'],
                                $value['note'],
                                $value['price'],
                                $value['quantity'],
                                $value['quote_created_at']->format('Y-m-d'),
                                $value['is_send_mail'] == 2 ? 'Rejected' : 'Pending',
                            ));
                        }




                        $i = $i + 3;
                        $sheet->mergeCells('A' . $i . ':H' . $i);

                        $sheet->cells('A' . $i . ':H' . $i, function($cells) {

                            $cells->setFontWeight('bold');

                            $cells->setAlignment('center');
                        });

                        $sheet->cells('A' . ($i + 1) . ':H' . ($i + $product_for_pricing['recordsTotal'] + 1) . '', function($cells) {

                            $cells->setBorder('thin', 'thin', 'thin', 'thin');
                        });



                        $sheet->row($i, array(
                            'Products For Pricing Total :' . $product_for_pricing['recordsTotal'],
                        ));

                        $i = $i + 1;

                        $sheet->row($i, array(
                            'Product Name',
                            'Product SKU',
                            'Internal Note',
                            'Estimated Price',
                            'Quantity',
                            'Created Date',
                            'Status'
                        ));

                        foreach ($product_for_pricing['data'] as $key => $value) {

                            $i++;

                            $sheet->row($i, array(
                                $value['name'],
                                $value['sku'],
                                $value['note'],
                                $value['price'],
                                $value['quantity'],
                                $value['quote_created_at']->format('Y-m-d'),
                                $value['status_value'],
                            ));
                        }









//                        $i = $i + 3;
//                        $sheet->mergeCells('A' . $i . ':H' . $i);
//                        $sheet->cells('A' . $i . ':H' . $i, function($cells)
//                        {
//                            $cells->setFontWeight('bold');
//                            $cells->setAlignment('center');
//                        });
//                        $sheet->cells('A' . ($i + 1) . ':H' . ($i + $porposals['recordsTotal'] + 1) . '', function($cells)
//                        {
//                            $cells->setBorder('thin', 'thin', 'thin', 'thin');
//                        });
//                        $sheet->row($i, array(
//                            'Proposals Total :' . $porposals['recordsTotal'],
//                        ));
//                        $i += 1;
//
//                        $sheet->row($i, array(
//                            'Product Name',
//                            'Internal Note',
//                            'Estimated Price',
//                            'Quantity',
//                            'Max',
//                            'Min',
//                            'Created Date',
//                            'Status'
//                        ));
//
//                        foreach ($porposals['data'] as $key => $value)
//                        {
//                            $i++;
//                            $sheet->row($i, array(
//                                $value['name'],
//                                $value['note'],
//                                $value['price'],
//                                $value['quantity'],
//                                $value['tlv_suggested_price_max'],
//                                $value['tlv_suggested_price_min'],
//                                $value['quote_created_at']->format('Y-m-d'),
//                                $value['is_send_mail'] == 2 ? 'Rejected' : 'Pending',
//                            ));
//                        }
//                        $i = $i + 3;
//                        $sheet->mergeCells('A' . $i . ':H' . $i);
//                        $sheet->cells('A' . $i . ':H' . $i, function($cells)
//                        {
//                            $cells->setFontWeight('bold');
//                            $cells->setAlignment('center');
//                        });
//                        $sheet->cells('A' . ($i + 1) . ':H' . ($i + $product_for_production['recordsTotal'] + 1) . '', function($cells)
//                        {
//                            $cells->setBorder('thin', 'thin', 'thin', 'thin');
//                        });
//                        $sheet->row($i, array(
//                            'For Production Total:' . $product_for_production['recordsTotal'],
//                        ));
//                        $i += 1;
//
//                        $sheet->row($i, array(
//                            'Product Name',
//                            'Internal Note',
//                            'Estimated Price',
//                            'Quantity',
//                            'Max',
//                            'Min',
//                            'Created Date',
//                            'Status'
//                        ));
//
//                        foreach ($product_for_production['data'] as $key => $value)
//                        {
//                            $i++;
//                            $sheet->row($i, array(
//                                $value['name'],
//                                $value['note'],
//                                $value['price'],
//                                $value['quantity'],
//                                $value['tlv_suggested_price_max'],
//                                $value['tlv_suggested_price_min'],
//                                $value['for_production_created_at']->format('Y-m-d'),
//                                $value['is_product_for_production'] == 2 ? 'Rejected' : 'Pending',
//                            ));
//                        }
//                        $i = $i + 3;
//                        $sheet->mergeCells('A' . $i . ':H' . $i);
//                        $sheet->cells('A' . $i . ':H' . $i, function($cells)
//                        {
//                            $cells->setFontWeight('bold');
//                            $cells->setAlignment('center');
//                        });
//                        $sheet->cells('A' . ($i + 1) . ':H' . ($i + $copyright['recordsTotal'] + 1) . '', function($cells)
//                        {
//                            $cells->setBorder('thin', 'thin', 'thin', 'thin');
//                        });
//                        $sheet->row($i, array(
//                            'Copywriter Total:' . $copyright['recordsTotal'],
//                        ));
//                        $i += 1;
//
//                        $sheet->row($i, array(
//                            'Product Name',
//                            'Internal Note',
//                            'Estimated Price',
//                            'Quantity',
//                            'Max',
//                            'Min',
//                            'Created Date',
//                            'Status'
//                        ));
//
//                        foreach ($copyright['data'] as $key => $value)
//                        {
//                            $i++;
//                            $sheet->row($i, array(
//                                $value['name'],
//                                $value['note'],
//                                $value['price'],
//                                $value['quantity'],
//                                $value['tlv_suggested_price_max'],
//                                $value['tlv_suggested_price_min'],
//                                $value['copyright_created_at']->format('Y-m-d'),
//                                $value['is_copyright'] == 2 ? 'Rejected' : 'Pending',
//                            ));
//                        }











                        $i = $i + 3;

                        $sheet->mergeCells('A' . $i . ':H' . $i);

                        $sheet->cells('A' . $i . ':H' . $i, function($cells) {

                            $cells->setFontWeight('bold');

                            $cells->setAlignment('center');
                        });

                        $sheet->cells('A' . ($i + 1) . ':H' . ($i + $approvalproducts['recordsTotal'] + 1) . '', function($cells) {

                            $cells->setBorder('thin', 'thin', 'thin', 'thin');
                        });

                        $sheet->row($i, array(
                            'Approval Total:' . $approvalproducts['recordsTotal'],
                        ));

                        $i += 1;



                        $sheet->row($i, array(
                            'Product Name',
                            'Product SKU',
                            'Internal Note',
                            'Estimated Price',
                            'Quantity',
                            'Created Date',
                            'Status'
                        ));



                        foreach ($approvalproducts['data'] as $key => $value) {

                            $i++;

                            $sheet->row($i, array(
                                $value['name'],
                                $value['sku'],
                                $value['note'],
                                $value['price'],
                                $value['quantity'],
                                $value['approved_created_at']->format('Y-m-d'),
                                $value['status_id'] == 19 ? 'Rejected' : 'Pending',
                            ));
                        }









                        $i = $i + 3;

                        $sheet->mergeCells('A' . $i . ':H' . $i);

                        $sheet->cells('A' . ($i + 1) . ':H' . ($i + $approved['recordsTotal'] + 1) . '', function($cells) {

                            $cells->setBorder('thin', 'thin', 'thin', 'thin');
                        });

                        $sheet->cells('A' . $i . ':H' . $i, function($cells) {

                            $cells->setFontWeight('bold');

                            $cells->setAlignment('center');
                        });



                        $sheet->row($i, array(
                            'Synced Total:' . $approved['recordsTotal'],
                        ));

                        $i += 1;



                        $sheet->row($i, array(
                            'Product Name',
                            'Product SKU',
                            'Internal Note',
                            'Estimated Price',
                            'Quantity',
                            'Created Date',
                            'Status'
                        ));



                        foreach ($approved['data'] as $key => $value) {

                            $i++;

                            $sheet->row($i, array(
                                $value['name'],
                                $value['sku'],
                                $value['note'],
                                $value['price'],
                                $value['quantity'],
                                $value['updated_at']->format('Y-m-d'),
                                $value['status_id'] == 18 ? 'Synced' : '---',
                            ));
                        }



//                        $sheet->cells('A' . $i . ':D' . ($i + 5) . '', function($cells)
//                        {
//                            $cells->setBorder('thin', 'thin', 'thin', 'thin');
//                        });
//                        if (isset($product_quote['product_id']['product_pending_images'][0]['name']))
//                        {
//                            $offset = 0;
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
//                        }
//                $i = $i + 7;
//                }
//                }
                    });
                })->save('xlsx');

                return $file . '.xlsx';
            }
        } else {

            if ($filter['state'] == 'product') {



//            $filter = $request->all();
//                $data['draw'] = $filter['draw'];
//        if (JWTAuth::parseToken()->authenticate()->getRoles()[0]->getId() == 1)
//        {

                $filter['id'] = $filter['seller_id'];



                $users_data_total = $this->product_repo->getProducts($filter);

                $data['data'] = $users_data_total['data'];



                $data['recordsTotal'] = $users_data_total['total'];

                $data['recordsFiltered'] = $this->product_repo->getProductsTotal($filter);
            } else if ($filter['state'] == 'proposal') {



//            $filter = $request->all();
//                $data['draw'] = $filter['draw'];

                $filter['id'] = $filter['seller_id'];



                $users_data_total = $this->product_quote_repo->getProductQuotations($filter);

                $data['data'] = $users_data_total['data'];



                $data['recordsTotal'] = $users_data_total['total'];

                $data['recordsFiltered'] = $this->product_quote_repo->getProductQuotationsTotal($filter);
            } else if ($filter['state'] == 'product_for_production') {

//                $data['draw'] = $filter['draw'];

                $filter['id'] = $filter['seller_id'];

                $users_data_total = $this->product_quote_repo->getProductForProductions($filter);

                $data['data'] = $users_data_total['data'];



                $data['recordsTotal'] = $users_data_total['total'];

                $data['recordsFiltered'] = $this->product_quote_repo->getProductForProductionsTotal($filter);
            } else if ($filter['state'] == 'copyright') {

//            $filter = $request->all();
//                $data['draw'] = $filter['draw'];

                $filter['id'] = $filter['seller_id'];

                $users_data_total = $this->product_quote_repo->getCopyrights($filter);

                $data['data'] = $users_data_total['data'];



                $data['recordsTotal'] = $users_data_total['total'];

                $data['recordsFiltered'] = $this->product_quote_repo->getCopyrightsTotal($filter);
            } else if ($filter['state'] == 'approvalproducts') {

//            $filter = $request->all();
//                $data['draw'] = $filter['draw'];

                $filter['id'] = $filter['seller_id'];

                $users_data_total = $this->product_quote_repo->getProductQuotationsFinal($filter);

                $data['data'] = $users_data_total['data'];



                $data['recordsTotal'] = $users_data_total['total'];

                $data['recordsFiltered'] = $this->product_quote_repo->getProductQuotationsFinalTotal($filter);
            } else if ($filter['state'] == 'approved') {

//            $filter = $request->all();
//                $data['draw'] = $filter['draw'];

                $filter['id'] = $filter['seller_id'];

                $users_data_total = $this->product_quote_repo->getProductQuotationsFinalSynced($filter);

                $data['data'] = $users_data_total['data'];



                $data['recordsTotal'] = $users_data_total['total'];

                $data['recordsFiltered'] = $this->product_quote_repo->getProductQuotationsFinalSyncedTotal($filter);
            }

            $data_final[$filter['state']] = $data;
        }



//        $data['data'] = $users_data_total['data'];
//
//        $data['recordsTotal'] = $users_data_total['total'];

        return response()->json($data_final, 200);
    }

    public function getProductReport_Temp(Request $request) {



        $filter = $request->all();



        $filter['pass_from'] = 'report';

//        $data['draw'] = $filter['draw'];

        $filter['id'] = $filter['seller_id'];



        if ($filter['state'] == 'all') {







            //for product for Review

            $users_data_total = $this->product_repo->getProductsReport($filter);

            $data['data'] = $users_data_total['data'];

            $data['recordsTotal'] = $users_data_total['total'];

            $data['recordsFiltered'] = $this->product_repo->getProductsTotalReport($filter);

            $data_final['product'] = $data;







            //for Proposal

            $users_data_total = $this->product_quote_repo->getProductQuotationsReport($filter);
            $data['data'] = $users_data_total['data'];
            $data['recordsTotal'] = $users_data_total['total'];
            $data['recordsFiltered'] = $this->product_quote_repo->getProductQuotationsTotalReport($filter);
            $data_final['proposal'] = $data;

            //for product for Production
            $users_data_total = $this->product_quote_repo->getProductForProductionsReport($filter);
            $data['data'] = $users_data_total['data'];
            $data['recordsTotal'] = $users_data_total['total'];
            $data['recordsFiltered'] = $this->product_quote_repo->getProductForProductionsTotalReport($filter);
            $data_final['product_for_production'] = $data;

            //for product for Pricing 
            $users_data_total = $this->product_quote_repo->getProductReportForPricings($filter);
            $data['data'] = $users_data_total['data'];
            $data['recordsTotal'] = $users_data_total['total'];
            $data['recordsFiltered'] = $this->product_quote_repo->getProductReportForPricingsTotal($filter);
            $data_final['product_for_pricing'] = $data;

            //for product for Awaiting Contract
            $users_data_total = $this->product_quote_repo->getProductReportForAwaitingContract($filter);
            $data['data'] = $users_data_total['data'];
            $data['recordsTotal'] = $users_data_total['total'];
            $data['recordsFiltered'] = $this->product_quote_repo->getProductReportForAwaitingContractTotal($filter);
            $data_final['awaiting_contract'] = $data;





//            //for Copyright
//            $users_data_total = $this->product_quote_repo->getCopyrightsReport($filter);
//            $data['data'] = $users_data_total['data'];
//
//            $data['recordsTotal'] = $users_data_total['total'];
//            $data['recordsFiltered'] = $this->product_quote_repo->getCopyrightsTotalReport($filter);
//
//            $data_final['copyright'] = $data;
            //for approvalproducts

            $users_data_total = $this->product_quote_repo->getProductQuotationsFinalReport($filter);

            $data['data'] = $users_data_total['data'];



            $data['recordsTotal'] = $users_data_total['total'];

            $data['recordsFiltered'] = $this->product_quote_repo->getProductQuotationsFinalTotalReport($filter);



            $data_final['approvalproducts'] = $data;





            //for approved

            $users_data_total = $this->product_quote_repo->getProductQuotationsFinalSyncedReport($filter);

            $data['data'] = $users_data_total['data'];



            $data['recordsTotal'] = $users_data_total['total'];

            $data['recordsFiltered'] = $this->product_quote_repo->getProductQuotationsFinalSyncedTotalReport($filter);



            $data_final['approved'] = $data;



            $data_final['seller'] = $this->seller_repo->SellerOfId($filter['seller_id']);

            $data_final['filter'] = $filter;





            if (isset($filter['is_excel_generate']) && $filter['is_excel_generate']) {



                $file = 'Report_detail_' . time();

                $data = array();

                Excel::create($file, function($excel) use($data_final) {

                    $excel->sheet('Sheet 1', function($sheet) use($data_final) {

                        $i = 1;
                        $products = $data_final['product'];
                        $porposals = $data_final['proposal'];
                        $product_for_production = $data_final['product_for_production'];
                        $product_for_awaiting_contract = $data_final['awaiting_contract'];
                        $product_for_pricing = $data_final['product_for_pricing'];
                        $approvalproducts = $data_final['approvalproducts'];
                        $approved = $data_final['approved'];

                        $sheet->setWidth(array(
                            'A' => 25,
                            'B' => 15,
                            'C' => 15,
                            'D' => 15,
                            'E' => 15,
                            'F' => 15,
                            'G' => 15,
                            'H' => 15,
                        ));

                        $sheet->setStyle(array(
                            'font' => array(
                                'name' => 'Calibri',
                                'size' => 12,
                                'bold' => false
                            )
                        ));

                        $sheet->mergeCells('A1:H1');
                        $sheet->mergeCells('A' . $i . ':H' . $i);
                        $sheet->cells('A' . $i . ':H' . $i, function($cells) {

                            $cells->setFontWeight('bold');

                            $cells->setAlignment('center');
                        });
                        $sheet->row($i, array(
                            'TLV Client Product Report',
                        ));

                        $i = $i + 2;

                        $sheet->mergeCells('A' . $i . ':H' . $i);
                        $sheet->row($i, array(
                            'Seller Name: ' . $data_final['seller']->getFirstname() . ' ' . $data_final['seller']->getLastname(),
                        ));

                        if (isset($data_final['filter']['start_date_updated']) && $data_final['filter']['end_date_updated']) {
                            $i = $i + 1;
                            $sheet->mergeCells('A' . $i . ':H' . $i);
                            $sheet->row($i, array(
                                'Dates: ' . $data_final['filter']['start_date_updated'] . ' to ' . $data_final['filter']['end_date_updated'],
                            ));
                            $i = $i + 2;
                        } else {
                            $i = $i + 2;
                        }

                        $sheet->mergeCells('A' . $i . ':H' . $i);

                        $sheet->cells('A' . $i . ':H' . $i, function($cells) {

                            $cells->setFontWeight('bold');

                            $cells->setAlignment('center');
                        });

                        $sheet->cells('A' . ($i + 1) . ':H' . ($i + $products['recordsTotal'] + 1) . '', function($cells) {

                            $cells->setBorder('thin', 'thin', 'thin', 'thin');
                        });



                        $sheet->row($i, array(
                            'Products For Review Total :' . $products['recordsTotal'],
                        ));

                        $i = $i + 1;

                        $sheet->row($i, array(
                            'Product Name',
                            'Product SKU',
                            'Internal Note',
                            'Estimated Price',
                            'Quantity',
                            'Created Date',
                            'Status'
                        ));

                        foreach ($products['data'] as $key => $value) {

                            $i++;

                            $sheet->row($i, array(
                                $value['name'],
                                $value['sku'],
                                $value['note'],
                                $value['price'],
                                $value['quantity'],
                                $value['created_at']->format('Y-m-d'),
                                $value['status_value'],
                            ));
                        }


                        $i = $i + 3;
                        $sheet->mergeCells('A' . $i . ':H' . $i);

                        $sheet->cells('A' . $i . ':H' . $i, function($cells) {

                            $cells->setFontWeight('bold');

                            $cells->setAlignment('center');
                        });

                        $sheet->cells('A' . ($i + 1) . ':H' . ($i + $product_for_awaiting_contract['recordsTotal'] + 1) . '', function($cells) {

                            $cells->setBorder('thin', 'thin', 'thin', 'thin');
                        });



                        $sheet->row($i, array(
                            'Products For Awaiting Contract Total :' . $product_for_awaiting_contract['recordsTotal'],
                        ));

                        $i = $i + 1;

                        $sheet->row($i, array(
                            'Product Name',
                            'Product SKU',
                            'Internal Note',
                            'Estimated Price',
                            'Quantity',
                            'Created Date',
                            'Status'
                        ));

                        foreach ($product_for_awaiting_contract['data'] as $key => $value) {

                            $i++;

                            $sheet->row($i, array(
                                $value['name'],
                                $value['sku'],
                                $value['note'],
                                $value['price'],
                                $value['quantity'],
                                $value['quote_created_at']->format('Y-m-d'),
                                $value['status_value'],
                            ));
                        }













                        $i = $i + 3;

                        $sheet->mergeCells('A' . $i . ':H' . $i);

                        $sheet->cells('A' . $i . ':H' . $i, function($cells) {

                            $cells->setFontWeight('bold');

                            $cells->setAlignment('center');
                        });

                        $sheet->cells('A' . ($i + 1) . ':H' . ($i + $product_for_production['recordsTotal'] + 1) . '', function($cells) {

                            $cells->setBorder('thin', 'thin', 'thin', 'thin');
                        });

                        $sheet->row($i, array(
//                            'Proposals / For Production Total :' . ($porposals['recordsTotal']+$product_for_production['recordsTotal']),

                            'For Production Total :' . ( $product_for_production['recordsTotal']),
                        ));

                        $i += 1;



                        $sheet->row($i, array(
                            'Product Name',
                            'Product SKU',
                            'Internal Note',
                            'Estimated Price',
                            'Quantity',
                            'Created Date',
                            'Status'
                        ));

                        foreach ($product_for_production['data'] as $key => $value) {

                            $i++;

                            $sheet->row($i, array(
                                $value['name'],
                                $value['sku'],
                                $value['note'],
                                $value['price'],
                                $value['quantity'],
                                $value['quote_created_at']->format('Y-m-d'),
                                $value['is_send_mail'] == 2 ? 'Rejected' : 'Pending',
                            ));
                        }




                        $i = $i + 3;
                        $sheet->mergeCells('A' . $i . ':H' . $i);

                        $sheet->cells('A' . $i . ':H' . $i, function($cells) {

                            $cells->setFontWeight('bold');

                            $cells->setAlignment('center');
                        });

                        $sheet->cells('A' . ($i + 1) . ':H' . ($i + $product_for_pricing['recordsTotal'] + 1) . '', function($cells) {

                            $cells->setBorder('thin', 'thin', 'thin', 'thin');
                        });



                        $sheet->row($i, array(
                            'Products For Pricing Total :' . $product_for_pricing['recordsTotal'],
                        ));

                        $i = $i + 1;

                        $sheet->row($i, array(
                            'Product Name',
                            'Product SKU',
                            'Internal Note',
                            'Estimated Price',
                            'Quantity',
                            'Created Date',
                            'Status'
                        ));

                        foreach ($product_for_pricing['data'] as $key => $value) {

                            $i++;

                            $sheet->row($i, array(
                                $value['name'],
                                $value['sku'],
                                $value['note'],
                                $value['price'],
                                $value['quantity'],
                                $value['quote_created_at']->format('Y-m-d'),
                                $value['status_value'],
                            ));
                        }

                        $i = $i + 3;

                        $sheet->mergeCells('A' . $i . ':H' . $i);

                        $sheet->cells('A' . $i . ':H' . $i, function($cells) {

                            $cells->setFontWeight('bold');

                            $cells->setAlignment('center');
                        });

                        $sheet->cells('A' . ($i + 1) . ':H' . ($i + $approvalproducts['recordsTotal'] + 1) . '', function($cells) {

                            $cells->setBorder('thin', 'thin', 'thin', 'thin');
                        });

                        $sheet->row($i, array(
                            'Approval Total:' . $approvalproducts['recordsTotal'],
                        ));

                        $i += 1;



                        $sheet->row($i, array(
                            'Product Name',
                            'Product SKU',
                            'Internal Note',
                            'Estimated Price',
                            'Quantity',
                            'Created Date',
                            'Status'
                        ));



                        foreach ($approvalproducts['data'] as $key => $value) {

                            $i++;

                            $sheet->row($i, array(
                                $value['name'],
                                $value['sku'],
                                $value['note'],
                                $value['price'],
                                $value['quantity'],
                                $value['approved_created_at']->format('Y-m-d'),
                                $value['status_id'] == 19 ? 'Rejected' : 'Pending',
                            ));
                        }









                        $i = $i + 3;

                        $sheet->mergeCells('A' . $i . ':H' . $i);

                        $sheet->cells('A' . ($i + 1) . ':H' . ($i + $approved['recordsTotal'] + 1) . '', function($cells) {

                            $cells->setBorder('thin', 'thin', 'thin', 'thin');
                        });

                        $sheet->cells('A' . $i . ':H' . $i, function($cells) {

                            $cells->setFontWeight('bold');

                            $cells->setAlignment('center');
                        });



                        $sheet->row($i, array(
                            'Synced Total:' . $approved['recordsTotal'],
                        ));

                        $i += 1;



                        $sheet->row($i, array(
                            'Product Name',
                            'Product SKU',
                            'Internal Note',
                            'Estimated Price',
                            'Quantity',
                            'Created Date',
                            'Status'
                        ));



                        foreach ($approved['data'] as $key => $value) {

                            $i++;

                            $sheet->row($i, array(
                                $value['name'],
                                $value['sku'],
                                $value['note'],
                                $value['price'],
                                $value['quantity'],
                                $value['updated_at']->format('Y-m-d'),
                                $value['status_id'] == 18 ? 'Synced' : '---',
                            ));
                        }


                    });
                })->save('xlsx');

                return $file . '.xlsx';
            }
        } else {

            if ($filter['state'] == 'product') {



//            $filter = $request->all();
//                $data['draw'] = $filter['draw'];
//        if (JWTAuth::parseToken()->authenticate()->getRoles()[0]->getId() == 1)
//        {

                $filter['id'] = $filter['seller_id'];



                $users_data_total = $this->product_repo->getProducts($filter);

                $data['data'] = $users_data_total['data'];



                $data['recordsTotal'] = $users_data_total['total'];

                $data['recordsFiltered'] = $this->product_repo->getProductsTotal($filter);
            } else if ($filter['state'] == 'proposal') {



//            $filter = $request->all();
//                $data['draw'] = $filter['draw'];

                $filter['id'] = $filter['seller_id'];



                $users_data_total = $this->product_quote_repo->getProductQuotations($filter);

                $data['data'] = $users_data_total['data'];



                $data['recordsTotal'] = $users_data_total['total'];

                $data['recordsFiltered'] = $this->product_quote_repo->getProductQuotationsTotal($filter);
            } else if ($filter['state'] == 'product_for_production') {

//                $data['draw'] = $filter['draw'];

                $filter['id'] = $filter['seller_id'];

                $users_data_total = $this->product_quote_repo->getProductForProductions($filter);

                $data['data'] = $users_data_total['data'];



                $data['recordsTotal'] = $users_data_total['total'];

                $data['recordsFiltered'] = $this->product_quote_repo->getProductForProductionsTotal($filter);
            } else if ($filter['state'] == 'copyright') {

//            $filter = $request->all();
//                $data['draw'] = $filter['draw'];

                $filter['id'] = $filter['seller_id'];

                $users_data_total = $this->product_quote_repo->getCopyrights($filter);

                $data['data'] = $users_data_total['data'];



                $data['recordsTotal'] = $users_data_total['total'];

                $data['recordsFiltered'] = $this->product_quote_repo->getCopyrightsTotal($filter);
            } else if ($filter['state'] == 'approvalproducts') {

//            $filter = $request->all();
//                $data['draw'] = $filter['draw'];

                $filter['id'] = $filter['seller_id'];

                $users_data_total = $this->product_quote_repo->getProductQuotationsFinal($filter);

                $data['data'] = $users_data_total['data'];



                $data['recordsTotal'] = $users_data_total['total'];

                $data['recordsFiltered'] = $this->product_quote_repo->getProductQuotationsFinalTotal($filter);
            } else if ($filter['state'] == 'approved') {

//            $filter = $request->all();
//                $data['draw'] = $filter['draw'];

                $filter['id'] = $filter['seller_id'];

                $users_data_total = $this->product_quote_repo->getProductQuotationsFinalSynced($filter);

                $data['data'] = $users_data_total['data'];



                $data['recordsTotal'] = $users_data_total['total'];

                $data['recordsFiltered'] = $this->product_quote_repo->getProductQuotationsFinalSyncedTotal($filter);
            }

            $data_final[$filter['state']] = $data;
        }



//        $data['data'] = $users_data_total['data'];
//
//        $data['recordsTotal'] = $users_data_total['total'];

        return response()->json($data_final, 200);
    }


    public function getStorageProductsReports(Request $request) {
        $filter = $request->all();

        $data['draw'] = $filter['draw'];
        $users_data_total = $this->product_quote_repo->getStorageProductReport($filter);
        $data['data'] = $users_data_total['data'];
        $data['recordsTotal'] = $users_data_total['total'];
        $data['recordsFiltered'] = $this->product_quote_repo->getStorageProductReportTotal($filter);
        foreach ($data['data'] as $key => $value) {

            $data['data'][$key]['storage_date'] = $this->product_storage_agreement_repo->getStorageDateReport($value['id']);
        }

        return response()->json($data, 200);
    }

    public function exportStorageProducts(Request $request) {
        $filter = $request->all();
        $filteredData = $this->product_quote_repo->getStorageProductReport($filter);
        
        $products = $filteredData['data'];

        $export_data = [];
        foreach ($products as $key => &$product) {
            // $product['storage_date'] = $this->product_storage_agreement_repo->getStorageDateReport($product['id']);
            $product['storage_date'] = $this->product_storage_agreement_repo->getStorageDateReport($product['id']);
            // $export_data[]
            
        }

        $file_name = 'Storage_Report_' . time() . '.xlsx';

        // $export = new StorageProductsExport([
        //     [1, 2, 3],
        //     [4, 5, 6]
        // ]);
        // return Excel::download(new UsersExport, 'users.xlsx');

        $file = 'public/exports/'.$file_name;

        $export = new StorageProductsExport($products);
        // dd($products);
        if (ob_get_contents()) ob_end_clean();
        ob_start(); // and this
        // Excel::store(new StorageProductsExport, $file);
        Excel::store($export, $file);

        $path = asset('api/storage/exports/'.$file_name);
        return $path;
    }
    

    public function exportStorageProducts_Old(Request $request) {
        $filter = $request->all();
        $filteredData = $this->product_quote_repo->getStorageProductReport($filter);
        $products = $filteredData['data'];
        foreach ($products as $key => &$product) {
            $product['storage_date'] = $this->product_storage_agreement_repo->getStorageDateReport($product['id']);
        }

        $file = 'Storage_Report_' . time();

        Excel::create($file, function($excel) use($products) {
            $excel->sheet('Sheet 1', function($sheet) use($products) {
                $i = 1;
                $sheet->setStyle(array(
                    'font' => array(
                        'name' => 'Calibri',
                        'size' => 12,
                        'bold' => false
                    )
                ));

                $sheet->mergeCells('A' . $i . ':E' . $i);
                $sheet->cells('A' . $i . ':E' . ($i+1), function($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });

                $sheet->row($i, array(
                    'TLV Storage Report',
                ));

                $i++;

                $sheet->row($i, array(
                    'Seller Name',
                    'Product Name',
                    'SKU',
                    'Storage Cost',
                    'Storage Start Date'
                ));

                foreach ($products as $key => $value) {
                    $i++;
                    $sheet->row($i, array(
                        $value['seller_name'],
                        $value['name'],
                        $value['sku'],
                        $value['storage_pricing'],
                        $value['storage_date']
                    ));
                }
            });
        })->save('xlsx');

        return $file . '.xlsx';

//        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//        $pdf->SetCreator(PDF_CREATOR);
//        $pdf->SetTitle('The Local Vault');
//        $pdf->SetSubject('Storage Report');
//        $pdf->SetHeaderData('../../../../../../assets/images/site_logo.png', PDF_HEADER_LOGO_WIDTH, '', '');
//        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
//        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
//        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
//        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
//        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
//        $pdf->SetFont('helvetica', '', 10);
//        $pdf->AddPage();
//        
//        $html = view('report.storage-report',['products'=>$products])->render();
//        
//        $pdf->writeHTML($html, true, false, true, false, '');
//        
//        $pdf->lastPage();
//
//        $file = 'storage-report.pdf';
//        
//        $path = public_path() . '/../../Uploads/storage_report/'.$file;
//        
//        $pdf->output($path, 'F');
//        return $file;
    }

}
