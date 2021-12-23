<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Entities\Users;
use App\Entities\Role;
use App\Repository\UserRepository as user_repo;
use App\Repository\RoleRepository as role_repo;
use App\Repository\OptionRepository as option_repo;
use App\Repository\ProductsRepository as product_repo;
use App\Repository\ProductsQuotationRepository as product_quotation_repo;
use App\Repository\SellRepository as sell_repo;
use App\Repository\SellerRepository as seller_repo;
use App\Repository\SubCategoryRepository as sub_category_repo;
use App\Repository\EmailTemplateRepository as email_template_repo;
use App\Repository\ProductsApprovedRepository as product_approved_repo;
use App\Repository\ImagesRepository as image_repo;
use App\Repository\MailRecordRepository as mail_record_repo;
use App\Repository\ProductQuoteAgreementRepository as product_quote_agreement_repo;
use App\Repository\ProductStorageAgreementRepository as product_storage_agreement_repo;
use App\Repository\OrdersRepository as orders_repo;
use Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Log;
use Excel;
use PHPExcel_Worksheet_Drawing;
use PHPExcel_Style_Fill;
use App\Exports\SyncProductExport;

class ProductsQuotationController extends Controller
{

    public function __construct(product_storage_agreement_repo $product_storage_agreement_repo, product_quote_agreement_repo $product_quote_agreement_repo, mail_record_repo $mail_record_repo, seller_repo $seller_repo, product_quotation_repo $product_quotation_repo, image_repo $image_repo, product_approved_repo $product_approved_repo, email_template_repo $email_template_repo, option_repo $option_repo, sub_category_repo $sub_category_repo, sell_repo $sell_repo, product_repo $product_repo, user_repo $user_repo, role_repo $role_repo, orders_repo $orders_repo)
    {

        $this->mail_record_repo = $mail_record_repo;

        $this->seller_repo = $seller_repo;

        $this->product_repo = $product_repo;

        $this->user_repo = $user_repo;

        $this->role_repo = $role_repo;

        $this->sell_repo = $sell_repo;

        $this->option_repo = $option_repo;

        $this->sub_category_repo = $sub_category_repo;

        $this->email_template_repo = $email_template_repo;

        $this->image_repo = $image_repo;

        $this->product_approved_repo = $product_approved_repo;

        $this->product_quotation_repo = $product_quotation_repo;

        $this->product_quote_agreement_repo = $product_quote_agreement_repo;
        $this->product_storage_agreement_repo = $product_storage_agreement_repo;
        $this->orders_repo = $orders_repo;
    }

    public function deleteUser(Request $request)
    {

        $user = $this->user_repo->UserOfId($request->id);

        $this->user_repo->delete($user);
    }

    public function getAllApprovalProducts()
    {

        return $this->product_quotation_repo->getAllApprovalProducts();
    }

    public function getProductsInProduction()
    {

        return $this->product_quotation_repo->getProductsInProduction();
    }

    public function getProposalInProposalProduction()
    {

        return $this->product_quotation_repo->getProposalInProposalProduction();
    }

    public function getProductProposalsInProgress(Request $request)
    {

        return $this->product_quotation_repo->getProductProposalsInProgress();
    }

    public function getProductsInCopyright(Request $request)
    {

        return $this->product_quotation_repo->getProductsInCopyright();
    }

    public function getProductFinals(Request $request)
    {

        $filter = $request->all();


        $data['draw'] = $filter['draw'];

        $users_data_total = $this->product_quotation_repo->getProductQuotationsFinal($filter);

        $data['data'] = $users_data_total['data'];


        $data['recordsTotal'] = $users_data_total['total'];

        $data['recordsFiltered'] = $this->product_quotation_repo->getProductQuotationsFinalTotal($filter);

        return response()->json($data, 200);
    }

    public function getProductForProductions(Request $request)
    {

        $filter = $request->all();


        $data['draw'] = $filter['draw'];

        $users_data_total = $this->product_quotation_repo->getProductForProductions($filter);

        $data['data'] = $users_data_total['data'];


        $data['recordsTotal'] = $users_data_total['total'];

        $data['recordsFiltered'] = $this->product_quotation_repo->getProductForProductionsTotal($filter);

        return response()->json($data, 200);
    }

    public function getProductForPricings(Request $request)
    {

        $filter = $request->all();


        $data['draw'] = $filter['draw'];

        $users_data_total = $this->product_quotation_repo->getProductForPricings($filter);

        $data['data'] = $users_data_total['data'];


        $data['recordsTotal'] = $users_data_total['total'];

        $data['recordsFiltered'] = $this->product_quotation_repo->getProductForPricingsTotal($filter);

        return response()->json($data, 200);
    }

    public function getProductForAwaiting_contract(Request $request)
    {

        $filter = $request->all();


        $data['draw'] = $filter['draw'];

        $users_data_total = $this->product_quotation_repo->getProductForAwaiting_contracts($filter);

        $data['data'] = $users_data_total['data'];


        $data['recordsTotal'] = $users_data_total['total'];

        $data['recordsFiltered'] = $this->product_quotation_repo->getProductForAwaiting_contractsTotal($filter);

        return response()->json($data, 200);
    }

    public function getProposalForProductions(Request $request)
    {

        $filter = $request->all();


        $data['draw'] = $filter['draw'];

        $users_data_total = $this->product_quotation_repo->getProposalForProductions($filter);

        $data['data'] = $users_data_total['data'];


        $data['recordsTotal'] = $users_data_total['total'];

        $data['recordsFiltered'] = $this->product_quotation_repo->getProposalForProductionsTotal($filter);

        return response()->json($data, 200);
    }

    public function getCopyrights(Request $request)
    {

        $filter = $request->all();


        $data['draw'] = $filter['draw'];

        $users_data_total = $this->product_quotation_repo->getCopyrights($filter);

        $data['data'] = $users_data_total['data'];


        $data['recordsTotal'] = $users_data_total['total'];

        $data['recordsFiltered'] = $this->product_quotation_repo->getCopyrightsTotal($filter);

        return response()->json($data, 200);
    }

    public function changeProductForProductionStatus(Request $request)
    {

        $data = $request->all();

        $is_send_email = 0;

        foreach ($data['product_status'] as $key => $value) {

            if (isset($value['product_quotation_id'])) {

                $product_quot = $this->product_quotation_repo->ProductQuotationOfId($value['product_quotation_id']);

                $pro = array();

                if ($value['product_for_production_status_id'] == 3) {

                    $pro['is_archived'] = 1;
                } else {

                    $pro['is_proposal_for_production'] = 1;
                    $pro['for_proposal_for_production_created_at'] = 1;

                    //changed

                    $pro['is_send_mail'] = 1;


                    //17 for pending

                    $pro['status_quot'] = $this->option_repo->OptionOfId(17);


                    if ($pro['is_proposal_for_production'] == 1) {

                        $is_send_email = 1;

//                        $introLines = array();
//                        $introLines[0] = "A new Copyright request has been added to the TLV Workflow.";
//                        $introLines[1] = "Please review here: https://tlv-workflowapp.com/seller/copyright";
//                        $myViewData = \View::make('emails.new_product_email', ['level' => 'success', 'outroLines' => [0 => ''], 'introLines' => $introLines])->render();
//                        $option = $this->option_repo->OptionOfId(80);
//                        if (app('App\Http\Controllers\EmailController')->sendMail($option->getValueText(), 'TLV Workflow: Copyright Request ', $myViewData))
//                        {
//
//                        }
                        //additional 18-03

                        $pro['is_copyright'] = 1;

                        $pro['is_approved_create_date'] = 1;

                        //additional 18-03 end
                    }

                    $pro['is_copyright_create_date'] = 1;

                    if (isset($data['copywriter_id'])) {

                        $copywriter = $this->user_repo->UserOfId($data['copywriter_id']);

                        $pro['copywriter_id'] = $copywriter;
                    }
                }

                $this->product_quotation_repo->update($product_quot, $pro);
            }
        }

        if ($is_send_email == 1) {

            $attachments = array();

            $bccs = array();

            $ccs = array();

            $other_emails = array();

            $other_emails = [];

            if (isset($data['copywriter_id']) && $copywriter) {

                $other_emails[] = $copywriter->getEmail();
            }

            $introLines = array();

            $introLines[0] = "A new Copyright request has been added to the TLV Workflow.";

            $introLines[1] = "Please review here: https://tlv-workflowapp.com/seller/copyright";

            $myViewData = \View::make('emails.new_product_email', ['level' => 'success', 'outroLines' => [0 => ''], 'introLines' => $introLines])->render();

            $option = $this->option_repo->OptionOfId(80);

            if (app('App\Http\Controllers\EmailController')->sendMail($option->getValueText(), 'TLV Workflow: Copyright Request ', $myViewData, $attachments, $bccs, $ccs, $other_emails)) {

            }
        }
    }

    public function savePropasalAcceptAwaitingcontract(Request $request)
    {

        $data = $request->all();

        $is_send_email = 0;

        foreach ($data['product_status'] as $key => $value) {

            if (isset($value['product_quotation_id'])) {

                $product_quot = $this->product_quotation_repo->ProductQuotationOfId($value['product_quotation_id']);

                $pro = array();
                $pro['is_awaiting_contract'] = 1;
                $pro['for_awaiting_contract_created_at'] = 1;
                $this->product_quotation_repo->update($product_quot, $pro);
            }
        }
    }

    public function saveAcknowledgementAwaitingcontract(Request $request)
    {
        $temp = $request->all();
        $products_approve = array();
        $products_reject = array();
        $seller = '';
        if (isset($temp['seller'])) {
            $seller = $this->seller_repo->SellerOfId($temp['seller']);
        }

        //change 22-08-2018
        $product_quot_ids = [];
        foreach ($temp['products'] as $key => $data) {
            $details = $this->product_quotation_repo->ProductQuotationOfId($data['id']);
            $products_approve[] = $details;
        }

        if ((count($products_approve) > 0 && $seller != '')) {

            if (isset($request->is_send_mail) && $request->is_send_mail == 'yes') {

                $product_quote_agreement = [];
                $product_quote_agreement['is_form_filled'] = 0;
                $product_quote_agreement['seller_id'] = $seller;
                $product_quote_agreement['pdf'] = '';
                $product_quote_agreement['quote_ids_json'] = json_encode($product_quot_ids);

                $product_quote_agreement_prepared_data = $this->product_quote_agreement_repo->prepareData($product_quote_agreement);
                $product_quote_agreement_obj = $this->product_quote_agreement_repo->create($product_quote_agreement_prepared_data);


                $product_quote_agreement_enc_id = \Crypt::encrypt($product_quote_agreement_obj->getId());
                $agreement_link = config('app.url') . 'seller_agreement/' . $product_quote_agreement_enc_id;

                $greeting = "Dear " . $seller->getFirstName() . ',';

                $introLines = array();

                $introLines_new = array();
                $introLines[0] = "Thank you so much for reaching out to us at TLV! We would be delighted to list your Item(s). Just to give
you a quick overview of our consignment terms, we ask for the following:";

                $introLines_new[] = 'TLV requires a 6-month exclusive contract to sell your Item(s).';
                $introLines_new[] = 'Consignor receives 60% of Sale Price.';
                $introLines_new[] = "Item(s) remain with the Consignor until they have sold. Once sold, our logistics team will
arrange a date for the Item's pick up.";
                $introLines_new[] = 'TLV has a limited amount of Storage space for consignors. Availability is not guaranteed.
Please let us know if you are interested!';
                $introLines_new[] = 'Once we have received a signed Consignment Agreement, TLV will arrange an
appointment to photograph and catalog the Item(s).';
                $introLines_new[] = 'TLV will present the Consignor with a Pricing Proposal after the Photoshoot. The
Consignor will have 48 hours after the Pricing Proposal has been sent to withdraw an
Item(s) from the Consignment Agreement.';
                $introLines_new[] = 'Items will be priced based upon condition, market trends and TLV sales data.';
                $introLines_new[] = 'There is a one-time Production Fee of $50 for the first 10 Items and $5 for each additional
Item photographed.';
                $introLines_new[] = 'Consignor Payment will be processed after a sold Item is received and accepted by the
Buyer.';

//                $line1 = 'If these terms sound like the right fit for you, please ';
//                $line2 = 'reply to this email ';
//                $line3 = 'so we can get started selling your Items!';
//
//                $introLines_reject = array();
//                $introLines_reject[0] = "While the remaining items listed below are lovely, we do not believe we have the audience for them at this point: ";

                $introLines_if_any_approved = array();
                $agreement_link_text = 'to complete and sign the TLV Consignment Agreement.';


                $myViewData = \View::make('emails.product_for_awaiting_contract_status_change', [
                    'agreement_link' => $agreement_link,
                    'introLines_if_any_approved' => $introLines_if_any_approved,
//                            'line1' => $line1,
//                            'line2' => $line2,
//                            'line3' => $line3,
//                            'introLines_reject' => $introLines_reject,
                    'introLines_new' => $introLines_new,
                    'greeting' => $greeting,
                    'seller' => $seller,
                    'products_approve' => $products_approve,
                    'products_reject' => $products_reject,
                    'level' => 'success',
                    'outroLines' => [0 => ''],
                    'introLines' => $introLines,
                    'agreement_link_text' => $agreement_link_text
                ])->render();

                $bccs = [];
                $attachments = [];
//                $attachments[] = 'TLV_SelfPhotographyGuide_06_02_2019.pdf';
                $ccs = [];

                $ccs[] = 'sell@thelocalvault.com';

                $other_emails = [];

                $other_emails[] = 'thelocalvaultcomproduction@thelocalvault.freshdesk.com';

                if (app('App\Http\Controllers\EmailController')->sendMail($seller->getEmail(), 'TLV Products for Sale Acknowledgement: ' . $seller->getLastname(), $myViewData, $attachments, $bccs, $ccs, $other_emails)) {

                }
            }
        }
        return 1;
    }

    public function savePricingProposalAwaitingcontract(Request $request)
    {
        ini_set('max_execution_time', 300);
        $products = $request->all();

        $approved_product_quots = array();
        $seller = $this->seller_repo->SellerOfId($products['seller']);
        $product_quot_ids = [];

        foreach ($products['products'] as $key => $value) {
            $product_quot_ids[] = $value['id'];
            $product_quot = $this->product_quotation_repo->ProductQuotationOfId($value['id']);
            $approved_product_quots[] = $product_quot;
            $data['is_send_mail'] = $value['is_send_mail'];
            $data['is_for_production_create_date'] = 'Yes';
            //17 for pending
            $data['status_quot'] = $this->option_repo->OptionOfId(17);
        }

        $file_name = app('App\Http\Controllers\ExportController')->downloadProductWordProposal($request);

        $request->merge([
            'isForClient' => true
        ]);
        $file_name_client = app('App\Http\Controllers\ExportController')->downloadProductPdfProposal($request);

        $link = config('app.url') . 'api/storage/exports/' . $file_name_client;
        $greeting = "Dear " . $seller->getFirstName() . ',';

        $introLines = array();
        $introLines[0] = 'We are excited to provide you with your TLV "Pricing Proposal". As discussed, we have priced your Item(s) based upon condition, market trends, and TLV sales data. As outlined in the TLV Consignment Agreement, an Item is listed at the "Advertised Price", which is the agreed "TLV Price" shown below for the first 3 months of the listing. If the "Item" has not sold after 3 months, the Advertised Price will be reduced by 30%.';
        $note_text = '* If an Item(s) is is noted as "Dropoff by Consignor Required" in the Pricing Proposal then Consignor must drop it off at The Local Vault Office in Cos Cob, CT within 2 weeks from the date the Item was purchased.';
        $line1 = "As stated in the TLV Consignment Agreement, Consignor has 48 hours after the Pricing Proposal is sent to withdraw any Item(s) from the consignment.";
        $line2 = "";
        $line3 = "We look forward to featuring your Item(s) on the site!";
        $attachments = array();
        //$attachments[] = 'TLV Client Sale Agreement_04_05_2020.pdf';
        $myViewData = \View::make('emails.product_proposal', [
//                    'agreement_link' => $agreement_link,
            'link' => $link,
            'product_quots' => $approved_product_quots,
            'line1' => $line1,
            'line2' => $line2,
            'line3' => $line3,
            'greeting' => $greeting,
            'seller' => $seller,
            'level' => 'success',
            'outroLines' => [0 => ''],
            'introLines' => $introLines,
            'note_text' => $note_text
        ])->render();
        $bccs = [];
        $ccs = [];
        $ccs[] = 'sell@thelocalvault.com';
        $other_emails = [];
        $other_emails[] = 'thelocalvaultcomproduction@thelocalvault.freshdesk.com';
        if (app('App\Http\Controllers\EmailController')->sendMail($seller->getEmail(), 'TLV Pricing Proposal: ' . $seller->getLastname(), $myViewData, $attachments, $bccs, $ccs, $other_emails)) {

        }

        return $file_name;
    }

    public function savePricingProposalAwaitingcontract_Old(Request $request)
    {
        // Working
        ini_set('max_execution_time', 300);
        $products = $request->all();

        $approved_product_quots = array();
        $seller = $this->seller_repo->SellerOfId($products['seller']);
        $product_quot_ids = [];

        foreach ($products['products'] as $key => $value) {
            $product_quot_ids[] = $value['id'];
            $product_quot = $this->product_quotation_repo->ProductQuotationOfId($value['id']);
            $approved_product_quots[] = $product_quot;
            $data['is_send_mail'] = $value['is_send_mail'];
            $data['is_for_production_create_date'] = 'Yes';
            //17 for pending
            $data['status_quot'] = $this->option_repo->OptionOfId(17);
        }

        //product_quote_agreement
        // removed consigment agreement link
//        $product_quote_agreement = [];
//        $product_quote_agreement['is_form_filled'] = 0;
//        $product_quote_agreement['seller_id'] = $seller;
//        $product_quote_agreement['pdf'] = '';
//        $product_quote_agreement['quote_ids_json'] = json_encode($product_quot_ids);
//        $product_quote_agreement_prepared_data = $this->product_quote_agreement_repo->prepareData($product_quote_agreement);
//        $product_quote_agreement_obj = $this->product_quote_agreement_repo->create($product_quote_agreement_prepared_data);
//
//        $product_quote_agreement_enc_id = \Crypt::encrypt($product_quote_agreement_obj->getId());
//        $agreement_link = config('app.url') . 'seller_agreement/' . $product_quote_agreement_enc_id;

        $file_name = app('App\Http\Controllers\ExportController')->downloadProductWordProposal($request);

        //proposal for client without internal note
        $request->merge([
            'isForClient' => true
        ]);
        $file_name_client = app('App\Http\Controllers\ExportController')->downloadProductPdfProposal($request);

        $link = config('app.url') . 'api/storage/exports/' . $file_name_client;
        $greeting = "Dear " . $seller->getFirstName() . ',';

        $introLines = array();
        $introLines[0] = 'We are excited to provide you with your TLV "Pricing Proposal". As discussed, we have priced your Item(s) based upon condition, market trends, and TLV sales data. As outlined in the TLV Consignment Agreement, an Item is listed at the "Advertised Price", which is the agreed "TLV Price" shown below for the first 3 months of the listing. If the "Item" has not sold after 3 months, the Advertised Price will be reduced by 30%.';
        $note_text = '* If an Item(s) is is noted as "Dropoff by Consignor Required" in the Pricing Proposal then Consignor must drop it off at The Local Vault Office in Cos Cob, CT within 2 weeks from the date the Item was purchased.';
        $line1 = "As stated in the TLV Consignment Agreement, Consignor has 48 hours after the Pricing Proposal is sent to withdraw any Item(s) from the consignment.";
        $line2 = "";
        $line3 = "We look forward to featuring your Item(s) on the site!";
        $attachments = array();
        //$attachments[] = 'TLV Client Sale Agreement_04_05_2020.pdf';
        $myViewData = \View::make('emails.product_proposal', [
//                    'agreement_link' => $agreement_link,
            'link' => $link,
            'product_quots' => $approved_product_quots,
            'line1' => $line1,
            'line2' => $line2,
            'line3' => $line3,
            'greeting' => $greeting,
            'seller' => $seller,
            'level' => 'success',
            'outroLines' => [0 => ''],
            'introLines' => $introLines,
            'note_text' => $note_text
        ])->render();
        $bccs = [];
        $ccs = [];
        $ccs[] = 'sell@thelocalvault.com';
        $other_emails = [];
        $other_emails[] = 'thelocalvaultcomproduction@thelocalvault.freshdesk.com';
        if (app('App\Http\Controllers\EmailController')->sendMail($seller->getEmail(), 'TLV Pricing Proposal: ' . $seller->getLastname(), $myViewData, $attachments, $bccs, $ccs, $other_emails)) {

        }

        return $file_name;
    }

