<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Entities\Users;
use App\Entities\Role;
use App\Repository\UserRepository as user_repo;
use App\Repository\RoleRepository as role_repo;
use App\Repository\ProductsRepository as product_repo;
use App\Repository\ProductsQuotationRepository as product_quote_repo;
use App\Repository\SellerRepository as seller_repo;
use App\Repository\OptionRepository as option_repo;
use App\Repository\ProductQuoteAgreementRepository as product_quote_agreement_repo;
use App\Repository\ProductStorageAgreementRepository as product_storage_agreement_repo;
use App\Repository\ProductQuoteRenewRepository as product_quote_renew_repo;
use Auth,Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class SellerController extends Controller {

    public function __construct(product_storage_agreement_repo $product_storage_agreement_repo, product_quote_renew_repo $product_quote_renew_repo, product_quote_agreement_repo $product_quote_agreement_repo, option_repo $option_repo, product_quote_repo $product_quote_repo, seller_repo $seller_repo, product_repo $product_repo, user_repo $user_repo, role_repo $role_repo) {

        $this->product_quote_repo = $product_quote_repo;

        $this->product_repo = $product_repo;

        $this->user_repo = $user_repo;

        $this->role_repo = $role_repo;

        $this->seller_repo = $seller_repo;

        $this->option_repo = $option_repo;

        $this->product_quote_agreement_repo = $product_quote_agreement_repo;

        $this->product_quote_renew_repo = $product_quote_renew_repo;

        $this->product_storage_agreement_repo = $product_storage_agreement_repo;
    }

    public function deleteSeller(Request $request) {

        $seller = $this->seller_repo->SellerOfId($request->id);

        $this->product_quote_repo->deleteAllProductQuotesOfSellerId($seller->getId());

        $this->product_repo->deleteAllProductsOfSellerId($seller->getId());

        $this->seller_repo->delete($seller);
    }

    public function agreeTermsAcknowledgement($seller_encrypted_id) {

        try {

            $seller_id = \Crypt::decrypt($seller_encrypted_id);

            $seller = $this->seller_repo->SellerOfId($seller_id);

            $greeting = 'Dear TLV,';

//            $greeting = $seller->getFirstName() . ' accepted terms.';

            $introLines = [];

            $introLines[] = $seller->getFirstName() . ' accepted terms.';

            $introLines[] = 'Yes, I agree to your selling terms. Below is the additional information I have on my items:';

            $myViewData = \View::make('emails.seller_agreements_agree_terms', [
                        'level' => 'success',
                        'outroLines' => [0 => ''],
                        'greeting' => $greeting,
                        'introLines' => $introLines])->render();

            app('App\Http\Controllers\EmailController')->sendMailSellerAgreement('vaibhav@esparkinfo.com', 'Seller agreement accepted:' . $seller->getLastname(), $myViewData);

//            if (app('App\Http\Controllers\EmailController')->sendMailSellerAgreement('sell@thelocalvault.com', 'TLV Workflow: Seller Agreements Accepts:' . $seller->getLastname(), $myViewData))

            if (app('App\Http\Controllers\EmailController')->sendMailSellerAgreement('sell@thelocalvault.com', 'Seller agreement accepted:' . $seller->getLastname(), $myViewData)) {
                
            }
        } catch (\RuntimeException $e) {



            // Content is not encrypted.
        }

        return redirect('./../');
    }

    public function saveuserSellerAgreement($seller_id) {



        $seller = $this->seller_repo->SellerOfId($seller_id);

        $seller_id_decrypt = \Crypt::encrypt($seller_id);

        $form_url = config('app.url') . 'seller_agreement/' . $seller_id_decrypt;

        $greeting = "Dear " . $seller->getFirstName() . ',';

        $introLines = [];

        $introLines[] = '';

        $myViewData = \View::make('emails.seller_agreements_email', ['level' => 'success', 'outroLines' => [0 => ''], 'form_url' => $form_url, 'greeting' => $greeting, 'introLines' => $introLines])->render();

        if (app('App\Http\Controllers\EmailController')->sendMailSellerAgreement($seller->getEmail(), 'TLV Workflow: Seller Agreements ', $myViewData)) {
            
        }

        return response()->json('Send Mail Successfully', 200);
    }

    public function checkSellerAgreement(Request $request) {

        $data = $request->all();

        $response_data = [];

        $response_data['is_valid'] = false;

        $response_data['status'] = false;

        if (isset($data['product_quote_agreement_id'])) {



            try {

                $product_quote_agreement_id = \Crypt::decrypt($data['product_quote_agreement_id']);

                $product_quote_agreement = $this->product_quote_agreement_repo->ofId($product_quote_agreement_id);



                if ($product_quote_agreement) {

                    $response_data['is_valid'] = true;

                    if ($product_quote_agreement->getIs_form_filled() == 1) {

                        $response_data['status'] = true;
                    }
                }
            } catch (\RuntimeException $e) {



                // Content is not encrypted.
            }
        }

        return response()->json($response_data, 200);
    }

    public function checkSellerStorageAgreement(Request $request) {

        $data = $request->all();
        $response_data = [];
        $response_data['is_valid'] = false;
        $response_data['status'] = false;

        if (isset($data['product_storage_agreement_id'])) {
            try {
                $product_storage_agreement_id = \Crypt::decrypt($data['product_storage_agreement_id']);
                $product_storage_agreement = $this->product_storage_agreement_repo->ofId($product_storage_agreement_id);
                if ($product_storage_agreement) {
                    $response_data['is_valid'] = true;
                    if ($product_storage_agreement->getIs_form_filled() == 1) {
                        $response_data['status'] = true;
                    }
                }
            } catch (\RuntimeException $e) {
                // Content is not encrypted.
            }
        }

        return response()->json($response_data, 200);
    }

    public function getAllSellerAgreementsOfWpSellerId($wp_seller_id) {





        $agreements = $this->product_quote_agreement_repo->getAllSellerAgreementsOfWpSellerId($wp_seller_id);

//        $request->wp_seller_id

        foreach ($agreements as $key => $agreement) {



            if (!file_exists('./../Uploads/user_agreement_pdf_without_card/' . $agreement['pdf'])) {

                $agreements[$key]['pdf_link'] = '';
            }
        }



        return response()->json($agreements, 200);
    }

    public function saveSellerAgreement(Request $request) {

        $data = $request->all();

//        $seller_id = \Crypt::decrypt($data['seller_ageement']['id']);

        $product_quote_agreement_id = \Crypt::decrypt($data['seller_ageement']['id']);



        if ($product_quote_agreement_id) {

            if (isset($data['signature']['dataUrl'])) {

                $image = $data['signature']['dataUrl']; // your base64 encoded

                $image = str_replace('data:image/png;base64,', '', $image);

                $image = str_replace(' ', '+', $image);

                $imageName = str_random(25) . '.' . 'png';

                \File::put(public_path() . '/../../Uploads/user_agreement_sign/' . $imageName, base64_decode($image));
            }

            $file = 'seller_agreement_' . time();

            $pdf_file_name = self::pdfGenerateSellerAgreement($data['seller_ageement'], $imageName, $file);

            $pdf_file_path_with_out_card = self::pdfGenerateSellerAgreement($data['seller_ageement'], $imageName, $file, true);

//            $pdf_file_name = self::pdfGenerateOldSellerAgreement($data['seller_ageement'], $imageName, $file);
//            $pdf_file_path_with_out_card = self::pdfGenerateOldSellerAgreement($data['seller_ageement'], $imageName, $file, true);



            if (isset($data['seller_ageement']['local_vault_date'])) {

                $data['seller_ageement']['local_vault_date'] = new \DateTime(date('Y-m-d H:i:s', strtotime($data['seller_ageement']['local_vault_date'])));
            }

            if (isset($data['seller_ageement']['consignor_date'])) {

                $data['seller_ageement']['consignor_date'] = new \DateTime(date('Y-m-d H:i:s', strtotime($data['seller_ageement']['consignor_date'])));
            }



            $temp_data['data_json'] = json_encode($data['seller_ageement']);

//            $temp_data['quote_ids_json'] = json_encode($data['quote_ids_json']);

            $temp_data['signature'] = $imageName;

            $temp_data['is_form_filled'] = 1;

            $temp_data['pdf'] = $pdf_file_name;



//            $seller = $this->seller_repo->SellerOfId($seller_id);

            $product_quote_agreement = $this->product_quote_agreement_repo->ofId($product_quote_agreement_id);

            $this->product_quote_agreement_repo->update($product_quote_agreement, $temp_data);

            $link = config('app.url') . 'Uploads/user_agreement_pdf/' . $pdf_file_name;

            $introLines = array();

            $introLines[0] = "Here is the Seller Agreement";
            $line = "Download Seller Agreement";
            $myViewData = \View::make('emails.seller_agreement_filled', ['link' => $link, 'level' => 'success', 'introLines' => $introLines, 'line' => $line])->render();



            $seller = $product_quote_agreement->getSeller_id();

            $sellerLastname = '';

            if ($seller) {

                $sellerLastname = $seller->getLastname();
            }



            $attachments = [];

            $bccs = [];

            $ccs = [];

            $ccs[] = 'sell@thelocalvault.com';



//            app('App\Http\Controllers\EmailController')->sendMail('vaibhav@esparkinfo.com', 'Seller Agreement: ' . $sellerLastname, $myViewData, $attachments, $bccs, $ccs);

            if (app('App\Http\Controllers\EmailController')->sendMail('Contract@thelocalvault.com', 'Seller Agreement: ' . $sellerLastname, $myViewData, $attachments, $bccs, $ccs)) {

                Log::info('in mmail');
            }

            Log::info('File name ' . $link);



            return response()->json('Product Quote Agreement Updated Successfully', 200);
        }
    }

    public function saveSellerStorageAgreement(Request $request) {

        $data = $request->all();

//        $seller_id = \Crypt::decrypt($data['seller_ageement']['id']);

        $product_storage_agreement_id = \Crypt::decrypt($data['seller_ageement']['id']);
        $product_storage_agreement = $this->product_storage_agreement_repo->ofId($product_storage_agreement_id);
        $storage_all_products = array();
        $storage_products = json_decode($product_storage_agreement->getQuote_ids_json());

        $wpProductIds = [];
        if (count($storage_products) > 0) {

            foreach ($storage_products as $key => $value) {
                $storage_products = $this->product_quote_repo->ProductQuotationOfId($value);
                $storageproducts = [];
                $storageproducts['is_storage_proposal'] = 1;
                $this->product_quote_repo->update($storage_products, $storageproducts);
                $storage_all_products[] = $storage_products;
                $wpProductId = $storage_products->getWp_product_id();

                if ($wpProductId) {
                    $wpProductIds[] = $wpProductId;
                }
            }
        }


        if ($product_storage_agreement_id) {

            if (isset($data['signature']['dataUrl'])) {

                $image = $data['signature']['dataUrl']; // your base64 encoded

                $image = str_replace('data:image/png;base64,', '', $image);

                $image = str_replace(' ', '+', $image);

                $imageName = str_random(25) . '.' . 'png';

                \File::put(public_path() . '/../../Uploads/storage_agreement_sign/' . $imageName, base64_decode($image));
            }

            $file = 'storage_agreement_' . time();

            $pdf_file_name = self::pdfGenerateStorageAgreement($data['seller_ageement'], $imageName, $file, $storage_all_products);


            if (isset($data['seller_ageement']['consignor_date'])) {
                $data['seller_ageement']['consignor_date'] = new \DateTime(date('Y-m-d H:i:s', strtotime($data['seller_ageement']['consignor_date'])));
            }



            $temp_data['data_json'] = json_encode($data['seller_ageement']);

//            $temp_data['quote_ids_json'] = json_encode($data['quote_ids_json']);

            $temp_data['signature'] = $imageName;

            $temp_data['is_form_filled'] = 1;

            $temp_data['pdf'] = $pdf_file_name;



//            $seller = $this->seller_repo->SellerOfId($seller_id);


            $this->product_storage_agreement_repo->update($product_storage_agreement, $temp_data);

            $link = config('app.url') . 'Uploads/storage_agreement_pdf/' . $pdf_file_name;

            $introLines = array();

            $introLines[0] = "Here is the Storage Agreement";
            $line = "Download Storage Agreement";
            $myViewData = \View::make('emails.seller_agreement_filled', ['link' => $link, 'level' => 'success', 'introLines' => $introLines, 'line' => $line])->render();



            $seller = $product_storage_agreement->getSeller_id();

            $sellerLastname = '';

            if ($seller) {

                $sellerLastname = $seller->getLastname();
            }



            $attachments = [];

            $bccs = [];

            $ccs = [];

            $ccs[] = 'sell@thelocalvault.com';

            if (app('App\Http\Controllers\EmailController')->sendMail('Contract@thelocalvault.com', 'Storage Agreement: ' . $sellerLastname, $myViewData, $attachments, $bccs, $ccs)) {

                Log::info('in mmail');
            }

            if (count($wpProductIds) > 0) {
                $this->updateWordpressProductAfterStorageAgreementSave($wpProductIds);
            }

            return response()->json('Product Storage Agreement Updated Successfully', 200);
        }
    }

    public function getAllMyProductQuoteAgreements(Request $request) {

        $seller_id = $request->id;

        $filter = $request->filter;

        $agreements = $this->product_quote_agreement_repo->getAllOfSellerId($seller_id, $filter);

        return response()->json($agreements, 200);
    }

    public function getAllMyProductQuoteRenews(Request $request) {

        $seller_id = $request->id;

        $filter = $request->filter;

        $renews = $this->product_quote_renew_repo->getAllOfSellerId($seller_id, $filter);

        return response()->json($renews, 200);
    }

    public function saveWPProductRenewsByWpProductId(Request $request) {







        $data = $request->all();

//        Log::info('saveWPProductRenewsByWpProductId_without_json:' . $data);

        Log::info('saveWPProductRenewsByWpProductId:' . json_encode($data));



        $post_data = json_decode($data['data']);



        if (isset($post_data) && is_array($post_data) && count($post_data) > 0) {

            foreach ($post_data as $key => $record) {

                $product_quote = $this->product_quote_repo->ProductQuotationOfWpProductId($record->wp_product_id);



                $product_quote_renew = array();

                $product_quote_renew['name'] = $record->name;

                $product_quote_renew['seller_id'] = $this->seller_repo->SellerOfWpId($record->wp_seller_id);

                $product_quote_renew['product_quote_id'] = $product_quote;

                $product_quote_renew['data_json'] = json_encode($record);

                $product_quote_renew['wp_product_id'] = $record->wp_product_id;

                $product_quote_renew_obj = $this->product_quote_renew_repo->prepareData($product_quote_renew);

                $obj = $this->product_quote_renew_repo->create($product_quote_renew_obj);
            }
        }
    }

    public function pdfGenerateOldSellerAgreement($data, $signature_image, $file = 'seller_agreement_', $hideCreditCard = false) {



        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);

        $pdf->SetAuthor('Test');

        $pdf->SetTitle('Product Reports');

//        $pdf->SetSubject('Daily Report of child');

        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

//        $pdf->SetHeaderData('../../../../../../admin/assets/images/logo.png', PDF_HEADER_LOGO_WIDTH, 'Daily Report for ' . $data['child']['firstname'].' '.$data['child']['lastname'], $date);
//        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);



        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP - 10, PDF_MARGIN_RIGHT);

