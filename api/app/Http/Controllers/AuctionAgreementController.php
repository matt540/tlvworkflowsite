<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\ProductsQuotationRepository;
use App\Repository\ProductsQuotationRepositoryNew;
use App\Repository\AuctionAgreementRepository;
use App\Repository\SellerRepository;

class AuctionAgreementController extends Controller {

    private $product_quotation_repo;
    private $product_quotation_repo_new;
    private $auction_agreement_repo;
    private $seller_repo;

    public function __construct(ProductsQuotationRepository $product_quotation_repo,
            ProductsQuotationRepositoryNew $product_quotation_repo_new,
            AuctionAgreementRepository $auction_agreement_repo,
            SellerRepository $sellerRepo) {

        $this->product_quotation_repo = $product_quotation_repo;
        $this->product_quotation_repo_new = $product_quotation_repo_new;
        $this->auction_agreement_repo = $auction_agreement_repo;
        $this->seller_repo = $sellerRepo;
    }

    public function checkAuctionAgreement(Request $request) {
        $data = $request->all();

        $response_data = [
            'is_valid' => false,
            'status' => false
        ];

        if (isset($data['auction_agreement_id'])) {
            try {
                $auctionAgreementId = \Crypt::decrypt($data['auction_agreement_id']);
                $auctionAgreement = $this->auction_agreement_repo->ofId($auctionAgreementId);

                if ($auctionAgreement) {
                    $response_data['is_valid'] = true;
                    if ($auctionAgreement->getIs_form_filled() == 1) {
                        $response_data['status'] = true;
                    }
                }
            } catch (\RuntimeException $e) {
                // Content is not encrypted.
            }
        }
        return response()->json($response_data, 200);
    }

    public function sendMailReject(Request $request) {

        $products = $request->all();
        $product_quots = array();
        $product_quot_ids = [];

        if (count($products['products']) > 0) {
            $seller = $this->seller_repo->SellerOfId($products['seller']);
            foreach ($products['products'] as $key => $value) {
                $product_quot_ids[] = $value['id'];
                $product_quot = $this->product_quotation_repo->ProductQuotationOfId($value['id']);
                $product_quots[] = $product_quot;
                if ($value['is_send_mail'] == 2) {

                    $data = [
                        'is_send_mail' => $value['is_send_mail']
                    ];

                    $this->product_quotation_repo->update($product_quot, $data);
                }
            }

            $auctionAgreementData = [
                'is_form_filled' => 0,
                'seller_id' => $seller,
                'pdf' => '',
                'quote_ids_json' => json_encode($product_quot_ids)
            ];

            $auctionAgreementPreparedData = $this->auction_agreement_repo->prepareData($auctionAgreementData);
            $auctionAgreementEntity = $this->auction_agreement_repo->create($auctionAgreementPreparedData);
            $auctionAgreementEncId = \Crypt::encrypt($auctionAgreementEntity->getId());
            $agreement_link = config('app.url') . 'auction_agreement/' . $auctionAgreementEncId;


            if ($product_quot->getProductId()->getSellerid()->getEmail() != '') {
                $greeting = "Dear " . $seller->getFirstName() . ',';
                $introLines = [
                    "Thank you so much for submitting your Item(s) on TLV! Based upon sales history and the buying preferences of our TLV audience, we believe your items will be best received on our platform, TLV Auctions! Listing your pieces on TLV Auctions will allow you to consummate sales quickly and at reasonable prices.",
                    "Just to give you a quick overview of TLV Auctions:"
                ];

                $auctionLines = [
                    "There are no fees associated with listing items on TLV Auctions.",
                    "Once we have received a signed TLV Auctions Agreement, TLV will arrange an appointment to photograph and catalog the Item(s).",
                    "TLV Auctions’ sales will be conducted on an online platform, Live Auctioneers, that engages with over 13 million registered bidders.",
                    "Sellers receive Net Sale Proceeds equal to 80% of their Item’s Hammer Price.",
                    "TLV will oversee all shipping and pick-ups of sold Items.",
                    "Free storage is available for Items listed on TLV Auctions. However, if a client would like TLV to move their pieces to the TLV Warehouse, this fee will be deducted from their commission upon close of auction.",
                    "Once sold items have been picked up, TLV will issue consignors Sellers their payments."
                ];

                $dataForView = [
                    'product_quots' => $product_quots,
                    'greeting' => $greeting,
                    'seller' => $seller,
                    'level' => 'success',
                    'outroLines' => [0 => ''],
                    'introLines' => $introLines,
                    'auctionLines' => $auctionLines,
                    'agreement_link' => $agreement_link
                ];

                $myViewData = \View::make('auction_agreement.reject_to_auction_mail', $dataForView)->render();

                if (app('App\Http\Controllers\EmailController')->sendMail($seller->getEmail(), 'Thank you for reaching out to us at TLV!', $myViewData)) {
                    
                }
            }
        }

        return response()->json('ProductQuotation saved Successfully', 200);
    }

