<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\ProductQuoteAgreementRepository;
use App\Repository\SellerRepository;
use Validator;

class ProductQuoteAgreementController extends Controller {

    private $productQuoteAgreementRepo;
    private $sellerRepo;

    public function __construct(ProductQuoteAgreementRepository $productQuoteAgreementRepo,
            SellerRepository $sellerRepo) {

        $this->productQuoteAgreementRepo = $productQuoteAgreementRepo;
        $this->sellerRepo = $sellerRepo;
    }

    public function saveExternalAgreement(Request $request) {

        $validationRules = [
            'seller_id' => 'required',
            'agreement' => 'required|file|mimes:pdf',
        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response(['status' => false, 'message' => 'validation fail'], 500);
        }

        $data = $request->only(array_keys($validationRules));
        $data['is_form_filled'] = 1;
        $data['externally_filled'] = 1;

        $agreementFile = $request->file('agreement');

        $pathToSave = public_path() . '/../../Uploads/user_agreement_pdf';
        $fileName = 'seller_agreement_' . time() . '.pdf';

        $agreementFile->move($pathToSave, $fileName);
        $data['pdf'] = $fileName;
        unset($data['agreement']);

        $data['seller_id'] = $this->sellerRepo->SellerOfId($data['seller_id']);

        $productQuoteObj = $this->productQuoteAgreementRepo->prepareData($data);
        $this->productQuoteAgreementRepo->create($productQuoteObj);

        return response(['status' => true, 'message' => 'added']);
    }

}