    // todo make change email text
    public function savePreliminaryPricingProposalAwaitingcontract(Request $request)
    {

        ini_set('max_execution_time', 300);
        $products = $request->all();

        $approved_product_quots = array();
        $seller = $this->seller_repo->SellerOfId($products['seller']);
        $product_quot_ids = [];

        foreach ($products['products'] as $key => $value) {
            $product_quot_ids[] = $value['id'];
            $product_quot = $this->product_quotation_repo->ProductQuotationOfId($value['id']);
            $approved_product_quots[] = $product_quot;
            $data['is_send_mail'] = $value['is_send_mail'];
            $data['is_for_production_create_date'] = 'Yes';
            //17 for pending
            // stopping product quotation to advance to next stage
//            $data['is_awaiting_contract'] = 1;
//            $data['status_quot'] = $this->option_repo->OptionOfId(17);
//            $this->product_quotation_repo->update($product_quot, $data);
        }

        //product_quote_agreement

        $product_quote_agreement = [];
        $product_quote_agreement['is_form_filled'] = 0;
        $product_quote_agreement['seller_id'] = $seller;
        $product_quote_agreement['pdf'] = '';
        $product_quote_agreement['quote_ids_json'] = json_encode($product_quot_ids);
        $product_quote_agreement_prepared_data = $this->product_quote_agreement_repo->prepareData($product_quote_agreement);
        $product_quote_agreement_obj = $this->product_quote_agreement_repo->create($product_quote_agreement_prepared_data);

        $product_quote_agreement_enc_id = \Crypt::encrypt($product_quote_agreement_obj->getId());
        $agreement_link = config('app.url') . 'seller_agreement/' . $product_quote_agreement_enc_id;
        $file_name = app('App\Http\Controllers\ExportController')->downloadProductWordProposal($request);

        //proposal for client without internal note
        $request->merge([
            'isForClient' => true
        ]);
        $file_name_client = app('App\Http\Controllers\ExportController')->downloadProductPdfProposal($request);
        $link = config('app.url') . 'api/storage/exports/' . $file_name_client;
        $greeting = "Dear " . $seller->getFirstName() . ',';

        $introLines = array();
        $introLines[0] = 'Please see below for your TLV "Preliminary Pricing Proposal". As discussed, we have priced your Item(s) based upon condition, market trends, and TLV sales data. This pricing is subject to change upon closer inspection of the Item(s). TLV will notify you of any change in pricing subsequent to the Photoshoot.  As outlined in the TLV Consignment Agreement, an Item is listed at the  "Advertised Price" which is the agreed "TLV Price" listed below for the first 3 months of the listing. If the "Item" has not sold after 3 months, the Advertised Price will be reduced by 30%. To list your Item(s) on TLV, we require a signed copy of our TLV Consignment Agreement. ';

        $note_text = '* If an Item(s) is categorized as "Dropoff by Consignor Required" in the Pricing Proposal then Consignor must drop it off at The Local Vault Office in Cos Cob, CT within 2 weeks from the date the Item was purchased.';
        $line1 = "Once we receive a signed copy of our TLV Consignment Agreement we will schedule the
Photoshoot where we come to photograph, measure and catalog your collection.";
        $line2 = "";
        $line3 = "We look forward to working with you!";
        $agreement_link_text = 'to complete and sign the Consignment Agreement';
        $link_text = 'to download a PDF of the TLV Preliminary Pricing Proposal';
        $attachments = array();
        //    $attachments[] = 'TLV Client Sale Agreement_04_05_2020.pdf';
        $myViewData = \View::make('emails.preliminary_pricing_proposal', [
            'agreement_link' => $agreement_link,
            'link' => $link,
            'product_quots' => $approved_product_quots,
            'line1' => $line1,
            'line2' => $line2,
            'line3' => $line3,
            'greeting' => $greeting,
            'seller' => $seller,
            'level' => 'success',
            'outroLines' => [0 => ''],
            'introLines' => $introLines,
            'note_text' => $note_text,
            'agreement_link_text' => $agreement_link_text,
            'link_text' => $link_text,
        ])->render();
        $bccs = [];
        $ccs = [];
        $ccs[] = 'sell@thelocalvault.com';
        $other_emails = [];
        $other_emails[] = 'thelocalvaultcomproduction@thelocalvault.freshdesk.com';
        if (app('App\Http\Controllers\EmailController')->sendMail($seller->getEmail(), 'TLV Preliminary Pricing Proposal: ' . $seller->getLastname(), $myViewData, $attachments, $bccs, $ccs, $other_emails)) {

        }

        return $file_name;
    }

    public function savePricingProposalProductForReview(Request $request)
    {

//        ini_set('max_execution_time', 300);
        $products = $request->all();

        $approved_product_quots = array();
        $seller = $this->seller_repo->SellerOfId($products['seller']);
        $product_quot_ids = [];
        foreach ($products['product_status'] as $key => $value) {
            $product_created = $this->product_repo->ProductOfId($value['product_id']);
            $data2['product_id'] = $product_created;
            $data2['price'] = $data2['product_id']->getPrice();
            $data2['quantity'] = $data2['product_id']->getQuantity();
            $data2['note'] = $data2['product_id']->getNote();
            $data2['images_from'] = 1;
            $data2['is_updated_details'] = 1;
            $data2['tlv_suggested_price_min'] = $data2['product_id']->getTlv_suggested_price_min();
            $data2['tlv_suggested_price_max'] = $data2['product_id']->getTlv_suggested_price_max();
            $data2['dimension_description'] = $data2['product_id']->getDescription();
            $production_quotation_prepared = $this->product_quotation_repo->prepareData($data2);
            $product_quotations[] = $this->product_quotation_repo->create($production_quotation_prepared);

            $produtc_data2['status'] = $this->option_repo->OptionOfId(7);
            $this->product_repo->update($product_created, $produtc_data2);
        }

        foreach ($product_quotations as $key => $value) {
            $product_quot_ids[]['id'] = $value->getId();
            $product_quot = $this->product_quotation_repo->ProductQuotationOfId($value->getId());
            $approved_product_quots[] = $product_quot;
        }


        //product_quote_agreement

        $product_quote_agreement = [];
        $product_quote_agreement['is_form_filled'] = 0;
        $product_quote_agreement['seller_id'] = $seller;
        $product_quote_agreement['pdf'] = '';
        $product_quote_agreement['quote_ids_json'] = json_encode($product_quot_ids);
        $product_quote_agreement_prepared_data = $this->product_quote_agreement_repo->prepareData($product_quote_agreement);
        $product_quote_agreement_obj = $this->product_quote_agreement_repo->create($product_quote_agreement_prepared_data);

        $product_quote_agreement_enc_id = \Crypt::encrypt($product_quote_agreement_obj->getId());
        $agreement_link = config('app.url') . 'seller_agreement/' . $product_quote_agreement_enc_id;
        $product_quot_request['seller'] = $seller->getId();
        $product_quot_request['products'] = $product_quot_ids;
        $file_name = app('App\Http\Controllers\ExportController')->downloadProductWordProposalProductForReview($product_quot_request);

        //proposal for client without internal note
        $request->merge([
            'isForClient' => true
        ]);
        $file_name_client = app('App\Http\Controllers\ExportController')->downloadProductPdfProposalProductForReview($product_quot_request);
        $link = config('app.url') . 'api/storage/exports/' . $file_name_client;
        $greeting = "Dear " . $seller->getFirstName() . ',';

        $introLines = array();
        $introLines[0] = 'Please see below for your TLV "Pricing Proposal". As discussed, we have priced your Item(s) based upon condition, market trends, and TLV sales data. This pricing is subject to change upon closer inspection. TLV will notify you of any change in pricing subsequent to the photoshoot. Any change in pricing must be mutually agreed upon prior to listing on the website. As outlined in the TLV Consignment agreement, an Item is listed at the agreed "Advertised Price" for the first 3 months of the listing. If the "Item" has not sold after 3 months, the Advertised Price will be reduced by 30% for the 4th month. In order to list your Items on TLV, we require a signed copy of our Consignment Agreement.';
        $line1 = "Once we receive a signed copy of our Consignment Agreement we will contact you to schedule a date to come and photograph, measure and catalog your collection.";
        $line2 = "";
        $line3 = "We look forward to working with you!";
        $attachments = array();
        $attachments[] = 'TLV Client Sale Agreement_04_05_2020.pdf';
        $myViewData = \View::make('emails.product_proposal', ['agreement_link' => $agreement_link, 'link' => $link, 'product_quots' => $approved_product_quots, 'line1' => $line1, 'line2' => $line2, 'line3' => $line3, 'greeting' => $greeting, 'seller' => $seller, 'level' => 'success', 'outroLines' => [0 => ''], 'introLines' => $introLines])->render();
        $bccs = [];
        $ccs = [];
        $ccs[] = 'sell@thelocalvault.com';
        $other_emails = [];
        $other_emails[] = 'thelocalvaultcomproduction@thelocalvault.freshdesk.com';
        if (app('App\Http\Controllers\EmailController')->sendMail($seller->getEmail(), 'Proposal Agreement: ' . $seller->getLastname(), $myViewData, $attachments, $bccs, $ccs, $other_emails)) {

        }

        return $file_name;
    }

    public function changeProductForAwaitingcontractStatus(Request $request)
    {

        $data = $request->all();

        $agent = $this->user_repo->UserOfId($data['assign_agent_id']);

        foreach ($data['product_status'] as $key => $value) {
            if (isset($value['product_quotation_id'])) {

                $product_quot = $this->product_quotation_repo->ProductQuotationOfId($value['product_quotation_id']);
                $pro = array();

                $pro['assign_agent_id'] = $agent;

                $pro['is_awaiting_contract'] = 1;
                $pro['for_awaiting_contract_created_at'] = 1;
                $this->product_quotation_repo->update($product_quot, $pro);
            }
        }

        if (count($data['product_status']) > 0) {
            $email = $agent->getEmail();

            $subject = "TLV Product Assignment";

            $greeting = "Dear, " . $agent->getFirstname() . ' ' . $agent->getLastname();

            $urlToRedirect = str_replace("/api", "", url("/"));

            $introLines = [
                'Products have been assigned to you in the TLV Workflow app.',
                "Please <a href='" . $urlToRedirect . "'>click here</a> to review."
            ];

            $viewData = ['greeting' => $greeting, 'level' => 'success', 'outroLines' => [], 'introLines' => $introLines];

            $emailView = \View::make('emails.new_product_email', $viewData)->render();

            app('App\Http\Controllers\EmailController')->sendMail($email, $subject, $emailView);
        }
    }

    public function changeProductForPricingStatus(Request $request)
    {

        $data = $request->all();

        $is_send_email = 0;

        foreach ($data['product_status'] as $key => $value) {
            if (isset($value['product_quotation_id'])) {
                $product_quot = $this->product_quotation_repo->ProductQuotationOfId($value['product_quotation_id']);
                $pro = array();
                $pro['is_product_for_pricing'] = 1;
                $pro['for_pricing_created_at'] = 1;
                $this->product_quotation_repo->update($product_quot, $pro);
            }
        }
    }

    public function changeCopyrightStatus(Request $request)
    {

        $data = $request->all();


        foreach ($data['product_status'] as $key => $value) {

            if (isset($value['product_quotation_id'])) {

                $product_quot = $this->product_quotation_repo->ProductQuotationOfId($value['product_quotation_id']);

                $pro = array();

                if ($value['copyright_status_id'] == 3) {

                    $pro['is_archived'] = 1;
                } else {

                    $pro['is_copyright'] = $value['copyright_status_id'];

                    $pro['is_approved_create_date'] = 1;
                }

                $this->product_quotation_repo->update($product_quot, $pro);
            }
        }
    }

    public function AllSyncProduct(Request $request)
    {


        $getAllSellers = $this->seller_repo->getAllQueueSeller();

        $response = json_encode(['in']);

        if (count($getAllSellers) > 0) {


            $seller = $getAllSellers[0];

            $product_quot_new = $this->product_quotation_repo->getProductQuotationQueueByOptionId(83, $seller['id']);


            $product_quot_new_array = [];

            $product_quot_ids = [];

            foreach ($product_quot_new as $key => $value) {

                $product_quot = $this->product_quotation_repo->ProductQuotationOfId($value['id']);

                $abc['in_queue'] = 1;

                $this->product_quotation_repo->update($product_quot, $abc);

//                $value['product_id']['city'] = '';
//                $value['product_id']['state'] = '';
//echo "<pre>";
//print_r($product_quot_new['data']);
//die;

                $product_quot_ids[] = $value['id'];

                $qid = $value['id'];

//        print_r($qid);
//                if (isset($value['product_id']['pick_up_location']))
//                {
//                    if (isset($value['product_id']['pick_up_location']['key_text']))
//                    {
//                        $details_location = json_decode($value['product_id']['pick_up_location']['key_text']);
//                        if (count($details_location) > 0)
//                        {
//                            if (isset($details_location[0]->city))
//                            {
//                                $value['product_id']['city'] = $details_location[0]->city;
//                            }
//                            if (isset($details_location[0]->state))
//                            {
//                                $value['product_id']['state'] = $details_location[0]->state;
//                            }
//                        }
//                    }
//                }

                if (isset($value['delivery_option']) && $value['delivery_option'] != '') {

                    if ($value['delivery_description'] != '') {

                        $value['delivery_description'] = $value['delivery_option'] . ', ' . $value['delivery_description'];
                    } else {

                        $value['delivery_description'] = $value['delivery_option'];
                    }
                }


//            $value = $product_quot_new;

                $product_quot_new_array[$key]['data'] = $value;
            }


            Log::info($product_quot_new_array);


            if (count($product_quot_new_array) > 0) {


                $product_quot_new_array_json['data'] = json_encode($product_quot_new_array);

                Log::info('Products Synced' . json_encode($product_quot_new_array));


//                $host = 'https://localvault.staging.wpengine.com/wp-content/themes/thelocalvault/new-product-loop.php';
                $host = env('WP_URL') . '/wp-content/themes/thelocalvault/new-product-loop.php';

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $host);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                curl_setopt($ch, CURLOPT_POST, true);

                curl_setopt($ch, CURLOPT_POSTFIELDS, $product_quot_new_array_json);

                //temp_stop

                $temp = curl_exec($ch);

                $quote_wp_ids = json_decode($temp);

                Log::info('Products Temp_sync_response' . json_encode($temp));

                $response = $temp;


//                $greeting = "Hi Admin,";
//                $myViewData = \View::make('emails.queue_processed', ['greeting' => $greeting, 'level' => 'success', 'outroLines' => [0 => ''], 'introLines' => ['sync_response' . json_encode($temp)]])->render();
//
//                $bccs = [];
////                $bccs[] = 'matt@540designstudio.com';
////                        $bccs[] = 'chintan@esparkinfo.com';
////                        if (app('App\Http\Controllers\EmailController')->sendMail($seller['email'], 'Proposal Agreement', $myViewData, $attachments, $bccs)) {
//                if (app('App\Http\Controllers\EmailController')->sendMail1('webdeveloper1011@gmail.com', 'Products Processed Response', $myViewData, array(), $bccs))
//                {
//
//                    Log::info('Products Temp_sync_response_email send');
//                }


                if (count($quote_wp_ids) > 0) {

                    foreach ($quote_wp_ids as $key1 => $value_quote_wp) {

                        $product_quot = $this->product_quotation_repo->ProductQuotationOfId($value_quote_wp->id);

                        $data_t['status_quot'] = $this->option_repo->OptionOfId(18);


                        $data_t['in_queue'] = 0;

                        $this->product_quotation_repo->update($product_quot, $data_t);

                        $this->saveWPId($value_quote_wp->id, $value_quote_wp->wp_id);
                    }

                    $sellerProducts = $this->product_quotation_repo->getProductQuotationQueueByOptionId(83, $seller['id']);

                    if (count($sellerProducts) == 0) {

                        $sellerObj = $this->seller_repo->SellerOfId($seller['id']);

                        $seller_data['in_queue'] = 0;

                        $this->seller_repo->update($sellerObj, $seller_data);

//                        $greeting = "Dear " . $seller['firstname'] . ',';

                        $greeting = "Hi Admin,";

                        $myViewData = \View::make('emails.queue_processed', ['greeting' => $greeting, 'level' => 'success', 'outroLines' => [0 => ''], 'introLines' => ['Sync completed. No products left in the queue.']])->render();


                        $emails = [];

                        $emails[] = 'sell@thelocalvault.com';

                        $emails[] = 'matt@540designstudio.com';

                        $bccs = [];

//                        $bccs[] = 'matt@540designstudio.com';
//                        $bccs[] = 'chintan@esparkinfo.com';
//                        if (app('App\Http\Controllers\EmailController')->sendMail($seller['email'], 'Proposal Agreement', $myViewData, $attachments, $bccs)) {

                        if (app('App\Http\Controllers\EmailController')->sendMail1($emails, 'Products Processed', $myViewData, array(), $bccs)) {
                            Log::info('Products Processed');
                        }

                        // sending mail to developer regarding sync products
                        $myViewData = \View::make('emails.sync-product-data', ['greeting' => 'Hi Admin.', 'product_details' => $product_quot_new_array_json['data']])->render();

                        $bccs = [];
                        if (app('App\Http\Controllers\EmailController')->sendMailONLY('wordpressdeveloper1011@gmail.com', ' TLV Workflow Seller Product Data', $myViewData, array(), $bccs)) {
                            Log::info('Sync Product Data');
                        }
                    }

//                    $this->product_quotation_repo->update($product_quot, $data_t);
//                    $this->saveWPIds($qid, $temp);
                } else {


                    foreach ($product_quot_ids as $key => $value_id) {

                        $product_quot_new = $this->product_quotation_repo->ProductQuotationOfId($value_id);

                        $abc_new['in_queue'] = 0;

                        $this->product_quotation_repo->update($product_quot_new, $abc_new);
                    }
                }
            } else {

                $seller_data = [];

                $sellerObj = $this->seller_repo->SellerOfId($seller['id']);

                $seller_data['in_queue'] = 0;

                $this->seller_repo->update($sellerObj, $seller_data);
            }
        }