//        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->setPrintHeader(false);



//        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);



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

        font-family: helvetica;

        font-size: 12px;

        padding: 10px;

    }

    tr {

        padding: 10px;

    }

    td {

       padding: 10px;         

    }

    td.second {

    }

    div.test {

        color: #000000;

        font-family: helvetica;

        font-size: 15px;

        border-style: solid solid solid solid;

        border-width: 0px 0px 0px 0px;

        text-align: center;

        padding:10px;

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

 <div style="width: 100%;display: block;text-align: center;">

     

  </div>

  

                

EOF;



        $html .= '<div style="width: 100%;display: block;text-align: center;">';

        $html .= '<img src="' . public_path() . '/assets/images/logo.png" style="height:100px;width:150px;">';

        $html .= '</div>';

        $html .= '<div style="font:size:20px;text-align:center;padding:4px;">STANDARD CLIENT SALE AGREEMENT</div>';

        $html .= '<table border="0">';

        $html .= '<tr>';

        $html .= '<td colspan="3">';

        $html .= "<b>Consignor's Name : </b><u>  " . $data['consignor_name'] . "  </u>";

        $html .= '</td>';

        $html .= '</tr>';



        $html .= '<tr>';

        $html .= '<td colspan="3">';

        $html .= '<b>Address : </b><u>  ' . $data['address'] . '  </u>';

        $html .= '</td>';

        $html .= '</tr>';



        $html .= '<tr>';

        $html .= '<td>';

        $html .= '<b>City : </b><u>  ' . $data['city'] . '  </u>';

        $html .= '</td>';

        $html .= '<td>';

        $html .= '<b>State : </b><u>  ' . $data['state'] . '  </u>';

        $html .= '</td>';

        $html .= '<td>';

        $html .= '<b>Zip : </b><u>  ' . $data['zip'] . '  </u>';

        $html .= '</td>';

        $html .= '</tr>';





        $html .= '</table>';



        $html .= '<table>';

        $html .= '<tr>';

        $html .= '<td>';

        $html .= '<b>Cell Phone : </b><u>  ' . $data['phone'] . '  </u>';

        $html .= '</td>';

        $html .= '</tr>';





        $html .= '<tr>';

        $html .= '<td>';

        $html .= '<b>Email : </b><u>  ' . $data['email'] . '  </u>';

        $html .= '</td>';

        $html .= '</tr>';

        $html .= '</table>';







        if (!$hideCreditCard) {



            $html .= '<table>';

            $html .= '<tr>';

            $html .= '<td colspan="2">';

            $html .= '<b>Credit Card Information:</b>';

            $html .= '</td>';

            $html .= '</tr>';



            $html .= '<tr>';

            $html .= '<td colspan="2">';

            $html .= 'CC# : <u> ' . $data['credit_card_cc'] . '    </u>';

            $html .= '</td>';

            $html .= '</tr>';



            $html .= '<tr>';

            $html .= '<td>';

            $html .= 'Exp Date : <u> ' . $data['credit_card_expiry_month'] . '/' . $data['credit_card_expiry_year'] . '    </u>';

            $html .= '</td>';

            $html .= '<td>';

            $html .= 'Security Code : <u>    ' . $data['credit_card_security_code'] . '    </u>';

            $html .= '</td>';

            $html .= '</tr>';



            $html .= '<tr>';

            $html .= '<td colspan="2">';

            $html .= 'Billing Address : <u>  ' . $data['credit_card_billing_address'] . '  </u>';

            $html .= '</td>';

            $html .= '</tr>';



            $html .= '</table>';





            $html .= '<table>';

            $html .= '<tr>';

            $html .= '<td>';

            $html .= 'City : <u>  ' . $data['credit_card_city'] . '  </u>';

            $html .= '</td>';

            $html .= '<td>';

            $html .= 'State : <u>  ' . $data['credit_card_state'] . '  </u>';

            $html .= '</td>';

            $html .= '<td>';

            $html .= 'Zip : <u>  ' . $data['credit_card_zip'] . '  </u>';

            $html .= '</td>';

            $html .= '</tr>';

            $html .= '</table>';

            $html .= '<ul>';

            $html .= '  <li><b>Please see section 8 for explanation for requiring credit card information.</b></li>';

            $html .= '</ul>';
        }









        $html .= '<p>';

        $html .= 'This Agreement is made by and among <u>  ' . $data['consignor_name2'] . '  </u> ("Consignor") and ';

        $html .= 'The Local Vault, LLC, Greenwich CT ("TLV") on the <u>  ' . $data['day'] . '  </u> day of <u>  ' . $data['month'] . '  </u> 20<u>' . $data['year'] . '  </u>.';

        $html .= '</p>';

        $html .= '<p>';

        $html .= 'Consignor grants unto TLV the authority to advertise, offer for sale and sell the Item(s) listed in the attached Sale Catalog. All Item(s) included in the Sale Catalog are generally described personal property belonging to the Consignor, or, the individual(s) or estate that Consignor is acting as the agent for. Hereinafter the personal property listed within the Sale Catalog will be described as “Item(s)”.';

        $html .= '</p>';





        $html .= '<p>

                    <b>Please select your preferred option with regard to local pick-up:</b>

                <p>

                <ul style="list-style: bold;list-style: none;">

                    <li>

                       ' . ((isset($data['preferred_local_pick_up']) && $data['preferred_local_pick_up'] == 1) ? "I am comfortable with pick-ups taking place at my residence without a TLV Member present." : "") . ' 

                       ' . ((isset($data['preferred_local_pick_up']) && $data['preferred_local_pick_up'] == 2) ? "I am willing to drop off small items at The Local Vault Headquarters in Cos Cob, CT within a week from purchase." : "") . '

                    </li>

                </ul>';





        $html .= '<p>';

        $html .= '<b>The Item(s) are or will be available for pick-up at:</b>';

        $html .= '</p>';

        $html .= '<p>';

        $html .= '<u>   ' . $data['available_item'] . '    </u>';

        $html .= '</p>';

        $html .= '<p>';

        $html .= '<br>';

        $html .= '</p>';

        $html .= '<p>';

        $html .= '<b>The sale shall be conducted as described below on or about 2 weeks from:</b> <u>   ' . $data['sale_weeks'] . '  </u>';

        $html .= '</p>';

        $html .= '<p>';

        $html .= '<b>Consignor and TLV agree as follows:</b>';

        $html .= '</p>';

//        $html .= '<ol style="list-style: bold;">
//                    <li>
//                        TLV will facilitate the sale of Items consigned through the use of an online (internet) sale
//                        accessible through TLV’s website at www.thelocalvault.com, as well as through our partner
//                        sites as appropriate. Our partner sites include but are not limited to: Houzz, eBay and 1stDibs.
//                        TLV reserves the right to reject or decline to handle the sale of any Item. 
//                    </li>
//                    <li>
//                        Consignors will have the option to provide their own high quality photos or to have TLV
//                        photograph the Item(s). TLV charges a Photography fee of $150.00 for the first 20 items and
//                        $50 for all additional items to be collected prior to the photoshoot. All Photographs of the
//                        Item(s) whether taken by the Consignor or TLV can be used in TLV promotional, advertising
//                        and marketing materials and activities including social media.
//                    </li>
//                    <li>
//                        All Item(s) must be readily accessible to TLV Staff during the photoshoot. Any labor required
//                        to support the listing of the Item(s) will be passed on to the Consignor and are not conditional
//                        on the sale of the Item(s).
//                    </li>
//                    <li>
//                        If the Consignor chooses to provide their own photographs for the Item(s) being consigned, all
//                        photographs must be of high quality. TLV reserves the right to refuse any photos that don’t
//                        meet the TLV standards as outlined in the TLV Self Photography Guide.
//                    </li>
//                    <li>
//                        The online sale will be advertised and accessible from TLV\'s website for up to 90 days. If an
//                        Item has not been purchased within the first 90 days of a sale, the listing may be extended with
//                        both the Consignor and TLV’s agreement under the current contract at 30 day intervals. After 3
//                        months TLV may extend the term of the agreement but exercises the right to include new
//                        provisions. While the sale at TLV is running, Consignor agrees not to list the Item(s) for sale or
//                        sell the item(s) through any means/channels including websites or social media sites. While the
//                        sale at TLV is running, Consignor shall not, verbally or through any website or social media
//                        sites, make any representations or warranties regarding the nature or quality of Item(s) listed for
//                        sale, other than those representations or warranties set forth in writing in the Sale Catalog or
//                        otherwise provided by Consignor to TLV in writing.
//                    </li>
//                    <li>
//                        Consignor acknowledges that some Items will be grouped and sold as lots to facilitate their sale.
//                        Items designated for sale cannot be withdrawn or removed by Consignor without penalty as
//                        stated in Section 9 below.
//                    </li>
//                    <li>
//                        TLV shall use its reasonable best efforts to promote the sales of the Item(s) but does not
//                        guarantee any Item(s) will be sold.
//                    </li>
//                    <li>
//                        The advertised price of each Item is set forth in the Sale Catalog, or will be mutually agreed
//                        prior to commencement of the sale. All Item(s) will be priced with a range to allow for
//                        discounting over the first 90 days. Prices listed for each Item exclude applicable sales tax, which
//                        TLV will add to the buyer\'s invoice for each Item sold and collect from buyers with payment for
//                        each Item.
//                        <span style="padding-left:15px;">
//                            <p>
//                                <span><b>8.1. </b></span> TLV reserves the right to discount items after the first 30 days through the life of the
//                                Item(s) listing. The initial discounts during the first 90 days will be within the price range
//                                as per the Sale Catalog. All other discounting after the initial 90 days will be agreed with
//                                the Consignor.
//                            </p>  
//                            <p>
//                                <span><b>8.2. </b></span> At times TLV receives offers for items that are listed on the site. TLV reserves the right to
//                                negotiate with the buyer to receive the best offer within the price range as per the Sale
//                                Catalog. 
//                            </p>
//                            <p>
//                                <span><b>8.3. </b></span> TLV uses Sales Events, Trade Discounts and Coupons to help drive sales of Items.
//                                Discounts range from 10-15% to be shared evenly between TLV and the Consignor.
//                            </p>
//                        </span>
//                    </li>
//                    <li>
//                        If Consignor withdraws or requests TLV to remove any Item listed for sale 24 hours post
//                        photoshoot, Consignor shall pay $50 per Item removed. Once Item(s) are posted online,
//                        Consignor shall pay TLV a cancellation fee equal to 40% of the target sale price of each
//                        cancelled sale or withdrawn Item.
//                    </li>
//                    <li>
//                        Promptly after closing of each sale TLV will schedule with Consignor a date and time for
//                        removal of sold Item(s) within a week after the close of each sale. Consignor will cooperate and
//                        coordinate with TLV to ensure that sold Item(s) is(are) easily accessible for pick-up. Easily
//                        accessible is defined as located on the first floor of a multi-story dwelling including the garage.
//                        All items must be prepared for pick up (i.e. removal of all personal belongings from the Item(s)
//                        sold). Beds must be disassembled. If items are not easily accessible or prepared for pickup,
//                        TLV will deduct 10% from the Consignor’s commission
//                    </li>
//                    <li>
//                        Promptly after closing of each sale TLV will schedule with Consignor a date and time for
//                        removal of sold Item(s) within a week after the close of each sale. Consignor will cooperate and
//                        coordinate with TLV to ensure that sold Item(s) is(are) easily accessible for pick-up. Easily
//                        accessible is defined as located on the first floor of a multi-story dwelling including the garage.
//                        All items must be prepared for pick up (i.e. removal of all personal belongings from the Item(s)
//                        sold). Beds must be disassembled. If items are not easily accessible or prepared for pickup,
//                        TLV will deduct 10% from the Consignor’s commission
//                    </li>
//                    <li>
//                        Buyers may return any or all Consigned Items to the Consignor provided the Item(s) is returned
//                        in the same condition as when removed form the Consignor\'s home or storage facility, and if it
//                        takes place within two weeks of purchase and/or within 48 hours of receipt. All local pickups
//                        must take place within two weeks of the sale unless arrangements are agreed upon with the
//                        Consignor to store for a longer period. No returns will be accepted for any item picked up after
//                        two-weeks from purchase. If the item is being shipped, Buyers must notify TLV of a return
//                        within 2 days after delivery. Buyers will incur return shipping and restocking charges. Buyers
//                        may not be reimbursed for returns that are not received in original condition. If the item is
//                        delivered to a Buyer and is not in the same condition as it was during the time of the inspection
//                        by TLV, the Consignor will bear the return shipping fee. If TLV has misrepresented the item,
//                        TLV will bear the cost. 
//                    </li>
//                    <li>
//                        TLV shall retain a commission of 40% of the net sale proceeds (calculated on the sales price,
//                        excluding sales tax) for its services. TLV shall forward a statement of sale and the remaining
//                        balance of the sales proceeds (excluding sales tax) less any agreed upon expenses to the
//                        Consignor at the address provided within approximately 14 business days after the Item(s) sold
//                        have been received by the buyer and the return period has passed.
//                    </li>
//                    <li>
//                        TLV is not responsible for insuring Items, whether or not in TLV\'s possession, or any other
//                        property or persons at Consignor\'s location(s) throughout the period of this Agreement or at any
//                        other time. Consignor agrees to maintain adequate insurance coverage. TLV shall not be liable
//                        for any loss or damage to items tendered, stored or handled, however caused, unless such loss or
//                        damage resulted from the gross negligence or willful misconduct of TLV. TLV provides no
//                        primary coverage against loss or damage to Consignor’s goods, however caused. The Consignor
//                        declares that TLV’s liability for any damage to any Item is limited to $10 per Item. Any value in
//                        excess of $10 per Item is solely the responsibility of the Consignor.
//                    </li>
//                    <li>
//                        Consignor warrants that s/he has full authority to transfer all title and property rights in all listed
//                        Item(s) free and clear of all liens, claims and encumbrances, and there are no reserved or hidden
//                        security interests in any Item(s) that are the subject of this Agreement.
//                    </li>
//                    <li>
//                        Consignor shall indemnify, defend and hold TLV harmless from and against any losses,
//                        damages, liabilities, costs and expenses, including reasonable attorney’s fees, arising from or
//                        relating to any claim, demand, suit, action or cause of action alleging any loss or damage to
//                        persons or property resulting directly or indirectly from the handling, marketing, sale, delivery
//                        or distribution of Items hereunder by TLV, its agents and employees, or the purchase, transfer,
//                        ownership or use of Item(s) by any third party, including, without limitation, any breach of
//                        warranty, misrepresentation or products liability claims made with respect of such Item(s).
//                    </li>
//                </ol>
//                <p>
//                    I have read the foregoing Agreement and understand the contents thereof; I further represent that
//                    the statements herein made by me are true to the best of my knowledge; that this Agreement
//                    contains and sets out the entire Agreement of the parties unless this is amended in writing signed
//                    by all parties to this Agreement. It is mutually agreed that this Agreement shall be binding and
//                    obligatory upon the undersigned, and the separate heirs, administrators, executors, assigns and
//                    successors of the undersigned:
//                </p>';



        $html .= '<ol style="list-style: bold;">

                    <li>

                        TLV will facilitate the sale of Items consigned through the use of an online 

                        (internet) sale accessible through TLV website at ​www.thelocalvault.com​,as 

                        well as through our partner sites as appropriate. Our partner sites include 

                        but are not limited to: Houzz, eBay and 1stDibs. TLV reserves the right to 

                        reject or decline to handle the sale of any Item.

                    </li>

                    <li>

                        TLV charges a Production fee of $150.00 to send a team member(s) to photograph, 

                        measure and catalog a Consignor’s Item(s). All Photographs of the Item(s) can be 

                        used in TLV promotional, advertising and marketing materials and activities 

                        including social media.

                    </li>

                    <li>

                        All Item(s) must be readily accessible to TLV Staff during the photoshoot. 

                        Any labor required to support the listing of the Item(s) will be passed on to the 

                        Consignor and are not conditional on the sale of the Item(s).

                    </li>

                    <li>

                        The online sale will be advertised and accessible from TLV website for 90 days. 

                        If an Item has not been purchased within the first 90 days of a sale, the listing 

                        will be automatically renewed under the current contract at 60 day intervals. 

                        Consignors may opt out of the renewal and end their sale. While the sale at TLV is 

                        running, Consignor agrees not to list the Item(s) for sale or sell the item(s) 

                        through any means/channels including websites or social media sites. 

                        While the sale at TLV is running, Consignor shall not, verbally or through any 

                        website or social media sites, make any representations or warranties regarding 

                        the nature or quality of Item(s) listed for sale, other than those representations 

                        or warranties set forth in writing in the Sale Catalog or otherwise provided by 

                        Consignor to TLV in writing.

                    </li>

                    <li>

                        Consignor acknowledges that some Items will be grouped and sold as lots to 

                        facilitate their sale. Items designated for sale cannot be withdrawn or removed 

                        by Consignor without penalty as stated in Section 9 below.

                    </li>

                    <li>

                        TLV shall use its reasonable best efforts to promote the sales of the Item(s) 

                        but does not guarantee any Item(s) will be sold.

                    </li>

                    <li>

                        The advertised price of each Item is set forth in the Sale Catalog, or will be 

                        mutually agreed prior to commencement of the sale. All Item(s) will be priced with 

                        a range to allow for discounting over the first 90 days. Prices listed for each 

                        Item exclude applicable sales tax, which TLV will add to the buyer\'s invoice for 

                        each Item sold and collect from buyers with payment for each Item.

                        <span style="padding-left:15px;">

                            <p>

                                <span><b>7.1. </b></span> TLV reserves the right to discount items after the first 

                                30 days through the life of the Item(s) listing. The initial discounts during the 

                                first 90 days will be within the price range as per the Sale Catalog. All other 

                                discounting after the initial 90 days will be agreed with the Consignor.

                            </p>  

                            <p>

                                <span><b>7.2. </b></span> At times TLV receives offers for items that are 

                                listed on the site. TLV reserves the right to negotiate with the buyer to 

                                receive the best offer within the price range as per the Sale Catalog.

                            </p>

                            <p>

                                <span><b>7.3. </b></span> TLV uses Sales Events, Trade Discounts and Coupons to 

                                help drive sales of Items. Discounts range from 10-15% to be shared evenly between 

                                TLV and the Consignor.

                            </p>

                        </span>

                    </li>

                    <li>

                        <b>If Consignor withdraws or requests TLV to remove any Item listed for sale 

                        24 hours post photoshoot, Consignor shall pay $50 per Item removed. Once 

                        Item(s) are posted online, Consignor shall pay TLV a cancellation fee equal to 

                        20% of the target sale price of each cancelled sale or withdrawn Item. 

                        TLV will charge the credit card provided herein.</b>

                        <br>

                        <b>Please initial here to acknowledge you have read section 8: <u>' . (isset($data['acknowledge_section_8']) ? $data['acknowledge_section_8'] : "") . '</u></b>

                        <br>

                    </li>

                    <li>

                       Promptly after closing of each sale TLV will schedule with Consignor a date and 

                       time for removal of sold Item(s) within a week after the close of each sale. 

                       Consignor will cooperate and coordinate with TLV to ensure that sold Item(s) 

                       is(are) easily accessible for pick-up. Easily accessible is defined as located 

                       on the first floor of a multi-story dwelling including the garage. All items 

                       must be prepared for pick up (i.e. removal of all personal belongings from the 

                       Item(s) sold). Beds must be disassembled. If items are not easily accessible or 

                       prepared for pickup, consignor may incur costs related to making the item(s) 

                       accessible.

                    </li>

                    <li>

                        Buyers may return any or all Consigned Items to the Consignor provided the 

                        Item(s) is returned in the same condition as when removed from the Consignor\'s 

                        home or storage facility, and if it takes place within​two weeks of purchase 

                        and/or within 48 hours of receipt. All local pickups must take place within 

                        two weeks of the sale unless arrangements are agreed upon with the Consignor 

                        to store for a longer period. No returns will be accepted for any item picked 

                        up after two-weeks from purchase. If the item is being shipped, Buyers must 

                        notify TLV of a return within 2 days after delivery. Buyers will incur return 

                        shipping and restocking charges. Buyers may not be reimbursed for returns that 

                        are not received in original condition. If the item is delivered to a Buyer and 

                        is not in the same condition as it was during the time of the inspection by TLV, 

                        the Consignor will bear the return shipping fee. If TLV has misrepresented the 

                        item, TLV will bear the cost.

                    </li>

                    <li>

                        TLV shall retain a commission of 40% of the net sale proceeds (calculated on 

                        the sales price, excluding sales tax and credit card fees) for its services. 

                        TLV shall forward a statement of sale and the remaining balance of the sales 

                        proceeds (excluding sales tax and credit card fees) less any agreed upon 

                        expenses to the Consignor at the address provided within approximately 14 

                        business days after the Item(s) sold have been received by the buyer and the 

                        return period has passed.

                    </li>

                    <li>

                        TLV is not responsible for insuring Items, whether or not in TLV possession, 

                        or any other property or persons at Consignor\'s location(s) throughout the 

                        period of this Agreement or at any other time. Consignor agrees to maintain 

                        adequate insurance coverage. TLV shall not be liable for any loss or damage 

                        to items tendered, stored or handled, however caused, unless such loss or 

                        damage resulted from the gross negligence or willful misconduct of TLV. 

                        TLV provides no primary coverage against loss or damage to Consignor​’​s goods, 

                        however caused. The Consignor declares that TLV liability for any damage to 

                        any Item is limited to $10 per Item. Any value in excess of $10 per Item is 

                        solely the responsibility of the Consignor.

                    </li>

                    <li>

                        Consignor warrants that s/he has full authority to transfer all title and 

                        property rights in all listed Item(s) free and clear of all liens, claims and 

                        encumbrances, and there are no reserved or hidden security interests in any 

                        Item(s) that are the subject of this Agreement.

                    </li>

                    <li>

                       Consignor shall indemnify, defend and hold TLV harmless from and against any 

                       losses, damages, liabilities, costs and expenses, including reasonable attorney​’​s 

                       fees, arising from or relating to any claim, demand, suit, action or cause of 

                       action alleging any loss or damage to persons or property resulting directly or 

                       indirectly from the handling, marketing, sale, delivery or distribution of Items 

                       hereunder by TLV, its agents and employees, or the purchase, transfer, ownership 

                       or use of Item(s) by any third party, including, without limitation, any breach 

                       of warranty, misrepresentation or products liability claims made with respect of 

                       such Item(s).

                    </li>

                </ol>

                <p>

                    I have read the foregoing Agreement and understand the contents thereof; I further represent that

                    the statements herein made by me are true to the best of my knowledge; that this Agreement

                    contains and sets out the entire Agreement of the parties unless this is amended in writing signed

                    by all parties to this Agreement. It is mutually agreed that this Agreement shall be binding and

                    obligatory upon the undersigned, and the separate heirs, administrators, executors, assigns and

                    successors of the undersigned:

                </p>';



        $html .= '<table border="0">';

        $html .= '<tr>';

        $html .= '<td colspan="2">';

        $html .= '<b>IN WITNESS WHEREOF, the parties have executed this Agreement:</b>';

        $html .= '</td>';

        $html .= '</tr>';



        $html .= '<tr>';

        $html .= '<td>';

        $html .= '<b>Consignor:</b>';

        $html .= '</td>';

        $html .= '<td>';

        $html .= '<b>The Local Vault:</b>';

        $html .= '</td>';

        $html .= '</tr>';



        $html .= '<tr>';

        $html .= '<td>';

        $html .= 'Name : <u>  ' . $data['consignor_name3'] . '  </u>';

        $html .= '</td>';

        $html .= '<td>';

        //$html .= 'Name : <u>  ' . $data['local_vault_name'] . '  </u>';

        $html .= '</td>';

        $html .= '</tr>';



        $html .= '<tr>';

        $html .= '<td>';

        $html .= 'Date : <u>  ' . $data['consignor_date'] . '  </u>';

        $html .= '</td>';

        $html .= '<td>';

        $html .= 'Date : <u>  ' . $data['local_vault_date'] . '  </u>';

        $html .= '</td>';

        $html .= '</tr>';



        $html .= '<tr>';

        $html .= '<td colspan="2">';

        $html .= '<b>Payment Information:</b>';

        $html .= '</td>';

        $html .= '</tr>';



        $html .= '<tr>';

        $html .= '<td colspan="2">';

        $html .= 'Check payable to : <u>  ' . $data['check_payable'] . '  </u>';

        $html .= '</td>';

        $html .= '</tr>';



        $html .= '<tr>';

        $html .= '<td colspan="2">';

        $html .= 'Mailing Street Address : <u>  ' . $data['mailing_street_address'] . '  </u>';

        $html .= '</td>';

        $html .= '</tr>';



        $html .= '</table>';



        $html .= '<table>';

        $html .= '<tr>';

        $html .= '<td>';

        $html .= 'City : <u>  ' . $data['payment_city'] . '  </u>';

        $html .= '</td>';

        $html .= '<td>';

        $html .= 'State : <u>  ' . $data['payment_state'] . '  </u>';

        $html .= '</td>';

        $html .= '<td>';

        $html .= 'Zip : <u>  ' . $data['payment_zip'] . '  </u>';

        $html .= '</td>';

        $html .= '</tr>';

        $html .= '</table>';

        $html .= '<p>*Direct Deposit Available Upon Request</p>';









//        $html .= '<br>';



        $html .= '<table style="margin-top:0px;">';

        $html .= '<tr>';

        $html .= '<td>';

        $html .= '</td>';

        $html .= '<td>';

        $html .= '</td>';

        $html .= '<td style="border:1px solid black;">';

        $html .= '<img src="' . public_path() . '/../../Uploads/user_agreement_sign/' . $signature_image . '" style="height:100px;width:250px;border:1px solid black;">';

        $html .= '</td>';

        $html .= '</tr>';



        $html .= '<tr>';

        $html .= '<td>';

        $html .= '</td>';

        $html .= '<td>';

        $html .= '</td>';

        $html .= '<td>';

        $html .= '<span>Signature</span>';

        $html .= '</td>';

        $html .= '</tr>';



        $html .= '</table>';







        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->lastPage();

//        $file = 'seller_agreement_' . time();

        if (!$hideCreditCard) {

            $filename = public_path() . '/../../Uploads/user_agreement_pdf/' . $file . '.pdf';
        } else {

            $filename = public_path() . '/../../Uploads/user_agreement_pdf_without_card/' . $file . '.pdf';
        }

        $pdf->output($filename, 'F');

        $pdfs = $file . '.pdf';

        return $pdfs;
    }

    public function pdfGenerateStorageAgreement($data, $signature_image, $file = 'storage_agreement_', $products_storage_agreement) {



        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);

        $pdf->SetAuthor('Test');

        $pdf->SetTitle('Product Reports');

