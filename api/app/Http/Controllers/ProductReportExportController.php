<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\ProductsQuotationRepository;
use App\Repository\ProductsQuotationRepositoryNew;
use App\Repository\SellerRepository;
use TCPDF;

class ProductReportExportController extends Controller {

    private $product_quote_repo;
    private $product_quote_new_repo;
    private $seller_repo;

    public function __construct(ProductsQuotationRepository $product_quote_repo,
            ProductsQuotationRepositoryNew $product_quote_new_repo,
            SellerRepository $seller_repo) {
        $this->product_quote_repo = $product_quote_repo;
        $this->product_quote_new_repo = $product_quote_new_repo;
        $this->seller_repo = $seller_repo;
    }

    public function exportSellerStageProducts(Request $request) {
        $sellerId = $request->get('seller_id');
        $stage = $request->get('stage');
        $seller = $this->seller_repo->SellerOfId($sellerId);

        if ($seller === null) {
            return response(null, 421);
        }

        $products = [];

        switch ($stage) {
            case 'awaiting_contract';
                $products = $this->product_quote_new_repo->getProductsOfSellerOfAwaitingContractStage($sellerId);
                break;
            case 'for_production':
                $products = $this->product_quote_new_repo->getProductsOfSellerOfProductionStage($sellerId);
                break;
            case 'for_pricing':
                $products = $this->product_quote_new_repo->getProductsOfSellerOfPricingStage($sellerId);
                break;
            case 'approval':
                $products = $this->product_quote_new_repo->getProductsOfSellerOfApprovalStage($sellerId);
                break;
        }

        $reportView = view('report.product-export', ['seller' => $seller, 'products' => $products])->render();

        $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('TLV');
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
        $pdf->writeHTML($reportView, true, false, true, false, '');
        $pdf->lastPage();

        $file = 'products.pdf';
        $path = public_path() . '/../../Uploads/export-seller-products/' . $file;
        $pdf->output($path, 'F');
        return $file;
    }

    public function exportsellerproductslabels(Request $request) {
        $sellerId = $request->get('seller_id');
        $stage = $request->get('stage');
        $seller = $this->seller_repo->SellerOfId($sellerId);

        if ($seller === null) {
            return response(null, 421);
        }

        $products = [];

        switch ($stage) {
            case 'for_production':
                $products = $this->product_quote_new_repo->getProductsOfSellerOfProductionStage($sellerId);
                break;
            case 'for_pricing':
                $products = $this->product_quote_new_repo->getProductsOfSellerOfPricingStage($sellerId);
                break;
            case 'approval':
                $products = $this->product_quote_new_repo->getProductsOfSellerOfApprovalStage($sellerId);
                break;
        }

        //  $products = $this->product_quote_new_repo->getProductsOfSellerOfProductionStage($sellerId);





        $productsChunks = array_chunk($products, 2);

        $reportView = view('report.product-labels-export', ['seller' => $seller, 'productsChunks' => $productsChunks])->render();

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('TLV');
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
        $pdf->writeHTML($reportView, true, false, true, false, '');
        $pdf->lastPage();

        $file = 'products.pdf';
        $path = public_path() . '/../../Uploads/export-seller-products/' . $file;
        $pdf->output($path, 'F');
        return $file;
    }

    public function productReviewExport(Request $request) {
        $sellerId = $request->get('seller_id');
        // $seller = $this->seller_repo->SellerOfId($sellerId);

        $products = $this->product_quote_new_repo->getProductReviewStageProducts($sellerId);

        $productsChunks = array_chunk($products, 2);

        $reportView = view('report.product-review-stage-export', ['productsChunks' => $productsChunks])->render();

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('TLV');
        $pdf->SetTitle('The Local Vault');
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
        $pdf->writeHTML($reportView, true, false, true, false, '');
        $pdf->lastPage();

        $file = 'products.pdf';
        $path = public_path() . '/../../Uploads/export-products-review/' . $file;
        $pdf->output($path, 'F');
        return $file;
    }

}