        return $response;


//        $product_quot_new = $this->product_quotation_repo->getProductQuotationQueueByOptionId(83);
//
//
//
//        $product_quot_new_array = [];
//        foreach ($product_quot_new as $key => $value)
//        {
//            $value['product_id']['city'] = '';
//            $value['product_id']['state'] = '';
////echo "<pre>";
////print_r($product_quot_new['data']);
////die;
//            $qid = $value['id'];
////        print_r($qid);
//            if (isset($value['product_id']['pick_up_location']))
//            {
//
//                if (isset($value['product_id']['pick_up_location']['key_text']))
//                {
//                    $details_location = json_decode($value['product_id']['pick_up_location']['key_text']);
//                    if (count($details_location) > 0)
//                    {
//                        if (isset($details_location[0]->city))
//                        {
//                            $value['product_id']['city'] = $details_location[0]->city;
//                        }
//                        if (isset($details_location[0]->state))
//                        {
//                            $value['product_id']['state'] = $details_location[0]->state;
//                        }
//                    }
//                }
//            }
//            if (isset($value['delivery_option']) && $value['delivery_option'] != '')
//            {
//                if ($value['delivery_description'] != '')
//                {
//                    $value['delivery_description'] = $value['delivery_option'] . ', ' . $value['delivery_description'];
//                }
//                else
//                {
//                    $value['delivery_description'] = $value['delivery_option'];
//                }
//            }
//
//
////            $value = $product_quot_new;
//            $product_quot_new_array[$key]['data'] = $value;
//        }
//
//
//        if (count($product_quot_new_array) > 0)
//        {
//
//            $product_quot_new_array_json['data'] = json_encode($product_quot_new_array);
//            Log::info('Products Synced' . json_encode($product_quot_new_array));
//            $host = 'https://thelocalvault.com/wp-content/themes/thelocalvault/new-product-loop.php';
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL, $host);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            curl_setopt($ch, CURLOPT_POST, true);
//            curl_setopt($ch, CURLOPT_POSTFIELDS, $product_quot_new_array_json);
//            //temp_stop
//            $temp = curl_exec($ch);
//            $quote_wp_ids = json_decode($temp);
//
//
//
//            if (count($quote_wp_ids) > 0)
//            {
//                foreach ($quote_wp_ids as $key1 => $value_quote_wp)
//                {
//                    $product_quot = $this->product_quotation_repo->ProductQuotationOfId($value_quote_wp->id);
//                    $data_t['status_quot'] = $this->option_repo->OptionOfId(18);
//                    $this->product_quotation_repo->update($product_quot, $data_t);
//                    $this->saveWPId($value_quote_wp->id, $value_quote_wp->wp_id);
//                }
////                    $this->product_quotation_repo->update($product_quot, $data_t);
////                    $this->saveWPIds($qid, $temp);
//            }
//            else
//            {
//
//            }
//        }
        //    return 1;
    }

    public function changeProductFinalStatus(Request $request)
    {

        $data = $request->all();


        $product_quot_new_array = [];

        $temp_product_final_status_id = "";

        foreach ($data['product_status'] as $key => $value) {


            if (isset($value['product_quotation_id'])) {

                $temp_product_final_status_id = $value['product_final_status_id'];

                $product_quot = $this->product_quotation_repo->ProductQuotationOfId($value['product_quotation_id']);

                if ($value['product_final_status_id'] == 'archived') {

                    $data_t['is_archived'] = 1;

                    $this->product_quotation_repo->update($product_quot, $data_t);
                } else {

                    $data_t['status_quot'] = $this->option_repo->OptionOfId($value['product_final_status_id']);

                    $this->product_quotation_repo->update($product_quot, $data_t);

                    if ($data_t['status_quot']->getId() == 18) {

                        $product_quot_new['data'] = $this->product_quotation_repo->getProductQuotationById($value['product_quotation_id']);

                        $qid = $product_quot_new['data']['id'];

                        if (isset($product_quot_new['data']['delivery_option']) && $product_quot_new['data']['delivery_option'] != '') {

                            if ($product_quot_new['data']['delivery_description'] != '') {

                                $product_quot_new['data']['delivery_description'] = $product_quot_new['data']['delivery_option'] . ', ' . $product_quot_new['data']['delivery_description'];
                            } else {

                                $product_quot_new['data']['delivery_description'] = $product_quot_new['data']['delivery_option'];
                            }
                        }

                        $product_quot_new['data'] = $product_quot_new;

                        $product_quot_new_array[] = $product_quot_new['data'];
                    }
                }
            }
        }


        if (count($product_quot_new_array) > 0) {

            $product_quot_new_array_json['data'] = json_encode($product_quot_new_array);

            Log::info('Products Synced' . json_encode($product_quot_new_array));


//            $host = 'https://localvault.staging.wpengine.com/wp-content/themes/thelocalvault/new-product-loop.php';
            $host = env('WP_URL') . '/wp-content/themes/thelocalvault/new-product-loop.php';

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $host);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_POST, true);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $product_quot_new_array_json);

            //temp_stop

            $temp = curl_exec($ch);

            $quote_wp_ids = json_decode($temp);


            if (count($quote_wp_ids) > 0) {

                foreach ($quote_wp_ids as $key1 => $value_quote_wp) {

//                          $product_quot = $this->product_quotation_repo->ProductQuotationOfId($value_quote_wp['id']);
//                         $this->product_quotation_repo->update($product_quot, $data_t);

                    $this->saveWPId($value_quote_wp->id, $value_quote_wp->wp_id);
                }

//                    $this->product_quotation_repo->update($product_quot, $data_t);
//                    $this->saveWPIds($qid, $temp);
            } else {

            }
        }

        if (isset($data['seller']) && $temp_product_final_status_id == '83') {


            $sellerObj = $this->seller_repo->SellerOfId($data['seller']);

            $seller_data['in_queue'] = 1;

            $this->seller_repo->update($sellerObj, $seller_data);
        }
        if (isset($data['send_sync_mail']) && $data['send_sync_mail'] == 1) {
            $seller_data = $this->seller_repo->SellerOfId($data['seller']);
            $greeting = "Dear " . $seller_data->getFirstName() . ',';

            $line1 = "YOU ARE ALMOST THERE!";
            $line2 = "IN THE MEANTIME...";
            $line3 = "";

            $line4 = "Your items are being synced to the site. Please allow 2-3 business days before checking them out on our New Arrivals page!";

            $introLines = array();
            $introLines[0] = 'As a TLV Seller, you have your own customized Seller "Dashboard" on the site which enables you to monitor your Item(s) listed for sale, offers received and Item(s) sold!';
            $introLines[1] = 'If you have not yet logged into your TLV account, not to worry! You can access your Seller Dashboard by following these simple steps:';

            $outroLines = array();
            $outroLines[0] = 'Go to thelocalvault.com';
            $outroLines[1] = 'Click "Log In"';
            $outroLines[2] = 'Your username has already been created with your email address.';
            $outroLines[3] = 'Click "Lost Your Password?" to reset a password to your liking.';
            $outroLines[4] = 'Once you have finished setting up your Account, click on "My Account" in the top right corner of the Home Page.';
            $outroLines[5] = 'From here, you can click "Dashboard" and have the ability to monitor your Item(s), Item(s) Sold and Offers Received.';
            $outroLines[6] = 'When you receive an offer, you have the option to Accept, Counter or Decline from this dashboard... but just remember, all offers expire after 48 hours!';
            $outroLines[7] = 'When an Item has sold that is noted as "Dropoff by Consignor Required" in your TLV Pricing Proposal you will need to drop it off at The Local Vault Office in Cos Cob, CT. Otherwise, you will be contacted by our logistics coordinator to arrange a date and time for pick up. This does not apply for Items that have been moved to the TLV Storage Facility.';
            $outroLines[8] = 'Once the Item has been received and accepted by the buyer, your payment will be issued!';

            $attachments = array();
//        $attachments[] = 'TLV Client Sale Agreement_04_05_2020.pdf';
            $myViewData = \View::make('emails.product_synchronize', ['agreement_link' => '', 'link' => '', 'product_quots' => '', 'line1' => $line1, 'line2' => $line2, 'line3' => $line3, 'line4' => $line4, 'greeting' => $greeting, 'seller' => $seller_data, 'level' => 'success', 'outroLines' => $outroLines, 'introLines' => $introLines])->render();

            $bccs = [];
            $ccs = [];
            $ccs[] = 'sell@thelocalvault.com';
            $other_emails = [];
            $other_emails[] = 'thelocalvaultcomproduction@thelocalvault.freshdesk.com';
            if (app('App\Http\Controllers\EmailController')->sendMail($seller_data->getEmail(), 'You are almost there!', $myViewData, $attachments, $bccs, $ccs, $other_emails)) {

            }
        }

        return 1;
    }

    public function sendMailProductFinalStatus(Request $request)
    {
        $data = $request->all();
        $product_quot_new_array = [];
        $temp_product_final_status_id = "";
//        foreach ($data['product_status'] as $key => $value)
//        {
//            if (isset($value['product_quotation_id']))
//            {
//                $temp_product_final_status_id = $value['product_final_status_id'];
//                $product_quot = $this->product_quotation_repo->ProductQuotationOfId($value['product_quotation_id']);
//                if ($value['product_final_status_id'] == 'archived')
//                {
//                    $data_t['is_archived'] = 1;
//                    $this->product_quotation_repo->update($product_quot, $data_t);
//                } else
//                {
//
//                    $data_t['status_quot'] = $this->option_repo->OptionOfId($value['product_final_status_id']);
//                    $this->product_quotation_repo->update($product_quot, $data_t);
//
//                    if ($data_t['status_quot']->getId() == 18)
//                    {
//
//                        $product_quot_new['data'] = $this->product_quotation_repo->getProductQuotationById($value['product_quotation_id']);
//
//                        $qid = $product_quot_new['data']['id'];
//
//                        if (isset($product_quot_new['data']['delivery_option']) && $product_quot_new['data']['delivery_option'] != '')
//                        {
//                            if ($product_quot_new['data']['delivery_description'] != '')
//                            {
//                                $product_quot_new['data']['delivery_description'] = $product_quot_new['data']['delivery_option'] . ', ' . $product_quot_new['data']['delivery_description'];
//                            } else
//                            {
//                                $product_quot_new['data']['delivery_description'] = $product_quot_new['data']['delivery_option'];
//                            }
//                        }
//
//
//                        $product_quot_new['data'] = $product_quot_new;
//                        $product_quot_new_array[] = $product_quot_new['data'];
//                    }
//
//                }
//            }
//        }

        if (isset($data['send_sync_mail']) && $data['send_sync_mail'] == 1) {
            $seller_data = $this->seller_repo->SellerOfId($data['seller']);
            $greeting = "Dear " . $seller_data->getFirstName() . ',';

            $line1 = "YOU ARE ALMOST THERE!";
            $line2 = "IN THE MEANTIME...";
            //$line3 = "HAPPY SELLING!";
            $line3 = "";

            $line4 = "Your items are being synced to the site. Please allow 2-3 business days before checking them out on our New Arrivals page!";

            $introLines = array();
            $introLines[0] = 'As a TLV Seller, you have your own customized Seller "Dashboard" on the site which enables you to monitor your Item(s) listed for sale, offers received and Item(s) sold!';
            $introLines[1] = 'If you have not yet logged into your TLV account, not to worry! You can access your Seller Dashboard by following these simple steps:';

            $outroLines = array();
            $outroLines[0] = 'Go to thelocalvault.com';
            $outroLines[1] = 'Click "Log In"';
            $outroLines[2] = 'Your username has already been created with your email address.';
            $outroLines[3] = 'Click "Lost Your Password?" to reset a password to your liking.';
            $outroLines[4] = 'Once you have finished setting up your Account, click on "My Account" in the top right corner of the Home Page.';
            $outroLines[5] = 'From here, you can click "Dashboard" and have the ability to monitor your Item(s), Item(s) Sold and Offers Received.';
            $outroLines[6] = 'When you receive an offer, you have the option to Accept, Counter or Decline from this dashboard... but just remember, all offers expire after 48 hours!';
            $outroLines[7] = 'When an Item has sold that is noted as "Dropoff by Consignor Required" in your TLV Pricing Proposal you will need to drop it off at The Local Vault Office in Cos Cob, CT. Otherwise, you will be contacted by our logistics coordinator to arrange a date and time for pick up. This does not apply for Items that have been moved to the TLV Storage Facility.';
            $outroLines[8] = 'Once the Item has been received and accepted by the buyer, your payment will be issued!';

            $attachments = array();
            //        $attachments[] = 'TLV Client Sale Agreement_04_05_2020.pdf';
            $myViewData = \View::make('emails.product_synchronize', ['agreement_link' => '', 'link' => '', 'product_quots' => '', 'line1' => $line1, 'line2' => $line2, 'line3' => $line3, 'line4' => $line4, 'greeting' => $greeting, 'seller' => $seller_data, 'level' => 'success', 'outroLines' => $outroLines, 'introLines' => $introLines])->render();

            $bccs = [];
            $ccs = [];
            $ccs[] = 'sell@thelocalvault.com';
            $other_emails = [];
            $other_emails[] = 'thelocalvaultcomproduction@thelocalvault.freshdesk.com';
            if (app('App\Http\Controllers\EmailController')->sendMail($seller_data->getEmail(), 'You are almost there!', $myViewData, $attachments, $bccs, $ccs, $other_emails)) {

            }
        }


//        echo "<pre>";
//        print_r($product_quot_new_array);
//        die;

        return 1;
    }

//send Proposal step 2

    public function sendMailApproveStatusChange(Request $request)
    {

        $products = $request->all();

        $approved_product_quots = array();

        $seller = $this->seller_repo->SellerOfId($products['seller']);

        foreach ($products['products'] as $key => $value) {

            $product_quot = $this->product_quotation_repo->ProductQuotationOfId($value['id']);

            $approved_product_quots[] = $product_quot;

            if ($value['is_send_mail'] == 3) {

                $data['is_archived'] = 1;
            } else {

                $data['is_send_mail'] = $value['is_send_mail'];

//                $data['is_for_production_create_date'] = 1;

                $data['is_for_production_create_date'] = 'Yes';

                //17 for pending

                $data['status_quot'] = $this->option_repo->OptionOfId(17);


                $data['is_product_for_production'] = 1;

                $data['is_copyright_create_date'] = 1;
            }


            $this->product_quotation_repo->update($product_quot, $data);
        }
    }

