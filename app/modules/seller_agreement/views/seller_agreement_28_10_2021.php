<div id="login" class="flex-scrollable" layout="column" ms-scroll>
    <div id="login-form-wrapper" layout="column" layout-align="center center">
        <div id="login-form" class="md-whiteframe-8dp">
            <!--<div class="logo md-accent-bg">-->
            <!--<span>F</span>-->
            <img src="../../../../assets/images/long_logo.png" height="100px" style="margin-bottom: 20px;">
            <!--</div>-->

            <div class="title">CONSIGNMENT AGREEMENT</div>
            <form name="SellerAgreementForm" novalidate method="POST" ng-submit="SaveSellerAgreement(SellerAgreementForm.$valid)">
                <p><b>
                        On the <input type="number"  class="input_text_box" placeholder="Day" style="width: 60px;" name="day" ng-model="seller_agreement.day"  ng-required="!seller_agreement.day">day of<input type="number"  style="width: 60px;" class="input_text_box" placeholder="Month" name="month" ng-model="seller_agreement.month"  ng-required="!seller_agreement.month">,&nbsp;20<input type="number"  style="width: 60px;" class="input_text_box" placeholder="Year" name="year" ng-model="seller_agreement.year"  ng-required="!seller_agreement.year"> this "Agreement" is made by and among
                        The Local Vault, LLC, 301 Valley Rd, Cos Cob, CT 06807 ("TLV") and :
                    </b></p>
                <div layout="row" layout-xs="column">
                    <div flex>
                        <md-input-container class="md-block">
                            <label>Consignor​’​s Name (“Consignor”)</label>
                            <input name="consignor_name" ng-model="seller_agreement.consignor_name"  ng-required="!seller_agreement.consignor_name">
                            <div ng-messages="SellerAgreementForm.consignor_name.$error" ng-show="ClientForm.consignor_name.$touched" role="alert">
                                <div ng-message="required">
                                    <span>Consignor​’​s Name is required</span>
                                </div>
                            </div>
                        </md-input-container>
                    </div>
                </div>
                <div layout="row" layout-xs="column">
                    <div flex>
                        <md-input-container class="md-block">
                            <label>Address</label>
                            <textarea rows="2" name="address" ng-model="seller_agreement.address" ng-required="!seller_agreement.address">
                            </textarea>                        
                            <div ng-messages="SellerAgreementForm.address.$error" ng-show="SellerAgreementForm.address.$touched" role="alert">
                                <div ng-message="required">
                                    <span>Address is required</span>
                                </div>
                            </div>
                        </md-input-container>
                    </div>
                </div>
                <div layout="row" layout-xs="column">
                    <div flex>
                        <md-input-container class="md-block">
                            <label>City</label>
                            <input name="city" ng-model="seller_agreement.city" ng-required="!seller_agreement.city">
                            <div ng-messages="SellerAgreementForm.city.$error" ng-show="SellerAgreementForm.city.$touched" role="alert">
                                <div ng-message="required">
                                    <span>City is required</span>
                                </div>
                            </div>
                        </md-input-container>
                    </div>
                    <div flex>
                        <md-input-container class="md-block">
                            <label>State</label>
                            <input name="state" ng-model="seller_agreement.state"  ng-required="!seller_agreement.state" >
                            <div ng-messages="SellerAgreementForm.state.$error" ng-show="SellerAgreementForm.state.$touched" role="alert">
                                <div ng-message="required">
                                    <span>State is required</span>
                                </div>
                            </div>
                        </md-input-container>
                    </div>
                    <div flex>
                        <md-input-container class="md-block">
                            <label>Zip</label>
                            <input name="zip" ng-model="seller_agreement.zip" ng-required="!seller_agreement.zip" >
                            <div ng-messages="SellerAgreementForm.zip.$error" ng-show="SellerAgreementForm.zip.$touched" role="alert">
                                <div ng-message="required">
                                    <span>Zip is required</span>
                                </div>
                            </div>
                        </md-input-container>
                    </div>
                </div>
                <div layout="row" layout-xs="column">
                    <div flex>
                        <md-input-container class="md-block">
                            <label>Cell Phone</label>
                            <input type="number" name="phone" ng-model="seller_agreement.phone"   ng-required="!seller_agreement.phone">
                            <div ng-messages="SellerAgreementForm.phone.$error" ng-show="SellerAgreementForm.phone.$touched" role="alert">
                                <div ng-message="required">
                                    <span>Phone is required</span>
                                </div>
                            </div>
                        </md-input-container>
                    </div>
                </div>
                <div layout="row" layout-xs="column">
                    <div flex>
                        <md-input-container class="md-block">
                            <label>Email</label>
                            <input type="email" name="email" ng-model="seller_agreement.email"  ng-required="!seller_agreement.email">
                            <div ng-messages="SellerAgreementForm.email.$error" ng-show="SellerAgreementForm.email.$touched" role="alert">
                                <div ng-message="required">
                                    <span>Email is required</span>
                                </div>
                            </div>
                        </md-input-container>
                    </div>
                </div>

                <p>
                    Hereinafter the personal property placed in consignment through this Consignment Agreement will be
                    described as “Item(s)”. Item(s) will be listed for an “Initial Term” of 6-months. Please review section 12
                    of this Agreement for full listing details.
                </p>
                <p>
                    Consignor grants unto TLV the authority to advertise, offer for sale and sell the Item(s) listed in the TLV
                    “Pricing Proposal” which you will receive after the Item(s) is photographed, measured and evaluated.
                    Consignor has the right to withdraw any Item(s) from the consignment within 48 hours after the Pricing
                    Proposal is sent. The Pricing Proposal will include the sale price at which TLV believes the Item(s)
                    should be listed for sale.
                </p>
                <p>
                    Consignor confirms that the Item(s) included in the “Products for Sale Acknowledgement”, and any
                    additional Item(s) which the Consignor chooses to consign with TLV, is generally described personal
                    property belonging to the Consignor or the individual(s) or estate that Consignor is acting as the agent for.
                </p>

                <p>
                    <b>
                        If an Item(s) is categorized as “small” in the Pricing Proposal then Consignor is willing to drop it
                        off at The Local Vault’s office in Cos Cob, CT within a week from the date the Item was purchased
                        by the “Buyer.” Please note that a $25 service fee will be charged should the Consignor not drop
                        off the Item(s) within this 1 week period.
                    </b>
                </p>
                <p>For larger Item(s) Consignor will allow pick-up to take place at the location designated below.</p>
                <p>
                    <b>Please indicate below the location where the larger Item(s) will be available for pick-up:</b>
                </p>

                <ul style="list-style: bold;list-style: none">
                    <li>
                    <md-checkbox ng-model="seller_agreement.address_as_above" ng-change="FillToAddress()" name="address_as_above" value="as_above">Consignor’s Address as listed above</md-checkbox>
                    </li>
                    <br>
                    <li>OR</li>
                    <li>
                        <div layout="row" layout-xs="column">
                            <div flex>
                                <md-input-container class="md-block">
                                    <label>Address</label>
                                    <textarea rows="2" name="other_address" ng-model="seller_agreement.other_address" >
                                    </textarea>                        
                                    <div ng-messages="SellerAgreementForm.other_address.$error" ng-show="SellerAgreementForm.other_address.$touched" role="alert">
                                        <div ng-message="required">
                                            <span>Address is required</span>
                                        </div>
                                    </div>
                                </md-input-container>
                            </div>
                        </div>
                        <div layout="row" layout-xs="column">
                            <div flex>
                                <md-input-container class="md-block">
                                    <label>City</label>
                                    <input name="other_city" ng-model="seller_agreement.other_city" >
                                    <div ng-messages="SellerAgreementForm.other_city.$error" ng-show="SellerAgreementForm.other_city.$touched" role="alert">
                                        <div ng-message="required">
                                            <span>City is required</span>
                                        </div>
                                    </div>
                                </md-input-container>
                            </div>
                            <div flex>
                                <md-input-container class="md-block">
                                    <label>State</label>
                                    <input name="other_state" ng-model="seller_agreement.other_state"  >
                                    <div ng-messages="SellerAgreementForm.other_state.$error" ng-show="SellerAgreementForm.other_state.$touched" role="alert">
                                        <div ng-message="required">
                                            <span>State is required</span>
                                        </div>
                                    </div>
                                </md-input-container>
                            </div>
                            <div flex>
                                <md-input-container class="md-block">
                                    <label>Zip</label>
                                    <input name="other_zip" ng-model="seller_agreement.other_zip" >
                                    <div ng-messages="SellerAgreementForm.other_zip.$error" ng-show="SellerAgreementForm.other_zip.$touched" role="alert">
                                        <div ng-message="required">
                                            <span>Zip is required</span>
                                        </div>
                                    </div>
                                </md-input-container>
                            </div>
                        </div>
                    </li>

                </ul>
                <p>
                    <b>
                        The Item(s) shall be offered for sale, as described below, as soon as practicable and expected to be
                        on or about 2 weeks from the date the Pricing Proposal is sent.
                    </b>
                </p>
                <p>
                    <b>Consignor and TLV agree as follows:</b>
                </p>

                <ol style="list-style: bold;">
                    <li>
                        TLV will facilitate the sale of Item(s) consigned through the use of an online (internet) sale
                        accessible through the TLV website at www.thelocalvault.com and, as TLV deems appropriate,
                        through our partner sites. Our partner sites include, but are not limited to, Houzz, eBay and
                        1stDibs.
                    </li>
                    <li>TLV reserves the right to decline to handle the sale of any Item(s).</li>
                    <li>
                        TLV will send a TLV agent(s) to photograph, measure and catalog a Consignor’s Item(s). TLV
                        charges a Production Fee of $50.00 when photographing 10 or less Items plus $5.00 for each
                        Item above 10 Items. All Item(s) must be readily accessible during this “Photoshoot”. Any labor
                        costs required to support the TLV agent in the photography and measurement of the Item(s) will
                        be passed on to the Consignor. Payment of such costs is not conditional on the sale of the
                        Item(s).
                    </li>
                    <li>
                        All photographs of the Item(s) can be used in TLV promotional, advertising and marketing
                        materials and activities including social media.
                    </li>
                    <li>
                        Unless, after the Photoshoot, it is determined that an Item is not suitable for listing, the Item(s)
                        will be advertised and offered for sale on the TLV website at the Advertised Price (defined
                        below). Consignor acknowledges that some Items may be grouped and sold as lots to facilitate
                        their sale. While Item(s) is for sale through TLV, Consignor agrees not to make the Item(s)
                        available for sale or sell the Item(s) through any other means/channels including but not limited
                        to websites, social media sites and other consignors. While Item(s) is for sale through TLV,
                        Consignor shall not, verbally or through any website or social media sites, make any
                        representations or warranties regarding the nature or quality of Item(s) offered for sale other than
                        those representations or warranties set forth in writing in the Pricing Proposal or otherwise
                        provided by Consignor to TLV in writing.
                    </li>
                    <li>
                        TLV shall use its reasonable best efforts to promote the sale of the Item(s) but does not
                        guarantee any Item(s) will be sold.
                    </li>
                    <li>
                        “Advertised Price” is the price at which each Item(s) is offered for sale on the TLV website. For
                        the initial 3 months of the sale, the “Advertised Price” of the Item(s) will be the “TLV Price” as
                        set forth in the Pricing Proposal unless a different price is mutually agreed prior to
                        commencement of the sale. Should an Item not sell during this initial 3-month period, the
                        Advertised Price for the Item(s) will automatically be reduced by 30%.
                    </li>
                    <li>
                        The Advertised Price listed for each Item excludes applicable sales tax, which TLV will add to
                        the Buyer's invoice for each Item sold and collect from the Buyer.
                    </li>
                    <li>
                        TLV uses Sales Events, Trade Discounts and Coupons to help drive sales of Items. These
                        "Discounts” offered to prospective buyers range from 10-15%. For a sold Item(s) any Discounts
                        from the Advertised Price will be shared between TLV and the Consignor.
                    </li>
                    <li>
                        Item(s) made available for sale through TLV include the “Make-an-Offer” functionality. Make-
                        an-Offer allows prospective buyers to “Offer” to buy an Item at a price below the Advertised
                        Price. If such an Offer is made the Consignor will then have the ability to “accept”, “reject” or
                        “counter” the Offer. Please note that Discounts will not be applied when Buyer is utilizing the
                        Make an Offer feature.
                    </li>
                    <li>
                        When Buyer takes possession of the Item(s) the sale is considered “Completed”. When the sale
                        of an Item(s) is Completed TLV shall retain a commission of 40% of the “Sale Price” for its
                        services. The Sale Price is the price paid by the Buyer for the Item(s) less any transaction fees.
                        The “Net Sale Proceeds” to be received by the Consignor is calculated as the Sale Price less
                        TLV commission. The Net Sale Proceeds will be sent to the Consignor at the address provided
                        within approximately 14 business days after the sale is Completed.
                    </li>
                    <li>
                        This Consignment Agreement is for an “Initial Term” of 6-months. Prior to the end of this Initial
                        Term TLV will notify the Consignor via email that an unsold Item(s) will be removed from the
                        site unless Consignor expresses a desire to extend the sale. If the Consignor wishes to continue
                        to list their unsold Item(s) the sale will be extended for an additional 2-month term at the
                        Advertised Price. Subsequent 2-month extensions will be determined via the same process at the
                        end of each 2-month term.
                    </li>
                    <li>
                        <b>
                            As stated above, Consignor has the right to withdraw any Item(s) from the consignment
                            for a period of 48 hours after the Pricing Proposal is sent. Thereafter, should Consignor
                            request or demand that Item(s) is withdrawn from sale Consignor shall pay TLV a
                            Cancellation Fee equal to 40% of the Advertised Price of any Item(s) in the event there is a
                            sale agreed with a Buyer that is cancelled by Consignor OR where Item(s) is withdrawn by
                            Consignor during the Initial Term or any subsequent extensions. In such event TLV will
                            charge the Consignor’s credit card provided herein or bill the Consignor for the
                            Cancellation Fee.
                        </b>
                        <br>
                        <br>
                        <b>Please initial here to acknowledge you have read section 13:<input class="input_text_box" name="sale_weeks" ng-model="seller_agreement.acknowledge_section_8"  required></b>
                        <br>
                        <br>
                        <p>
                            <b>Credit Card Information:</b>
                        </p>
                    </li>
                    <div layout="row" layout-xs="column">
                        <div flex>
                            <md-input-container class="md-block">
                                <label>Name on Credit Card</label>
                                <input name="credit_card_name"  ng-model="seller_agreement.credit_card_name"  ng-required="!seller_agreement.credit_card_name">
                                <div ng-messages="SellerAgreementForm.credit_card_name.$error" ng-show="SellerAgreementForm.credit_card_name.$touched" role="alert">
                                    <div ng-message="required">
                                        <span>Name on Credit Card is required</span>
                                    </div>
                                </div>
                            </md-input-container>
                        </div> 
                    </div> 
                    <div layout="row" layout-xs="column">
                        <div flex>
                            <md-input-container class="md-block">
                                <label>CC#</label>
                                <input name="credit_card_cc"  ng-model="seller_agreement.credit_card_cc"  ng-required="!seller_agreement.credit_card_cc" maxlength="25">
                                <div ng-messages="SellerAgreementForm.credit_card_cc.$error" ng-show="SellerAgreementForm.credit_card_cc.$touched" role="alert">
                                    <div ng-message="required">
                                        <span>CC is required</span>
                                    </div>
                                </div>
                            </md-input-container>
                        </div> 
                    </div> 
                    <div layout="row" layout-xs="column">
                        <div flex>
                            <div layout="row" layout-xs="column">
                                <div flex>
                                    <md-input-container class="md-block">
                                        <label>Expiry Month</label>
                                        <input type="number"  name="credit_card_expiry_month" ng-model="seller_agreement.credit_card_expiry_month"  ng-required="!seller_agreement.credit_card_expiry_month">
                                        <div ng-messages="SellerAgreementForm.credit_card_expiry_month.$error" ng-show="SellerAgreementForm.credit_card_expiry_month.$touched" role="alert">
                                            <div ng-message="required">
                                                <span>Expiry Month is required</span>
                                            </div>
                                        </div>
                                    </md-input-container>
                                </div>
                                <div flex>
                                    <md-input-container class="md-block">
                                        <label>Expiry Year</label>
                                        <input type="number"  name="credit_card_expiry_year" ng-model="seller_agreement.credit_card_expiry_year"  ng-required="!seller_agreement.credit_card_expiry_year">
                                        <div ng-messages="SellerAgreementForm.credit_card_expiry_year.$error" ng-show="SellerAgreementForm.credit_card_expiry_year.$touched" role="alert">
                                            <div ng-message="required">
                                                <span>Expiry Year is required</span>
                                            </div>
                                        </div>
                                    </md-input-container>
                                </div>
                            </div>
                        </div>
                        <div flex>
                            <md-input-container class="md-block">
                                <label>CVV Code:</label>
                                <input name="credit_card_security_code" ng-model="seller_agreement.credit_card_security_code" ng-required="!seller_agreement.credit_card_security_code">
                                <div ng-messages="SellerAgreementForm.credit_card_security_code.$error" ng-show="SellerAgreementForm.credit_card_security_code.$touched" role="alert">
                                    <div ng-message="required">
                                        <span>CVV Code is required</span>
                                    </div>
                                </div>
                            </md-input-container>
                        </div>
                    </div>
                    <div layout="row" layout-xs="column">
                        <div flex>
                            <md-input-container class="md-block">
                                <label>Billing Address</label>
                                <textarea rows="2" name="credit_card_billing_address" ng-model="seller_agreement.credit_card_billing_address"  ng-required="!seller_agreement.credit_card_billing_address">
                                </textarea>                        
                                <div ng-messages="SellerAgreementForm.credit_card_billing_address.$error" ng-show="SellerAgreementForm.credit_card_billing_address.$touched" role="alert">
                                    <div ng-message="required">
                                        <span>Billing Address is required</span>
                                    </div>
                                </div>
                            </md-input-container>
                        </div> 
                    </div> 
                    <div layout="row" layout-xs="column">
                        <div flex>
                            <md-input-container class="md-block">
                                <label>City</label>
                                <input name="credit_card_city" ng-model="seller_agreement.credit_card_city" ng-required="!seller_agreement.credit_card_city">
                                <div ng-messages="SellerAgreementForm.credit_card_city.$error" ng-show="SellerAgreementForm.credit_card_city.$touched" role="alert">
                                    <div ng-message="required">
                                        <span>City is required</span>
                                    </div>
                                </div>
                            </md-input-container>
                        </div>
                        <div flex>
                            <md-input-container class="md-block">
                                <label>State</label>
                                <input name="credit_card_state" ng-model="seller_agreement.credit_card_state"  ng-required="!seller_agreement.credit_card_state" >
                                <div ng-messages="SellerAgreementForm.credit_card_state.$error" ng-show="SellerAgreementForm.credit_card_state.$touched" role="alert">
                                    <div ng-message="required">
                                        <span>State is required</span>
                                    </div>
                                </div>
                            </md-input-container>
                        </div>
                        <div flex>
                            <md-input-container class="md-block">
                                <label>Zip</label>
                                <input name="credit_card_zip" ng-model="seller_agreement.credit_card_zip" ng-required="!seller_agreement.credit_card_zip" >
                                <div ng-messages="SellerAgreementForm.credit_card_zip.$error" ng-show="SellerAgreementForm.credit_card_zip.$touched" role="alert">
                                    <div ng-message="required">
                                        <span>Zip is required</span>
                                    </div>
                                </div>
                            </md-input-container>
                        </div>
                    </div>
                    <li>
                        After sale of an Item is agreed with a Buyer(s), unless Consignor is obligated to drop off Item at
                        The Local Vault’s office, TLV will schedule with Consignor a date and time for a pick-up of
                        sold Item. Pick-up is expected to occur within a week after the agreement of Item’s sale.
                        Consignor will cooperate and coordinate with TLV to ensure that sold Item is Easily Accessible
                        for pick-up. “Easily Accessible” is defined as located on the first floor of a multi-story dwelling
                        including the garage. All Items must be prepared for pick up (i.e. removal of all personal
                        belongings from the Item(s) sold and beds must be disassembled). If Items are not Easily
                        Accessible and prepared for pickup, Consignor may incur costs related to picking up the Items.
                    </li>
                    <li>
                        Buyer is responsible for delivery or shipping costs of the Item. Buyer may refuse any Item at
                        pickup or at the time of delivery should the Item not meet Buyer’s expectation. If Buyer chooses
                        to not accept an Item even though it is in the condition that was represented on the TLV website
                        then Buyer shall be responsible for any costs related to the return of the Item. If TLV has
                        misrepresented the item then TLV will bear the return costs. Once Buyer takes possession of
                        Item the Item is no longer eligible for return and the sale is considered “Completed”.
                    </li>
                    <li>
                        TLV shall not be liable for any loss or damage to Item(s) tendered, stored or handled, however
                        caused, unless such loss or damage resulted from the gross negligence or willful misconduct of
                        TLV. TLV provides no primary coverage against loss or damage to Consignor’s Item(s),
                        however caused. Consignor agrees to maintain adequate insurance coverage.
                    </li>
                    <li>
                        Consignor warrants that he/she/it has full authority to transfer all title and property rights in the
                        consigned Item(s) free and clear of all liens, claims and encumbrances, and there are no reserved
                        or hidden security interests in any Item(s) that is the subject of this Agreement.
                    </li>
                    <li>
                        Consignor shall indemnify and defend TLV from and against any losses, damages, liabilities,
                        and expenses, including reasonable attorney’s fees, arising from or relating to any claim alleging
                        any loss or damage to persons or property, related to any transaction or interaction with TLV and
                        its agents.
                    </li>
                </ol>
                <p>
                    If any court determines that any provision of this Agreement is invalid or unenforceable, any invalidity or
                    unenforceability will affect only that provision and will not make any other provision of this agreement
                    invalid or unenforceable.
                </p>
                <p>
                    I have read the foregoing Agreement and understand the contents thereof. I further represent that the
                    statements herein made by me are true to the best of my knowledge and that this Agreement contains and
                    sets out the entire Agreement of the parties unless this is amended in writing and signed by all parties to
                    this Agreement. It is mutually agreed that this Agreement shall be binding and obligatory upon the
                    undersigned, and the separate heirs, administrators, executors, assigns and successors of the undersigned:
                </p>
                <p>
                    <b>IN WITNESS WHEREOF, the parties have executed this Agreement:</b>
                </p>
                <p>
                    <b>The Local Vault, LLC</b>
                </p>
                <p>
                    <b>and</b>
                </p>
                <p>
                    <b>Consignor:</b>
                </p>
                <div layout="row" layout-xs="column">
                    <div flex="50">
                        <md-input-container class="md-block">
                            <label>Name</label>
                            <input name="consignor_name3" ng-model="seller_agreement.consignor_name3"   ng-required="!seller_agreement.consignor_name3">
                            <div ng-messages="SellerAgreementForm.consignor_name3.$error" ng-show="SellerAgreementForm.consignor_name3.$touched" role="alert">
                                <div ng-message="required">
                                    <span>Name is required</span>
                                </div>
                            </div>
                        </md-input-container>
                    </div>
                </div>
                <div layout="row" layout-xs="column">
                    <div flex="20">Date : </div>
                    <div flex>
                        <md-datepicker md-open-on-focus name="consignor_date" ng-model="seller_agreement.consignor_date"  ng-required="!seller_agreement.consignor_date">
                        </md-datepicker>
                        <div ng-messages="SellerAgreementForm.consignor_date.$error" ng-show="SellerAgreementForm.consignor_date.$touched" role="alert">
                            <div ng-message="required">
                                <span  style="color:rgb(213,0,0);font-size: 12px;padding-left: 50px;">Date is required</span>
                            </div>
                        </div>
                    </div>
                </div>
                <p>
                    <b>
                        Information for Payment to Consignor (if payment recipient is not Consignor and/or if mailing
                        address is not address listed above):
                    </b>
                </p>
                <div layout="row" layout-xs="column">
                    <div flex>
                        <md-input-container class="md-block">
                            <label>Check payable to</label>
                            <input name="check_payable" ng-model="seller_agreement.check_payable"  ng-required="!seller_agreement.check_payable">
                            <div ng-messages="SellerAgreementForm.check_payable.$error" ng-show="SellerAgreementForm.check_payable.$touched" role="alert">
                                <div ng-message="required">
                                    <span>Check payable to is required</span>
                                </div>
                            </div>
                        </md-input-container>
                    </div> 
                </div> 
                <div layout="row" layout-xs="column">
                    <div flex>
                        <md-input-container class="md-block">
                            <label>Street Address for Mailing</label>
                            <textarea rows="2" name="mailing_street_address" ng-model="seller_agreement.mailing_street_address"  ng-required="!seller_agreement.mailing_street_address">
                            </textarea>                        
                            <div ng-messages="SellerAgreementForm.mailing_street_address.$error" ng-show="SellerAgreementForm.mailing_street_address.$touched" role="alert">
                                <div ng-message="required">
                                    <span>Street Address for Mailing is required</span>
                                </div>
                            </div>
                        </md-input-container>
                    </div> 
                </div> 
                <div layout="row" layout-xs="column">
                    <div flex>
                        <md-input-container class="md-block">
                            <label>City</label>
                            <input name="payment_city" ng-model="seller_agreement.payment_city" ng-required="!seller_agreement.payment_city">
                            <div ng-messages="SellerAgreementForm.payment_city.$error" ng-show="SellerAgreementForm.payment_city.$touched" role="alert">
                                <div ng-message="required">
                                    <span>City is required</span>
                                </div>
                            </div>
                        </md-input-container>
                    </div>
                    <div flex>
                        <md-input-container class="md-block">
                            <label>State</label>
                            <input name="payment_state" ng-model="seller_agreement.payment_state"  ng-required="!seller_agreement.payment_state" >
                            <div ng-messages="SellerAgreementForm.payment_state.$error" ng-show="SellerAgreementForm.payment_state.$touched" role="alert">
                                <div ng-message="required">
                                    <span>State is required</span>
                                </div>
                            </div>
                        </md-input-container>
                    </div>
                    <div flex>
                        <md-input-container class="md-block">
                            <label>Zip</label>
                            <input name="payment_zip" ng-model="seller_agreement.payment_zip" ng-required="!seller_agreement.payment_zip" >
                            <div ng-messages="SellerAgreementForm.payment_zip.$error" ng-show="SellerAgreementForm.payment_zip.$touched" role="alert">
                                <div ng-message="required">
                                    <span>Zip is required</span>
                                </div>
                            </div>
                        </md-input-container>
                    </div>
                </div>
                <p>*Direct Deposit Available Upon Request</p>

                <div class="row" >
                    <div style="margin: 30px auto 0 auto;height: 100px;width: 220px;">
                        <signature-pad accept="accept" style="border: 1px solid;" clear="clear" height="100" width="250" dataurl="dataurl">
                        </signature-pad>
                        <div layout="row" flex class="buttons">
                            <md-button flex type="button" ng-click="clear()" class="md-raised md-warn submit-button" aria-label="Clear">             
                                Clear
                            </md-button>
                            <!--                            <md-button style="margin-left: 10px;" flex type="button" ng-click="dataurl = signature.dataUrl" ng-disabled="!signature" class="md-raised md-primary submit-button" aria-label="Reset">             
                                                            Reset
                                                        </md-button>-->
                            <!--                            <md-button style="margin-left: 10px;" flex type="button" ng-click="signature = accept()" class="md-raised md-primary submit-button" aria-label="Use">             
                                                            Use
                                                        </md-button>-->
                            <!--                            <button type="button" ng-click="clear()">Clear</button>
                                                        <button type="button" ng-click="dataurl = signature.dataUrl" ng-disabled="!signature">Reset</button>
                                                        <button type="button" ng-click="signature = accept()">Use</button>-->
                        </div>

                        <!--                    <div class="sizes">
                                                <input type="text" ng-model="boundingBox.width"> x <input type="text" ng-model="boundingBox.height">
                                            </div>-->
                    </div>
                    <!--<p></p>-->
                    <br>
                    <br>
                    <br>
                    <br>
                    <div class="row" style="text-align: center;" >
                        <!--<span style="font-weight: 500;">Use your mouse to sign your name. Click <b>Use</b> to commit your signature.</span>-->
                        <span style="font-weight: 500;">Use your mouse to sign your name.</span>
                    </div>

                </div>




                <div class="result" ng-show="signature">
                    <img ng-src="{{ signature.dataUrl}}">
                </div>
                <div layout="row" layout-xs="column">
                    <md-button type="submit" class="md-raised md-primary submit-button" aria-label="SUBMIT">             
                        Submit
                    </md-button>
                </div>

            </form>
        </div>
    </div>
</div>


<style>
    #login #login-form-wrapper #login-form{
        width: 65% !important; 
        max-width: none !important; 
        padding: 50px;
        background: #FFFFFF;
        text-align: center;
        border-radius: 8px;
    }
    ol {
        margin:0 0 1.5em;
        padding:0;
        counter-reset:item;
    }

    ol>li {
        margin:0;
        padding:0 0 0 2em;
        text-indent:-2em;
        list-style-type:none;
        counter-increment:item;
    }

    ol>li:before {
        display:inline-block;
        width:1.5em;
        padding-right:0.5em;
        font-weight:bold;
        text-align:right;
        content:counter(item) ".";
    }
    .input_text_box{
        color: rgba(0, 0, 0, 0.87);
        border-top: none;
        border-right: none;
        border-left: none;
        border-image: initial;
        border-bottom: 1px solid;
        border-color: #e3e3e3;
        padding: 5px;
        margin-left: 5px;
        margin-right: 5px;
    }
    .md-datepicker-input{
        width:328px;
    }

    .result {
        border: 1px solid blue;
        margin: 30px auto 0 auto;
        height: 100px;
        width: 220px;
    }
    input.ng-invalid{
        border-color: rgb(213,0,0);
    }
</style>