<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\ProductsQuotationRepository;
use App\Repository\ProductsRepository;
use App\Repository\ProductStorageAgreementRepository;
use App\Repository\ProductsQuotationRepositoryNew;
use App\Repository\SellerRepository;
use App\Repository\MailRecordRepository;
use TCPDF;

class WordpressAPIController extends Controller {

    private $product_quote_repo;
    private $product_quote_repo_new;
    private $product_repo;
    private $product_storage_agreement_repo;
    private $seller_repo;
    private $mail_record_repo;

    public function __construct(ProductsQuotationRepository $product_quote_repo,
            ProductsQuotationRepositoryNew $product_quote_repo_new,
            ProductsRepository $product_repo,
            ProductStorageAgreementRepository $product_storage_agreement_repo,
            SellerRepository $seller_repo,
            MailRecordRepository $mail_record_repo) {
        $this->product_quote_repo = $product_quote_repo;
        $this->product_quote_repo_new = $product_quote_repo_new;
        $this->product_repo = $product_repo;
        $this->product_storage_agreement_repo = $product_storage_agreement_repo;
        $this->seller_repo = $seller_repo;
        $this->mail_record_repo = $mail_record_repo;
    }

    public function getSellerProductOfStage(Request $request) {
        // todo auth the request
        $wpSellerId = $request->get('wp_seller_id');
        $stage = $request->get('stage');

        $data = [];

        switch ($stage) {
            case 'awaiting_contract':
                $data = $this->product_quote_repo->getAllProductQuotationOfWpSellerOfAwaitingContractStage($wpSellerId);
                break;
            case 'for_production':
                $data = $this->product_quote_repo->getAllProductQuotationOfWpSellerOfForProductionStage($wpSellerId);
                break;
            case 'for_pricing':
                $data = $this->product_quote_repo->getAllProductQuotationOfWpSellerOfForPricingStage($wpSellerId);
                break;
            case 'approval':
                $data = $this->product_quote_repo->getAllProductQuotationOfWpSellerOfApprovalStage($wpSellerId);
                break;
            case 'product_for_review';
                $data = $this->product_repo->getAllProductOfWpSellerOfProductsForReview($wpSellerId);
                break;
            case 'decline':
                $data = $this->product_quote_repo->getAllProductQuotationOfWpSellerOfRejected($wpSellerId);
                break;
            case 'in_storage':
                $data = $this->product_quote_repo->getAllProductQuotationOfWpSellerOfStorage($wpSellerId);
              
                foreach ($data as &$product) {
                    $product['storage_date'] = $this->product_storage_agreement_repo->getStorageDateReport($product['id']);
                }
                break;
        }

        return response()->json($data);
    }

    public function getSellerProductCount(Request $request) {
        // todo auth the request
        $wpSellerId = $request->get('wp_seller_id');

        $awaitingContractCount = $this->product_quote_repo->getAllProductQuotationOfWpSellerOfAwaitingContractStage($wpSellerId, true);
        $forProductionCount = $this->product_quote_repo->getAllProductQuotationOfWpSellerOfForProductionStage($wpSellerId, true);
        $forPricingCount = $this->product_quote_repo->getAllProductQuotationOfWpSellerOfForPricingStage($wpSellerId, true);
        $approvalCount = $this->product_quote_repo->getAllProductQuotationOfWpSellerOfApprovalStage($wpSellerId, true);
        $productForReviewCount = $this->product_repo->getAllProductOfWpSellerOfProductsForReview($wpSellerId, true);
        $declineCount = $this->product_quote_repo->getAllProductQuotationOfWpSellerOfRejected($wpSellerId, true);
        $inStorageCount = $this->product_quote_repo->getAllProductQuotationOfWpSellerOfStorage($wpSellerId, true);

        $response = [
            'awaiting_contract' => $awaitingContractCount,
            'for_production' => $forProductionCount,
            'for_pricing' => $forPricingCount,
            'approval' => $approvalCount,
            'product_for_review' => $productForReviewCount,
            'decline' => $declineCount,
            'in_storage' => $inStorageCount,
        ];

        return response()->json($response);
    }

    public function getSellerAllStorageAgreement(Request $request) {
        $wpSellerId = $request->get('wp_seller_id');
        $storageAgreements = $this->product_storage_agreement_repo->getSellerAllStorageAgreement($wpSellerId);
        foreach ($storageAgreements as &$agreement) {
            if (!file_exists('./../Uploads/storage_agreement_pdf/' . $agreement['pdf'])) {
                $agreement['pdf_link'] = '';
            }
        }
        return response()->json($storageAgreements);
    }

    public function getProductInforFromWpProductIds(Request $request) {
        $wpProductIds = $request->get('wp_product_ids', []);

        if (count($wpProductIds) > 0) {
            $products = $this->product_quote_repo->getProductInfoFromWpProductIds($wpProductIds);
            return response()->json($products);
        }

        return response()->json([]);
    }