    public function saveAuctionAgreement(Request $request) {
        $data = $request->all();
        $auction_agreement_id = \Crypt::decrypt($data['auction_agreement']['id']);

        if ($auction_agreement_id) {

            if (isset($data['signature']['dataUrl'])) {

                $image = $data['signature']['dataUrl']; // your base64 encoded

                $image = str_replace('data:image/png;base64,', '', $image);

                $image = str_replace(' ', '+', $image);

                $imageName = str_random(25) . '.' . 'png';

                \File::put(public_path() . '/../../Uploads/auction_agreement_sign/' . $imageName, base64_decode($image));
            }

            $file = 'auction_agreement_' . time();

            $pdf_file_name = self::pdfGenerateAuctionAgreement($data['auction_agreement'], $imageName, $file);

            $pdf_file_path_with_out_card = self::pdfGenerateAuctionAgreement($data['auction_agreement'], $imageName, $file, true);


            if (isset($data['auction_agreement']['agreement_date'])) {

                $data['auction_agreement']['agreement_date'] = new \DateTime(date('Y-m-d H:i:s', strtotime($data['auction_agreement']['agreement_date'])));
            }


            $auctionAgreementUpdateData = [
                'data_json' => json_encode($data['auction_agreement']),
                'signature' => $imageName,
                'is_form_filled' => 1,
                'pdf' => $pdf_file_name,
                'fillup_date' => new \DateTime()
            ];

            $auction_agreement = $this->auction_agreement_repo->ofId($auction_agreement_id);

            $this->auction_agreement_repo->update($auction_agreement, $auctionAgreementUpdateData);

            $quoteIdsJson = $auction_agreement->getQuote_ids_json();
            $quoteIds = json_decode($quoteIdsJson, true);

            foreach ($quoteIds as $quoteId) {
                $product_quot = $this->product_quotation_repo->ProductQuotationOfId($quoteId);
                $this->product_quotation_repo->update($product_quot, ['reject_to_auction' => 1]);
            }


            $link = config('app.url') . 'Uploads/auction_agreement_pdf/' . $pdf_file_name;
            $linkText = "Click here to download agreement";

            $introLines = array();

            $introLines[0] = "TLV Auction Agreement Submitted";
            $myViewData = \View::make('emails.auction_agreement_filled', ['link' => $link, 'level' => 'success', 'introLines' => $introLines, 'linkText' => $linkText])->render();

            $seller = $auction_agreement->getSeller_id();

            $sellerLastname = '';

            if ($seller) {

                $sellerLastname = $seller->getLastname();
            }


            $attachments = [];
            $bccs = [];
            $ccs = [];
            $ccs[] = 'sell@thelocalvault.com';
            if (app('App\Http\Controllers\EmailController')->sendMail('Contract@thelocalvault.com', 'Auction Agreement: ' . $sellerLastname, $myViewData, $attachments, $bccs, $ccs)) {
                
            }

            return response()->json('Auction Agreement Updated Successfully', 200);
        }
    }

    public function pdfGenerateAuctionAgreement($data, $signature_image, $file = 'auction_agreement_', $hideCreditCard = false) {

        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('TLV');
        $pdf->SetTitle('Auction Agreement');
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP - 10, PDF_MARGIN_RIGHT);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->setPrintHeader(false);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        $pdf->SetFont('helvetica', '', 10);
        $pdf->AddPage();


        $html = view('auction_agreement.auction_pdf', compact('data', 'signature_image', 'hideCreditCard'))->render();
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->lastPage();

        if (!$hideCreditCard) {
            $filename = public_path() . '/../../Uploads/auction_agreement_pdf/' . $file . '.pdf';
        } else {
            $filename = public_path() . '/../../Uploads/auction_agreement_pdf_without_card/' . $file . '.pdf';
        }

        $pdf->output($filename, 'F');
        $pdfs = $file . '.pdf';
        return $pdfs;
    }

    public function getRejectToAuctionSellers(Request $request) {
        $filter = $request->all();
        $data['draw'] = $filter['draw'];
        $seller_data_total = $this->product_quotation_repo_new->getSellerProductRejectToAuction($filter);

        $data['data'] = $seller_data_total['data'];
        $data['recordsTotal'] = $seller_data_total['total'];
        $data['recordsFiltered'] = $this->product_quotation_repo_new->getSellerProductRejectToAuctionFilterCount($filter);

        return response()->json($data, 200);
    }

    public function getRejectProducts(Request $request) {
        $filter = $request->all();
        $data['draw'] = $filter['draw'];
        $product_data_total = $this->product_quotation_repo_new->getRejectToAuctionProduct($filter);

        $data['data'] = $product_data_total['data'];
        $data['recordsTotal'] = $product_data_total['total'];
        $data['recordsFiltered'] = $this->product_quotation_repo_new->getRejectToAuctionProductFilterTotal($filter);

        return response()->json($data, 200);
    }

}
