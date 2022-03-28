<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Entities\Users;
use App\Entities\Role;
use Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use App\Repository\ProductsRepository as product_repo;
use App\Repository\ProductsQuotationRepository as product_quotation_repo;
use App\Repository\SubCategoryRepository as sub_category_repo;

class WebhookProductStockUpdateController extends Controller
{

    public function __construct(product_quotation_repo $product_quotation_repo, product_repo $product_repo)
    {

        $this->product_quotation_repo = $product_quotation_repo;
        $this->product_repo = $product_repo;
    }

    public function WebhooksProductStockUpdate(Request $request)
    {

        Log::info(json_encode($request->all()));

        $data_product_quo = array();

        $wp_product_id = $request->id;

        $product_quot = $this->product_quotation_repo->ProductQuotationOfWpProductId($wp_product_id);

            if (isset($request->stock_quantity)) {
                $data_product_quo['wp_stock_quantity'] = $request->stock_quantity;
            }

            if (isset($request->stock_status)) {
                $data_product_quo['wp_stock_status'] = $request->stock_status;
            }

            if ($this->product_quotation_repo->update($product_quot, $data_product_quo)) {

                return response()->json('Product Updated Successfully', 200);
            }

    }

}
