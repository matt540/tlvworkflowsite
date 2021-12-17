<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\ProductQuoteAgreementRepository;
use App\Repository\ProductStorageAgreementRepository;
use App\Repository\ConsignmentAgreementWithStorageRepository;
use App\Repository\SellerRepository;
use Validator;

class AgreementsController extends Controller {

    private $productQuoteAgreementRepo;
    private $productStorageAgreementRepo;
    private $consignmentAgreementWithStorageRepo;
    private $sellerRepo;

    public function __construct(ProductQuoteAgreementRepository $productQuoteAgreementRepo,
            ProductStorageAgreementRepository $productStorageAgrementRepo,
            ConsignmentAgreementWithStorageRepository $consignmentAgreementWithStorageRepo,
            SellerRepository $sellerRepo) {

        $this->productQuoteAgreementRepo = $productQuoteAgreementRepo;
        $this->productStorageAgreementRepo = $productStorageAgrementRepo;
        $this->consignmentAgreementWithStorageRepo = $consignmentAgreementWithStorageRepo;
        $this->sellerRepo = $sellerRepo;
    }

    public function getAgreements(Request $request) {
        $sellerId = $request->get('seller_id');
        $productQuoteAgreements = $this->productQuoteAgreementRepo->getAllFilledAgreementsOfSeller($sellerId);
        $productStorageAgreements = $this->productStorageAgreementRepo->getAllFilledAgreementsOfSeller($sellerId);
        $consignmentWithStorageAgreements = $this->consignmentAgreementWithStorageRepo->getAllFilledAgreementsOfSeller($sellerId);

        return [
            'product_quote_agreements' => $productQuoteAgreements,
            'product_storage_agreements' => $productStorageAgreements,
            'consignment_with_storage_agreements' => $consignmentWithStorageAgreements
        ];
    }

    public function saveExternalAgreement(Request $request) {

        $validationRules = [
            'seller_id' => 'required',
            'agreement' => 'required|file|mimes:pdf',
            'type' => 'required'
        ];

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response(['status' => false,'message' => 'validation fail','errors' => $validator->errors()], 500);
        }

        $data = $request->only(array_keys($validationRules));
        $data['is_form_filled'] = 1;
        $data['externally_filled'] = 1;
        $data['seller_id'] = $this->sellerRepo->SellerOfId($data['seller_id']);

        $agreementFile = $request->file('agreement');

        switch ($data['type']) {
            case 'product_quote_agreements':
                $pathToSave = public_path() . '/../../Uploads/user_agreement_pdf';
                $fileName = 'seller_agreement_' . time() . '.pdf';

                $agreementFile->move($pathToSave, $fileName);
                $data['pdf'] = $fileName;
                unset($data['agreement']);
                unset($data['type']);

                $productQuoteAgrementObj = $this->productQuoteAgreementRepo->prepareData($data);
                $this->productQuoteAgreementRepo->create($productQuoteAgrementObj);
                break;
            case 'product_storage_agreements':
                $pathToSave = public_path() . '/../../Uploads/storage_agreement_pdf';
                $fileName = 'storage_agreement_' . time() . '.pdf';

                $agreementFile->move($pathToSave, $fileName);
                $data['pdf'] = $fileName;
                unset($data['agreement']);
                unset($data['type']);

                $productStorageAgreementObj = $this->productStorageAgreementRepo->prepareData($data);
                $this->productStorageAgreementRepo->create($productStorageAgreementObj);
                break;

            case 'consignment_with_storage_agreements':
                $pathToSave = public_path() . '/../../Uploads/consignment_agreement_with_storage_pdf';
                $fileName = 'seller_agreement_' . time() . '.pdf';

                $agreementFile->move($pathToSave, $fileName);
                $data['pdf'] = $fileName;
                unset($data['agreement']);
                unset($data['type']);

                $consignmentWithStorageAgreementObj = $this->consignmentAgreementWithStorageRepo->prepareData($data);
                $this->consignmentAgreementWithStorageRepo->create($consignmentWithStorageAgreementObj);
                break;
        }


        return response(['status' => true, 'message' => 'added']);
    }

}
