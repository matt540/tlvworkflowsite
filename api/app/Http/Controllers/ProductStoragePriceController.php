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
use Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Log;
use Cartalyst\Stripe\Stripe;

class ProductStoragePriceController extends Controller
{

    public function __construct(product_quote_agreement_repo $product_quote_agreement_repo, mail_record_repo $mail_record_repo, seller_repo $seller_repo, product_quotation_repo $product_quotation_repo, image_repo $image_repo, product_approved_repo $product_approved_repo, email_template_repo $email_template_repo, option_repo $option_repo, sub_category_repo $sub_category_repo, sell_repo $sell_repo, product_repo $product_repo, user_repo $user_repo, role_repo $role_repo)
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
    }

    public function checkProductStoragePrice(Request $request, $product_id)
    {

        $data = $request->all();
        $base_url = config('app.url');
        if (isset($product_id))
        {
            try
            {
                $product_quote_id = \Crypt::decrypt($product_id);
                $product_quote = $this->product_quotation_repo->ProductQuotationOfId($product_quote_id);
                if ($product_quote->getStripe_plan_id() == '' || $product_quote->getStripe_plan_id() == null)
                {
                    
                    return view('payment.index', compact('product_quote', 'product_quote_id'));
                } else
                {
                    return redirect()->away($base_url);
                }
            } catch (\RuntimeException $e)
            {
                return redirect()->away($base_url);
            }
        }

        return redirect()->away($base_url);
    }

    public function checkProductPayment(Request $request)
    {
        $data = $request->all();
        $base_url = config('app.url');
        $stripe = new Stripe(config('app.stripe_secreat'), '');
        $product_quote = $this->product_quotation_repo->ProductQuotationOfId($data['product_quote_id']);
        $product_seller = $this->seller_repo->SellerOfId($product_quote->getProductId()->getSellerid()->getId());

        $token = $data['stripe_token'];
        if ($product_quote->getProductId()->getSellerid()->getEmail() != '' && $product_quote->getProductId()->getSellerid()->getEmail() != null)
        {
            $email = $product_quote->getProductId()->getSellerid()->getEmail();
        } else
        {
            $email = $data['cardholder-email'];
        }
        try
        {
            $plan = $stripe->plans()->create(array(
                "product"        => [
                    "name" => $product_quote->getProductId()->getName(),
                    "type" => "service"
                ],
                "nickname"       => $product_quote->getProductId()->getName(),
                "interval"       => "month",
                "interval_count" => "1",
                "currency"       => "usd",
                "amount"         => $product_quote->getStorage_pricing(),
            ));

            if ($product_seller->getStripe_customer_id() != '' && $product_seller->getStripe_customer_id() != null)
            {
                $customer['id'] = $product_seller->getStripe_customer_id();
            } else
            {
                $customer = $stripe->customers()->create([
                    'email'  => $email,
                    'source' => $token,
                ]);
            }

            $subscription = $stripe->subscriptions()->create($customer['id'], array(
                "items" => array(
                    array(
                        "plan" => $plan['id'],
                    ),
            )));

            if (isset($subscription['id']) && isset($customer['id']) && isset($plan['id']))
            {
                $tempdata['stripe_customer_id'] = $customer['id'];
                $temp_data['stripe_plan_id'] = $plan['id'];
                $temp_data['stripe_subscriptions_id'] = $subscription['id'];
                $this->seller_repo->update($product_seller, $tempdata);
                $this->product_quotation_repo->update($product_quote, $temp_data);
            }
            return response()->json('Storage Payment Completed', 200);
        } catch (\RuntimeException $e)
        {
            return response()->json('Payment Not Completed please retry', 500);
        }
        return response()->json('Payment Not Completed please retry', 500);
    }

}