//        $pdf->SetSubject('Daily Report of child');

        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

//        $pdf->SetHeaderData('../../../../../../admin/assets/images/logo.png', PDF_HEADER_LOGO_WIDTH, 'Daily Report for ' . $data['child']['firstname'].' '.$data['child']['lastname'], $date);
//        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);



        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP - 10, PDF_MARGIN_RIGHT);

//        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->setPrintHeader(false);



//        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);



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

        font-family: helvetica;

        font-size: 12px;

        padding: 10px;

    }

    tr {

        padding: 10px;

    }

    td {

       padding: 10px;         

    }

    td.second {

    }

    div.test {

        color: #000000;

        font-family: helvetica;

        font-size: 15px;

        border-style: solid solid solid solid;

        border-width: 0px 0px 0px 0px;

        text-align: center;

        padding:10px;

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

 <div style="width: 100%;display: block;text-align: center;">

     

  </div>

  

                

EOF;



        $html .= '<div style="width: 100%;display: block;text-align: center;">';

        $html .= '<img src="' . public_path() . '/assets/images/long-logo.png" style="height:100px;">';

        $html .= '</div>';

        $html .= '<div style="font:size:20px;text-align:center;padding:4px;">TLV Storage Agreement</div>';

        $html .= '<p>';
        $html .= 'This STORAGE AGREEMENT (herein referred to as the “Agreement”) is a legal agreement between';
        $html .= '<br>The Local Vault (“Storer”) at 301 Valley Road, Cos Cob, CT 06807';
        $html .= '<br>and';
        $html .= '<br><u>   ' . $data['consignor_name'] . ' </u>("Renter") at';
        $html .= '<br><u>   ' . $data['consignor_address'] . '  </u>(Renter’s Address)';
        $html .= '<br>and is made effective as of the <u>   ' . $data['day'] . '    </u> day of <u> ' . $data['month'] . '  </u>, 20 <u>    ' . $data['year'] . '   </u>.';

        $html .= ' 
                <p>
                    In consideration of the respective covenants contained herein the parties hereto agree as follows:
                </p>
                <p>
                   “Property” - includes all items placed in storage at Storage Facility. This includes those items listed in the TLV
                    Storage Proposal as well as any items which Renter subsequently places in storage, as will be agreed in
                    additional Attachments or documents. The items will be considered Property so long as items remain at the
                    Storage Facility.
                </p>
                <p>
                    Renter owns Property and requires a facility or location to temporarily store the said Property.
                </p>
                <p>
                    “Storage Facility” or “Facility” – 480 and/or 510 Barnum Ave, Bridgeport, CT or any additional property leased or
                    owned by Storer for the purpose of storage.
                </p>
                <p>
                    During the term of this Agreement, Storer hereby agrees to store the said Property at the Facility.
                </p>
                <p>
                    “Storage Period” - Storage shall commence on the day the Property arrives at the Storage Facility and shall
                    continue on a month to month basis until Renter takes back the Property or the Property is sold through the
                    Storer per the Renter’s Consignment Agreement winew agreement updateth The Local Vault.
                </p>
                <p>
                    “Storage Fee” or “Fee” - Renter will be charged each month, on or after the first of the month, the Storage Fee
                    for that calendar month. This Fee will be the sum of the individual Storage Fees, as outlined in the TLV Storage
                    Proposal, for all items that are at the Facility as of the 1 st of the month. The first month’s Storage Fee for an
                    item shall be from the date of arrival of the Property at the Facility and will be prorated for the number of days
                    left in the month. Storer may charge to Renter’s credit card, or other payment methods provided by Renter, the
                    Fee on due date of such. There are no options for partial monthly payments or refunds after the first month.
                </p>
                <p>
                    Storage Fee Increase & Decrease - The monthly Fee is subject to increase or decrease based on the items added
                    to or removed from Property. If Renter wishes to remove any Property then Storer must be notified at
                    logistics@thelocalvault.com to arrange for pickup of this Property from the Storage Facility.
                </p>
                <p>
                    Inspection - Storer and Renter are each responsible for inspection of goods when they arrive at the Facility.
                    Storer is not responsible for damage caused by moving, delivery or packing materials not provided by Storer.
                </p>
                <p>
                    No Refunds - Storage Fees paid by Renter are not refundable.
                </p>
                <p>
                    Termination of Agreement - The Storer reserves the right to terminate this Agreement at any time by giving the
                    Renter thirty (30) days written notice of its intention to do so. In the event the Renter fails to remove any stored
                    Property within the thirty (30) day period the Storer reserves the right to have the Property removed from the
                    Storage Facility and disposed of at the expense of the Renter. In such an event the Storer shall be relieved of any
                    liability with respect to the Property therefore or thereafter. The Renter has the right to terminate the
                    Agreement at any time and once all Property is removed from Facility then Storage Fees will no longer be
                    charged.
                </p>
                <p>
                    Non-Payment of Storage Fees - In the event the Renter does not pay any unpaid balance of Storage Fees the
                    Storer, after giving the Renter thirty (30) days written notice, can treat the Property as abandoned. Storer may
                    sell such abandoned Property in a commercially reasonable manner and apply the proceeds from such sale
                    towards any unpaid Storage Fees as well as to any costs incurred in the sale. Storer will forward any remaining
                    balance of the proceeds to Renter. Storer also has the right to donate or dispose of the Property as it is deemed
                    abandoned. In all such events the Storer shall be relieved of any liability with respect to the Property therefore
                    or thereafter. Any expense Storer incurs to donate or dispose of the Property will be for the account of Renter.
                </p>
                <p>
                    Ownership of the Property - Nothing contained in this Agreement shall be construed or interpreted as conveying
                    title to, or any interest in, the Property to the Storer other than in the event of Non-Payment of Storage Fees.
                </p>
                <p>
                    Warranties - The Renter represents and warrants that he/she/it is the legal owner of the Property, or an agent
                    thereof, and has the legal right and authority to contract for services for all of the Property. Renter agrees to
                    indemnify and hold harmless the Storer from and against any and all claims relating to breach of this warranty.
                </p>
                <p>
                    Renter’s Property Insurance Requirement - Renter agrees to maintain adequate insurance coverage for
                    Property. The Storer agrees to exercise reasonable care to protect the Property from theft or damage and shall
                    maintain adequate insurance to protect the Renter from any loss or damage caused by the Storer\'s gross
                    negligence or willful misconduct. Storer shall not be liable for any loss or damage to Item(s) tendered, stored or
                    handled, however caused, unless such loss or damage resulted from the gross negligence or willful misconduct
                    of Storer. Storer provides no primary coverage against loss or damage to Property, unless such loss or damage is
                    caused by Storer’s gross negligence or willful misconduct. Renter agrees to maintain adequate insurance
                    coverage.
                </p>
                <p>
                    <b>
                        Warranty Disclaimer - STORER PROVIDES THE FACILITY AND THE SERVICES "AS IS" WITHOUT WARRANTY OF ANY
                        KIND, EITHER EXPRESS, IMPLIED OR STATUTORY.
                    </b>
                </p>
                <p>
                    Entire Agreement - This document represents the entire agreement and understanding between the parties for
                    the storage of Property. It replaces and supersedes any and all oral agreements between the parties, as well as
                    any prior written agreements, for the storage of Property.
                </p>
                <p>
                    Successors and Assignees - This agreement binds and benefits the heirs, successors, and assignees of the parties.
                </p>
                <p>
                    Notices- All notices which may be or are required to be given by any party to the other under this Agreement,
                    shall be in writing and (i) delivered personally, or (ii) sent by prepaid courier service or registered mail to the
                    parties at their respective addresses first above mentioned. Any such notice so given shall be deemed
                    conclusively to have been given and received when so personally delivered or delivered by courier, or on the
                    fifth day, in the absence of evidence to the contrary, following the sending thereof by registered mail. Any party
                    may from time to time change its address hereinbefore set forth by notice to the other parties in accordance
                    with this paragraph.
                </p>
                <p>
                    Governing Law -This agreement will be governed by and construed in accordance with the laws of the state of
                    Connecticut.
                </p>
                <p>
                    Dispute Resolution - Any controversy or claim arising out of or relating to this contract, the breach thereof, or
                    the goods affected thereby, whether such claims be found in tort or contract shall be settled by arbitration
                    under the rules of the American Arbitration Association, provided however, that upon any such arbitration the
                    arbitrator(s) may not vary or modify any of the foregoing provisions.
                </p>
                <p>
                    Modification - This agreement may be modified only by a written agreement signed by all the parties.
                </p>
                <p>
                    Waiver - If one party waives any term or provision of this agreement at any time, that waiver will only be
                    effective for the specific instance and specific purpose for which the waiver was given. If either party fails to
                    exercise or delays exercising any of its rights or remedies under this agreement, that party retains the right to
                    enforce that term or provision at a later time.
                </p>
                <p>
                    Severability - If any court determines that any provision of this Agreement is invalid or unenforceable any
                    invalidity or unenforceability will affect only that provision and will not make any other provision of this
                    agreement invalid or unenforceable.
                </p>
                <p>
                    IN WITNESS WHEREOF, the parties hereto have executed this Agreement subject to the terms and conditions
                    herein set forth
                </p>';

        $html .= '<br>
                    <p>
                        STORER - The Local Vault, LLC
                    </p>
                    <p>
                        RENTER - <u>    ' . $data['consignor_renter'] . '   </u>
                    </p>
                <br>';
        $html .= '<table border = "0">';

        $html .= '<tr>';
        $html .= '<td colspan = "3" style="text-align:center;">';
        $html .= "<b>Please complete the information below:</b>";
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td colspan="3">';
        $html .= '<p>
                    I <u>   ' . $data['billing_name'] . '   </u> authorize The Local Vault, LLC to charge my
                    credit card indicated below for Storage Fees.
                 </p>';
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td colspan="3">';
        $html .= '<b>Billing Address : </b><u>  ' . $data['billing_address'] . '  </u>';
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td>';
        $html .= '<b>City : </b><u>  ' . $data['billing_city'] . '  </u>';
        $html .= '</td>';
        $html .= '<td>';
        $html .= '<b>State : </b><u>  ' . $data['billing_state'] . '  </u>';
        $html .= '</td>';
        $html .= '<td>';
        $html .= '<b>Zip : </b><u>  ' . $data['billing_zip'] . '  </u>';
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '</table>';

        $html .= '<table>';

        $html .= '<tr>';
        $html .= '<td>';
        $html .= '<b>Phone : </b><u>  ' . $data['billing_phone'] . '  </u>';
        $html .= '</td>';
        $html .= '<td>';
        $html .= '<b>Email : </b><u>  ' . $data['billing_email'] . '  </u>';
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '</table>';

        $html .= '<ul style="list-style: bold;list-style: none;display:inline;">
                    <li style="display:inline;padding:5px">
                      ' . ((isset($data['card_type']) && $data['card_type'] == "VISA") ? "VISA" : "") . ' 
                      ' . ((isset($data['card_type']) && $data['card_type'] == "MASTERCARD") ? "MASTERCARD" : "") . ' 
                      ' . ((isset($data['card_type']) && $data['card_type'] == "AMEX") ? "AMEX" : "") . ' 
                      ' . ((isset($data['card_type']) && $data['card_type'] == "DISCOVER") ? "DISCOVER" : "") . ' 
                    </li>
                  </ul>';
        $html .= '<table>';

        $html .= '<tr>';
        $html .= '<td colspan="2">';
        $html .= '<b>Card Number : </b><u>  ' . $data['credit_card_cc'] . '  </u>';
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td>';
        $html .= '<b>Exp Date : </b><u> ' . $data['credit_card_expiry_month'] . '/' . $data['credit_card_expiry_year'] . '    </u>';
        $html .= '</td>';
        $html .= '<td>';
        $html .= '<b>CVV Code : </b><u>  ' . $data['credit_card_security_code'] . '  </u>';
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td colspan="2">';
        $html .= '<b>Date : </b><u>  ' . $data['consignor_date'] . '  </u>';
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '</table>';

        $html .= '<br>';

        $html .= '<table style="margin-top:0px;">';

        $html .= '<tr>';
        $html .= '<td>';
        $html .= '</td>';
        $html .= '<td>';
        $html .= '</td>';
        $html .= '<td style="border:1px solid black;">';
        $html .= '<img src="' . public_path() . '/../../Uploads/storage_agreement_sign/' . $signature_image . '" style="height:100px;width:250px;border:1px solid black;">';
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td>';
        $html .= '</td>';
        $html .= '<td>';
        $html .= '</td>';
        $html .= '<td>';
        $html .= '<span>Signature</span>';
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '</table>';


        $html .= '<table border = "1">';

        $html .= '<tr>';
        $html .= '<td colspan = "5" style="text-align:center;">';
        $html .= "<b>Storage Products</b>";
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<th>';
        $html .= '<b>Image</b>';
        $html .= '</th>';
        $html .= '<th>';
        $html .= '<b>Product Name</b>';
        $html .= '</th>';
        $html .= '<th>';
        $html .= '<b>Product SKU</b>';
        $html .= '</th>';
        $html .= '<th>';
        $html .= '<b>Price</b>';
        $html .= '</th>';
        $html .= '<th>';
        $html .= '<b>Storage Price</b>';
        $html .= '</th>';
        $html .= '</tr>';

        foreach ($products_storage_agreement as $key => $value) {
            $html .= '<tr>';
            $html .= '<td>';
            if (count($value->getProductId()->getProductPendingImages()) > 0) {
                $html .= '<img style = "height: 100px;width: 100px;margin: 10px;" src = "' . config('app.url') . '/Uploads/product/' . $value->getProductId()->getProductPendingImages()[0]->getName() . '"/>';
            }
            $html .= '</td>';
            $html .= '<td>';
            if ($value->getProductId()->getName()) {
                $html .= $value->getProductId()->getName();
            }
            $html .= '</td>';
            $html .= '<td>';
            if ($value->getProductId()->getSku()) {
                $html .= $value->getProductId()->getSku();
            }
            $html .= '</td>';
            $html .= '<td>';
            if ($value->getPrice()) {
                $html .= $value->getPrice();
            }
            $html .= '</td>';
            $html .= '<td>';
            if ($value->getStorage_pricing()) {
                $html .= $value->getStorage_pricing();
            }
            $html .= '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';



        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->lastPage();

//        $file = 'seller_agreement_' . time();



        $filename = public_path() . '/../../Uploads/storage_agreement_pdf/' . $file . '.pdf';


        $pdf->output($filename, 'F');

        $pdfs = $file . '.pdf';

        return $pdfs;
    }

    public function pdfGenerateSellerAgreement($data, $signature_image, $file = 'seller_agreement_', $hideCreditCard = false) {



        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);

        $pdf->SetAuthor('Test');

        $pdf->SetTitle('Product Reports');

        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);



        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP - 10, PDF_MARGIN_RIGHT);