    public function sendStorageProposalFromWP(Request $request) {

        $wpProductIds = $request->get('wp_product_ids', []);
        $wpSellerId = $request->get('wp_seller_id');

        $product_quot_ids = [];
        $product_quot = [];

        foreach ($wpProductIds as $wpPId) {
            $pq = $this->product_quote_repo->ProductQuotationOfWpProductId($wpPId);
            $product_quot_ids[] = $pq->getId();
            $product_quot[] = $pq;
        }

        if (count($product_quot) > 0) {
            //product_quote_agreement
            $seller_data = $this->seller_repo->SellerOfWpId($wpSellerId);

            $product_storage_agreement = [];
            $product_storage_agreement['is_form_filled'] = 0;
            $product_storage_agreement['seller_id'] = $this->seller_repo->SellerOfWpId($wpSellerId);
            $product_storage_agreement['pdf'] = '';
            $product_storage_agreement['quote_ids_json'] = json_encode($product_quot_ids);

            $product_storage_agreement_prepared_data = $this->product_storage_agreement_repo->prepareData($product_storage_agreement);
            $product_storage_agreement_obj = $this->product_storage_agreement_repo->create($product_storage_agreement_prepared_data);
            $product_storage_agreement_enc_id = \Crypt::encrypt($product_storage_agreement_obj->getId());
            $agreement_link = config('app.url') . 'storage_agreement/' . $product_storage_agreement_enc_id;


            $greeting = "Dear " . $seller_data->getFirstName() . ',';
            $link = '';
            $introLines = array();
            $introLines[0] = 'Please see below for your TLV "Storage Proposal". As outlined in our Storage Agreement, you will be charged the below Storage Fee for each Item on a monthly basis for your Item(s) stored at our facility. The first month’s fee shall be pro-rated from the date of arrival of the Item(s) at the Storage Facility for the number of days left of the month. As we sell through your Item(s), you will note that the total monthly Storage Fee will be reduced by the Storage Cost for the sold Item(s). In order to store your Item(s) at our Facility, we require a signed copy of our TLV Storage Agreement.';
            $line1 = "Once we receive a signed copy of your Storage Agreement, you will be contacted by logistics@thelocalvault.com to provide you with a quote to move your Item(s) to our Facility and arrange a date for your Item(s) to be picked up. ";
            $line2 = "";
            $line3 = "";
            $outroLines = array();
            $outroLines[0] = '';

            $attachments = array();
            $attachments[] = 'TLV Storage Agreement_31_10_2019.pdf';

            $file_name_client = $this->downloadStrageProductPdfProposal($wpSellerId, $product_quot_ids);
            $link = config('app.url') . 'api/storage/exports/' . $file_name_client;

//                        $attachments[] = 'TLV Storage Pricing List_05 _08_2019.pdf';
            $myViewData = \View::make('emails.product_storage_price', ['agreement_link' => $agreement_link, 'link' => $link, 'product_quots' => $product_quot, 'line1' => $line1, 'line2' => $line2, 'line3' => $line3, 'greeting' => $greeting, 'seller' => $seller_data, 'level' => 'success', 'outroLines' => [0 => ''], 'introLines' => $introLines])->render();

            $bccs = [];
            $ccs = [];
//            $ccs[] = 'sell@thelocalvault.com';
            $other_emails = [];
//            $other_emails[] = 'thelocalvaultcomproduction@thelocalvault.freshdesk.com';

            if (app('App\Http\Controllers\EmailController')->sendMailONLY($seller_data->getEmail(), 'TLV Storage Proposal: ' . $seller_data->getLastname(), $myViewData, $attachments, $bccs, $ccs, $other_emails)) {
                
            }
        }
        return response()->json(['file_link' => $link], 200);
    }

    private function downloadStrageProductPdfProposal($wpSellerId, $productQuots) {

        ini_set('memory_limit', '4096M');
        ini_set('max_execution_time', 0);

        $seller = $this->seller_repo->SellerOfWpId($wpSellerId);

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

        foreach ($productQuots as $product_quote_id) {
            $product_quote = $this->product_quote_repo->getProductQuotationById($product_quote_id);

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

        return $file . '.pdf';
    }

    public function getProductInforFromWpProductId(Request $request) {
        $wpProductId = $request->get('wp_product_id');
        $product = $this->product_quote_repo_new->getProductQuotationById($wpProductId);
        return response()->json($product);
    }

    public function getProductInfoFromMultipleWpProductIds(Request $request) {
        $wpProductIds = $request->get('wp_product_ids');
        $products = $this->product_quote_repo_new->getProductQuotationByWpProductIds($wpProductIds);
        return response()->json($products);
    }

    public function setSameStoragePriceToProducts(Request $request) {
        $wpProductIds = $request->get('wp_product_ids');
        $storagePrice = $request->get('storage_price');

        $productsUpdated = [];
        foreach ($wpProductIds as $wpProductId) {
            $pq = $this->product_quote_repo_new->getProductQuotationObjFromWpProductId($wpProductId);

            if (!is_null($pq)) {
                $this->product_quote_repo_new->setStoragePrice($pq, $storagePrice);

                array_push($productsUpdated, $wpProductId);
            }
        }

        return response()->json($productsUpdated);
    }

    public function setStoragePriceToProduct(Request $request) {
        $products = $request->get('products', []);

        $productsUpdated = [];
        foreach ($products as $product) {
            $pq = $this->product_quote_repo_new->getProductQuotationObjFromWpProductId($product['wp_product_id']);

            if (!is_null($pq)) {
                $this->product_quote_repo_new->setStoragePrice($pq, $product['storage_price']);

                array_push($productsUpdated, $product['wp_product_id']);
            }
        }

        return response()->json($productsUpdated);
    }

}
