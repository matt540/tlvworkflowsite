<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\ProductsQuotationRepository;
use App\Repository\ProductsRepository;
use App\Repository\ProductStorageAgreementRepository;
use App\Repository\ProductQuoteAgreementRepository;
use App\Repository\ProductsQuotationRepositoryNew;

class DashboardController extends Controller {

    private $product_quote_repo;
    private $product_quote_repo_new;
    private $product_repo;
    private $product_storage_agreement_repo;
    private $product_quote_agreement_repo;

    public function __construct(ProductsQuotationRepository $product_quote_repo,
            ProductsQuotationRepositoryNew $product_quote_repo_new,
            ProductsRepository $product_repo,
            ProductStorageAgreementRepository $product_storage_agreement_repo,
            ProductQuoteAgreementRepository $product_quote_agreement_repo) {
        $this->product_quote_repo = $product_quote_repo;
        $this->product_quote_repo_new = $product_quote_repo_new;
        $this->product_repo = $product_repo;
        $this->product_storage_agreement_repo = $product_storage_agreement_repo;
        $this->product_quote_agreement_repo = $product_quote_agreement_repo;
    }

    public function dashboardCount(Request $request) {
        $awaitingContractStageProductCount = $this->product_quote_repo_new->getAwaitingContractStageProductCount();
        $productForReviewStageProductCount = $this->product_quote_repo_new->getProductForReviewStageProductCount();
        $forProductionStageProductCount = $this->product_quote_repo_new->getForProductionStageProductCount();
        $pricingStageProductCount = $this->product_quote_repo_new->getPricingStageProductCount();
        $pricingProposalSentCount = $this->product_quote_agreement_repo->getTotalProductQuateAgreementCount();
        $pricingProposalApprovedCount = $this->product_quote_agreement_repo->getTotalProductQuateAgreementFilledCount();

        $response = [
            'awaiting_contract_stage_count' => $awaitingContractStageProductCount,
            'product_for_review_stage_count' => $productForReviewStageProductCount,
            'for_production_stage_count' => $forProductionStageProductCount,
            'pricing_stage_count' => $pricingStageProductCount,
            'pricing_proposal_sent_count' => $pricingProposalSentCount,
            'pricing_proposal_approved_count' => $pricingProposalApprovedCount,
        ];

        return $response;
    }

}