//        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->setPrintHeader(false);



//        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);



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

        font-family: helvetica;

        font-size: 12px;

        padding: 10px;

    }

    tr {

        padding: 10px;

    }

    td {

       padding: 10px;         

    }

    td.second {

    }

    div.test {

        color: #000000;

        font-family: helvetica;

        font-size: 15px;

        border-style: solid solid solid solid;

        border-width: 0px 0px 0px 0px;

        text-align: center;

        padding:10px;

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

 <div style="width: 100%;display: block;text-align: center;">

     

  </div>

  

                

EOF;



        $html .= '<div style = "width: 100%;display: block;text-align: center;">';

        $html .= '<img src = "' . public_path() . '/assets/images/long-logo.png" style = "height:100px;">';

        $html .= '</div>';

        $html .= '<div style = "font:size:20px;text-align:center;padding:4px;">CONSIGNMENT AGREEMENT</div>';

        $html .= '<p><b>';

        $html .= 'On the <u> ' . $data['day'] . ' </u> day of <u> ' . $data['month'] . ' </u>, 20<u>' . $data['year'] . ' </u> this “Agreement” is made by and among';

        $html .= 'The Local Vault, LLC, 301 Valley Rd, Cos Cob, CT 06807 ("TLV") and :';

        $html .= '</b></p>';



        $html .= '<table border = "0">';



        $html .= '<tr>';

        $html .= '<td colspan = "3">';

        $html .= "<b>Consignor's Name : </b><u> " . $data['consignor_name'] . " </u> (“Consignor”)";

        $html .= '</td>';

        $html .= '</tr>';



        $html .= '<tr>';

        $html .= '<td colspan="3">';

        $html .= '<b>Address : </b><u>  ' . $data['address'] . '  </u>';

        $html .= '</td>';

        $html .= '</tr>';



        $html .= '<tr>';

        $html .= '<td>';

        $html .= '<b>City : </b><u>  ' . $data['city'] . '  </u>';

        $html .= '</td>';

        $html .= '<td>';

        $html .= '<b>State : </b><u>  ' . $data['state'] . '  </u>';

        $html .= '</td>';

        $html .= '<td>';

        $html .= '<b>Zip : </b><u>  ' . $data['zip'] . '  </u>';

        $html .= '</td>';

        $html .= '</tr>';



        $html .= '</table>';



        $html .= '<table>';



        $html .= '<tr>';

        $html .= '<td>';

        $html .= '<b>Cell Phone : </b><u>  ' . $data['phone'] . '  </u>';

        $html .= '</td>';

        $html .= '</tr>';



        $html .= '<tr>';

        $html .= '<td>';

        $html .= '<b>Email : </b><u>  ' . $data['email'] . '  </u>';

        $html .= '</td>';

        $html .= '</tr>';



        $html .= '</table>';



        $html .= '<p>Hereinafter the personal property placed in consignment through this Consignment Agreement will be described as
“Item(s)”. Item(s) will be listed for sale on TLV’s website for an “Initial Term” of 6-months. Please review section 12 of
this Agreement for full listing details.</p>';



        $html .= '<p>Consignor grants unto TLV the authority to advertise, offer for sale and sell the Item(s) listed in the TLV “Pricing
Proposal” which you will receive after the Item(s) is photographed, measured and evaluated. Consignor has the right to
withdraw any Item(s) from the consignment within 48 hours after the Pricing Proposal is sent. The Pricing Proposal will
include the sale price at which TLV believes the Item(s) should be listed for sale.
</p>';

        $html .= '<p>Consignor confirms that the Item(s) included in the “Pricing Proposal”, and any additional Item(s) which the Consignor
chooses to consign with TLV, is generally described personal property belonging to the Consignor or the individual(s) or
estate that Consignor is acting as the agent for. Consignor agrees to indemnify and hold TLV harmless from and against
any and all claims relating to breach of this warranty.</p>';

        $html .= '<p>In the Pricing Proposal, when <b>"Dropoff by Consignor Required"</b> is checked and the Item is not in TLV storage facility,
the Consignor agrees to drop it off at The Local Vaults office in Cos Cob, CT, within two weeks from the date the Item
was purchased by the "Buyer." Should the Consignor not drop off the Item within these two weeks, TLV will arrange for
the Item to be picked up, and the Consignor will be charged a $75 fee.</p>';

//        $html .= '<p>
//
//                    <b>Please choose your preferred option for Item(s) pick-up:</b>
//
//                </p>
//
//                <ul style="list-style: bold;
//            list-style: none;
//            ">
//
//                    <li>
//
//                       ' . ((isset($data['preferred_local_pick_up']) && $data['preferred_local_pick_up'] == 1) ? "I will allow pick-up of Item(s) to take place at the location designated below." : "") . ' 
//
//                       ' . ((isset($data['preferred_local_pick_up']) && $data['preferred_local_pick_up'] == 2) ? "If Item(s) are small, I am willing to drop them off at The Local Vault Office in Cos Cob, CT within a week from the date the Item was purchased by the Buyer." : "") . '
//
//                    </li>
//
//                </ul>';


        $html .= 'For larger Item(s) Consignor will allow pick-up to take place at the location designated below.';

        $html .= '<p>

                    <b>Please indicate below the location where the larger Item(s) will be available for pick-up:</b>

                </p>';



        $html .= '<ul style="list-style: bold;
            list-style: none">

                 <li>';

//        if (isset($data['address_as_above']) && $data['address_as_above'])
//        {
//            $html .= "Consignor’s Address as listed above";
//        } else
//        {



        $html .= '<table border="0">';



        $html .= '<tr>';

        $html .= '<td colspan="3">';

        $html .= '<b>Address : </b><u>' . (isset($data['other_address']) ? $data['other_address'] : "") . '</u>';

        $html .= '</td>';

        $html .= '</tr>';



        $html .= '<tr>';

        $html .= '<td>';

        $html .= '<b>City : </b><u>' . (isset($data['other_city']) ? $data['other_city'] : "") . '</u>';

        $html .= '</td>';

        $html .= '<td>';

        $html .= '<b>State : </b><u>' . (isset($data['other_state']) ? $data['other_state'] : "") . '</u>';

        $html .= '</td>';

        $html .= '<td>';

        $html .= '<b>Zip : </b><u>' . (isset($data['other_zip']) ? $data['other_zip'] : "") . '</u>';

        $html .= '</td>';

        $html .= '</tr>';



        $html .= '</table>';

//        }

        $html .= '</li></ul>';



        $html .= '<p>

                    <b>The Item(s) shall be listed for sale, as described below, as soon as practicable and expected to be on or about 2
weeks from the date the Pricing Proposal is sent.</b>

                </p>';



        $html .= '<p>

                    <b>Consignor and TLV agree as follows:</b>

                 </p>';

        $html .= '<ol style="list-style: bold;
            ">

                 <li>
                        TLV will facilitate the sale of Item(s) consigned through the use of an online (internet) sale accessible through
the TLV website at <a href="https://thelocalvault.com" target="_blank">www.thelocalvault.com</a> and, as TLV deems appropriate, through our partner sites. Our
partner sites include, but are not limited to, Houzz, eBay and 1stDibs. 

                    </li>

                    <li>

                       TLV reserves the right to decline to handle the sale of any Item(s). 

                    </li>

                    <li>
                        TLV will send a TLV agent(s) to photograph, potentially video, measure and catalog a Consignor’s Item(s). TLV
charges a Production Fee of $50.00 when photographing 10 or less Items plus $5.00 for each Item above 10
Items. All Item(s) must be readily accessible during this “Photoshoot”. Any labor costs required to support the
TLV agent in the photography and measurement of the Item(s) will be passed on to the Consignor. Payment of
such costs is not conditional on the sale of the Item(s). Consignor confirms that all LIGHTING that is consigned
is operational unless otherwise noted to TLV agent.<b>Should Item condition change after Photoshoot
Consignor will notify TLV of such change so listing can be adjusted and, if the Item has sold, change can
be discussed with buyer prior to TLV arranging pickup of the Item.</b>
                    </li>

                    <li>
                        All photographs and videos which capture the Item(s) can be used in TLV promotional, advertising and
marketing materials and activities including use on websites, in social media and on other promotional platforms.   
                    </li>

                    <li>
Unless, after the Photoshoot, it is determined that an Item is not suitable for listing, the Item(s) will be advertised
and offered for sale on the TLV website at the Advertised Price (defined below). Consignor acknowledges that
some Items may be grouped and sold as lots to facilitate their sale. While Item(s) is for sale through TLV,
Consignor agrees not to make the Item(s) available for sale or sell the Item(s) through any other means/channels
including but not limited to websites, social media sites and other consignors. While Item(s) is for sale through
TLV, Consignor shall not, verbally or through any website or social media sites, make any representations or
warranties regarding the nature or quality of Item(s) offered for sale other than those representations or
warranties set forth in writing in the Pricing Proposal or otherwise provided by Consignor to TLV in writing. 
                    </li>

                    <li>
                        TLV shall use its reasonable best efforts to promote the sale of the Item(s) but does not guarantee any Item(s)
will be sold. 
                    </li>

                    <li>
“Advertised Price” is the price at which each Item(s) is offered for sale on the TLV website. For the initial 3
months of the sale, the “Advertised Price” of the Item(s) will be the “TLV Price” as set forth in the Pricing
Proposal unless a different price is mutually agreed prior to commencement of the sale. Should an Item not sell
during this initial 3-month period, the Advertised Price for the Item(s) will automatically be reduced by 30%.

                    </li>

                    <li>
The Advertised Price listed for each Item excludes applicable sales tax, which TLV will add to the Buyers
invoice for each Item sold and collect from the Buyer.

                    </li>

                    <li>
                   TLV uses Sales Events, Trade Discounts and Coupons to help drive sales of Items. These "Discounts” offered to
prospective buyers range from 10-15%. For a sold Item(s) any Discounts from the Advertised Price will be
shared between TLV and the Consignor.
                    </li>

                    <li>              
                    Item(s) made available for sale through TLV include the “Make-an-Offer” functionality. Make-an-Offer allows
prospective buyers to “Offer” to buy an Item at a price below the Advertised Price. If such an Offer is made the
Consignor will then have the ability to “accept”, “reject” or “counter” the Offer. Please note that Discounts will
not be applied when Buyer is utilizing the Make an Offer feature.

                    </li>

            <li>
                When Buyer takes possession of the Item(s) the sale is considered “Completed”. When the sale of an Item(s) is
Completed TLV shall retain a commission of 40% of the “Sale Price” for its services. The Sale Price is the price
paid by the Buyer for the Item(s) less any transaction fees. The “Net Sale Proceeds” to be received by the
Consignor is calculated as the Sale Price less TLV commission. The Net Sale Proceeds will be sent to the
Consignor at the address provided within approximately 14 business days after the sale is Completed. 
            </li>

            <li>
               This Consignment Agreement is for an “Initial Term” of 6-months. Prior to the end of this Initial Term TLV will
notify the Consignor via email that an unsold Item(s) will be removed from the site unless Consignor expresses a
desire to extend the sale. If the Consignor wishes to continue to list their unsold Item(s) the sale will be extended
for an additional 3-month term at the Advertised Price. Subsequent 3-month extensions will be determined via
the same process at the end of each 3-month term.
            </li>

            <li>

            <b>
                As stated above, Consignor has the right to withdraw any Item(s) from the consignment for a period of 48
hours after the Pricing Proposal is sent. Thereafter, should Consignor request or demand that Item(s) is
withdrawn from sale Consignor shall pay TLV a Cancellation Fee equal to 40% of the Advertised Price of
any Item(s) in the event there is a sale agreed with a Buyer that is cancelled by Consignor OR where
Item(s) is withdrawn by Consignor during the Initial Term or any subsequent extensions. In such event 
TLV will charge the Consignor’s credit card provided herein or bill the Consignor for the Cancellation
Fee. 

                </b>

            <br>

            <br>

            <b>Please initial here to acknowledge you have read section 13:<u>' . (isset($data['acknowledge_section_8']) ? $data['acknowledge_section_8'] : "") . '</u></b>

            <br>

            </li>';





        if (!$hideCreditCard) {



            $html .= '<table>';

            $html .= '<tr>';

            $html .= '<td colspan = "2">';

            $html .= '<b>Credit Card Information:</b>';

            $html .= '</td>';

            $html .= '</tr>';



            $html .= '<tr>';

            $html .= '<td colspan = "2">';

            $html .= 'Name on Credit Card : <u> ' . $data['credit_card_name'] . ' </u>';

            $html .= '</td>';

            $html .= '</tr>';



            $html .= '<tr>';

            $html .= '<td colspan = "2">';

            $html .= 'CC# : <u> ' . $data['credit_card_cc'] . '    </u>';

            $html .= '</td>';

            $html .= '</tr>';



            $html .= '<tr>';

            $html .= '<td>';

            $html .= 'Exp Date : <u> ' . $data['credit_card_expiry_month'] . '/' . $data['credit_card_expiry_year'] . '    </u>';

            $html .= '</td>';

            $html .= '<td>';

            $html .= 'CVV Code: <u>    ' . $data['credit_card_security_code'] . '    </u>';

            $html .= '</td>';

            $html .= '</tr>';



            $html .= '<tr>';

            $html .= '<td colspan="2">';

            $html .= 'Billing Address : <u>  ' . $data['credit_card_billing_address'] . '  </u>';

            $html .= '</td>';

            $html .= '</tr>';



            $html .= '</table>';





            $html .= '<table>';

            $html .= '<tr>';

            $html .= '<td>';

            $html .= 'City : <u>  ' . $data['credit_card_city'] . '  </u>';

            $html .= '</td>';

            $html .= '<td>';

            $html .= 'State : <u>  ' . $data['credit_card_state'] . '  </u>';

            $html .= '</td>';

            $html .= '<td>';

            $html .= 'Zip : <u>  ' . $data['credit_card_zip'] . '  </u>';

            $html .= '</td>';

            $html .= '</tr>';

            $html .= '</table>';
        }



        $html .= ' <li>
                    After sale of an Item is agreed with a Buyer(s), unless Consignor is obligated to drop off Item at The Local
Vault’s office, TLV will schedule with Consignor a date and time for a pick-up of sold Item. After the Item’s
sale pick-up is expected to occur within 2 weeks for delivery to a local buyer and within 6 weeks if delivery to
the buyer will be by a national shipper. Consignor will cooperate and coordinate with TLV to ensure that sold
Item is Easily Accessible for pick-up. “Easily Accessible” is defined as located on the first floor of a multi-story
dwelling including the garage. All Items must be prepared for pick up (i.e. removal of all personal belongings
from the Item(s) sold and beds must be disassembled). If Items are not Easily Accessible and prepared for
pickup, Consignor may incur costs related to picking up the Items. 
                    </li>

                    <li>
                       Buyer is responsible for delivery or shipping costs of the Item. Buyer may refuse any Item at pickup or at the
time of delivery should the Item not meet Buyer’s expectation. If Buyer chooses to not accept an Item even
though it is in the condition that was represented on the TLV website then Buyer shall be responsible for any
costs related to the return of the Item. If TLV has misrepresented the item then TLV will bear the return costs.
Once Buyer takes possession of Item the Item is no longer eligible for return and the sale is considered
“Completed”.
                    </li>

                    <li>
                        TLV shall not be liable for any loss or damage to Item(s) tendered, stored or handled, however caused, unless
such loss or damage resulted from the gross negligence or willful misconduct of TLV. TLV provides no primary
coverage against loss or damage to Consignor’s Item(s), however caused. Consignor agrees to maintain adequate
insurance coverage.
                    </li>

                    <li>
                        Consignor warrants that he/she/it has full authority to transfer all title and property rights in the consigned
Item(s) free and clear of all liens, claims and encumbrances, and there are no reserved or hidden security
interests in any Item(s) that is the subject of this Agreement.  
                    </li>

                    <li>
                        Consignor shall indemnify and defend TLV from and against any losses, damages, liabilities, and expenses,
including reasonable attorney’s fees, arising from or relating to any claim alleging any loss or damage to persons
or property, related to any transaction or interaction with TLV and its agents. 
 
                    </li>

                </ol>

                <p>
                    If any court determines that any provision of this Agreement is invalid or unenforceable, any invalidity or
unenforceability will affect only that provision and will not make any other provision of this agreement invalid or
unenforceable.
                </p>

                <p>
                   Successors and Assignees - This agreement binds and benefits the heirs, successors, and assignees of the parties.
                </p>
                
                <p>
                Governing Law -This agreement will be governed by and construed in accordance with the laws of the state of
Connecticut.
                </p>
                
                <p>
                Dispute Resolution - Any controversy or claim arising out of or relating to this contract, the breach thereof, or the goods
affected thereby, whether such claims be found in tort or contract shall be settled by arbitration under the rules of the 
American Arbitration Association, provided however, that upon any such arbitration the arbitrator(s) may not vary or
modify any of the foregoing provisions.
                </p>
                
                <p>
                Modification - This agreement may be modified only by a written agreement signed by all the parties.   
                </p>
                
                <p>
                Waiver - If one party waives any term or provision of this agreement at any time, that waiver will only be effective for the
specific instance and specific purpose for which the waiver was given. If either party fails to exercise or delays exercising
any of its rights or remedies under this agreement, that party retains the right to enforce that term or provision at a later
time.
                </p>
                
                <p>
                Entire Agreement - This document represents the entire agreement and understanding between the parties. It replaces and
supersedes any and all oral agreements between the parties, as well as any prior written agreements
                </p>
                
                <p>
                   I have read the foregoing Agreement and understand the contents thereof. I further represent that the statements herein
made by me are true to the best of my knowledge and that this Agreement contains and sets out the entire Agreement of
the parties unless this is amended in writing and signed by all parties to this Agreement. It is mutually agreed that this
Agreement shall be binding and obligatory upon the undersigned, and the separate heirs, administrators, executors, assigns
and successors of the undersigned:  
                </p>

    ';



        $html .= '<table border="0">';

        $html .= '<tr>';
        $html .= '<td colspan="2">';
        $html .= '<b>IN WITNESS WHEREOF, the parties have executed this Agreement:</b>';
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td colspan="2">';
        $html .= '<b>The Local Vault, LLC</b>';
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td colspan="2">';
        $html .= '<b>and</b>';
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td colspan="2">';
        $html .= '<b>Consignor:</b>';
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td>';
        $html .= 'Name : <u>  ' . $data['consignor_name3'] . '  </u>';
        $html .= '</td>';
        $html .= '<td>';
        $html .= '</td>';
        $html .= '</tr>';


        $html .= '<tr>';
        $html .= '<td>';
        $html .= 'Date : <u>  ' . $data['consignor_date'] . '  </u>';
        $html .= '</td>';
        $html .= '<td>';
//        $html .= 'Date : <u>  ' . $data['local_vault_date'] . '  </u>';
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td colspan="2">';
        $html .= '<b>Information for Payment to Consignor (if payment recipient is not Consignor and/or if mailing address is not address listed above):</b>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td colspan="2">';
        $html .= 'Check payable to : <u>  ' . $data['check_payable'] . '  </u>';
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td colspan="2">';
        $html .= 'Mailing Street Address : <u>  ' . $data['mailing_street_address'] . '  </u>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';



        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<td>';
        $html .= 'City : <u>  ' . $data['payment_city'] . '  </u>';
        $html .= '</td>';
        $html .= '<td>';
        $html .= 'State : <u>  ' . $data['payment_state'] . '  </u>';
        $html .= '</td>';
        $html .= '<td>';
        $html .= 'Zip : <u>  ' . $data['payment_zip'] . '  </u>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $html .= '<p>*Direct Deposit Available Upon Request</p>';

//        $html .= '<br>';

        $html .= '<table style="margin-top:0px;">';

        $html .= '<tr>';
        $html .= '<td>';
        $html .= '</td>';
        $html .= '<td>';
        $html .= '</td>';
        $html .= '<td style="border:1px solid black;">';
        $html .= '<img src="' . public_path() . '/../../Uploads/user_agreement_sign/' . $signature_image . '" style="height:100px;width:250px;border:1px solid black;">';
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td>';
        $html .= '</td>';
        $html .= '<td>';
        $html .= '</td>';
        $html .= '<td>';
        $html .= '<span>Signature</span>';
        $html .= '</td>';
        $html .= '</tr>';

        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->lastPage();

//        $file = 'seller_agreement_' . time();

        if (!$hideCreditCard) {

            $filename = public_path() . '/../../Uploads/user_agreement_pdf/' . $file . '.pdf';
        } else {

            $filename = public_path() . '/../../Uploads/user_agreement_pdf_without_card/' . $file . '.pdf';
        }

        $pdf->output($filename, 'F');

        $pdfs = $file . '.pdf';

        return $pdfs;
    }

    public function saveSeller(Request $request) {

        $data = $request->all();

        // $data['assign_agent_id'] = '';
        if (isset($request->assign_agent_id['id'])) {
            $data['assign_agent_id'] = $this->user_repo->UserOfId($request->assign_agent_id['id']);
        }
        
        if ($request->id) {

            $data['firstname'] = str_replace(' ', '', $data['firstname']);

            $data['lastname'] = str_replace(' ', '', $data['lastname']);



            $data['update_seller_roles'] = [];

//            foreach ($data['seller_roles'] as $key => $value)
//            {
//            $data['update_seller_roles'][] = $this->option_repo->OptionOfId($data['seller_roles']);
//            }

            if ($data['password'] == '********') {

                unset($data['password']);
            }

            $details = $this->seller_repo->SellerOfId($request->id);
            // dd($details);

            if ($this->seller_repo->update($details, $data)) {
                
                $seller_details = array();

                $seller_details['data'] = json_encode($data);

//                $host = 'https://localvault.staging.wpengine.com/wp-content/themes/thelocalvault/seller-update.php';
                $host = 'https://thelocalvault.com/wp-content/themes/thelocalvault/seller-update.php';

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $host);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                curl_setopt($ch, CURLOPT_POST, true);

                curl_setopt($ch, CURLOPT_POSTFIELDS, $seller_details);

                //temp_stop

                $temp = curl_exec($ch);



                return response()->json('Seller Updated Successfully', 200);
            }
        } else {

            $d = $request->all();



            $d['firstname'] = str_replace(' ', '', $d['firstname']);

            $d['lastname'] = str_replace(' ', '', $d['lastname']);



            $seller = $d;

            $d['key'] = 'mltvqwqs';

            if ($d['seller_roles'] == 81) {

                $d['role'] = 'seller';
            }

            if ($d['seller_roles'] == 82) {

                $d['role'] = 'trader';
            }



            $data['data'] = json_encode($d);



//            $host = 'https://localvault.staging.wpengine.com/wp-content/themes/thelocalvault/new-user.php';
            $host = 'https://thelocalvault.com/wp-content/themes/thelocalvault/new-user.php';

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $host);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_POST, true);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            //temp_stop

            $temp = curl_exec($ch);



            if ($temp != "User already exists.") {

                $temp2 = json_decode($temp);

//                $seller['firstname'] = $data['data']['firstname'];
//                $seller['firstname'] = $data['data']['lastname'];
//                $seller['shopname'] = $d['shopname']; 
//                $seller['shopurl'] = $d['shopurl'];
//                $seller['password'] = bcrypt($d['password']);
//                $seller['wp_seller_id'] = $temp2->data->ID;
//                $seller['display_name'] = $temp2->data->display_name;
//
//                $prepared_data_seller = $this->seller_repo->prepareData($seller);
//                $s = $this->seller_repo->create($prepared_data_seller);

                return response()->json('Seller saved Successfully', 200);
            } else {

                return response()->json($temp, 500);
            }
        }
    }

    public function saveWPSeller(Request $request) {

        $data = $request->all();

        Log::info(json_encode($data));

        $seller_detail = array();



        if (isset($data['account_first_name'])) {

//            $seller_detail['firstname'] = $data['account_first_name'];

            $seller_detail['firstname'] = str_replace(' ', '', $data['account_first_name']);
        } else if (isset($data['first_name'])) {

            $seller_detail['firstname'] = str_replace(' ', '', $data['first_name']);
        } else if (isset($data['firstname'])) {

            $seller_detail['firstname'] = str_replace(' ', '', $data['firstname']);
        } else if (isset($data['fname'])) {

            $seller_detail['firstname'] = str_replace(' ', '', $data['fname']);

            ;
        }







        if (isset($data['account_last_name'])) {

            $seller_detail['lastname'] = str_replace(' ', '', $data['account_last_name']);
        } else if (isset($data['last_name'])) {

            $seller_detail['lastname'] = str_replace(' ', '', $data['last_name']);
        } else if (isset($data['lastname'])) {

            $seller_detail['lastname'] = str_replace(' ', '', $data['lastname']);
        } else if (isset($data['lname'])) {

            $seller_detail['lastname'] = str_replace(' ', '', $data['lname']);
        }





        if (isset($data['account_email'])) {

            $seller_detail['email'] = $data['account_email'];
        } else if (isset($data['email'])) {

            $seller_detail['email'] = $data['email'];
        }



        if (isset($data['display_name'])) {

            $seller_detail['displayname'] = $data['display_name'];
        } else {

            $seller_detail['displayname'] = $seller_detail['firstname'] . ' ' . $seller_detail['lastname'];
        }





        if (isset($data['user_id'])) {

            $seller_detail['wp_seller_id'] = $data['user_id'];
        }



        if (!empty($data['password_1'])) {

            $seller_detail['password'] = bcrypt($data['password_1']);
        }

        if (!empty($data['pass1'])) {

            $seller_detail['password'] = bcrypt($data['pass1']);
        }

        if (!empty($data['surl'])) {

            $seller_detail['shopurl'] = $data['surl'];
        }

        $address = '';

        if (isset($data['billing_address_1']) && $data['billing_address_1'] != '') {

            $address .= $data['billing_address_1'] . ", ";
        }

        if (isset($data['billing_address_2']) && $data['billing_address_2'] != '') {

            $address .= $data['billing_address_2'] . ", ";
        }

        if (isset($data['billing_city']) && $data['billing_city'] != '') {

            $address .= $data['billing_city'] . ", ";
        }

        if (isset($data['billing_country']) && $data['billing_country'] != '') {

            $address .= $data['billing_country'] . ", ";
        }

        if (isset($data['billing_postcode']) && $data['billing_postcode'] != '') {

            $address .= $data['billing_postcode'] . ", ";
        }

        if (isset($data['billing_state']) && $data['billing_state'] != '') {

            $address .= $data['billing_state'];
        }


        if (isset($data['address'])) {
            $address = $data['address'];
        }

        if (isset($data['phone'])) {
            $seller_detail['phone'] = $data['phone'];
        }

        $seller_detail['address'] = $address;

        $seller_detail['seller_roles'] = [];

//        if (!empty($data['role']))
//        {
//            if ($data['role'] == 'seller')
//            {
//                $seller_detail['seller_roles'][] = $this->option_repo->OptionOfId(81);
//            }
//            if ($data['role'] == 'trader')
//            {
//                $seller_detail['seller_roles'][] = $this->option_repo->OptionOfId(82);
//            }
//        }

        if (!empty($data['seller_roles'])) {

//            if ($data['role'] == 'seller')
//            {

            $seller_detail['seller_roles'][] = $this->option_repo->OptionOfId($data['seller_roles']);

//            }
//            if ($data['role'] == 'trader')
//            {
//                $seller_detail['seller_roles'][] = $this->option_repo->OptionOfId(82);
//            }
        } else if (isset($data['role'])) {

            if ($data['role'] == 'seller') {

                $seller_detail['seller_roles'][] = $this->option_repo->OptionOfId(81);
            } else if ($data['role'] == 'trader') {

                $seller_detail['seller_roles'][] = $this->option_repo->OptionOfId(82);
            }
        }



        $prepareData = $this->seller_repo->prepareData($seller_detail);

        $seller = $this->seller_repo->create($prepareData);

//        self::saveuserSellerAgreement($seller);
    }

    public function deleteWPSeller(Request $request) {

        $data = $request->all();

        $seller = $this->seller_repo->SellerOfWpId($data['user_id']);

        $this->product_quote_repo->deleteAllProductQuotesOfSellerId($seller->getId());

        $this->product_repo->deleteAllProductsOfSellerId($seller->getId());

        $this->seller_repo->delete($seller);

        return 1;
    }

    public function updateWPSeller(Request $request) {

        $data = $request->all();

        //  Log::info(json_encode($data));



        $seller_detail = array();

//        $seller_detail['firstname'] = $data['account_first_name'];
//        $seller_detail['lastname'] = $data['account_last_name'];
//        $seller_detail['email'] = $data['account_email'];



        if (isset($data['account_first_name'])) {

            $seller_detail['firstname'] = str_replace(' ', '', $data['account_first_name']);
        } else if (isset($data['first_name'])) {

            $seller_detail['firstname'] = str_replace(' ', '', $data['first_name']);
        } else if (isset($data['firstname'])) {

            $seller_detail['firstname'] = str_replace(' ', '', $data['firstname']);
        }





        if (isset($data['account_last_name'])) {

            $seller_detail['lastname'] = str_replace(' ', '', $data['account_last_name']);
        } else if (isset($data['last_name'])) {

            $seller_detail['lastname'] = str_replace(' ', '', $data['last_name']);
        } else if (isset($data['lastname'])) {

            $seller_detail['lastname'] = str_replace(' ', '', $data['lastname']);
        }







        if (isset($data['email'])) {

            $seller_detail['email'] = $data['email'];
        } else if (isset($data['email'])) {

            $seller_detail['email'] = $data['email'];
        }





        if (isset($data['display_name'])) {

            $seller_detail['display_name'] = $data['display_name'];
        }



        if (isset($data['dokan_store_name'])) {

            $seller_detail['shopname'] = $data['dokan_store_name'];
        }

        if (isset($data['dokan_store_phone'])) {

            $seller_detail['phone'] = $data['dokan_store_phone'];
        }



        $address = '';

        if (isset($data['billing_address_1']) && $data['billing_address_1'] != '') {

            $address .= $data['billing_address_1'] . ", ";
        }

        if (isset($data['billing_address_2']) && $data['billing_address_2'] != '') {

            $address .= $data['billing_address_2'] . ", ";
        }

        if (isset($data['billing_city']) && $data['billing_city'] != '') {

            $address .= $data['billing_city'] . ", ";
        }

        if (isset($data['billing_country']) && $data['billing_country'] != '') {

            $address .= $data['billing_country'] . ", ";
        }

        if (isset($data['billing_postcode']) && $data['billing_postcode'] != '') {

            $address .= $data['billing_postcode'] . ", ";
        }

        if (isset($data['billing_state']) && $data['billing_state'] != '') {

            $address .= $data['billing_state'];
        }

        $seller_detail['address'] = $address;


        if (isset($data['address'])) {
            $address = $data['address'];
        }

        if (isset($data['phone'])) {
            $seller_detail['phone'] = $data['phone'];
        }

        if (!empty($data['password_1'])) {

            $seller_detail['password'] = bcrypt($data['password_1']);
        }

        if (!empty($data['pass1'])) {

            $seller_detail['password'] = bcrypt($data['pass1']);
        }





        if (!empty($data['surl'])) {

            $seller_detail['shopurl'] = $data['surl'];
        }





        $seller = $this->seller_repo->SellerOfWpId($data['user_id']);

        if ($seller && $seller != NULL) {



            $this->seller_repo->removeDeletedAt($seller);

            $this->seller_repo->update($seller, $seller_detail);
        } else {

            if (isset($data['user_id'])) {

                $seller_detail['wp_seller_id'] = $data['user_id'];

                $seller_detail['seller_roles'][] = $this->option_repo->OptionOfId(81);

                $prepareData = $this->seller_repo->prepareData($seller_detail);

                $this->seller_repo->create($prepareData);
            }
        }
    }

    public function getSeller(Request $request) {

        return $this->seller_repo->SellerById($request->id);
    }

    public function getSellerCityState(Request $request) {

        $temp_data = $this->seller_repo->SellerById($request->sellerid);

        $post = [
            'wp_seller_id' => $temp_data['wp_seller_id'],
            'apikey' => 'thelocalvault2018',
        ];

//        $host = 'https://localvault.staging.wpengine.com/wp-content/themes/thelocalvault/seller-location-api.php';
        $host = 'https://thelocalvault.com/wp-content/themes/thelocalvault/seller-location-api.php';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $host);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

        curl_setopt($ch, CURLOPT_HEADER, false);

        //temp_stop

        $temp = curl_exec($ch);

        $temp = json_decode($temp, true);



        return $temp;
    }

    public function getAllSellers() {

        return $this->seller_repo->getAllSellers();
    }

    public function getSellers(Request $request) {

        $filter = $request->all();



        $data['draw'] = $filter['draw'];



        $users_data_total = $this->seller_repo->getSellers($filter);

        $data['data'] = $users_data_total['data'];



        $data['recordsTotal'] = $users_data_total['total'];

        $data['recordsFiltered'] = $this->seller_repo->getSellersTotal($filter);

        return response()->json($data, 200);
    }

    public function getArchivedSellers(Request $request) {

        $filter = $request->all();



        $data['draw'] = $filter['draw'];



        $users_data_total = $this->seller_repo->getSellerArchivedProducts($filter);

        $data['data'] = $users_data_total['data'];



        $data['recordsTotal'] = $users_data_total['total'];

        $data['recordsFiltered'] = $this->seller_repo->getSellerArchivedProductsTotal($filter);

        return response()->json($data, 200);
    }

    public function getProductsInStateSellers(Request $request) {

        $filter = $request->all();



        $data['draw'] = $filter['draw'];



        $users_data_total = $this->seller_repo->getProductsInStateSellers($filter);

        foreach ($users_data_total['data'] as $key => $value) {

            //product_created_at

            $users_data_total['data'][$key]['product_created_at'] = $this->seller_repo->getFirstCreatedAtOfSellerId($value['id']);

            $users_data_total['data'][$key]['is_touched'] = $this->seller_repo->getIsAnyProductTouchedOfSellerId($value['id']);
        }

        $data['data'] = $users_data_total['data'];



        $data['recordsTotal'] = $users_data_total['total'];

        $data['recordsFiltered'] = $this->seller_repo->getProductsInStateSellersTotal($filter);

        return response()->json($data, 200);
    }

    public function getSellerProduct(Request $request) {

        $filter = $request->all();


        $data['draw'] = $filter['draw'];


        if ($filter['name'] == 'product') {

            $users_data_total = $this->seller_repo->getSellerProducts($filter);

            foreach ($users_data_total['data'] as $key => $value) {

                $users_data_total['data'][$key]['pending_count'] = $this->product_repo->getProductForReviewPendingCountBySellerId($value['id']);
            }

            $data['recordsFiltered'] = $this->seller_repo->getSellerProductsTotal($filter);
        } else if ($filter['name'] == 'proposal') {



            $users_data_total = $this->seller_repo->getSellerProposals($filter);

            foreach ($users_data_total['data'] as $key => $value) {

                $users_data_total['data'][$key]['pending_count'] = $this->product_quote_repo->getProposalPendingCountBySellerId($value['id']);
            }

//            $users_data_total = $this->seller_repo->getSellerProposals($filter);

            $data['recordsFiltered'] = $this->seller_repo->getSellerProposalsTotal($filter);
        } else if ($filter['name'] == 'product_for_production') {

            $users_data_total = $this->seller_repo->getSellerProductForProduction($filter);

            foreach ($users_data_total['data'] as $key => $value) {

                $users_data_total['data'][$key]['pending_count'] = $this->product_quote_repo->getAllPendingProductForProductionCountOfSellerId($value['id']);
            }



            $data['recordsFiltered'] = $this->seller_repo->getSellerProductForProductionTotal($filter);
        } else if ($filter['name'] == 'awaiting_contract') {

            $users_data_total = $this->seller_repo->getSellerAwaitingContract($filter);

            foreach ($users_data_total['data'] as $key => $value) {

                $users_data_total['data'][$key]['pending_count'] = $this->product_quote_repo->getAllPendingAwaitingContractCountOfSellerId($value['id']);
            }


            $data['recordsFiltered'] = $this->seller_repo->getSellerAwaitingContractTotal($filter);
        } else if ($filter['name'] == 'product_for_pricing' || $filter['name'] === 'product_for_only_pricing') {

            $users_data_total = $this->seller_repo->getSellerProposalForPricing($filter);

            foreach ($users_data_total['data'] as $key => $value) {

                $users_data_total['data'][$key]['pending_count'] = $this->product_quote_repo->getAllPendingProposalForPricingCountOfSellerId($value['id']);
            }


            $data['recordsFiltered'] = $this->seller_repo->getSellerProposalForPricingTotal($filter);
        } else if ($filter['name'] == 'proposal_for_production') {
            $users_data_total = $this->seller_repo->getSellerProposalForProduction($filter);

            foreach ($users_data_total['data'] as $key => $value) {

                $users_data_total['data'][$key]['pending_count'] = $this->product_quote_repo->getAllPendingProposalForProductionCountOfSellerId($value['id']);
            }


            $data['recordsFiltered'] = $this->seller_repo->getSellerProposalForProductionTotal($filter);
        } else if ($filter['name'] == 'copyright') {

            // old copywrite
            // $users_data_total = $this->seller_repo->getSellerCopyright($filter);
            // foreach ($users_data_total['data'] as $key => $value) {
            // $users_data_total['data'][$key]['pending_count'] = $this->product_quote_repo->getAllPendingCopyrightsCountOfSellerId($value['id']);
            // }
            // $data['recordsFiltered'] = $this->seller_repo->getSellerCopyrightTotal($filter);
            // 10/09/2020 sending pricing stage date for copywriter
            $users_data_total = $this->seller_repo->getSellerProposalForPricing($filter);
            foreach ($users_data_total['data'] as $key => $value) {
                $users_data_total['data'][$key]['pending_count'] = $this->product_quote_repo->getAllPendingProposalForPricingCountOfSellerId($value['id']);
            }
            $data['recordsFiltered'] = $this->seller_repo->getSellerProposalForPricingTotal($filter);
        } else if ($filter['name'] == 'approvedproducts') {

            $users_data_total = $this->seller_repo->getSellerApprovedProducts($filter);

            foreach ($users_data_total['data'] as $key => $value) {

                $users_data_total['data'][$key]['pending_count'] = $this->product_quote_repo->getAllProductsInProductionBySellerId($value['id']);
            }

            $data['recordsFiltered'] = $this->seller_repo->getSellerApprovedProductsTotal($filter);
        }



        $data['data'] = $users_data_total['data'];



        $data['recordsTotal'] = $users_data_total['total'];

        return response()->json($data, 200);
    }

    public function updateAllSellerRoles() {

        $sellers = $this->seller_repo->getAllSellers();

        echo "<pre>";

        print_r($sellers);

        die;

        foreach ($sellers as $key => $value) {

            $seller = $this->seller_repo->SellerOfId($value['id']);

            $data['update_seller_roles'] = array($this->option_repo->OptionOfId(81));



            $this->seller_repo->update($seller, $data);
        }
    }

    public function insertSeller() {

        ini_set('max_execution_time', 30000);



//        $host = 'https://localvault.staging.wpengine.com/wp-content/themes/thelocalvault/seller-api.php';
        $host = 'https://thelocalvault.com/wp-content/themes/thelocalvault/seller-api.php';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $host);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

        curl_setopt($ch, CURLOPT_HEADER, false);

        //temp_stop

        $temp = curl_exec($ch);



        $temp = json_decode($temp, true);

        echo "<pre>";

        print_r($temp);

        die;

        foreach ($temp as $key => $value) {

//            if (isset($value['data']['myrole']))
//            {

            $data = [];

            $seller = $this->seller_repo->SellerOfWpId($value['data']['ID']);

            if ($seller == null) {

//                echo "<pre>";
//                print_r($value);
            } else {

                if (isset($value['data']['fname'])) {

                    $data['firstname'] = $value['data']['fname'];
                }

                if (isset($value['data']['lname'])) {

                    $data['lastname'] = $value['data']['lname'];
                }

                if (isset($value['data']['display_name'])) {

                    if (trim($value['data']['display_name']) != '') {

                        $data['display_name'] = $value['data']['display_name'];
                    } else if (isset($value['data']['user_login'])) {

                        if (trim($value['data']['user_login']) != '') {

                            $data['display_name'] = $value['data']['user_login'];
                        }
                    }

                    $this->seller_repo->update($seller, $data);
                }

                echo "<pre>";

                print_r($data);
            }

//            }
        }

        die;



        echo "<pre>";

        print_r($temp);

        die;



        foreach ($temp as $key => $value) {

            if (isset($value['data']['myrole'])) {

                $seller = $this->seller_repo->SellerOfWpId($value['data']['ID']);

                if ($seller == NULL) {

//                    $data = [];
//                    $data['wp_seller_id'] = $value['data']['ID'];
//                    if (isset($value['data']['fname']))
//                    {
//                        $data['firstname'] = $value['data']['fname'];
//                    }
//                    if (isset($value['data']['lname']))
//                    {
//                        $data['lastname'] = $value['data']['lname'];
//                    }
//                    if (isset($value['data']['user_email']))
//                    {
//                        $data['email'] = $value['data']['user_email'];
//                    }
//                    if (isset($value['data']['user_pass']))
//                    {
//                        $data['password'] = $value['data']['user_pass'];
//                    }
//                    if (isset($value['data']['sinfo']['store_name']))
//                    {
//                        $data['shopname'] = $value['data']['sinfo']['store_name'];
//                    }
//                    if (isset($value['data']['sinfo']['surl']))
//                    {
//                        $data['shopurl'] = $value['data']['surl'];
//                    }
//                    if (isset($value['data']['sinfo']['phone']))
//                    {
//                        $data['phone'] = $value['data']['sinfo']['phone'];
//                    }
//
//                    if (isset($value['data']['sinfo']['display_name']))
//                    {
//                        $data['display_name'] = $value['data']['sinfo']['display_name'];
//                    }
//                    $address = '';
//                    if (isset($value['data']['add1']) && $value['data']['add1'] != '')
//                    {
//                        $address .= $value['data']['add1'] . ", ";
//                    }
//                    if (isset($value['data']['add2']) && $value['data']['add2'] != '')
//                    {
//                        $address .= $value['data']['add2'] . ", ";
//                    }
//                    if (isset($value['data']['city']) && $value['data']['city'] != '')
//                    {
//                        $address .= $value['data']['city'] . ", ";
//                    }
//                    if (isset($value['data']['country']) && $value['data']['country'] != '')
//                    {
//                        $address .= $value['data']['country'] . ", ";
//                    }
//                    if (isset($value['data']['zip']) && $value['data']['zip'] != '')
//                    {
//                        $address .= $value['data']['zip'] . ", ";
//                    }
//                    if (isset($value['data']['state']) && $value['data']['state'] != '')
//                    {
//                        $address .= $value['data']['state'];
//                    }
//                    $data['address'] = $address;
//                    $roles = [];
//                    foreach ($value['data']['myroles'] as $key => $role)
//                    {
//                        if ($role == 'seller')
//                        {
//                            $roles[] = $this->option_repo->OptionOfId(81);
//                        }
//                        if ($role == 'trader')
//                        {
//                            $roles[] = $this->option_repo->OptionOfId(82);
//                        }
//                    }
//                    $data['seller_roles'] = $roles;
//
//                    $prepareData = $this->seller_repo->prepareData($data);
//                    $this->seller_repo->create($prepareData);
                } else {

                    $data = [];

//                    $roles = [];
//                    foreach ($value['data']['myroles'] as $key => $role)
//                    {
//
//                        if ($role == 'seller')
//                        {
////                            if ($value['data']['ID'] == 801)
////                            {
////                                echo "<pre>";
////                                print_r('inasddsa123');
////                                die;
////                            }
//                            $roles[] = $this->option_repo->OptionOfId(81);
//                        }
//
//                        if ($role == 'trader')
//                        {
//                            $roles[] = $this->option_repo->OptionOfId(82);
//                        }
//                    }
//                    if ($value['data']['ID'] == 801)
//                    {
//                        echo "<pre>";
//                        print_r(count($roles));
//                        die;
//                    }
//                    $data['update_seller_roles'] = $roles;

                    $this->seller_repo->update($seller, $data);

//                    $data['seller_roles'] = $roles;
//
//                    $prepareData = $this->seller_repo->prepareData($data);
//                    $this->seller_repo->create($prepareData);
                }

//                foreach ($value['data']['myroles'] as $key => $role)
//                {
//                    $data = [];
//                    $data['update_seller_roles'] = [];
//                    if ($role == "trader")
//                    {
//                        $data[''];
//                    }
//                    else
//                    {
//                        echo "<pre>";
//                        print_r($value);
//                    }
//                }
            } else {

                echo "<pre>";

                print_r($value);
            }
        }

        echo "insd";

        die;



        echo "<pre>";

        print_r($temp);

        die;



        foreach ($temp as $key => $value) {

            $seller = $this->seller_repo->SellerOfWpId($value['data']['ID']);

            if ($seller == null) {
                
            } else {

                $data = [];

//                $data['wp_seller_id'] = $value['data']['ID'];
//                if (isset($value['data']['fname']))
//                {
//                    $data['firstname'] = $value['data']['fname'];
//                }
//                if (isset($value['data']['lname']))
//                {
//                    $data['lastname'] = $value['data']['lname'];
//                }
//                if (isset($value['data']['user_email']))
//                {
//                    $data['email'] = $value['data']['user_email'];
//                }
//                if (isset($value['data']['user_pass']))
//                {
//                    $data['password'] = $value['data']['user_pass'];
//                }
//                if (isset($value['data']['sinfo']['store_name']))
//                {
//                    $data['shopname'] = $value['data']['sinfo']['store_name'];
//                }
//                if (isset($value['data']['sinfo']['surl']))
//                {
//                    $data['shopurl'] = $value['data']['surl'];
//                }
//                if (isset($value['data']['sinfo']['phone']))
//                {
//                    $data['phone'] = $value['data']['sinfo']['phone'];
//                }
//                $data['address'] = '';
//                if (isset($value['data']['sinfo']['display_name']))
//                {
//                    $data['display_name'] = $value['data']['sinfo']['display_name'];
//                }

                $address = '';

                if (isset($value['data']['add1']) && $value['data']['add1'] != '') {

                    $address .= $value['data']['add1'] . ", ";
                }

                if (isset($value['data']['add2']) && $value['data']['add2'] != '') {

                    $address .= $value['data']['add2'] . ", ";
                }

                if (isset($value['data']['city']) && $value['data']['city'] != '') {

                    $address .= $value['data']['city'] . ", ";
                }

                if (isset($value['data']['country']) && $value['data']['country'] != '') {

                    $address .= $value['data']['country'] . ", ";
                }

                if (isset($value['data']['zip']) && $value['data']['zip'] != '') {

                    $address .= $value['data']['zip'] . ", ";
                }

                if (isset($value['data']['state']) && $value['data']['state'] != '') {

                    $address .= $value['data']['state'];
                }





                $data['address'] = $address;

                $this->seller_repo->update($seller, $data);

//                $data['address']= $value['data']['add1'];
//                $prepareData = $this->seller_repo->prepareData($data);
//                $this->seller_repo->create($prepareData);
            }





//            $data['wp_seller_id'] = $value['data']['ID'];
//            $data['firstname'] = $value['data']['fname'];
//            $data['lastname'] = $value['data']['lname'];
//            $data['email'] = $value['data']['user_email'];
//            $data['password'] = $value['data']['user_pass'];
//            $data['shopname'] = $value['data']['sinfo']['store_name'];
//            $data['shopurl'] = $value['data']['surl'];
//            $data['phone'] = $value['data']['sinfo']['phone'];
//            $data['address'] = '';
//            $data['display_name'] = $value['data']['display_name'];
//
//            $prepareData = $this->seller_repo->prepareData($data);
//            $this->seller_repo->create($prepareData);
        }



        return 'in';
    }

    public function updateSeller() {

        ini_set('max_execution_time', 30000);



//        $host = 'https://localvault.staging.wpengine.com/wp-content/themes/thelocalvault/seller-api.php';
        $host = 'https://thelocalvault.com/wp-content/themes/thelocalvault/seller-api.php';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $host);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

        curl_setopt($ch, CURLOPT_HEADER, false);

        //temp_stop

        $temp = curl_exec($ch);



        $temp = json_decode($temp, true);



        echo "<pre>";

        print_r($temp);

        die;



        foreach ($temp as $key => $value) {



            $data['lastname'] = $value['data']['lname'];



            $user = $this->seller_repo->SellerOfWpId($value['data']['ID']);

            if ($user) {

                $this->seller_repo->update($user, $data);
            }
        }



        return 'in';
    }

    private function updateWordpressProductAfterStorageAgreementSave($wpProductIds) {

        $postData = [
            "tlv" => "a1PYB21QaLV43LTlE786eEtUJBlhDti5yN",
            "ids" => $wpProductIds,
            "is_storage_proposal" => 1
        ];

        $host = 'https://thelocalvault.com/wp-json/tlv_update_agreement/update_agreement';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $host);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        curl_exec($ch);
    }

    public function searchSellers(Request $request) {
        $query = $request->get('q', null);

        if (empty($query) || strlen($query) < 2) {
            return [];
        }

        return $this->seller_repo->searchSeller($query);
    }

    public function assignAgent(Request $request) {

        $sellerId = $request->get('seller_id');
        $agentId = $request->get('agent_id');

        $seller = $this->seller_repo->SellerOfId($sellerId);
        $agent = $this->user_repo->UserOfId($agentId);

        $dataToUpdate['assign_agent_id'] = $agent;

        $this->seller_repo->update($seller, $dataToUpdate);

        return response()->json("agent assigned");
    }

}