//Generate Proposal step 2 or 4

    public function sendMailApprove(Request $request)
    {

        ini_set('max_execution_time', 300);

        $products = $request->all();

        $approved_product_quots = array();

        $seller = $this->seller_repo->SellerOfId($products['seller']);

        $product_quot_ids = [];

        foreach ($products['products'] as $key => $value) {

            $product_quot_ids[] = $value['id'];


            $product_quot = $this->product_quotation_repo->ProductQuotationOfId($value['id']);

            $approved_product_quots[] = $product_quot;

            if ($value['is_send_mail'] == 3) {

                $data['is_archived'] = 1;
            } else {

                $data['is_send_mail'] = $value['is_send_mail'];

//                $data['is_product_for_production'] = 1;

                $data['is_for_production_create_date'] = 'Yes';

                //17 for pending

                $data['status_quot'] = $this->option_repo->OptionOfId(17);
            }


//            $this->product_quotation_repo->update($product_quot, $data);
        }


        //product_quote_agreement

        $product_quote_agreement = [];

        $product_quote_agreement['is_form_filled'] = 0;

        $product_quote_agreement['seller_id'] = $seller;

        $product_quote_agreement['pdf'] = '';

        $product_quote_agreement['quote_ids_json'] = json_encode($product_quot_ids);


        $product_quote_agreement_prepared_data = $this->product_quote_agreement_repo->prepareData($product_quote_agreement);

        $product_quote_agreement_obj = $this->product_quote_agreement_repo->create($product_quote_agreement_prepared_data);


        $product_quote_agreement_enc_id = \Crypt::encrypt($product_quote_agreement_obj->getId());

        $agreement_link = config('app.url') . 'seller_agreement/' . $product_quote_agreement_enc_id;


//        $file_name = app('App\Http\Controllers\ExportController')->downloadProductPdfProposal($request);


        $file_name = app('App\Http\Controllers\ExportController')->downloadProductWordProposal($request);

        //proposal for client without internal note

        $request->merge([
            'isForClient' => true
        ]);


        $file_name_client = app('App\Http\Controllers\ExportController')->downloadProductPdfProposal($request);

//         Log::info('Product pdf:' . env('APP_URL') . 'api/storage/exports/' . $file_name_pdf);
//        $file_name_client = app('App\Http\Controllers\ExportController')->downloadProductWordProposal($request);
//        $link = env('APP_URL') . 'api/storage/exports/' . $file_name;

        $link = config('app.url') . 'api/storage/exports/' . $file_name_client;

        $greeting = "Dear " . $seller->getFirstName() . ',';


        $introLines = array();

//        if ($temp['is_referral'] == 1)
//        {
//        old content 25-06-2019
//        $introLines[0] = 'Please see below for your TLV Sale Catalog.  As discussed, we have priced your Item(s) based upon the information you provided, online comps as well as TLV historical sales data. This pricing is subject to change upon closer inspection.  TLV will notify you of any change in pricing subsequent to the photo shoot. Items are priced within a range.  We will begin the listing at the high price "maximum" and reduce throughout the listing period never going lower than the indicated "minimum" price.';
//        $line1 = "In order to list your pieces on TLV, we require a signed copy of our Client Agreement. Once we receive your signed contract we will contact you to schedule a date to come and photograph, measure and catalog your collection. Please send your Client Agreement to ";
//        $line2 = "sell@thelocalvault.com.";
//        $line3 = "We are looking forward to working with you!";
//
//        old content 26-06-2019
//        $introLines[0] = 'Please see below for your TLV Pricing Proposal. As discussed, we have priced your Item(s) based upon condition, market trends, and TLV sales data. This pricing is subject to change upon closer inspection. TLV will notify you of any change in pricing subsequent to the photoshoot. Any change in pricing must be mutually agreed upon prior to listing on the website. Items are priced at the "List Price" for the first 3 months of the listing. If the item has not sold after 3 months, the price will be reduced by 30% for the 4th month. In order to list your items on TLV, we require a signed copy of our Client Agreement.';
//        $line1 = "Once we receive a signed copy of our agreement, we will contact you to schedule a date to come and photograph, measure and catalog your collection.";
//        $line2 = "";
//        $line3 = "We look forward to working with you!";


        $introLines[0] = 'Please see below for your TLV "Pricing Proposal". As discussed, we have priced your Item(s) based upon condition, market trends, and TLV sales data. This pricing is subject to change upon closer inspection. TLV will notify you of any change in pricing subsequent to the photoshoot. Any change in pricing must be mutually agreed upon prior to listing on the website. As outlined in the TLV Consignment agreement, an Item is listed at the agreed "Advertised Price" for the first 3 months of the listing. If the "Item" has not sold after 3 months, the Advertised Price will be reduced by 30% for the 4th month. In order to list your Items on TLV, we require a signed copy of our Consignment Agreement.';

        $line1 = "Once we receive a signed copy of our Consignment Agreement we will contact you to schedule a date to come and photograph, measure and catalog your collection.";

        $line2 = "";

        $line3 = "We look forward to working with you!";

//        }

        $attachments = array();

//        $attachments[] = 'TLV Client Sale Agreement 7_31_17.docx.pdf';
//        $attachments[] = 'TLV Client Sale Agreement_12_2_1019.pdf';

        $attachments[] = 'TLV Client Sale Agreement_04_05_2020.pdf';

        $myViewData = \View::make('emails.product_proposal', ['agreement_link' => $agreement_link, 'link' => $link, 'product_quots' => $approved_product_quots, 'line1' => $line1, 'line2' => $line2, 'line3' => $line3, 'greeting' => $greeting, 'seller' => $seller, 'level' => 'success', 'outroLines' => [0 => ''], 'introLines' => $introLines])->render();


        $bccs = [];

//        $bccs[] = 'sell@thelocalvault.com';
//        $bccs[] = 'support@thelocalvault.freshdesk.com';

        $ccs = [];

        $ccs[] = 'sell@thelocalvault.com';

        $other_emails = [];

        $other_emails[] = 'thelocalvaultcomproduction@thelocalvault.freshdesk.com';

//        if (app('App\Http\Controllers\EmailController')->sendMail('webdeveloper1011@gmail.com', 'Proposal Agreement: ' . $seller->getLastname(), $myViewData, $attachments, $bccs, $ccs, $other_emails))

        if (app('App\Http\Controllers\EmailController')->sendMail($seller->getEmail(), 'Proposal Agreement: ' . $seller->getLastname(), $myViewData, $attachments, $bccs, $ccs, $other_emails)) {

        }


        return $file_name;

//        return response()->json('ProductQuotation saved Successfully', 200);
    }

    public function sendMailPricingApprove(Request $request)
    {

        ini_set('max_execution_time', 300);

        $products = $request->all();

        $approved_product_quots = array();

        $seller = $this->seller_repo->SellerOfId($products['seller']);

        $product_quot_ids = [];

        foreach ($products['products'] as $key => $value) {

            $product_quot_ids[] = $value['id'];


            $product_quot = $this->product_quotation_repo->ProductQuotationOfId($value['id']);

            $approved_product_quots[] = $product_quot;

            if ($value['is_send_mail'] == 3) {

                $data['is_archived'] = 1;
            } else {

                $data['is_send_mail'] = $value['is_send_mail'];

//                $data['is_for_production_create_date'] = 1;

                $data['is_for_production_create_date'] = 'Yes';

                //17 for pending

                $data['status_quot'] = $this->option_repo->OptionOfId(17);
            }


//            $this->product_quotation_repo->update($product_quot, $data);
        }


        //product_quote_agreement

        $product_quote_agreement = [];

        $product_quote_agreement['is_form_filled'] = 0;

        $product_quote_agreement['seller_id'] = $seller;

        $product_quote_agreement['pdf'] = '';

        $product_quote_agreement['quote_ids_json'] = json_encode($product_quot_ids);


        $product_quote_agreement_prepared_data = $this->product_quote_agreement_repo->prepareData($product_quote_agreement);

        $product_quote_agreement_obj = $this->product_quote_agreement_repo->create($product_quote_agreement_prepared_data);


        $product_quote_agreement_enc_id = \Crypt::encrypt($product_quote_agreement_obj->getId());

        $agreement_link = config('app.url') . 'seller_agreement/' . $product_quote_agreement_enc_id;


//        $file_name = app('App\Http\Controllers\ExportController')->downloadProductPdfProposal($request);


        $file_name = app('App\Http\Controllers\ExportController')->downloadProductWordProposal($request);

        //proposal for client without internal note

        $request->merge([
            'isForClient' => true
        ]);


        $file_name_client = app('App\Http\Controllers\ExportController')->downloadProductPdfProposal($request);

//         Log::info('Product pdf:' . env('APP_URL') . 'api/storage/exports/' . $file_name_pdf);
//        $file_name_client = app('App\Http\Controllers\ExportController')->downloadProductWordProposal($request);
//        $link = env('APP_URL') . 'api/storage/exports/' . $file_name;

        $link = config('app.url') . 'api/storage/exports/' . $file_name_client;

        $greeting = "Dear " . $seller->getFirstName() . ',';


        $introLines = array();

//        if ($temp['is_referral'] == 1)
//        {
//        old content 25-06-2019
//        $introLines[0] = 'Please see below for your TLV Sale Catalog.  As discussed, we have priced your Item(s) based upon the information you provided, online comps as well as TLV historical sales data. This pricing is subject to change upon closer inspection.  TLV will notify you of any change in pricing subsequent to the photo shoot. Items are priced within a range.  We will begin the listing at the high price "maximum" and reduce throughout the listing period never going lower than the indicated "minimum" price.';
//        $line1 = "In order to list your pieces on TLV, we require a signed copy of our Client Agreement. Once we receive your signed contract we will contact you to schedule a date to come and photograph, measure and catalog your collection. Please send your Client Agreement to ";
//        $line2 = "sell@thelocalvault.com.";
//        $line3 = "We are looking forward to working with you!";
//
//        old content 26-06-2019
//        $introLines[0] = 'Please see below for your TLV Pricing Proposal. As discussed, we have priced your Item(s) based upon condition, market trends, and TLV sales data. This pricing is subject to change upon closer inspection. TLV will notify you of any change in pricing subsequent to the photoshoot. Any change in pricing must be mutually agreed upon prior to listing on the website. Items are priced at the "List Price" for the first 3 months of the listing. If the item has not sold after 3 months, the price will be reduced by 30% for the 4th month. In order to list your items on TLV, we require a signed copy of our Client Agreement.';
//        $line1 = "Once we receive a signed copy of our agreement, we will contact you to schedule a date to come and photograph, measure and catalog your collection.";
//        $line2 = "";
//        $line3 = "We look forward to working with you!";


        $introLines[0] = 'Please see below for your TLV "Pricing Proposal". As discussed, we have priced your Item(s) based upon condition, market trends, and TLV sales data. This pricing is subject to change upon closer inspection. TLV will notify you of any change in pricing subsequent to the photoshoot. Any change in pricing must be mutually agreed upon prior to listing on the website. As outlined in the TLV Consignment agreement, an Item is listed at the agreed "Advertised Price" for the first 3 months of the listing. If the "Item" has not sold after 3 months, the Advertised Price will be reduced by 30% for the 4th month. In order to list your Items on TLV, we require a signed copy of our Consignment Agreement.';

        $line1 = "Once we receive a signed copy of our Consignment Agreement we will contact you to schedule a date to come and photograph, measure and catalog your collection.";

        $line2 = "";

        $line3 = "We look forward to working with you!";

//        }

        $attachments = array();

//        $attachments[] = 'TLV Client Sale Agreement 7_31_17.docx.pdf';
//        $attachments[] = 'TLV Client Sale Agreement_12_2_1019.pdf';

        $attachments[] = 'TLV Client Sale Agreement_04_05_2020.pdf';

        $myViewData = \View::make('emails.product_proposal', ['agreement_link' => $agreement_link, 'link' => $link, 'product_quots' => $approved_product_quots, 'line1' => $line1, 'line2' => $line2, 'line3' => $line3, 'greeting' => $greeting, 'seller' => $seller, 'level' => 'success', 'outroLines' => [0 => ''], 'introLines' => $introLines])->render();


        $bccs = [];

//        $bccs[] = 'sell@thelocalvault.com';
//        $bccs[] = 'support@thelocalvault.freshdesk.com';

        $ccs = [];

        $ccs[] = 'sell@thelocalvault.com';

        $other_emails = [];

        $other_emails[] = 'thelocalvaultcomproduction@thelocalvault.freshdesk.com';

//        if (app('App\Http\Controllers\EmailController')->sendMail('webdeveloper1011@gmail.com', 'Proposal Agreement: ' . $seller->getLastname(), $myViewData, $attachments, $bccs, $ccs, $other_emails))

        if (app('App\Http\Controllers\EmailController')->sendMail($seller->getEmail(), 'Proposal Agreement: ' . $seller->getLastname(), $myViewData, $attachments, $bccs, $ccs, $other_emails)) {

        }


        return $file_name;

//        return response()->json('ProductQuotation saved Successfully', 200);
    }

    public function sendProposalMail(Request $request)
    {

        $data = $request->all();

        $data['seller_id'] = $this->seller_repo->SellerOfId($data['seller_id']);

        $data['file_name'] = $data['seller_id']->getLastProposalFileName();

        $filename = $data['file_name'];

        $file_data = config('app.url') . 'api/' . $data['seller_id']->getLastProposalFileNameBase();


//        $file_data = 'https://drive.google.com/file/d/' . $data['seller_id']->getLastProposalFileNameBase() . '/view'
//                . '';
//        return Storage::cloud()->get($file['path']);

        $data['from_state'] = 'proposal';

        $data['file_path'] = $data['seller_id']->getLastProposalFileNameBase();

        $myViewData = \View::make('emails.product_status_change', ['level' => 'success', 'outroLines' => [0 => ''], 'introLines' => [0 => $data['message'], 1 => $file_data]])->render();


        if (app('App\Http\Controllers\EmailController')->sendMail($data['seller_id']->getEmail(), $data['subject'], $myViewData)) {

        }

        $prepared = $this->mail_record_repo->prepareData($data);

        $this->mail_record_repo->create($prepared);
    }

    public function sendMailReject(Request $request)
    {

        $products = $request->all();

        $product_quots = array();

        if (count($products['products']) > 0) {

            $product_quot = $this->product_quotation_repo->ProductQuotationOfId($products['products'][0]['id']);

            $seller = $product_quot->getProductId()->getSellerid();

            foreach ($products['products'] as $key => $value) {

                $product_quot = $this->product_quotation_repo->ProductQuotationOfId($value['id']);


                $product_quots[] = $product_quot;

                if ($value['is_send_mail'] == 2) {

                    $data['is_send_mail'] = $value['is_send_mail'];

                    $this->product_quotation_repo->update($product_quot, $data);
                }
            }

            if ($product_quot->getProductId()->getSellerid()->getEmail() != '') {

                $greeting = "Dear " . $seller->getFirstName() . ',';

                $introLines = array();

                $introLines[0] = "Thank you so much for submitting your Item(s) on TLV!  While they are lovely, we do not believe we currently have the proper audience for them. Sometimes we have to make these tough decisions based upon sales history and the buying preferences of our TLV audience. Please don’t let this news keep you from reaching out to us in the future regarding other Items! We wish you the best of luck and thank you for thinking of us!";

                $myViewData = \View::make('emails.proposal_status_reject', ['product_quots' => $product_quots, 'greeting' => $greeting, 'seller' => $seller, 'level' => 'success', 'outroLines' => [0 => ''], 'introLines' => $introLines])->render();

                if (app('App\Http\Controllers\EmailController')->sendMail($seller->getEmail(), 'Thank you for reaching out to us at TLV!', $myViewData)) {

                }
            }
        }


//        foreach ($products['products'] as $key => $value)
//        {
//            $product_quot = $this->product_quotation_repo->ProductQuotationOfId($value['id']);
//            if ($value['is_send_mail'] == 3)
//            {
//                $data['is_archived'] = 1;
//            }
//            else
//            {
//                $data['is_send_mail'] = $value['is_send_mail'];
////                $data['is_send_mail'] = $value['is_send_mail'];
//                $data['is_for_production_create_date'] = 'yes';
//                //17 for pending
//                $data['status_quot'] = $this->option_repo->OptionOfId(17);
//            }
//
//
//
//            $this->product_quotation_repo->update($product_quot, $data);
//        }

        return response()->json('ProductQuotation saved Successfully', 200);
    }

    public function sendMailArchive(Request $request)
    {


        $products = $request->all();

        foreach ($products['products'] as $key => $value) {

            $product_quot = $this->product_quotation_repo->ProductQuotationOfId($value['id']);

            if ($value['is_send_mail'] == 3) {

                //is_archived

                $data['is_archived'] = 1;

                $this->product_quotation_repo->update($product_quot, $data);
            }
        }

        return response()->json('ProductQuotation saved Successfully', 200);
    }

    public function DeleteAllProductQuotation(Request $request)
    {


        $products = $request->all();


        foreach ($products['products'] as $key => $value) {

            if (isset($value['is_send_mail']) == 85) {

                $product_quot = $this->product_quotation_repo->ProductQuotationOfId($value['id']);

                $this->product_quotation_repo->delete($product_quot);
            }

            if (isset($value['product_final_status_id']) == 85) {

                $product_quots = $this->product_quotation_repo->ProductQuotationOfId($value['product_quotation_id']);

                $this->product_quotation_repo->delete($product_quots);
            }

            if (isset($value['copyright_status_id']) == 85) {

                $product_quots = $this->product_quotation_repo->ProductQuotationOfId($value['product_quotation_id']);

                $this->product_quotation_repo->delete($product_quots);
            }
        }

        return response()->json('Product Quotation Delete Successfully', 200);
    }

    public function sendMail_old(Request $request)
    {

        $products = $request->all();


        foreach ($products['products'] as $key => $value) {

            $product_quot = $this->product_quotation_repo->ProductQuotationOfId($value['id']);

            if ($value['is_send_mail'] == 3) {

                $data['is_archived'] = 1;
            } else {

                $data['is_send_mail'] = $value['is_send_mail'];

//                $data['is_send_mail'] = $value['is_send_mail'];
//                $data['is_for_production_create_date'] = 1;

                $data['is_for_production_create_date'] = 'Yes';

                //17 for pending

                $data['status_quot'] = $this->option_repo->OptionOfId(17);
            }


            $this->product_quotation_repo->update($product_quot, $data);
        }

        return response()->json('ProductQuotation saved Successfully', 200);
    }

    public function saveProductForProduction(Request $request)
    {

        $data = $request->all();

        $product_quot = $this->product_quotation_repo->ProductQuotationOfId($request->id);

        if (isset($request->product_for_production_status_id)) {

            if ($data['product_for_production_status_id'] == 3) {

                $data['is_archived'] = 1;
            } else {

                $data['is_product_for_production'] = $request['product_for_production_status_id'];

                $data['is_copyright_create_date'] = 1;
            }
        }

        $data_product['name'] = $data['product_id']['name'];

        if (isset($data['price'])) {

            $data_product['price'] = $data['price'];
        }

        $data_product['description'] = $data['product_id']['description'];

        $data_product['quantity'] = $data['quantity'];

        $data_product['note'] = $data['note'];

        $data_product['sku'] = $data['product_id']['sku'];


        if (isset($data['product_id']['state'])) {

            $data_product['state'] = $data['product_id']['state'];
        }

        if (isset($data['product_id']['city'])) {

            $data_product['city'] = $data['product_id']['city'];
        }

        if (isset($data['product_id']['location'])) {

            $data_product['location'] = $data['product_id']['location'];
        }

        if (isset($data['product_id']['category_local'])) {

            $data_product['category_local'] = $data['product_id']['category_local'];
        }

        if (isset($data['product_id']['brand_local'])) {

            $data_product['brand_local'] = $data['product_id']['brand_local'];
        }

//        if (isset($data['product_id']['item_type_local']))
//        {
//            $data_product['item_type_local'] = $data['product_id']['item_type_local'];
//        }

        if (isset($data['product_id']['age'])) {

            $data_product['age'] = $this->sub_category_repo->SubCategoryOfId($data['product_id']['age']);
        }

        if (isset($data['product_id']['condition_local'])) {

            $data_product['condition_local'] = $data['product_id']['condition_local'];
        }


        $data_product['product_pending_images'] = array();

        if (isset($data['images'])) {

            if (count($data['images']) > 0) {

                foreach ($data['images'] as $key => $value) {

                    $data_product['product_pending_images'][] = $this->image_repo->ImageOfId($value);
                }
            } else {

                $data_product['product_pending_images'] = array();
            }

            foreach ($data['product_id']['cat'] as $x => $y) {

                if ($x != '') {

//                    if ($x == 'Condition')
//                    {
//                        $data_product['con'] = $this->sub_category_repo->SubCategoryOfId($y);
//                    }
//                    else

                    if ($x == 'Collection') {

                        foreach ($y as $y_key => $y_value) {

                            $data_product['product_collection'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                        }
                    } else if ($x == 'Room') {

                        foreach ($y as $y_key => $y_value) {

                            $data_product['product_room'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                        }
                    } else if ($x == 'Look') {

                        $data_product['product_look'] = [];


                        foreach ($y as $y_key => $y_value) {

                            $data_product['product_look'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                        }
                    } else if ($x == 'Color') {

                        foreach ($y as $y_key => $y_value) {

                            $data_product['product_color'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                        }
                    }

//                    else if ($x == 'Sub Category')
//                    {
//
//                        $data_product['category'] = $this->sub_category_repo->SubCategoryOfId($y);
//                    }
                    else if ($x == 'Sub Category') {

                        foreach ($y as $y_key => $y_value) {


                            $data_product['product_category'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                        }
                    } else if ($x == 'Condition') {

                        foreach ($y as $y_key => $y_value) {


                            $data_product['product_con'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                        }
                    } else {

                        $data_product[strtolower($x)] = $this->sub_category_repo->SubCategoryOfId($y);
                    }
                }
            }
        }

        $this->product_repo->update($product_quot->getProductId(), $data_product);

        unset($data['product_id']);

        if ($this->product_quotation_repo->update($product_quot, $data)) {

            return response()->json('Product Updated Successfully', 200);
        }
    }

//final approve

    public function saveProductStoragePricing(Request $request)
    {

        $data = $request->all();
        if (isset($data)) {
            foreach ($data['product_status'] as $key => $value) {
                $product_quot_ids[] = $value['product_quotation_id'];
                $product_quot_storage = $this->product_quotation_repo->ProductQuotationOfId($value['product_quotation_id']);
                $product_quot[] = $product_quot_storage;
            }

            if (count($product_quot) > 0) {
                //product_quote_agreement
                $seller_data = $this->seller_repo->SellerOfId($data['seller']);

                $product_storage_agreement = [];
                $product_storage_agreement['is_form_filled'] = 0;
                $product_storage_agreement['seller_id'] = $this->seller_repo->SellerOfId($data['seller']);
                $product_storage_agreement['pdf'] = '';
                $product_storage_agreement['quote_ids_json'] = json_encode($product_quot_ids);

                $product_storage_agreement_prepared_data = $this->product_storage_agreement_repo->prepareData($product_storage_agreement);
                $product_storage_agreement_obj = $this->product_storage_agreement_repo->create($product_storage_agreement_prepared_data);
                $product_storage_agreement_enc_id = \Crypt::encrypt($product_storage_agreement_obj->getId());
                $agreement_link = config('app.url') . 'storage_agreement/' . $product_storage_agreement_enc_id;


                $greeting = "Dear " . $seller_data->getFirstName() . ',';
                $link = '';
                $introLines = array();
//                $introLines[0] = 'Please see below for your TLV "Storage Proposal". As discussed, we have priced your Item(s) based upon condition, market trends, and TLV sales data. This pricing is subject to change upon closer inspection. TLV will notify you of any change in pricing subsequent to the photoshoot. Any change in pricing must be mutually agreed upon prior to listing on the website. As outlined in the TLV Consignment agreement, an Item is listed at the agreed "Advertised Price" for the first 3 months of the listing. If the "Item" has not sold after 3 months, the Advertised Price will be reduced by 30% for the 4th month. In order to list your Items on TLV, we require a signed copy of our Consignment Agreement.';
                $introLines[0] = 'Please see below for your TLV "Storage Proposal". As outlined in our Storage Agreement, you will be charged the below Storage Fee for each Item on a monthly basis for your Item(s) stored at our facility. The first month’s fee shall be pro-rated from the date of arrival of the Item(s) at the Storage Facility for the number of days left of the month. As we sell through your Item(s), you will note that the total monthly Storage Fee will be reduced by the Storage Cost for the sold Item(s). In order to store your Item(s) at our Facility, we require a signed copy of our TLV Storage Agreement.';
//                $line1 = "Once we receive a signed copy of our Consignment Agreement we will contact you to schedule a date to come and photograph, measure and catalog your collection.";
                $line1 = "Once we receive a signed copy of your Storage Agreement, you will be contacted by logistics@thelocalvault.com to provide you with a quote to move your Item(s) to our Facility and arrange a date for your Item(s) to be picked up.";
                $line2 = "";
//                $line3 = "We look forward to working with you!";
                $line3 = "";
                $outroLines = array();
                $outroLines[0] = '';

                $attachments = array();
                $attachments[] = 'TLV Storage Agreement_31_10_2019.pdf';

//                $link = config('app.url') . 'Uploads/default_pdf/TLV Storage Agreement_14_10_2019.pdf';
                $file_name_client = app('App\Http\Controllers\ExportController')->downloadStrageProductPdfProposal($request);
                $link = config('app.url') . 'api/storage/exports/' . $file_name_client;

//                        $attachments[] = 'TLV Storage Pricing List_05 _08_2019.pdf';
                $myViewData = \View::make('emails.product_storage_price', ['agreement_link' => $agreement_link, 'link' => $link, 'product_quots' => $product_quot, 'line1' => $line1, 'line2' => $line2, 'line3' => $line3, 'greeting' => $greeting, 'seller' => $seller_data, 'level' => 'success', 'outroLines' => [0 => ''], 'introLines' => $introLines])->render();

                $bccs = [];
                $ccs = [];
                $ccs[] = 'sell@thelocalvault.com';
                $other_emails = [];
                $other_emails[] = 'thelocalvaultcomproduction@thelocalvault.freshdesk.com';

                if (app('App\Http\Controllers\EmailController')->sendMailONLY($seller_data->getEmail(), 'TLV Storage Proposal: ' . $seller_data->getLastname(), $myViewData, $attachments, $bccs, $ccs, $other_emails)) {

                }
            }
            return response()->json('Storage Proposal send Successfully', 200);
        }
        return response()->json('Product Not Available', 500);
    }

    public function saveProductAwaitingContract(Request $request)
    {

        $data = $request->all();


        if ($request->id) {

            $product_quot = $this->product_quotation_repo->ProductQuotationOfId($request->id);

            if (isset($data['product_id']['ship_size'])) {

                $data_product['ship_size'] = $data['product_id']['ship_size'];
            }

            if (isset($data['product_id']['ship_material'])) {

                $data_product['ship_material'] = $data['product_id']['ship_material'];
            }

            if (isset($data['product_id']['ship_cat'])) {

                $data_product['ship_cat'] = $data['product_id']['ship_cat'];
            }

            if (isset($data['product_id']['flat_rate_packaging_fee'])) {

                $data_product['flat_rate_packaging_fee'] = $data['product_id']['flat_rate_packaging_fee'];
            }


            $data_product['name'] = $data['product_id']['name'];

            if (isset($data['price'])) {

                $data_product['price'] = $data['price'];
            }

            if (isset($data['tlv_price'])) {

                $data_product['tlv_price'] = $data['tlv_price'];
            }

            $data_product['description'] = $data['product_id']['description'];

            $data_product['quantity'] = $data['quantity'];

            $data_product['note'] = $data['note'];

            $data_product['sku'] = $data['product_id']['sku'];


            if (isset($data['product_id']['state'])) {

                $data_product['state'] = $data['product_id']['state'];
            }

            if (isset($data['product_id']['city'])) {

                $data_product['city'] = $data['product_id']['city'];
            }

            if (isset($data['product_id']['ship_size'])) {

                $data_product['ship_size'] = $data['product_id']['ship_size'];
            }

            if (isset($data['product_id']['ship_material'])) {

                $data_product['ship_material'] = $data['product_id']['ship_material'];
            }

            if (isset($data['product_id']['local_pickup_available'])) {

                $data_product['local_pickup_available'] = $data['product_id']['local_pickup_available'];
            }

            if (isset($data['product_id']['location'])) {

                $data_product['location'] = $data['product_id']['location'];
            }

            if (isset($data['product_id']['category_local'])) {

                $data_product['category_local'] = $data['product_id']['category_local'];
            }

            if (isset($data['product_id']['brand_local'])) {

                $data_product['brand_local'] = $data['product_id']['brand_local'];
            }

            if (isset($data['product_id']['age'])) {

                $data_product['age'] = $this->sub_category_repo->SubCategoryOfId($data['product_id']['age']);
            }

//            if (isset($data['product_id']['product_material'])) {
//
//                $data_product['product_material'] = $this->sub_category_repo->SubCategoryOfId($data['product_id']['product_material']);
//            }

            if (isset($data['product_id']['pick_up_location'])) {

                $data_product['pick_up_location'] = $this->option_repo->OptionOfId($data['product_id']['pick_up_location']);
            }

            if (isset($data['product_id']['local_drop_off'])) {

                $data_product['local_drop_off'] = $data['product_id']['local_drop_off'];
            }
            if (isset($data['product_id']['local_drop_off_city'])) {
                if ($data['product_id']['local_drop_off'] == 1) {
                    $data_product['local_drop_off_city'] = $data['product_id']['local_drop_off_city'];
                } else {
                    $data_product['local_drop_off_city'] = NULL;
                }
            }

            if (isset($data['product_id']['pet_free'])) {

                $data_product['pet_free'] = $data['product_id']['pet_free'];
            }

            if (isset($data['product_id']['condition_local'])) {

                $data_product['condition_local'] = $data['product_id']['condition_local'];
            }


            $data_product['product_pending_images'] = array();

            if (isset($data['images'])) {

                if (count($data['images']) > 0) {

                    foreach ($data['images'] as $key => $value) {

                        $data_product['product_pending_images'][] = $this->image_repo->ImageOfId($value);
                    }
                } else {

                    $data_product['product_pending_images'] = array();
                }

                foreach ($data['product_id']['cat'] as $x => $y) {


                    if ($x != '') {

//                    if ($x == 'Condition')
//                    {
//                        $data_product['con'] = $this->sub_category_repo->SubCategoryOfId($y);
//                    }
//                    else

                        if ($x == 'Collection') {

                            $data_product['product_collection'] = [];

                            foreach ($y as $y_key => $y_value) {

                                $data_product['product_collection'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                            }
                        } else if ($x == 'Room') {

                            $data_product['product_room'] = [];

                            foreach ($y as $y_key => $y_value) {

                                $data_product['product_room'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                            }
                        } else if ($x == 'Color') {

                            $data_product['product_color'] = [];

                            foreach ($y as $y_key => $y_value) {

                                $data_product['product_color'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                            }
                        } else if ($x == 'Look') {

                            $data_product['product_look'][] = $this->sub_category_repo->SubCategoryOfId($y);

                            // foreach ($y as $y_key => $y_value) {

                            //     $data_product['product_look'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                            // }
                        }

//                    else if ($x == 'Sub Category')
//                    {
//
//
//                        $data_product['category'] = $this->sub_category_repo->SubCategoryOfId($y);
//                    }
                        else if ($x == 'Category') {

                            $data_product['product_category'] = [];

                            foreach ($y as $y_key => $y_value) {

                                $data_product['product_category'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                            }
                        } else if ($x == 'Condition') {

                            $data_product['product_con'] = [];

                            foreach ($y as $y_key => $y_value) {

                                $data_product['product_con'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                            }
                        } else if ($x == 'product_materials') {
                            $data_product['product_materials'] = [];
                            foreach ($y as $y_key => $y_value) {
                                $data_product['product_materials'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                            }
                        } else {


                            $data_product[strtolower($x)] = $this->sub_category_repo->SubCategoryOfId($y);
                        }
                    }
                }
            }

            $this->product_repo->update($product_quot->getProductId(), $data_product);

            if (isset($request->passfrom)) {

                if ($request->passfrom == 'product_final') {

                    if (isset($request->product_final_status_id)) {

                        if ($request->product_final_status_id == 'archived') {

                            $data['is_archived'] = 1;

//                $this->product_quotation_repo->update($product_quot, $data_t);
                        } else {

                            $data['status_quot'] = $this->option_repo->OptionOfId($request->product_final_status_id);

                            unset($data['product_id']);

                            $this->product_quotation_repo->update($product_quot, $data);


                            if ($data['status_quot']->getId() == 18) {

                                $product_quot_new = array();

                                $product_quot_new['data'] = $this->product_quotation_repo->getProductQuotationById($request->id);

//                            $product_quot_new['data'] = $this->product_quotation_repo->getProductQuotationById($value['product_quotation_id']);

                                $product_quot_new['data']['product_id']['city'] = '';

                                $product_quot_new['data']['product_id']['state'] = '';

                                if (isset($product_quot_new['data']['product_id']['pick_up_location'])) {


                                    if (isset($product_quot_new['data']['product_id']['pick_up_location']['key_text'])) {

                                        $details_location = json_decode($product_quot_new['data']['product_id']['pick_up_location']['key_text']);

                                        if (count($details_location) > 0) {

                                            if (isset($details_location[0]->city)) {

                                                $product_quot_new['data']['product_id']['city'] = $details_location[0]->city;
                                            }

                                            if (isset($details_location[0]->state)) {

                                                $product_quot_new['data']['product_id']['state'] = $details_location[0]->state;
                                            }
                                        }
                                    }
                                }


                                if (isset($product_quot_new['data']['delivery_option']) && $product_quot_new['data']['delivery_option'] != '') {


                                    if ($product_quot_new['data']['delivery_description'] != '') {

                                        $product_quot_new['data']['delivery_description'] = $product_quot_new['data']['delivery_option'] . ', ' . $product_quot_new['data']['delivery_description'];
                                    } else {

                                        $product_quot_new['data']['delivery_description'] = $product_quot_new['data']['delivery_option'];
                                    }


//                                $product_quot_new['data']['delivery_description'] = $product_quot_new['data']['delivery_option'] . ', ' . $product_quot_new['data']['delivery_description'];
                                }


//echo "<pre>";
//print_r($product_quot_new['data']);
//die;

                                $qid = $product_quot_new['data']['id'];

//        print_r($qid);

                                $product_quot_new['data'] = json_encode($product_quot_new);


//                            $data = array('name' => 'Ross', 'php_master' => true);
//                                $host = 'https://localvault.staging.wpengine.com/wp-content/themes/thelocalvault/new-product-temp.php';
                                $host = env('WP_URL') . '/wp-content/themes/thelocalvault/new-product-temp.php';

                                $ch = curl_init();

                                curl_setopt($ch, CURLOPT_URL, $host);

                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                                curl_setopt($ch, CURLOPT_POST, true);

                                curl_setopt($ch, CURLOPT_POSTFIELDS, $product_quot_new);

                                //temp_stop

                                $temp = curl_exec($ch);


                                if ($temp) {

//                                $this->product_quotation_repo->update($product_quot, $data);

                                    $this->saveWPId($qid, $temp);
                                } else {

                                }
                            }
                        }
                    }
                } else if ($request->passfrom == 'product_for_production') {

                    if (isset($request->product_for_production_status_id)) {

                        if ($data['product_for_production_status_id'] == 3) {

                            $data['is_archived'] = 1;
                        } else {

                            $data['is_product_for_production'] = $request['product_for_production_status_id'];

                            $data['is_copyright_create_date'] = 1;
                        }
                    }
                } else if ($request->passfrom == 'copyright') {

                    if (isset($request->copyright_status_id)) {

                        if ($request['copyright_status_id'] == 3) {

                            $data['is_archived'] = 1;
                        } else {

                            $data['is_copyright'] = $data['copyright_status_id'];

                            $data['is_approved_create_date'] = 1;
                        }
                    }
                }
            }

            unset($data['product_id']);

            if ($this->product_quotation_repo->update($product_quot, $data)) {

                return response()->json('Product Updated Successfully', 200);
            }
        } else {

            //           $product_quot = $this->product_quotation_repo->ProductQuotationOfId($request->id);

            $data2['is_updated_details'] = 1;

            $data_product = array();


            if (isset($data['product_id']['ship_size'])) {

                $data_product['ship_size'] = $data['product_id']['ship_size'];
            }

            if (isset($data['product_id']['ship_material'])) {

                $data_product['ship_material'] = $data['product_id']['ship_material'];
            }

            if (isset($data['product_id']['ship_cat'])) {

                $data_product['ship_cat'] = $data['product_id']['ship_cat'];
            }

            if (isset($data['product_id']['flat_rate_packaging_fee'])) {

                $data_product['flat_rate_packaging_fee'] = $data['product_id']['flat_rate_packaging_fee'];
            }

            if (isset($data['product_id']['pick_up_location'])) {

                $data_product['pick_up_location'] = $this->option_repo->OptionOfId($data['product_id']['pick_up_location']);
            }

            if (isset($data['product_id']['pet_free'])) {

                $data_product['pet_free'] = $data['product_id']['pet_free'];
            }

            if (isset($data['note'])) {

                $data_product['note'] = $data['note'];
            }

            if (isset($data['price'])) {

                $data_product['price'] = $data['price'];
            }

            if (isset($data['tlv_price'])) {

                $data_product['tlv_price'] = $data['tlv_price'];
            }

//            if (isset($data['sort_description']))
//            {
//                $data_product['description'] = $data['sort_description'];
//            }

            if (isset($data['dimension_description'])) {

                $data_product['description'] = $data['dimension_description'];
            }

            if (isset($data['quantity'])) {

                $data_product['quantity'] = $data['quantity'];
            }

            if (isset($data['tlv_suggested_price_min'])) {

                $data_product['tlv_suggested_price_min'] = $data['tlv_suggested_price_min'];
            }

            if (isset($data['tlv_suggested_price_max'])) {

                $data_product['tlv_suggested_price_max'] = $data['tlv_suggested_price_max'];
            }

//            if (isset($data['pick_up_location']))
//            {
//                $data_product['pick_up_location'] = $this->option_repo->OptionOfId($value['pick_up_location']);
//            }

            if (isset($data['product_id']['name'])) {

                $data_product['name'] = $data['product_id']['name'];
            }

            foreach ($data['product_id']['cat'] as $x => $y) {

                if ($x != '') {

//                    if ($x == 'Condition')
//                    {
//                        $data_product['con'] = $this->sub_category_repo->SubCategoryOfId($y);
//                    }
//                    else

                    if ($x == 'Collection') {

                        $data_product['product_collection'] = [];

                        foreach ($y as $y_key => $y_value) {

                            $data_product['product_collection'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                        }
                    } else if ($x == 'Room') {

                        $data_product['product_room'] = [];

                        foreach ($y as $y_key => $y_value) {

                            $data_product['product_room'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                        }
                    } else if ($x == 'Color') {

                        $data_product['product_color'] = [];

                        foreach ($y as $y_key => $y_value) {

                            $data_product['product_color'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                        }
                    } else if ($x == 'Look') {

                        $data_product['product_look'] = [];

                        $data_product['product_look'][] = $this->sub_category_repo->SubCategoryOfId($y);

                        // foreach ($y as $y_key => $y_value) {

                        // $data_product['product_look'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                        // }
                    }

//                    else if ($x == 'Sub Category')
//                    {
//
//                        $data_product['category'] = $this->sub_category_repo->SubCategoryOfId($y);
//                    }
                    else if ($x == 'Sub Category') {

                        $data_product['product_category'] = [];

                        foreach ($y as $y_key => $y_value) {


                            $data_product['product_category'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                        }
                    } else if ($x == 'Condition') {

                        $data_product['product_con'] = [];

                        foreach ($y as $y_key => $y_value) {


                            $data_product['product_con'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                        }
                    } else if ($x == 'product_materials') {
                        $data_product['product_materials'] = [];
                        foreach ($y as $y_key => $y_value) {
                            $data_product['product_materials'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                        }
                    } else {

                        $data_product[strtolower($x)] = $this->sub_category_repo->SubCategoryOfId($y);
                    }
                }
            }

            if (isset($data['product_id']['seller_id'])) {

                $data_product['sellerid'] = $this->seller_repo->SellerOfId($data['product_id']['seller_id']);


                if ($data_product['sellerid']->getFirstname() != '' && $data_product['sellerid']->getLastname() != '') {

                    $data_product['sku'] = substr($data_product['sellerid']->getFirstname(), 0, 3) . substr($data_product['sellerid']->getLastname(), 0, 3);
                } else {

                    $data_product['sku'] = substr($data_product['sellerid']->getDisplayname(), 0, 3);
                }

                $sku_number = $data_product['sellerid']->getLastSku();

                $sku_number += 1;

                $seller_updated_sku['is_update_last_sku'] = 1;

                $seller_updated_sku['last_sku'] = $sku_number;

                $this->seller_repo->update($data_product['sellerid'], $seller_updated_sku);


                if ($sku_number < 100) {

                    if ($sku_number < 10) {

                        $sku_number = '00' . $sku_number;
                    } else {

                        $sku_number = '0' . $sku_number;
                    }
                }


//                $data_product['sku'] = $data_product['sku'] . $sku_number;

                $data_product['sku'] = $data_product['sku'] . $data_product['sellerid']->getWp_seller_id() . $sku_number;


//               $data_product['sellerid']= $data['product_id']['name'];
            }

            if (isset($data['images'])) {

                if (count($data['images']) > 0) {

                    foreach ($data['images'] as $key => $value) {


                        $data_product['product_pending_images'][] = $this->image_repo->ImageOfId($value);
                    }
                } else {

                    $data_product['product_pending_images'] = array();
                }
            }

//            if (isset($data['is_send_mail']))
//            {
//                if ($data['is_send_mail'] == 3)
//                {
//                    $data['is_archived'] = 1;
//                    unset($data['is_send_mail']);
//                }
//                else
//                {
//                    $data['is_send_mail'] = $data['is_send_mail'];
//                    //17 for pending
//                    $data['status_quot'] = $this->option_repo->OptionOfId(17);
//                }
//            }


            if (isset($data['product_id']['state'])) {

                $data_product['state'] = $data['product_id']['state'];
            }

            if (isset($data['product_id']['city'])) {

                $data_product['city'] = $data['product_id']['city'];
            }

            if (isset($data['product_id']['location'])) {

                $data_product['location'] = $data['product_id']['location'];
            }

            if (isset($data['product_id']['category_local'])) {

                $data_product['category_local'] = $data['product_id']['category_local'];
            }

            if (isset($data['product_id']['brand_local'])) {

                $data_product['brand_local'] = $data['product_id']['brand_local'];
            }

//            if (isset($data['product_id']['item_type_local']))
//            {
//                $data_product['item_type_local'] = $data['product_id']['item_type_local'];
//            }

            if (isset($data['product_id']['age'])) {

                $data_product['age'] = $this->sub_category_repo->SubCategoryOfId($data['product_id']['age']);
            }

            if (isset($data['product_id']['product_material'])) {

                $data_product['product_material'] = $this->sub_category_repo->SubCategoryOfId($data['product_id']['product_material']);
            }

            if (isset($data['product_id']['condition_local'])) {

                $data_product['condition_local'] = $data['product_id']['condition_local'];
            }

            //APPROVEED

            $data_product['status'] = $this->option_repo->OptionOfId(7);

            //SET approved_date

            $data_product['is_set_approved_date'] = 1;

            $product_obj = $this->product_repo->prepareData($data_product);

            $product_id = $this->product_repo->create($product_obj);


//            $introLines = array();
//            $introLines[0] = "A new product sell request has been added to the TLV Workflow.";
//            $introLines[1] = "Please review here: https://tlv-workflowapp.com/seller/product";
//            $myViewData = \View::make('emails.new_product_email', ['level' => 'success', 'outroLines' => [0 => ''], 'introLines' => $introLines])->render();
//            $option = $this->option_repo->OptionOfId(79);
//            if (app('App\Http\Controllers\EmailController')->sendMail($option->getValueText(), 'TLV Workflow: Product Sell Request ', $myViewData))
//            {
//
//            }

            $product_created = $this->product_repo->ProductOfId($product_id);

            $data2['product_id'] = $product_created;

            $data2['price'] = $data2['product_id']->getPrice();

            $data2['tlv_price'] = $data2['product_id']->getTLVPrice();

            $data2['quantity'] = $data2['product_id']->getQuantity();

            $data2['note'] = $data2['product_id']->getNote();

            $data2['images_from'] = 1;

            $data2['is_updated_details'] = 1;

            $data2['tlv_suggested_price_min'] = $data2['product_id']->getTlv_suggested_price_min();

            $data2['tlv_suggested_price_max'] = $data2['product_id']->getTlv_suggested_price_max();


            //07-08-2018 start

            if (isset($data['curator_commission'])) {

                $data2['curator_commission'] = $data['curator_commission'];
            }

            if (isset($data['curator_name'])) {

                $data2['curator_name'] = $data['curator_name'];
            }

            //07-08-2018 end


            if (isset($data['sort_description'])) {

                $data2['sort_description'] = $data['sort_description'];
            }

//            $data2['sort_description'] = $data2['product_id']->getDescription();

            $data2['dimension_description'] = $data2['product_id']->getDescription();


            if (isset($data['commission'])) {

                $data2['commission'] = $data['commission'];
            }

            if (isset($data['dimension_description'])) {

                $data2['dimension_description'] = $data['dimension_description'];
            }

            if (isset($data['condition_note'])) {

                $data2['condition_note'] = $data['condition_note'];
            }

            if (isset($data['delivery_option'])) {

                $data2['delivery_option'] = $data['delivery_option'];
            }

            if (isset($data['delivery_description'])) {

                $data2['delivery_description'] = $data['delivery_description'];
            }

            $production_quotation_prepared = $this->product_quotation_repo->prepareData($data2);

            $quote_created_obj = $this->product_quotation_repo->create($production_quotation_prepared);


            $new_data = [];

            $new_data['is_send_mail'] = 1;

//                $data['is_for_production_create_date'] = 1;

            $new_data['is_for_production_create_date'] = 'Yes';

            //17 for pending
//            $new_data['status_quot'] = $this->option_repo->OptionOfId(17);

            $this->product_quotation_repo->update($quote_created_obj, $new_data);

            if (count($data2['product_id']->getProductPendingImages()) > 0) {

                $data_product['images_from'] = 0;
            } else {

                $data_product['images_from'] = 1;
            }

//            $details2 = $this->product_repo->ProductOfId($data['product_id']);
//            $this->product_repo->update($details2, $data);
//            unset($data['product_id']);
//            if ($this->product_quotation_repo->update($product_quot, $data))
//            {

            return response()->json('Product Quotation Saved Successfully', 200);

//            }
        }
    }

    public function saveProductPricingFinal(Request $request)
    {

        $data = $request->all();


        if ($request->id) {

            $product_quot = $this->product_quotation_repo->ProductQuotationOfId($request->id);


            if (isset($data['product_id']['ship_size'])) {

                $data_product['ship_size'] = $data['product_id']['ship_size'];
            }

            if (isset($data['product_id']['ship_material'])) {

                $data_product['ship_material'] = $data['product_id']['ship_material'];
            }

            if (isset($data['product_id']['ship_cat'])) {

                $data_product['ship_cat'] = $data['product_id']['ship_cat'];
            }

            if (isset($data['product_id']['flat_rate_packaging_fee'])) {

                $data_product['flat_rate_packaging_fee'] = $data['product_id']['flat_rate_packaging_fee'];
            }


            $data_product['name'] = $data['product_id']['name'];

            if (isset($data['price'])) {

                $data_product['price'] = $data['price'];
            }

            if (isset($data['tlv_price'])) {

                $data_product['tlv_price'] = $data['tlv_price'];
            }

            $data_product['description'] = $data['product_id']['description'];

            $data_product['quantity'] = $data['quantity'];

            $data_product['note'] = $data['note'];

            $data_product['sku'] = $data['product_id']['sku'];


            if (isset($data['product_id']['state'])) {

                $data_product['state'] = $data['product_id']['state'];
            }

            if (isset($data['product_id']['city'])) {

                $data_product['city'] = $data['product_id']['city'];
            }

            if (isset($data['product_id']['ship_size'])) {

                $data_product['ship_size'] = $data['product_id']['ship_size'];
            }

            if (isset($data['product_id']['ship_material'])) {

                $data_product['ship_material'] = $data['product_id']['ship_material'];
            }

            if (isset($data['product_id']['local_pickup_available'])) {

                $data_product['local_pickup_available'] = $data['product_id']['local_pickup_available'];
            }

            if (isset($data['product_id']['location'])) {

                $data_product['location'] = $data['product_id']['location'];
            }

            if (isset($data['product_id']['category_local'])) {

                $data_product['category_local'] = $data['product_id']['category_local'];
            }

            if (isset($data['product_id']['brand_local'])) {

                $data_product['brand_local'] = $data['product_id']['brand_local'];
            }

//        if (isset($data['product_id']['item_type_local']))
//        {
//            $data_product['item_type_local'] = $data['product_id']['item_type_local'];
//        }

            if (isset($data['product_id']['age'])) {

                $data_product['age'] = $this->sub_category_repo->SubCategoryOfId($data['product_id']['age']);
            }

            if (isset($data['product_id']['pick_up_location'])) {

                $data_product['pick_up_location'] = $this->option_repo->OptionOfId($data['product_id']['pick_up_location']);
            }

            if (isset($data['product_id']['local_drop_off'])) {

                $data_product['local_drop_off'] = $data['product_id']['local_drop_off'];
            }
            if (isset($data['product_id']['local_drop_off_city'])) {
                if ($data['product_id']['local_drop_off'] == 1) {
                    $data_product['local_drop_off_city'] = $data['product_id']['local_drop_off_city'];
                } else {
                    $data_product['local_drop_off_city'] = NULL;
                }
            }

            if (isset($data['product_id']['pet_free'])) {

                $data_product['pet_free'] = $data['product_id']['pet_free'];
            }

            if (isset($data['product_id']['condition_local'])) {

                $data_product['condition_local'] = $data['product_id']['condition_local'];
            }


            $data_product['product_pending_images'] = array();

            if (isset($data['images'])) {

                if (count($data['images']) > 0) {

                    foreach ($data['images'] as $key => $value) {

                        $data_product['product_pending_images'][] = $this->image_repo->ImageOfId($value);
                    }
                } else {

                    $data_product['product_pending_images'] = array();
                }

                foreach ($data['product_id']['cat'] as $x => $y) {


                    if ($x != '') {

//                    if ($x == 'Condition')
//                    {
//                        $data_product['con'] = $this->sub_category_repo->SubCategoryOfId($y);
//                    }
//                    else

                        if ($x == 'Collection') {

                            $data_product['product_collection'] = [];

                            foreach ($y as $y_key => $y_value) {

                                $data_product['product_collection'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                            }
                        } else if ($x == 'Room') {

                            $data_product['product_room'] = [];

                            foreach ($y as $y_key => $y_value) {

                                $data_product['product_room'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                            }
                        } else if ($x == 'Color') {

                            $data_product['product_color'] = [];

                            foreach ($y as $y_key => $y_value) {

                                $data_product['product_color'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                            }
                        } else if ($x == 'Look') {

                            $data_product['product_look'] = [];

                            foreach ($y as $y_key => $y_value) {

                                $data_product['product_look'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                            }
                        }

//                    else if ($x == 'Sub Category')
//                    {
//
//
//                        $data_product['category'] = $this->sub_category_repo->SubCategoryOfId($y);
//                    }
                        else if ($x == 'Sub Category') {

                            $data_product['product_category'] = [];

                            foreach ($y as $y_key => $y_value) {

                                $data_product['product_category'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                            }
                        } else if ($x == 'Condition') {

                            $data_product['product_con'] = [];

                            foreach ($y as $y_key => $y_value) {

                                $data_product['product_con'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                            }
                        } else if ($x == 'product_materials') {
                            $data_product['product_materials'] = [];
                            foreach ($y as $y_key => $y_value) {
                                $data_product['product_materials'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                            }
                        } else {


                            $data_product[strtolower($x)] = $this->sub_category_repo->SubCategoryOfId($y);
                        }
                    }
                }
            }

            $this->product_repo->update($product_quot->getProductId(), $data_product);


            if (isset($request->passfrom)) {

                if ($request->passfrom == 'product_final') {

                    if (isset($request->product_final_status_id)) {

                        if ($request->product_final_status_id == 'archived') {

                            $data['is_archived'] = 1;

//                $this->product_quotation_repo->update($product_quot, $data_t);
                        } else {

                            $data['status_quot'] = $this->option_repo->OptionOfId($request->product_final_status_id);

                            unset($data['product_id']);

                            $this->product_quotation_repo->update($product_quot, $data);


                            if ($data['status_quot']->getId() == 18) {

                                $product_quot_new = array();

                                $product_quot_new['data'] = $this->product_quotation_repo->getProductQuotationById($request->id);

//                            $product_quot_new['data'] = $this->product_quotation_repo->getProductQuotationById($value['product_quotation_id']);

                                $product_quot_new['data']['product_id']['city'] = '';

                                $product_quot_new['data']['product_id']['state'] = '';

                                if (isset($product_quot_new['data']['product_id']['pick_up_location'])) {


                                    if (isset($product_quot_new['data']['product_id']['pick_up_location']['key_text'])) {

                                        $details_location = json_decode($product_quot_new['data']['product_id']['pick_up_location']['key_text']);

                                        if (count($details_location) > 0) {

                                            if (isset($details_location[0]->city)) {

                                                $product_quot_new['data']['product_id']['city'] = $details_location[0]->city;
                                            }

                                            if (isset($details_location[0]->state)) {

                                                $product_quot_new['data']['product_id']['state'] = $details_location[0]->state;
                                            }
                                        }
                                    }
                                }


                                if (isset($product_quot_new['data']['delivery_option']) && $product_quot_new['data']['delivery_option'] != '') {


                                    if ($product_quot_new['data']['delivery_description'] != '') {

                                        $product_quot_new['data']['delivery_description'] = $product_quot_new['data']['delivery_option'] . ', ' . $product_quot_new['data']['delivery_description'];
                                    } else {

                                        $product_quot_new['data']['delivery_description'] = $product_quot_new['data']['delivery_option'];
                                    }


//                                $product_quot_new['data']['delivery_description'] = $product_quot_new['data']['delivery_option'] . ', ' . $product_quot_new['data']['delivery_description'];
                                }


//echo "<pre>";
//print_r($product_quot_new['data']);
//die;

                                $qid = $product_quot_new['data']['id'];

//        print_r($qid);

                                $product_quot_new['data'] = json_encode($product_quot_new);


//                            $data = array('name' => 'Ross', 'php_master' => true);
//                                $host = 'https://localvault.staging.wpengine.com/wp-content/themes/thelocalvault/new-product-temp.php';
                                $host = env('WP_URL') . '/wp-content/themes/thelocalvault/new-product-temp.php';

                                $ch = curl_init();

                                curl_setopt($ch, CURLOPT_URL, $host);

                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                                curl_setopt($ch, CURLOPT_POST, true);

                                curl_setopt($ch, CURLOPT_POSTFIELDS, $product_quot_new);

                                //temp_stop

                                $temp = curl_exec($ch);


                                if ($temp) {

//                                $this->product_quotation_repo->update($product_quot, $data);

                                    $this->saveWPId($qid, $temp);
                                } else {

                                }
                            }
                        }
                    }
                } else if ($request->passfrom == 'product_for_production') {

                    if (isset($request->product_for_production_status_id)) {

                        if ($data['product_for_production_status_id'] == 3) {

                            $data['is_archived'] = 1;
                        } else {

                            $data['is_product_for_production'] = $request['product_for_production_status_id'];

                            $data['is_copyright_create_date'] = 1;
                        }
                    }
                } else if ($request->passfrom == 'copyright') {

                    if (isset($request->copyright_status_id)) {

                        if ($request['copyright_status_id'] == 3) {

                            $data['is_archived'] = 1;
                        } else {

                            $data['is_copyright'] = $data['copyright_status_id'];

                            $data['is_approved_create_date'] = 1;
                        }
                    }
                }
            }


            unset($data['product_id']);

            if ($this->product_quotation_repo->update($product_quot, $data)) {

                return response()->json('Product Updated Successfully', 200);
            }
        } else {


            //           $product_quot = $this->product_quotation_repo->ProductQuotationOfId($request->id);

            $data2['is_updated_details'] = 1;


            $data_product = array();


            if (isset($data['product_id']['ship_size'])) {

                $data_product['ship_size'] = $data['product_id']['ship_size'];
            }

            if (isset($data['product_id']['ship_material'])) {

                $data_product['ship_material'] = $data['product_id']['ship_material'];
            }

            if (isset($data['product_id']['ship_cat'])) {

                $data_product['ship_cat'] = $data['product_id']['ship_cat'];
            }

            if (isset($data['product_id']['flat_rate_packaging_fee'])) {

                $data_product['flat_rate_packaging_fee'] = $data['product_id']['flat_rate_packaging_fee'];
            }


            if (isset($data['product_id']['pick_up_location'])) {

                $data_product['pick_up_location'] = $this->option_repo->OptionOfId($data['product_id']['pick_up_location']);
            }

            if (isset($data['product_id']['pet_free'])) {

                $data_product['pet_free'] = $data['product_id']['pet_free'];
            }

            if (isset($data['note'])) {

                $data_product['note'] = $data['note'];
            }

            if (isset($data['price'])) {

                $data_product['price'] = $data['price'];
            }

            if (isset($data['tlv_price'])) {

                $data_product['tlv_price'] = $data['tlv_price'];
            }

//            if (isset($data['sort_description']))
//            {
//                $data_product['description'] = $data['sort_description'];
//            }

            if (isset($data['dimension_description'])) {

                $data_product['description'] = $data['dimension_description'];
            }

            if (isset($data['quantity'])) {

                $data_product['quantity'] = $data['quantity'];
            }

            if (isset($data['tlv_suggested_price_min'])) {

                $data_product['tlv_suggested_price_min'] = $data['tlv_suggested_price_min'];
            }

            if (isset($data['tlv_suggested_price_max'])) {

                $data_product['tlv_suggested_price_max'] = $data['tlv_suggested_price_max'];
            }

//            if (isset($data['pick_up_location']))
//            {
//                $data_product['pick_up_location'] = $this->option_repo->OptionOfId($value['pick_up_location']);
//            }

            if (isset($data['product_id']['name'])) {

                $data_product['name'] = $data['product_id']['name'];
            }

            foreach ($data['product_id']['cat'] as $x => $y) {

                if ($x != '') {

//                    if ($x == 'Condition')
//                    {
//                        $data_product['con'] = $this->sub_category_repo->SubCategoryOfId($y);
//                    }
//                    else

                    if ($x == 'Collection') {

                        $data_product['product_collection'] = [];

                        foreach ($y as $y_key => $y_value) {

                            $data_product['product_collection'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                        }
                    } else if ($x == 'Room') {

                        $data_product['product_room'] = [];

                        foreach ($y as $y_key => $y_value) {

                            $data_product['product_room'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                        }
                    } else if ($x == 'Color') {

                        $data_product['product_color'] = [];

                        foreach ($y as $y_key => $y_value) {

                            $data_product['product_color'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                        }
                    } else if ($x == 'Look') {

                        $data_product['product_look'] = [];

                        foreach ($y as $y_key => $y_value) {

                            $data_product['product_look'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                        }
                    }

//                    else if ($x == 'Sub Category')
//                    {
//
//                        $data_product['category'] = $this->sub_category_repo->SubCategoryOfId($y);
//                    }
                    else if ($x == 'Sub Category') {

                        $data_product['product_category'] = [];

                        foreach ($y as $y_key => $y_value) {


                            $data_product['product_category'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                        }
                    } else if ($x == 'Condition') {

                        $data_product['product_con'] = [];

                        foreach ($y as $y_key => $y_value) {


                            $data_product['product_con'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                        }
                    } else if ($x == 'product_materials') {
                        $data_product['product_materials'] = [];
                        foreach ($y as $y_key => $y_value) {
                            $data_product['product_materials'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                        }
                    } else {

                        $data_product[strtolower($x)] = $this->sub_category_repo->SubCategoryOfId($y);
                    }
                }
            }

            if (isset($data['product_id']['seller_id'])) {

                $data_product['sellerid'] = $this->seller_repo->SellerOfId($data['product_id']['seller_id']);


                if ($data_product['sellerid']->getFirstname() != '' && $data_product['sellerid']->getLastname() != '') {

                    $data_product['sku'] = substr($data_product['sellerid']->getFirstname(), 0, 3) . substr($data_product['sellerid']->getLastname(), 0, 3);
                } else {

                    $data_product['sku'] = substr($data_product['sellerid']->getDisplayname(), 0, 3);
                }

                $sku_number = $data_product['sellerid']->getLastSku();

                $sku_number += 1;

                $seller_updated_sku['is_update_last_sku'] = 1;

                $seller_updated_sku['last_sku'] = $sku_number;

                $this->seller_repo->update($data_product['sellerid'], $seller_updated_sku);


                if ($sku_number < 100) {

                    if ($sku_number < 10) {

                        $sku_number = '00' . $sku_number;
                    } else {

                        $sku_number = '0' . $sku_number;
                    }
                }


//                $data_product['sku'] = $data_product['sku'] . $sku_number;

                $data_product['sku'] = $data_product['sku'] . $data_product['sellerid']->getWp_seller_id() . $sku_number;


//               $data_product['sellerid']= $data['product_id']['name'];
            }

            if (isset($data['images'])) {

                if (count($data['images']) > 0) {

                    foreach ($data['images'] as $key => $value) {


                        $data_product['product_pending_images'][] = $this->image_repo->ImageOfId($value);
                    }
                } else {

                    $data_product['product_pending_images'] = array();
                }
            }

//            if (isset($data['is_send_mail']))
//            {
//                if ($data['is_send_mail'] == 3)
//                {
//                    $data['is_archived'] = 1;
//                    unset($data['is_send_mail']);
//                }
//                else
//                {
//                    $data['is_send_mail'] = $data['is_send_mail'];
//                    //17 for pending
//                    $data['status_quot'] = $this->option_repo->OptionOfId(17);
//                }
//            }


            if (isset($data['product_id']['state'])) {

                $data_product['state'] = $data['product_id']['state'];
            }

            if (isset($data['product_id']['city'])) {

                $data_product['city'] = $data['product_id']['city'];
            }

            if (isset($data['product_id']['location'])) {

                $data_product['location'] = $data['product_id']['location'];
            }

            if (isset($data['product_id']['category_local'])) {

                $data_product['category_local'] = $data['product_id']['category_local'];
            }

            if (isset($data['product_id']['brand_local'])) {

                $data_product['brand_local'] = $data['product_id']['brand_local'];
            }

//            if (isset($data['product_id']['item_type_local']))
//            {
//                $data_product['item_type_local'] = $data['product_id']['item_type_local'];
//            }

            if (isset($data['product_id']['age'])) {

                $data_product['age'] = $this->sub_category_repo->SubCategoryOfId($data['product_id']['age']);
            }

            if (isset($data['product_id']['condition_local'])) {

                $data_product['condition_local'] = $data['product_id']['condition_local'];
            }

            //APPROVEED

            $data_product['status'] = $this->option_repo->OptionOfId(7);

            //SET approved_date

            $data_product['is_set_approved_date'] = 1;

            $product_obj = $this->product_repo->prepareData($data_product);

            $product_id = $this->product_repo->create($product_obj);


//            $introLines = array();
//            $introLines[0] = "A new product sell request has been added to the TLV Workflow.";
//            $introLines[1] = "Please review here: https://tlv-workflowapp.com/seller/product";
//            $myViewData = \View::make('emails.new_product_email', ['level' => 'success', 'outroLines' => [0 => ''], 'introLines' => $introLines])->render();
//            $option = $this->option_repo->OptionOfId(79);
//            if (app('App\Http\Controllers\EmailController')->sendMail($option->getValueText(), 'TLV Workflow: Product Sell Request ', $myViewData))
//            {
//
//            }


            $product_created = $this->product_repo->ProductOfId($product_id);


            $data2['product_id'] = $product_created;

            $data2['price'] = $data2['product_id']->getPrice();

            $data2['tlv_price'] = $data2['product_id']->getTLVPrice();

            if (isset($data['storage_pricing'])) {
                $data2['storage_pricing'] = $data['storage_pricing'];
            }

            $data2['quantity'] = $data2['product_id']->getQuantity();

            $data2['note'] = $data2['product_id']->getNote();

            $data2['images_from'] = 1;

            $data2['is_updated_details'] = 1;

            $data2['tlv_suggested_price_min'] = $data2['product_id']->getTlv_suggested_price_min();

            $data2['tlv_suggested_price_max'] = $data2['product_id']->getTlv_suggested_price_max();


            //07-08-2018 start

            if (isset($data['curator_commission'])) {

                $data2['curator_commission'] = $data['curator_commission'];
            }

            if (isset($data['curator_name'])) {

                $data2['curator_name'] = $data['curator_name'];
            }

            //07-08-2018 end


            if (isset($data['sort_description'])) {

                $data2['sort_description'] = $data['sort_description'];
            }

//            $data2['sort_description'] = $data2['product_id']->getDescription();

            $data2['dimension_description'] = $data2['product_id']->getDescription();


            if (isset($data['commission'])) {

                $data2['commission'] = $data['commission'];
            }

            if (isset($data['dimension_description'])) {

                $data2['dimension_description'] = $data['dimension_description'];
            }

            if (isset($data['condition_note'])) {

                $data2['condition_note'] = $data['condition_note'];
            }

            if (isset($data['delivery_option'])) {

                $data2['delivery_option'] = $data['delivery_option'];
            }

            if (isset($data['delivery_description'])) {

                $data2['delivery_description'] = $data['delivery_description'];
            }

            if (isset($data['seller_to_drop_off'])) {

                $data2['seller_to_drop_off'] = $data['seller_to_drop_off'];
            }

            if (isset($data['shipping_calculator'])) {

                $data2['shipping_calculator'] = $data['shipping_calculator'];
            }


            $production_quotation_prepared = $this->product_quotation_repo->prepareData($data2);

            $quote_created_obj = $this->product_quotation_repo->create($production_quotation_prepared);


            $new_data = [];

            $new_data['is_send_mail'] = 1;

//                $data['is_for_production_create_date'] = 1;

            $new_data['is_proposal_for_production'] = 1;
            $new_data['for_proposal_for_production_created_at'] = 1;
            $new_data['is_awaiting_contract'] = 1;
            $new_data['for_awaiting_contract_created_at'] = 1;


            //17 for pending

            $new_data['status_quot'] = $this->option_repo->OptionOfId(17);


            $this->product_quotation_repo->update($quote_created_obj, $new_data);


            if (count($data2['product_id']->getProductPendingImages()) > 0) {

                $data_product['images_from'] = 0;
            } else {

                $data_product['images_from'] = 1;
            }

//            $details2 = $this->product_repo->ProductOfId($data['product_id']);
//            $this->product_repo->update($details2, $data);
//            unset($data['product_id']);
//            if ($this->product_quotation_repo->update($product_quot, $data))
//            {

            return response()->json('Product Quotation Saved Successfully', 200);

//            }
        }
    }

    public function saveProductQuotationFinal(Request $request)
    {

        $data = $request->all();

        if ($request->id) {

            $product_quot = $this->product_quotation_repo->ProductQuotationOfId($request->id);

            if (isset($data['product_id']['ship_size'])) {

                $data_product['ship_size'] = $data['product_id']['ship_size'];
            }

            if (isset($data['product_id']['ship_material'])) {

                $data_product['ship_material'] = $data['product_id']['ship_material'];
            }

            if (isset($data['product_id']['ship_cat'])) {

                $data_product['ship_cat'] = $data['product_id']['ship_cat'];
            }

            if (isset($data['product_id']['flat_rate_packaging_fee'])) {

                $data_product['flat_rate_packaging_fee'] = $data['product_id']['flat_rate_packaging_fee'];
            }


            $data_product['name'] = $data['product_id']['name'];

            if (isset($data['price'])) {

                $data_product['price'] = $data['price'];
            }

            if (isset($data['tlv_price'])) {

                $data_product['tlv_price'] = $data['tlv_price'];
            }

            $data_product['description'] = $data['product_id']['description'];

            $data_product['quantity'] = $data['quantity'];

            $data_product['note'] = $data['note'];

            $data_product['sku'] = $data['product_id']['sku'];


            if (isset($data['product_id']['state'])) {

                $data_product['state'] = $data['product_id']['state'];
            }

            if (isset($data['product_id']['city'])) {

                $data_product['city'] = $data['product_id']['city'];
            }

            if (isset($data['product_id']['ship_size'])) {

                $data_product['ship_size'] = $data['product_id']['ship_size'];
            }

            if (isset($data['product_id']['ship_material'])) {

                $data_product['ship_material'] = $data['product_id']['ship_material'];
            }

            if (isset($data['product_id']['local_pickup_available'])) {

                $data_product['local_pickup_available'] = $data['product_id']['local_pickup_available'];
            }

            if (isset($data['product_id']['location'])) {

                $data_product['location'] = $data['product_id']['location'];
            }

            if (isset($data['product_id']['category_local'])) {

                $data_product['category_local'] = $data['product_id']['category_local'];
            }

            if (isset($data['product_id']['brand_local'])) {

                $data_product['brand_local'] = $data['product_id']['brand_local'];
            }

            if (isset($data['product_id']['age'])) {

                $data_product['age'] = $this->sub_category_repo->SubCategoryOfId($data['product_id']['age']);
            }

            if (isset($data['product_id']['pick_up_location'])) {

                $data_product['pick_up_location'] = $this->option_repo->OptionOfId($data['product_id']['pick_up_location']);
            }

            if (isset($data['product_id']['local_drop_off'])) {

                $data_product['local_drop_off'] = $data['product_id']['local_drop_off'];
            }
            if (isset($data['product_id']['local_drop_off_city'])) {
                if ($data['product_id']['local_drop_off'] == 1) {
                    $data_product['local_drop_off_city'] = $data['product_id']['local_drop_off_city'];
                } else {
                    $data_product['local_drop_off_city'] = NULL;
                }
            }

            if (isset($data['product_id']['pet_free'])) {

                $data_product['pet_free'] = $data['product_id']['pet_free'];
            }

            if (isset($data['product_id']['condition_local'])) {

                $data_product['condition_local'] = $data['product_id']['condition_local'];
            }

            $data_product['product_pending_images'] = array();

            if (isset($data['images'])) {

                if (count($data['images']) > 0) {

                    foreach ($data['images'] as $key => $value) {

                        $data_product['product_pending_images'][] = $this->image_repo->ImageOfId($value);
                    }
                } else {

                    $data_product['product_pending_images'] = array();
                }

                foreach ($data['product_id']['cat'] as $x => $y) {


                    if ($x != '') {

//                    if ($x == 'Condition')
//                    {
//                        $data_product['con'] = $this->sub_category_repo->SubCategoryOfId($y);
//                    }
//                    else

                        if ($x == 'Collection') {

                            $data_product['product_collection'] = [];

                            foreach ($y as $y_key => $y_value) {

                                $data_product['product_collection'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                            }
                        } else if ($x == 'Room') {

                            $data_product['product_room'] = [];

                            foreach ($y as $y_key => $y_value) {

                                $data_product['product_room'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                            }
                        } else if ($x == 'Color') {

                            $data_product['product_color'] = [];

                            foreach ($y as $y_key => $y_value) {

                                $data_product['product_color'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                            }
                        } else if ($x == 'Look') {

                            $data_product['product_look'] = [];

                            foreach ($y as $y_key => $y_value) {

                                $data_product['product_look'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                            }
                        }

//                    else if ($x == 'Sub Category')
//                    {
//
//
//                        $data_product['category'] = $this->sub_category_repo->SubCategoryOfId($y);
//                    }
                        else if ($x == 'Sub Category') {

                            $data_product['product_category'] = [];

                            foreach ($y as $y_key => $y_value) {

                                $data_product['product_category'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                            }
                        } else if ($x == 'Condition') {

                            $data_product['product_con'] = [];

                            foreach ($y as $y_key => $y_value) {

                                $data_product['product_con'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                            }
                        } else if ($x == 'product_materials') {
                            $data_product['product_materials'] = [];
                            foreach ($y as $y_key => $y_value) {
                                $data_product['product_materials'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                            }
                        } else {


                            $data_product[strtolower($x)] = $this->sub_category_repo->SubCategoryOfId($y);
                        }
                    }
                }
            }

            $this->product_repo->update($product_quot->getProductId(), $data_product);

            if (isset($request->passfrom)) {

                if ($request->passfrom == 'product_final') {

                    if (isset($request->product_final_status_id)) {

                        if ($request->product_final_status_id == 'archived') {

                            $data['is_archived'] = 1;

//                $this->product_quotation_repo->update($product_quot, $data_t);
                        } else {

                            $data['status_quot'] = $this->option_repo->OptionOfId($request->product_final_status_id);

                            unset($data['product_id']);

                            $this->product_quotation_repo->update($product_quot, $data);


                            if ($data['status_quot']->getId() == 18) {

                                $product_quot_new = array();

                                $product_quot_new['data'] = $this->product_quotation_repo->getProductQuotationById($request->id);

//                            $product_quot_new['data'] = $this->product_quotation_repo->getProductQuotationById($value['product_quotation_id']);

                                $product_quot_new['data']['product_id']['city'] = '';

                                $product_quot_new['data']['product_id']['state'] = '';

                                if (isset($product_quot_new['data']['product_id']['pick_up_location'])) {


                                    if (isset($product_quot_new['data']['product_id']['pick_up_location']['key_text'])) {

                                        $details_location = json_decode($product_quot_new['data']['product_id']['pick_up_location']['key_text']);

                                        if (count($details_location) > 0) {

                                            if (isset($details_location[0]->city)) {

                                                $product_quot_new['data']['product_id']['city'] = $details_location[0]->city;
                                            }

                                            if (isset($details_location[0]->state)) {

                                                $product_quot_new['data']['product_id']['state'] = $details_location[0]->state;
                                            }
                                        }
                                    }
                                }


                                if (isset($product_quot_new['data']['delivery_option']) && $product_quot_new['data']['delivery_option'] != '') {


                                    if ($product_quot_new['data']['delivery_description'] != '') {

                                        $product_quot_new['data']['delivery_description'] = $product_quot_new['data']['delivery_option'] . ', ' . $product_quot_new['data']['delivery_description'];
                                    } else {

                                        $product_quot_new['data']['delivery_description'] = $product_quot_new['data']['delivery_option'];
                                    }


//                                $product_quot_new['data']['delivery_description'] = $product_quot_new['data']['delivery_option'] . ', ' . $product_quot_new['data']['delivery_description'];
                                }


//echo "<pre>";
//print_r($product_quot_new['data']);
//die;

                                $qid = $product_quot_new['data']['id'];

//        print_r($qid);

                                $product_quot_new['data'] = json_encode($product_quot_new);


//                            $data = array('name' => 'Ross', 'php_master' => true);
//                                $host = 'https://localvault.staging.wpengine.com/wp-content/themes/thelocalvault/new-product-temp.php';
                                $host = env('WP_URL') . '/wp-content/themes/thelocalvault/new-product-temp.php';

                                $ch = curl_init();

                                curl_setopt($ch, CURLOPT_URL, $host);

                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                                curl_setopt($ch, CURLOPT_POST, true);

                                curl_setopt($ch, CURLOPT_POSTFIELDS, $product_quot_new);

                                //temp_stop

                                $temp = curl_exec($ch);


                                if ($temp) {

//                                $this->product_quotation_repo->update($product_quot, $data);

                                    $this->saveWPId($qid, $temp);
                                } else {

                                }
                            }
                        }
                    }
                } else if ($request->passfrom == 'product_for_production') {

                    if (isset($request->product_for_production_status_id)) {

                        if ($data['product_for_production_status_id'] == 3) {

                            $data['is_archived'] = 1;
                        } else {

                            $data['is_product_for_production'] = $request['product_for_production_status_id'];

                            $data['is_copyright_create_date'] = 1;
                        }
                    }
                } else if ($request->passfrom == 'copyright') {

                    if (isset($request->copyright_status_id)) {

                        if ($request['copyright_status_id'] == 3) {

                            $data['is_archived'] = 1;
                        } else {

                            $data['is_copyright'] = $data['copyright_status_id'];

                            $data['is_approved_create_date'] = 1;
                        }
                    }
                }
            }
            unset($data['product_id']);

            if (isset($data['assign_agent_id']) && !is_array($data['assign_agent_id'])) {
                $data['assign_agent_id'] = $this->user_repo->UserOfId($data['assign_agent_id']);
            }

            if ($this->product_quotation_repo->update($product_quot, $data)) {

                return response()->json('Product Updated Successfully', 200);
            }
        } else {


            //           $product_quot = $this->product_quotation_repo->ProductQuotationOfId($request->id);

            $data2['is_updated_details'] = 1;

            $data_product = array();

            if (isset($data['product_id']['ship_size'])) {

                $data_product['ship_size'] = $data['product_id']['ship_size'];
            }

            if (isset($data['product_id']['ship_material'])) {

                $data_product['ship_material'] = $data['product_id']['ship_material'];
            }

            if (isset($data['product_id']['ship_cat'])) {

                $data_product['ship_cat'] = $data['product_id']['ship_cat'];
            }

            if (isset($data['product_id']['flat_rate_packaging_fee'])) {

                $data_product['flat_rate_packaging_fee'] = $data['product_id']['flat_rate_packaging_fee'];
            }

            if (isset($data['product_id']['pick_up_location'])) {

                $data_product['pick_up_location'] = $this->option_repo->OptionOfId($data['product_id']['pick_up_location']);
            }

            if (isset($data['product_id']['pet_free'])) {

                $data_product['pet_free'] = $data['product_id']['pet_free'];
            }

            if (isset($data['note'])) {

                $data_product['note'] = $data['note'];
            }

            if (isset($data['price'])) {

                $data_product['price'] = $data['price'];
            }

            if (isset($data['tlv_price'])) {

                $data_product['tlv_price'] = $data['tlv_price'];
            }

            if (isset($data['dimension_description'])) {

                $data_product['description'] = $data['dimension_description'];
            }

            if (isset($data['quantity'])) {

                $data_product['quantity'] = $data['quantity'];
            }

            if (isset($data['tlv_suggested_price_min'])) {

                $data_product['tlv_suggested_price_min'] = $data['tlv_suggested_price_min'];
            }

            if (isset($data['tlv_suggested_price_max'])) {

                $data_product['tlv_suggested_price_max'] = $data['tlv_suggested_price_max'];
            }

            if (isset($data['product_id']['name'])) {

                $data_product['name'] = $data['product_id']['name'];
            }

            foreach ($data['product_id']['cat'] as $x => $y) {

                if ($x != '') {

//                    if ($x == 'Condition')
//                    {
//                        $data_product['con'] = $this->sub_category_repo->SubCategoryOfId($y);
//                    }
//                    else

                    if ($x == 'Collection') {

                        $data_product['product_collection'] = [];

                        foreach ($y as $y_key => $y_value) {

                            $data_product['product_collection'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                        }
                    } else if ($x == 'Room') {

                        $data_product['product_room'] = [];

                        foreach ($y as $y_key => $y_value) {

                            $data_product['product_room'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                        }
                    } else if ($x == 'Color') {

                        $data_product['product_color'] = [];

                        foreach ($y as $y_key => $y_value) {

                            $data_product['product_color'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                        }
                    } else if ($x == 'Look') {

                        $data_product['product_look'] = [];

                        foreach ($y as $y_key => $y_value) {

                            $data_product['product_look'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                        }
                    }

//                    else if ($x == 'Sub Category')
//                    {
//
//                        $data_product['category'] = $this->sub_category_repo->SubCategoryOfId($y);
//                    }
                    else if ($x == 'Sub Category') {

                        $data_product['product_category'] = [];

                        foreach ($y as $y_key => $y_value) {


                            $data_product['product_category'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                        }
                    } else if ($x == 'Condition') {

                        $data_product['product_con'] = [];

                        foreach ($y as $y_key => $y_value) {


                            $data_product['product_con'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                        }
                    } else if ($x == 'product_materials') {
                        $data_product['product_materials'] = [];
                        foreach ($y as $y_key => $y_value) {
                            $data_product['product_materials'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                        }
                    } else {

                        $data_product[strtolower($x)] = $this->sub_category_repo->SubCategoryOfId($y);
                    }
                }
            }

            if (isset($data['product_id']['seller_id'])) {

                $data_product['sellerid'] = $this->seller_repo->SellerOfId($data['product_id']['seller_id']);


                if ($data_product['sellerid']->getFirstname() != '' && $data_product['sellerid']->getLastname() != '') {

                    $data_product['sku'] = substr($data_product['sellerid']->getFirstname(), 0, 3) . substr($data_product['sellerid']->getLastname(), 0, 3);
                } else {

                    $data_product['sku'] = substr($data_product['sellerid']->getDisplayname(), 0, 3);
                }

                $sku_number = $data_product['sellerid']->getLastSku();

                $sku_number += 1;

                $seller_updated_sku['is_update_last_sku'] = 1;

                $seller_updated_sku['last_sku'] = $sku_number;

                $this->seller_repo->update($data_product['sellerid'], $seller_updated_sku);


                if ($sku_number < 100) {

                    if ($sku_number < 10) {

                        $sku_number = '00' . $sku_number;
                    } else {

                        $sku_number = '0' . $sku_number;
                    }
                }


//                $data_product['sku'] = $data_product['sku'] . $sku_number;

                $data_product['sku'] = $data_product['sku'] . $data_product['sellerid']->getWp_seller_id() . $sku_number;


//               $data_product['sellerid']= $data['product_id']['name'];
            }

            if (isset($data['images'])) {

                if (count($data['images']) > 0) {

                    foreach ($data['images'] as $key => $value) {


                        $data_product['product_pending_images'][] = $this->image_repo->ImageOfId($value);
                    }
                } else {

                    $data_product['product_pending_images'] = array();
                }
            }

            if (isset($data['product_id']['state'])) {

                $data_product['state'] = $data['product_id']['state'];
            }

            if (isset($data['product_id']['city'])) {

                $data_product['city'] = $data['product_id']['city'];
            }

            if (isset($data['product_id']['location'])) {

                $data_product['location'] = $data['product_id']['location'];
            }

            if (isset($data['product_id']['category_local'])) {

                $data_product['category_local'] = $data['product_id']['category_local'];
            }

            if (isset($data['product_id']['brand_local'])) {

                $data_product['brand_local'] = $data['product_id']['brand_local'];
            }

            if (isset($data['product_id']['age'])) {

                $data_product['age'] = $this->sub_category_repo->SubCategoryOfId($data['product_id']['age']);
            }

            if (isset($data['product_id']['condition_local'])) {

                $data_product['condition_local'] = $data['product_id']['condition_local'];
            }


            //APPROVEED

            $data_product['status'] = $this->option_repo->OptionOfId(7);

            //SET approved_date

            $data_product['is_set_approved_date'] = 1;

            $product_obj = $this->product_repo->prepareData($data_product);

            $product_id = $this->product_repo->create($product_obj);


//            $introLines = array();
//            $introLines[0] = "A new product sell request has been added to the TLV Workflow.";
//            $introLines[1] = "Please review here: https://tlv-workflowapp.com/seller/product";
//            $myViewData = \View::make('emails.new_product_email', ['level' => 'success', 'outroLines' => [0 => ''], 'introLines' => $introLines])->render();
//            $option = $this->option_repo->OptionOfId(79);
//            if (app('App\Http\Controllers\EmailController')->sendMail($option->getValueText(), 'TLV Workflow: Product Sell Request ', $myViewData))
//            {
//
//            }

            $product_created = $this->product_repo->ProductOfId($product_id);


            $data2['product_id'] = $product_created;

            $data2['price'] = $data2['product_id']->getPrice();

            $data2['tlv_price'] = $data2['product_id']->getTLVPrice();

            $data2['quantity'] = $data2['product_id']->getQuantity();

            $data2['note'] = $data2['product_id']->getNote();

            $data2['images_from'] = 1;

            $data2['is_updated_details'] = 1;

            $data2['tlv_suggested_price_min'] = $data2['product_id']->getTlv_suggested_price_min();

            $data2['tlv_suggested_price_max'] = $data2['product_id']->getTlv_suggested_price_max();

            if (isset($data['assign_agent_id']) && !is_array($data['assign_agent_id'])) {
                $data2['assign_agent_id'] = $this->user_repo->UserOfId($data['assign_agent_id']);
            }


            //07-08-2018 start

            if (isset($data['curator_commission'])) {

                $data2['curator_commission'] = $data['curator_commission'];
            }

            if (isset($data['curator_name'])) {

                $data2['curator_name'] = $data['curator_name'];
            }

            //07-08-2018 end


            if (isset($data['sort_description'])) {

                $data2['sort_description'] = $data['sort_description'];
            }

//            $data2['sort_description'] = $data2['product_id']->getDescription();

            $data2['dimension_description'] = $data2['product_id']->getDescription();

            $data2['is_product_for_pricing'] = 0;
            $data2['for_pricing_created_at'] = 1;

            if (isset($data['commission'])) {

                $data2['commission'] = $data['commission'];
            }

            if (isset($data['dimension_description'])) {

                $data2['dimension_description'] = $data['dimension_description'];
            }

            if (isset($data['condition_note'])) {

                $data2['condition_note'] = $data['condition_note'];
            }

            if (isset($data['delivery_option'])) {

                $data2['delivery_option'] = $data['delivery_option'];
            }

            if (isset($data['delivery_description'])) {

                $data2['delivery_description'] = $data['delivery_description'];
            }

            if (isset($data['units'])) {

                $data2['units'] = $data['units'];
            }

            if (isset($data['width'])) {

                $data2['width'] = $data['width'];
            }

            if (isset($data['depth'])) {

                $data2['depth'] = $data['depth'];
            }

            if (isset($data['height'])) {

                $data2['height'] = $data['height'];
            }

            if (isset($data['seat_height'])) {

                $data2['seat_height'] = $data['seat_height'];
            }

            if (isset($data['arm_height'])) {

                $data2['arm_height'] = $data['arm_height'];
            }

            if (isset($data['seller_to_drop_off'])) {

                $data2['seller_to_drop_off'] = $data['seller_to_drop_off'];
            }

            if (isset($data['shipping_calculator'])) {

                $data2['shipping_calculator'] = $data['shipping_calculator'];
            }


            $production_quotation_prepared = $this->product_quotation_repo->prepareData($data2);

            $quote_created_obj = $this->product_quotation_repo->create($production_quotation_prepared);


            $new_data = [];

            $new_data['is_send_mail'] = 1;

            $new_data['is_awaiting_contract'] = 1;

            $new_data['for_awaiting_contract_created_at'] = 1;

            //17 for pending

            $new_data['status_quot'] = $this->option_repo->OptionOfId(17);


            $this->product_quotation_repo->update($quote_created_obj, $new_data);


            if (count($data2['product_id']->getProductPendingImages()) > 0) {

                $data_product['images_from'] = 0;
            } else {

                $data_product['images_from'] = 1;
            }

//            $details2 = $this->product_repo->ProductOfId($data['product_id']);
//            $this->product_repo->update($details2, $data);
//            unset($data['product_id']);
//            if ($this->product_quotation_repo->update($product_quot, $data))
//            {

            return response()->json('Product Quotation Saved Successfully', 200);

//            }
        }
    }

    public function saveProductQuotation(Request $request)
    {

        $data = $request->all();


        if ($request->id) {

            $is_send_mail = $data['is_send_mail'];

            $product_quot = $this->product_quotation_repo->ProductQuotationOfId($request->id);

            $data['is_updated_details'] = 1;


            $data_product = array();

            if (isset($data['images'])) {

                if (count($data['images']) > 0) {

                    foreach ($data['images'] as $key => $value) {


                        $data_product['product_pending_images'][] = $this->image_repo->ImageOfId($value);
                    }
                } else {

                    $data_product['product_pending_images'] = array();
                }
            }

//            if ($data['with_send_mail'] != 1)
//                {
//
//                }
//                else
//                {
//                    unset($data['is_send_mail']);
//                }

            if (isset($data['is_send_mail'])) {


                if ($data['is_send_mail'] == 3) {

                    $data['is_archived'] = 1;

                    unset($data['is_send_mail']);
                } else if ($data['is_send_mail'] == 2) {

                    $data['is_send_mail'] = $data['is_send_mail'];
                } else if ($data['is_send_mail'] == 1) {

                    //is_send_mail=1 accept

                    if ($data['with_send_mail'] == 1) {

                        unset($data['is_send_mail']);
                    } else {

                        $data['is_for_production_create_date'] = 'Yes';

                        //17 for pending

                        $data['status_quot'] = $this->option_repo->OptionOfId(17);
                    }
                }
            }


            if (isset($data['product_id']['pick_up_location'])) {

                $data_product['pick_up_location'] = $this->option_repo->OptionOfId($data['product_id']['pick_up_location']);
            }

            if (isset($data['product_id']['pet_free'])) {

                $data_product['pet_free'] = $data['product_id']['pet_free'];
            }


            if (isset($data['product_id']['name'])) {

                $data_product['name'] = $data['product_id']['name'];
            }


            if (isset($data['product_id']['state'])) {

                $data_product['state'] = $data['product_id']['state'];
            }


            if (isset($data['product_id']['city'])) {

                $data_product['city'] = $data['product_id']['city'];
            }

            if (isset($data['product_id']['location'])) {

                $data_product['location'] = $data['product_id']['location'];
            }

            if (isset($data['product_id']['category_local'])) {

                $data_product['category_local'] = $data['product_id']['category_local'];
            }

            if (isset($data['product_id']['brand_local'])) {

                $data_product['brand_local'] = $data['product_id']['brand_local'];
            }

//            if (isset($data['product_id']['item_type_local']))
//            {
//                $data_product['item_type_local'] = $data['product_id']['item_type_local'];
//            }

            if (isset($data['product_id']['age'])) {

                $data_product['age'] = $this->sub_category_repo->SubCategoryOfId($data['product_id']['age']);
            }

            if (isset($data['product_id']['condition_local'])) {

                $data_product['condition_local'] = $data['product_id']['condition_local'];
            }

            $this->product_repo->update($product_quot->getProductId(), $data_product);


            unset($data['product_id']);

            if ($this->product_quotation_repo->update($product_quot, $data)) {

                if ($data['with_send_mail'] == 1) {

                    $product_quot_updated = $this->product_quotation_repo->ProductQuotationOfId($product_quot->getId());

                    $seller = $product_quot_updated->getProductId()->getSellerid();

                    if ($is_send_mail == 1) {


                        $approved_product_quots = array($product_quot_updated);


                        $file_name = app('App\Http\Controllers\ExportController')->downloadProductWordProposalPopUp($seller, $product_quot_updated);

                        $link = config('app.url') . 'api/storage/exports/' . $file_name;


                        $greeting = "Dear " . $seller->getFirstName() . ',';


                        $introLines = array();

//        if ($temp['is_referral'] == 1)
//        {

                        $introLines[0] = "Please see below for your TLV Sale Catalog. As discussed, we have priced your Item(s) based on data from our past sales as well as comps found online. You will notice we give a suggested starting price called Max (Maximum Price). If your Item does not sell after 30 days at our proposed starting price, we will mark down your Item 10% and continue to do so every 30 days until we reach the indicated Min (Minimum Price). We will not go any lower than this price unless we confirm with you ahead of time.";

                        $introLines[1] = "Additionally, we have attached a copy of our Client Agreement. Please do not hesitate to reach out to us with any questions.";

                        $introLines[2] = "We are looking forward to publishing your Item(s) on The Local Vault!";

//            $introLines[0] = "Thank you so much for sending over photos of your items. While they are lovely, we do not believe we currently have the audience for them. We wish you the best of luck and thank you for thinking of us.";
//        }

                        $attachments = array();

//                        $attachments[] = 'TLV Client Sale Agreement 7_31_17.docx.pdf';

                        $attachments[] = 'TLV Client Sale Agreement_12_2_1019.pdf';

                        $myViewData = \View::make('emails.proposal_status_approve', ['product_quots' => $approved_product_quots, 'greeting' => $greeting, 'seller' => $seller, 'level' => 'success', 'outroLines' => [0 => ''], 'introLines' => $introLines])->render();


                        if (app('App\Http\Controllers\EmailController')->sendMail($seller->getEmail(), 'Proposal Agreement', $myViewData, $attachments)) {

                        }


                        return $file_name;
                    } else if ($is_send_mail == 2) {

                        if ($product_quot->getProductId()->getSellerid()->getEmail() != '') {

                            $product_quots = array($product_quot_updated);

                            $greeting = "Dear " . $seller->getFirstName() . ',';


                            $introLines = array();

//                if ($temp['is_referral'] == 1)
//                {
//                    $introLines[0] = "Thank you so much for sending over the photo of your items. While they are lovely, I do not think we have the proper audience for them at this point. We do have a partnership with Blackrock Galleries in Greenwich and Bridgeport and I think they may be a good option for you. They are an auction based model. Please let me know if you would like an introduction.";
//                }
//                else
//                {

                            $introLines[0] = "Thank you so much for sending over photos of your items. While they are lovely, we do not believe we currently have the audience for them. We wish you the best of luck and thank you for thinking of us.";

//                }

                            $myViewData = \View::make('emails.proposal_status_reject', ['product_quots' => $product_quots, 'greeting' => $greeting, 'seller' => $seller, 'level' => 'success', 'outroLines' => [0 => ''], 'introLines' => $introLines])->render();


                            if (app('App\Http\Controllers\EmailController')->sendMail($seller->getEmail(), 'Proposal Agreement', $myViewData)) {

                            }
                        }
                    }
                }


                return response()->json('Product Quotation Updated Successfully', 200);
            }
        } else {


//           $product_quot = $this->product_quotation_repo->ProductQuotationOfId($request->id);

            $data2['is_updated_details'] = 1;


            $data_product = array();


            if (isset($data['product_id']['pick_up_location'])) {

                $data_product['pick_up_location'] = $this->option_repo->OptionOfId($data['product_id']['pick_up_location']);
            }

            if (isset($data['product_id']['pet_free'])) {

                $data_product['pet_free'] = $data['product_id']['pet_free'];
            }

            if (isset($data['note'])) {

                $data_product['note'] = $data['note'];
            }

            if (isset($data['price'])) {

                $data_product['price'] = $data['price'];
            }

            if (isset($data['dimension_description'])) {

                $data_product['description'] = $data['dimension_description'];
            }

            if (isset($data['quantity'])) {

                $data_product['quantity'] = $data['quantity'];
            }

            if (isset($data['tlv_suggested_price_min'])) {

                $data_product['tlv_suggested_price_min'] = $data['tlv_suggested_price_min'];
            }

            if (isset($data['tlv_suggested_price_max'])) {

                $data_product['tlv_suggested_price_max'] = $data['tlv_suggested_price_max'];
            }

//            if (isset($data['pick_up_location']))
//            {
//                $data_product['pick_up_location'] = $this->option_repo->OptionOfId($value['pick_up_location']);
//            }

            if (isset($data['product_id']['name'])) {

                $data_product['name'] = $data['product_id']['name'];
            }

            if (isset($data['product_id']['seller_id'])) {

                $data_product['sellerid'] = $this->seller_repo->SellerOfId($data['product_id']['seller_id']);


                if ($data_product['sellerid']->getFirstname() != '' && $data_product['sellerid']->getLastname() != '') {

                    $data_product['sku'] = substr($data_product['sellerid']->getFirstname(), 0, 3) . substr($data_product['sellerid']->getLastname(), 0, 3);
                } else {

                    $data_product['sku'] = substr($data_product['sellerid']->getDisplayname(), 0, 3);
                }

                $sku_number = $data_product['sellerid']->getLastSku();

                $sku_number += 1;

                $seller_updated_sku['is_update_last_sku'] = 1;

                $seller_updated_sku['last_sku'] = $sku_number;

                $this->seller_repo->update($data_product['sellerid'], $seller_updated_sku);


                if ($sku_number < 100) {

                    if ($sku_number < 10) {

                        $sku_number = '00' . $sku_number;
                    } else {

                        $sku_number = '0' . $sku_number;
                    }
                }


//                $data_product['sku'] = $data_product['sku'] . $sku_number;

                $data_product['sku'] = $data_product['sku'] . $data_product['sellerid']->getWp_seller_id() . $sku_number;


//               $data_product['sellerid']= $data['product_id']['name'];
            }

            if (isset($data['images'])) {

                if (count($data['images']) > 0) {

                    foreach ($data['images'] as $key => $value) {


                        $data_product['product_pending_images'][] = $this->image_repo->ImageOfId($value);
                    }
                } else {

                    $data_product['product_pending_images'] = array();
                }
            }

//            if (isset($data['is_send_mail']))
//            {
//                if ($data['is_send_mail'] == 3)
//                {
//                    $data['is_archived'] = 1;
//                    unset($data['is_send_mail']);
//                }
//                else
//                {
//                    $data['is_send_mail'] = $data['is_send_mail'];
//                    //17 for pending
//                    $data['status_quot'] = $this->option_repo->OptionOfId(17);
//                }
//            }


            if (isset($data['product_id']['state'])) {

                $data_product['state'] = $data['product_id']['state'];
            }

            if (isset($data['product_id']['city'])) {

                $data_product['city'] = $data['product_id']['city'];
            }

            if (isset($data['product_id']['location'])) {

                $data_product['location'] = $data['product_id']['location'];
            }

            if (isset($data['product_id']['category_local'])) {

                $data_product['category_local'] = $data['product_id']['category_local'];
            }

            if (isset($data['product_id']['brand_local'])) {

                $data_product['brand_local'] = $data['product_id']['brand_local'];
            }

//            if (isset($data['product_id']['item_type_local']))
//            {
//                $data_product['item_type_local'] = $data['product_id']['item_type_local'];
//            }

            if (isset($data['product_id']['age'])) {

                $data_product['age'] = $this->sub_category_repo->SubCategoryOfId($data['product_id']['age']);
            }

            if (isset($data['product_id']['condition_local'])) {

                $data_product['condition_local'] = $data['product_id']['condition_local'];
            }

            //APPROVEED

            $data_product['status'] = $this->option_repo->OptionOfId(7);

            //SET approved_date

            $data_product['is_set_approved_date'] = 1;

            $product_obj = $this->product_repo->prepareData($data_product);

            $product_id = $this->product_repo->create($product_obj);


            $introLines = array();

            $introLines[0] = "A new product sell request has been added to the TLV Workflow.";

            $introLines[1] = "Please review here: https://tlv-workflowapp.com/seller/product";

            $myViewData = \View::make('emails.new_product_email', ['level' => 'success', 'outroLines' => [0 => ''], 'introLines' => $introLines])->render();

            $option = $this->option_repo->OptionOfId(79);

            if (app('App\Http\Controllers\EmailController')->sendMail($option->getValueText(), 'TLV Workflow: Product Sell Request ', $myViewData)) {

            }


            $product_created = $this->product_repo->ProductOfId($product_id);


            $data2['product_id'] = $product_created;

            $data2['price'] = $data2['product_id']->getPrice();

            $data2['quantity'] = $data2['product_id']->getQuantity();

            $data2['note'] = $data2['product_id']->getNote();

            $data2['images_from'] = 1;

            $data2['is_updated_details'] = 1;

            $data2['tlv_suggested_price_min'] = $data2['product_id']->getTlv_suggested_price_min();

            $data2['tlv_suggested_price_max'] = $data2['product_id']->getTlv_suggested_price_max();

//            $data2['sort_description'] = $data2['product_id']->getDescription();

            $data2['dimension_description'] = $data2['product_id']->getDescription();

            $production_quotation_prepared = $this->product_quotation_repo->prepareData($data2);

            $this->product_quotation_repo->create($production_quotation_prepared);

            if (count($data2['product_id']->getProductPendingImages()) > 0) {

                $data_product['images_from'] = 0;
            } else {

                $data_product['images_from'] = 1;
            }

//            $details2 = $this->product_repo->ProductOfId($data['product_id']);
//            $this->product_repo->update($details2, $data);
//            unset($data['product_id']);
//            if ($this->product_quotation_repo->update($product_quot, $data))
//            {

            return response()->json('Product Quotation Saved Successfully', 200);

//            }
        }
    }

    public function getProductQuotation(Request $request)
    {

        return $this->product_quotation_repo->getProductQuotationById($request->id);
    }

//    public function getAllUsers()
//    {
//        return $this->user_repo->getAllUsers();
//    }


    public function getProductQuotations(Request $request)
    {

        $filter = $request->all();


        $data['draw'] = $filter['draw'];


//        if (JWTAuth::parseToken()->authenticate()->getRoles()[0]->getId() == 1)
//        {

        $users_data_total = $this->product_quotation_repo->getProductQuotations($filter);

        $data['data'] = $users_data_total['data'];


        $data['recordsTotal'] = $users_data_total['total'];

        $data['recordsFiltered'] = $this->product_quotation_repo->getProductQuotationsTotal($filter);

//        }
//        else
//        {
//            $filter['userid'] = JWTAuth::parseToken()->authenticate()->getId();
//            $users_data_total = $this->product_repo->getTLVStaffProducts($filter);
//            $data['data'] = $users_data_total['data'];
//
//            $data['recordsTotal'] = $users_data_total['total'];
//            $data['recordsFiltered'] = $this->product_repo->getTLVStaffProductsTotal($filter);
//        }


        return response()->json($data, 200);
    }

    public function changeProductQuotationStatus(Request $request)
    {

        $data = $request->all();


        $data['status_quot'] = $this->option_repo->OptionOfId($data['status_quot']);

        $productQuotation = $this->product_repo->ProductQuotationOfId($data['product_quotation_id']);


        $this->product_repo->update($productQuotation, $data);


        //insert data into approved product
//        if ($data['product_quotation_id'] == 7)
//        {
//            $data_product['sell_id'] = $this->sell_repo->SellOfId($details->getSell_id());
//            $data_product['product_id'] = $details;
//            $data_product['status'] = $this->option_repo->OptionOfId(11);
//
//            $data_product['name'] = $details->getName();
//            $data_product['price'] = $details->getPrice();
//            $data_product['description'] = $details->getDescription();
//            $data_product['quantity'] = $details->getQuantity();
//
////            $data_product['sku'] = $details->getSeller_firstname() . $details->getSeller_lastname();
//            $data_product['sku'] = $details->getSku();
//
//            $data_product['seller_id'] = $details->getSeller_id();
//
//            $data_product['room'] = $details->getRoom();
//            $data_product['look'] = $details->getLook();
//            $data_product['color'] = $details->getColor();
//            $data_product['brand'] = $details->getBrand();
//            $data_product['category'] = $details->getCategory();
//            $data_product['collection'] = $details->getCollection();
//            $data_product['con'] = $details->getCondition();
//            foreach ($details->getProductPendingImages() as $key => $value)
//            {
//
//                $data_product['product_images'][] = $value;
//            }
//
//            $prepared_data = $this->product_approved_repo->prepareData($data_product);
//            $this->product_approved_repo->create($prepared_data);
//        }


        return 1;
    }

    public function get_all_product_quotation_status(Request $request)
    {

        //change id

        $all_order_status = $this->option_repo->get_all_of_select_id(3);

        return $all_order_status;
    }

    public function saveWPId($product_quotation_id, $wpid)
    {

        $data['wp_product_id'] = $wpid;

        $product_quot = $this->product_quotation_repo->ProductQuotationOfId($product_quotation_id);

        if ($this->product_quotation_repo->update($product_quot, $data)) {

            return 1;
        } else {

            return 0;
        }
    }

    public function getAllPendingProductQuotation()
    {

        return $this->product_quotation_repo->getAllPendingProductQuotation();
    }

    public function getAllSyncProduct(Request $request)
    {

        ini_set('max_execution_time', 300000);


        $filter = $request->all();

        $data['draw'] = $filter['draw'];

        $product_data = $this->product_quotation_repo->getAllSyncProduct($filter);
        $data['data'] = $product_data['data'];

        $data['recordsTotal'] = $product_data['total'];
        $data['recordsFiltered'] = $product_data['total'];

        return response()->json($data, 200);
    }

    public function getSyncProductOrder(Request $request)
    {


        $data['product_detail'] = $this->product_quotation_repo->getProductWpProductIds($request->wp_product_id);


        $order_list = $this->product_quotation_repo->getSyncProductOrder($request->wp_product_id);

        $data['data'] = $order_list['data'];

        return response()->json($data, 200);
    }

    public function getSyncProductOrderReport(Request $request)
    {

        ini_set('max_execution_time', 300000);

        $filter = $request->all();


        $product_data = $this->product_quotation_repo->getSyncProductReport($filter);
        $data['data'] = $product_data['data'];

        $products = [];

        $i = 1;
        foreach ($data['data'] as $key => $value) {

            $category_arr = array();
            foreach ($value['product_id']['product_category'] as $category_val) {

                if ($category_val['is_enable'] == '1') {

                    $category_arr[] = $category_val['sub_category_name'];
                }
            }
            $category_list = implode(',', $category_arr);

            $subcategory_arr = array();
            foreach ($value['product_id']['product_category'] as $subcategory_val) {

                if ($subcategory_val['is_enable'] == '0') {

                    $subcategory_arr[] = $subcategory_val['sub_category_name'];
                }
            }
            $subcategory_list = implode(',', $subcategory_arr);

            if (!empty($value['wp_published_date'])) {

                if ($value['wp_published_date']->format('Y-m-d H:i:s') !== '-0001-11-30 00:00:00') {
                    $wp_published_date = $value['wp_published_date'];
                } else {
                    $wp_published_date = '';
                }
            } else {
                $wp_published_date = '';
            }

            $seller_to_drop_off = 'False';
            if(!empty($value['seller_to_drop_off'])){
                if($value['seller_to_drop_off'] == true){
                    $seller_to_drop_off = 'True';
                }else{
                    $seller_to_drop_off = 'False';
                }
            }

            $i = $i + 1;


            if ($value['wp_product_id'] != '' && $value['wp_product_id'] != '0') {

                $order = $this->product_quotation_repo->getSyncProductOrder($value['wp_product_id']);


                if (count($order['data']) > 0) {

                    $orders = $order['data'];

                    foreach ($orders as $orders_key => $orders_value) {

                        $order_list = json_decode($orders_value['order_list'], true);


                        $lv_product_sale_price = '';
                        $lv_no_of_items = '';
                        $lv_order_comm_sub_total = '';
                        $lv_order_comm_commission = '';
                        $lv_order_comm_total = '';
                        $lv_order_orignal_total = '';

                        //Log::info($order_list);
                        if ($order_list != '' && $order_list != null) {
                            foreach ($order_list as $key => $order_list_val) {
                                //Log::info($order_list_val['lv_order_comm_sub_total']);


                                if ($order_list_val['lv_order_product_id'] == $value['wp_product_id']) {
                                    if ($order_list_val['lv_order_comm_sub_total']) {
                                        $lv_product_sale_price = $order_list_val['lv_order_comm_sub_total'];
                                    } else {
                                        $lv_product_sale_price = '';
                                    }

                                    if ($order_list_val['lv_no_of_items']) {
                                        $lv_no_of_items = $order_list_val['lv_no_of_items'];
                                    } else {
                                        $lv_no_of_items = '';
                                    }

                                    if ($order_list_val['lv_order_comm_sub_total']) {
                                        $lv_order_comm_sub_total = $order_list_val['lv_order_comm_sub_total'];
                                    } else {
                                        $lv_order_comm_sub_total = '';
                                    }

                                    if ($order_list_val['lv_order_comm_commission']) {
                                        $lv_order_comm_commission = $order_list_val['lv_order_comm_commission'];
                                    } else {
                                        $lv_order_comm_commission = '';
                                    }

                                    if ($order_list_val['lv_order_comm_total']) {
                                        $lv_order_comm_total = $order_list_val['lv_order_comm_total'];
                                    } else {
                                        $lv_order_comm_total = '';
                                    }

                                    if ($order_list_val['lv_order_orignal_total']) {
                                        $lv_order_orignal_total = $order_list_val['lv_order_orignal_total'];
                                    } else {
                                        $lv_order_orignal_total = '';
                                    }
                                }
                            }
                        }


                        $billings = json_decode($orders_value['billing']);
                        $buyer_name = $billings->first_name . ' ' . $billings->last_name;
                        $buyer_email = $billings->email;
                        $shippings = json_decode($orders_value['shipping']);

                        $shippings_lines = json_decode($orders_value['shipping_lines']);


                        $billing = $billings->first_name . ' ' . $billings->last_name . ', ' . $billings->company . ', ' . $billings->address_1 . ', ' . $billings->city . ', ' . $billings->state . ', ' . $billings->postcode . ', ' . $billings->email . ', ' . $billings->phone;

                        $shipping = $shippings->first_name . ' ' . $shippings->last_name . ', ' . $shippings->company . ', ' . $shippings->address_1 . ', ' . $shippings->city . ', ' . $shippings->state . ', ' . $shippings->postcode;
                        $tlv_make_an_offer = $orders_value['tlv_make_an_offer'] == 1 ? "Yes" : "No";
                        $customer_username = $orders_value['customer_username'] != '' ? $orders_value['customer_username'] : "-";


                        if (isset($shippings_lines[0]->method_title)) {

                            $shipping_method_title = $shippings_lines[0]->method_title;
                            $shipping_total = $shippings_lines[0]->total;
                        } else {
                            $shipping_method_title = '';
                            $shipping_total = '';
                        }


                        $products[] = array(
                            $value['product_id']['sellerid']['firstname'] . ' ' . $value['product_id']['sellerid']['lastname'],
                            $value['product_id']['name'],
                            $category_list,
                            $subcategory_list,
                            $value['product_id']['sku'],
                            $value['product_id']['quantity'],
                            $value['sort_description'],
                            $value['dimension_description'],
                            $value['condition_note'],
                            $value['note'],
                            $value['price'],
                            $value['tlv_price'],
                            $value['storage_pricing'],
                            $value['wp_sale_price'],
                            $value['commission'],
                            $value['units'],
                            $value['width'],
                            $value['depth'],
                            $value['height'],
                            $value['seat_height'],
                            $value['arm_height'],
                            $value['inside_seat_depth'],
                            $value['product_id']['ship_size'],
                            $value['product_id']['pet_free'],
                            $value['delivery_option'],
                            $value['product_id']['flat_rate_packaging_fee'],
                            $value['product_id']['city'],
                            $value['product_id']['state'],
                            $value['curator_name'],
                            $value['curator_commission'],
                            $value['wp_stock_status'],
                            $wp_published_date,
                            $value['wp_product_expire_date'],
                            $seller_to_drop_off,
                            $orders_value['order_number'],
                            $orders_value['date_created'],
                            $orders_value['status'],
                            $orders_value['payment_method'],
                            $orders_value['payment_method_title'],
                            $orders_value['transaction_id'],
                            $lv_product_sale_price,
                            $lv_no_of_items,
                            $lv_order_comm_sub_total,
                            $lv_order_comm_commission,
                            $lv_order_comm_total,
                            $lv_order_orignal_total,
                            $customer_username,
                            $buyer_name,
                            $buyer_email,
                            $billing,
                            $shipping,
                            $orders_value['buyer_user_role'],
                            $tlv_make_an_offer,
                            $shipping_method_title,
                            $shipping_total
                        );

                        $i++;
                    }

                    $i = $i - 1;
                } else {


                    $products[] = array(
                        $value['product_id']['sellerid']['firstname'] . ' ' . $value['product_id']['sellerid']['lastname'],
                        $value['product_id']['name'],
                        $category_list,
                        $subcategory_list,
                        $value['product_id']['sku'],
                        $value['product_id']['quantity'],
                        $value['sort_description'],
                        $value['dimension_description'],
                        $value['condition_note'],
                        $value['note'],
                        $value['price'],
                        $value['tlv_price'],
                        $value['storage_pricing'],
                        $value['wp_sale_price'],
                        $value['commission'],
                        $value['units'],
                        $value['width'],
                        $value['depth'],
                        $value['height'],
                        $value['seat_height'],
                        $value['arm_height'],
                        $value['inside_seat_depth'],
                        $value['product_id']['ship_size'],
                        $value['product_id']['pet_free'],
                        $value['delivery_option'],
                        $value['product_id']['flat_rate_packaging_fee'],
                        $value['product_id']['city'],
                        $value['product_id']['state'],
                        $value['curator_name'],
                        $value['curator_commission'],
                        $value['wp_stock_status'],
                        $wp_published_date,
                        $value['wp_product_expire_date'],
                        $seller_to_drop_off,
                    );

                }
            }
        }

        $file_name = 'Sync_Product_Report_' . time() . '.xlsx';

        $file = 'public/exports/' . $file_name;

        $export = new SyncProductExport($products);

        ob_end_clean(); // this
        ob_start(); // and this

        Excel::store($export, $file);

        $path = asset('api/storage/exports/' . $file_name);
        return $path;

    }

}
