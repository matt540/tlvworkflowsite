<?php

namespace App\Http\Controllers;

use App\Repository\OptionRepository as option_repo;
use App\Repository\SellerRepository as seller_repo;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Entities\Users;
use App\Entities\Role;
use Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use App\Repository\ProductsRepository as product_repo;
use App\Repository\ProductsQuotationRepository as product_quotation_repo;

class ProductStuckRemoveController extends Controller
{

    public function __construct(product_quotation_repo $product_quotation_repo, product_repo $product_repo, seller_repo $seller_repo, option_repo $option_repo)
    {

        $this->product_quotation_repo = $product_quotation_repo;
        $this->product_repo = $product_repo;
        $this->seller_repo = $seller_repo;
        $this->option_repo = $option_repo;
    }

    public function product_stuck_remove(Request $request)
    {

        $data_product = array();
        $data_product_quo = array();
        $data_seller = array();

        $sku = $request->sku;
        try {
            if ($sku != '') {
                $product = $this->product_repo->ProductOfSku($sku);

                if ($product !== null) {

                    $product_quo = $this->product_quotation_repo->ProductQuotationOfProductId($product->getId());
                    $seller = $this->seller_repo->SellerOfId($product->getSellerid());

                    if ($product_quo->getWp_product_id() == '') {

//                    $data_product['city'] = $data_val['lv_pro_city_name'];
//                    $this->product_repo->update($product, $data_product);

                        $data_product_quo['in_queue'] = 0;
                        $data_product_quo['status_quot'] = $this->option_repo->OptionOfId(17);
                        $this->product_quotation_repo->update($product_quo, $data_product_quo);

                        $data_seller['in_queue'] = 1;
                        $this->seller_repo->update($seller, $data_seller);
                        return response()->json('Successfully', 200);
                    }
                    return response()->json('Error While Processing', 400);
                }
                return response()->json('Error While Processing', 400);
            }
            return response()->json('Error While Processing', 400);
        } catch (\Exception $e) {
            return response()->json('Error While Processing', 400);
        }
    }
}
