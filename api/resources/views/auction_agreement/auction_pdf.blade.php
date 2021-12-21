<html>
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
    <body>
        <div style="width: 100%;display: block;text-align: center;">
            <img src="{{public_path().'/assets/images/tlv_auction_logo_1.png'}}" style = "height:100px;">
        </div>
        <div style="font-size:20px;text-align:center;padding:4px;">AUCTION AGREEMENT</div>
        <p>
            <b>
                On the <u> {{$data['day'] }} </u> day of <u> {{ $data['month'] }}' </u>, 20<u>{{ $data['year'] }} </u> this “Agreement” is made by and among
                The Local Vault, LLC, 301 Valley Rd, Cos Cob, CT 06807 ("TLV") and :
            </b>
        </p>
        <table border = "0">
            <tr>
                <td colspan = "3">
                    <b>Seller's Name : </b><u> {{ $data['seller_name'] }} </u> (“Seller”)
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <b>Address : </b><u>  {{ $data['address'] }}  </u>
                </td>
            </tr>
            <tr>
                <td>
                    <b>City : </b><u>  {{ $data['city'] }}  </u>
                </td>
                <td>
                    <b>State : </b><u>  {{ $data['state'] }}  </u>
                </td>
                <td>
                    <b>Zip : </b><u>  {{ $data['zip'] }}  </u>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td>
                    <b>Cell Phone : </b><u>  {{ $data['phone'] }}  </u>
                </td>
            </tr>
            <tr>
                <td>
                    <b>Email : </b><u>  {{ $data['email'] }}  </u>
                </td>
            </tr>
        </table>
        <p>
            Hereinafter the personal property referenced in this Auction Agreement will be described as “Item(s)”. 
            Seller grants to TLV the authority to advertise, offer for sale via auction, and sell the Item(s) 
            listed in the TLV “Auction Proposal” which you will receive after the Item(s) is photographed, measured and evaluated.
        </p>
        <p>
            Seller confirms that the Item(s) included in  the “Auction Proposal”, and any additional Item(s) which the 
            Seller chooses to offer at auction, is generally described personal property belonging to the Seller or the 
            individual(s) or estate that Seller is acting as the agent for.
        </p>
        <p>
            This auction will be conducted by “TLV Auctions”.
        </p>
        <p>
            Seller has the right to withdraw any Item(s) from the auction within 48 hours after the Auction Proposal is sent. 
        </p>
        <p>
            Pick-up of sold Item(s) to take place at the location designated below:
        </p>
        <ul style="list-style: bold;list-style: none">
            <li>
                <table border="0">
                    <tr>
                        <td colspan="3">
                            <b>Address : </b><u>{{ (isset($data['other_address']) ? $data['other_address'] : "") }}</u>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>City : </b><u>{{ (isset($data['other_city']) ? $data['other_city'] : "") }}</u>
                        </td>
                        <td>
                            <b>State : </b><u>{{ (isset($data['other_state']) ? $data['other_state'] : "") }}</u>
                        </td>
                        <td>
                            <b>Zip : </b><u>{{ (isset($data['other_zip']) ? $data['other_zip'] : "") }}</u>
                        </td>
                    </tr>
                </table>
            </li>
        </ul>
        <p>
            <b>Seller and TLV agree as follows:</b>
        </p>
        <ol style="list-style: bold;">
            <li>
                TLV will facilitate the sale of Item(s) through an online auction of Item(s). This auction will be conducted by 
                TLV Auctions and, as TLV deems appropriate, in conjunction with TLV Auctions’ partner sites. 
                Partner sites include, but are not limited to, Live Auctioneers.
            </li>
            <li>
                TLV reserves the right to decline to handle the sale of any Item(s). 
            </li>
            <li>
                TLV will send a TLV agent(s) to photograph, measure and catalog a Seller’s Item(s). All Item(s) must be 
                readily accessible during this “Photoshoot”. Any labor costs required to support the TLV agent in the 
                photography and measurement of the Item(s) will be passed on to the Seller. Payment of such costs is not 
                conditional on the sale of the Item(s).
            </li>
            <li>
                All photographs of the Item(s) can be used in TLV promotional, advertising and marketing materials and 
                activities including social media. 
            </li>
            <li>
                Unless, after the Photoshoot, it is determined by TLV that an Item is not suitable for sale, the Item(s) 
                photographed by TLV Agent will be offered for sale. Seller acknowledges that some Items may be grouped and 
                sold as lots to facilitate their sale. While Item(s) is for sale through TLV, Seller agrees not to make the 
                Item(s) available for sale or sell the Item(s) through any other means/channels including but not limited 
                to websites, social media sites and other auction houses. While Item(s) is for sale through TLV, Seller shall 
                not, verbally or through any website or social media sites, make any representations or warranties regarding 
                the nature or quality of Item(s) offered for sale other than those representations or warranties set forth in 
                writing in the Pricing Proposal or otherwise provided by Seller to TLV in writing. 
            </li>
            <li>TLV does not guarantee any Item(s) will be sold. </li>
            <li>
                When Buyer takes possession of the Item(s) the sale is considered “Completed”.   When the sale of an Item that 
                has been auctioned is Completed, TLV shall pay to the Seller “Net Sale Proceeds” equal to 80% of the 
                “Hammer Price”. The Hammer Price is defined as the winning bid for a lot at auction, determining the sale price,
                but does not include the buyer's premium.  The Net Sale Proceeds will be sent to the Seller at the address 
                provided within approximately 14 business days after the sale is Completed.
            </li>
            <li>
                TLV will work to facilitate the successful auction of the Item(s) in a timely manner and expects that the 
                agreed upon Item(s) will be listed as part of an auction within 2 months from date of photography by TLV Agent. 
            </li>
            <li>
                <b>
                    As stated above, Seller has the right to withdraw any Item(s) from the auction for a period of 48 hours 
                    after the Auction Proposal is sent. Thereafter, should Seller request or demand that Item(s) is withdrawn 
                    from sale Seller shall pay TLV a Cancellation Fee of $50 per Item for any Item(s) that is withdrawn by 
                    Seller. In such event, TLV will charge the Seller’s credit card provided herein or bill the Seller for 
                    the Cancellation Fee. 
                    <br>
                    <br>
                    <b>Please initial here to acknowledge you have read section 9:<u>{{ (isset($data['acknowledge_section']) ? $data['acknowledge_section'] : "") }}</u></b>
                    <br>
                </b>
            </li>

            @if(!$hideCreditCard)
            <table>
                <tr>
                    <td colspan = "2">
                        <b>Credit Card Information:</b>
                    </td>
                </tr>
                <tr>
                    <td colspan = "2">
                        Name on Credit Card : <u> {{ $data['credit_card_name'] }}</u>
                    </td>
                </tr>
                <tr>
                    <td colspan = "2">
                        CC# : <u> {{ $data['credit_card_cc'] }}   </u>
                    </td>
                </tr>
                <tr>
                    <td>
                        Exp Date : <u> {{ $data['credit_card_expiry_month'] }}/{{ $data['credit_card_expiry_year'] }}   </u>
                    </td>
                    <td>
                        CVV Code: <u>    {{ $data['credit_card_security_code'] }}   </u>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        Billing Address : <u>  {{ $data['credit_card_billing_address'] }} </u>
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td>
                        City : <u>  {{ $data['credit_card_city'] }}  </u>
                    </td>
                    <td>
                        State : <u>  {{ $data['credit_card_state'] }} </u>
                    </td>
                    <td>
                        Zip : <u>  {{ $data['credit_card_zip'] }}  </u>
                    </td>
                </tr>
            </table>
            @endif
            <li>
                TLV will schedule with Seller a date and time for a pick-up of sold Item(s). Seller will cooperate and 
                coordinate with TLV to ensure that sold Item is Easily Accessible for pick-up. “Easily Accessible” is defined 
                as located on the first floor of a multi-story dwelling including the garage. All Items must be prepared for 
                pick up (i.e. removal of all personal belongings from the Item(s) sold and beds must be disassembled). 
                If Items are not Easily Accessible and prepared for pickup, Seller may incur costs related to picking up the Items.
            </li>
            <li>
                TLV shall not be liable for any loss or damage to Item(s) tendered, stored or handled, however caused, unless such 
                loss or damage resulted from the gross negligence or willful misconduct of TLV. TLV provides no primary coverage against 
                loss or damage to Seller’​s Item(s), however caused. Seller agrees to maintain adequate insurance coverage.
            </li>
            <li>
                Seller warrants that he/she/it has full authority to transfer all title and property rights in the consigned 
                Item(s) free and clear of all liens, claims and encumbrances, and there are no reserved or hidden security 
                interests in any Item(s) that is the subject of this Agreement.
            </li>
            <li>
                Seller shall indemnify and defend TLV from and against any losses, damages, liabilities, and expenses, including 
                reasonable attorney​’​s fees, arising from or relating to any claim alleging any loss or damage to persons or property, 
                related to any transaction or interaction with TLV and its agents.  
            </li>
        </ol>
        <p>
            If any court determines that any provision of this Agreement is invalid or unenforceable, any invalidity or 
            unenforceability will affect only that provision and will not make any other provision of this agreement invalid or unenforceable.
        </p>
        <p>
            I have read the foregoing Agreement and understand the contents thereof. I further represent that the statements 
            herein made by me are true to the best of my knowledge and that this Agreement contains and sets out the entire 
            Agreement of the parties unless this is amended in writing and signed by all parties to this Agreement. It is 
            mutually agreed that this Agreement shall be binding and obligatory upon the undersigned, and the separate heirs, 
            administrators, executors, assigns and successors of the undersigned: 
        </p>
        <table border="0">
            <tr>
                <td colspan="2">
                    <b>IN WITNESS WHEREOF, the parties have executed this Agreement:</b>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <b>The Local Vault, LLC</b>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <b>and</b>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <b>Seller:</b>
                </td>
            </tr>
            <tr>
                <td>
                    Date : <u>  {{ $data['agreement_date'] }}  </u>
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <b>Information for Payment to Seller (if payment recipient is not Seller and/or if mailing address is not address listed above):</b>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Check payable to : <u>  {{ $data['check_payable'] }}  </u>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    Mailing Street Address : <u>  {{ $data['mailing_street_address'] }}  </u>
                </td>
            </tr>

        </table>

        <table>
            <tr>
                <td>
                    City : <u>  {{ $data['payment_city'] }}  </u>
                </td>
                <td>
                    State : <u>  {{ $data['payment_state'] }}  </u>
                </td>
                <td>
                    Zip : <u>  {{ $data['payment_zip'] }}  </u>
                </td>
            </tr>
        </table>
        <p>*Direct Deposit Available Upon Request</p>
        <table style="margin-top:0px;">
            <tr>
                <td>
                </td>
                <td>
                </td>
                <td style="border:1px solid black;">
                    <img src="{{ public_path() . '/../../Uploads/auction_agreement_sign/' . $signature_image }}" 
                         style="height:100px;width:250px;border:1px solid black;">
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td>
                </td>
                <td>
                    <span>Signature</span>
                </td>
            </tr>
        </table>
    </body>
</html>