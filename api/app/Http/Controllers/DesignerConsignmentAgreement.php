<?php

namespace App\Http\Controllers;

use App\Repository\ProductQuoteAgreementRepository as product_quote_agreement_repo;
use Illuminate\Http\Request;
use App\Repository\ConsignmentAgreementWithStorageRepository;
use App\Repository\SellerRepository;
use App\Repository\ProductsRepository as product_repo;
use Illuminate\Support\Facades\Log;

class DesignerConsignmentAgreement extends Controller
{

    private $product_repo;
    private $product_quote_agreement_repo;

    public function __construct(product_repo $product_repo, product_quote_agreement_repo $product_quote_agreement_repo)
    {
        $this->product_repo = $product_repo;
        $this->product_quote_agreement_repo = $product_quote_agreement_repo;

    }

    public function sendDesignerConsignmentAgreement(Request $request)
    {

        $temp = $request->all();
        $products_approve = array();
        $products_reject = array();
        $seller = '';

        if (count(($temp['product_status'])) > 0) {
            $seller = $this->product_repo->ProductOfId($temp['product_status'][0]['product_id'])->getSellerid();
        }

        $product_quot_ids = [];
        if (isset($request->is_send_mail) && $request->is_send_mail == 'yes') {

            $product_quote_agreement = [];
            $product_quote_agreement['is_form_filled'] = 0;
            $product_quote_agreement['seller_id'] = $seller;
            $product_quote_agreement['pdf'] = '';
            $product_quote_agreement['quote_ids_json'] = json_encode($product_quot_ids);

            $product_quote_agreement_prepared_data = $this->product_quote_agreement_repo->prepareData($product_quote_agreement);
            $product_quote_agreement_obj = $this->product_quote_agreement_repo->create($product_quote_agreement_prepared_data);


            $product_quote_agreement_enc_id = \Crypt::encrypt($product_quote_agreement_obj->getId());
            $agreement_link = config('app.url') . 'designer_consignment_agreement/' . $product_quote_agreement_enc_id;

            $greeting = "Dear " . $seller->getFirstName() . ',';

            $introLines = array();
            $introLines_new = array();

            $introLines[0] = "Thank you so much for reaching out to us at TLV! We would be delighted to list your Item(s). Just to give you a quick overview of our consignment terms, we ask for the following:";

            $introLines_new[] = 'TLV requires a 6-month exclusive contract to sell your Item(s).';
            $introLines_new[] = 'Consignor receives 60% of Sale Price.';
            $introLines_new[] = 'Item(s) remain with the Consignor until they have sold. Once sold, our logistics team will arrange a date for the Item\'s pick up.';
            $introLines_new[] = 'TLV has a limited amount of Storage space for consignors. Availability is not guaranteed.
Please let us know if you are interested!';
            $introLines_new[] = 'Once we have received a signed Consignment Agreement, TLV will arrange an appointment to photograph and catalog the Item(s).';
            $introLines_new[] = 'TLV will present the Consignor with a Pricing Proposal after the Photoshoot. The Consignor will have 48 hours after the Pricing Proposal has been sent to withdraw an Item(s) from the Consignment Agreement.';
            $introLines_new[] = 'Items will be priced based upon condition, market trends and TLV sales data.';
            $introLines_new[] = 'There is a one-time Production Fee of $50 for the first 10 Items and $5 for each additional Item photographed.';
            $introLines_new[] = 'Consignor Payment will be processed after a sold Item is received and accepted by the Buyer.';

            $introLines_if_any_approved = array();
            $myViewData = \View::make('emails.product_for_designer_consignment_agreement', [
                'agreement_link' => $agreement_link,
                'introLines_if_any_approved' => $introLines_if_any_approved,
                'introLines_new' => $introLines_new,
                'greeting' => $greeting,
                'seller' => $seller,
                'products_approve' => $products_approve,
                'products_reject' => $products_reject,
                'level' => 'success',
                'outroLines' => [0 => ''],
                'introLines' => $introLines
            ])->render();
            $bccs = [];
            $attachments = [];
            $ccs = [];

            $ccs[] = 'sell@thelocalvault.com';

            $other_emails = [];

            $other_emails[] = 'thelocalvaultcomproduction@thelocalvault.freshdesk.com';

            if (app('App\Http\Controllers\EmailController')->sendMail($seller->getEmail(), 'Designer Consignment with The Local Vault: ' . $seller->getLastname(), $myViewData, $attachments, $bccs, $ccs, $other_emails)) {

            }
        }
        return 1;
    }
    public function checkDesignerConsignmentAgreement(Request $request)
    {
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
    public function saveDesignerConsignmentAgreement(Request $request)
    {
        $data = $request->all();

        $product_quote_agreement_id = \Crypt::decrypt($data['seller_ageement']['id']);
        if ($product_quote_agreement_id) {

            if (isset($data['signature']['dataUrl'])) {

                $image = $data['signature']['dataUrl']; // your base64 encoded

                $image = str_replace('data:image/png;base64,', '', $image);

                $image = str_replace(' ', '+', $image);

                $imageName = str_random(25) . '.' . 'png';

                \File::put(public_path() . '/../../Uploads/user_agreement_sign/' . $imageName, base64_decode($image));
            }

            $file = 'designer_consignment_agreement_' . time();

            $pdf_file_name = self::pdfGenerateDesignerConsignmentAgreement($data['seller_ageement'], $imageName, $file);

            $pdf_file_path_with_out_card = self::pdfGenerateDesignerConsignmentAgreement($data['seller_ageement'], $imageName, $file, true);

            if (isset($data['seller_ageement']['local_vault_date'])) {
                $data['seller_ageement']['local_vault_date'] = new \DateTime(date('Y-m-d H:i:s', strtotime($data['seller_ageement']['local_vault_date'])));
            }

            if (isset($data['seller_ageement']['consignor_date'])) {
                $data['seller_ageement']['consignor_date'] = new \DateTime(date('Y-m-d H:i:s', strtotime($data['seller_ageement']['consignor_date'])));
            }
            $temp_data['data_json'] = json_encode($data['seller_ageement']);
            $temp_data['signature'] = $imageName;
            $temp_data['is_form_filled'] = 1;
            $temp_data['pdf'] = $pdf_file_name;

            $product_quote_agreement = $this->product_quote_agreement_repo->ofId($product_quote_agreement_id);

            $this->product_quote_agreement_repo->update($product_quote_agreement, $temp_data);

            $link = config('app.url') . 'Uploads/user_agreement_pdf/' . $pdf_file_name;

            $introLines = array();

            $introLines[0] = "Here is the Designer Consignment Agreement";
            $line = "Download Designer Consignment Agreement";
            $myViewData = \View::make('emails.designer_consignment_agreement_filled', ['link' => $link, 'level' => 'success', 'introLines' => $introLines, 'line' => $line])->render();


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

            if (app('App\Http\Controllers\EmailController')->sendMail('Contract@thelocalvault.com', 'Designer Consignment Agreement: ' . $sellerLastname, $myViewData, $attachments, $bccs, $ccs)) {

                Log::info('in mmail');
            }

            Log::info('File name ' . $link);


            return response()->json('Product Quote Agreement Updated Successfully', 200);
        }
    }

    public function pdfGenerateDesignerConsignmentAgreement($data, $signature_image, $file = 'seller_agreement_', $hideCreditCard = false)
    {


        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);

        $pdf->SetAuthor('Test');

        $pdf->SetTitle('Product Reports');

        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

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

        $html .= '<img src = "/assets/images/long-logo.png" style = "height:100px;">';

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
“Item(s)”. Item(s) will be listed for sale on TLV’s website for an “Initial Term” of 6-months. Please review all sections of
this Agreement for full listing details.</p>';


        $html .= '<p>Consignor grants unto TLV the authority to advertise, offer for sale and sell the Item(s) listed in the TLV “Pricing
Proposal” which you will receive after the Item(s) is photographed, measured and evaluated. Consignor has the right to
withdraw any Item(s) from the consignment within 48 hours after the Pricing Proposal is sent. The Pricing Proposal will
include the sale price at which TLV believes the Item(s) should be listed for sale.</p>';

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
the TLV website at<a href="https://thelocalvault.com" target="_blank">www.thelocalvault.com</a> and, as TLV deems appropriate, through our partner sites. Our
partner sites include, but are not limited to, eBay and social media.

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
is operational unless otherwise noted to TLV agent.
                    </li>
                    <li>
                    Should Item condition change after Photoshoot Consignor will notify TLV of such change so listing can be
adjusted and, if the Item has sold, change can be discussed with buyer prior to TLV arranging pickup of the Item.
If TLV is not notified of condition change and the item is sold then, at the discretion of TLV, Consignor will be
responsible for shipping charges to deliver and return the Item or will accept either a reduction in sale price equal
to the cost to repair the Item or any reduction in sale price agreed between TLV and the Buyer to Complete the
sale.
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
The Advertised Price listed for each Item excludes applicable sales tax, which TLV will add to the Buyer s
invoice for each Item sold and collect from the Buyer.

                    </li>

                    <li>
                   TLV uses Sales Events, including early access for Trade members, Trade Discounts, and Coupons to help drive
sales of Items. The Discounts and Coupons offered to prospective buyers range from 10-15%. For a sold Item(s)
any reduction from the Advertised Price will be shared between TLV and the Consignor.
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
        $html .= '<img src="/Uploads/user_agreement_sign/' . $signature_image . '" style="height:100px;width:250px;border:1px solid black;">';
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

}
