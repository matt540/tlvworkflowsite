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

class WebhooksProductsController extends Controller {

    public function __construct(product_quotation_repo $product_quotation_repo, product_repo $product_repo, sub_category_repo $sub_category_repo) {

        $this->product_quotation_repo = $product_quotation_repo;
        $this->product_repo = $product_repo;
        $this->sub_category_repo = $sub_category_repo;
    }

    public function WebhooksProducts(Request $request) {

        Log::info(json_encode($request->all()));



        $data_product = array();
        $data_product_quo = array();

        $meta_data = array(
            'lv_pro_state_name' => 'state',
            'lv_pro_city_name' => 'city',
            'txt_pen_seller_shipping_size' => 'ship_size',
            'txt_pen_seller_material' => 'ship_material',
            'txt_pen_local_pickup_allow' => 'local_pickup_available',
            'tlv_is_this_pet_free' => 'pet_free',
            'lv_pro_location_type' => 'location',
            'estimated_retail_price' => 'price',
            'txt_pen_flat_rate_ship' => 'flat_rate_packaging_fee',
        );

        $meta_data_pro_quo = array(
            'txt_pro_commission' => 'commission',
            'txt_pen_seller_units' => 'units',
            'txt_pen_seller_seat_height' => 'seat_height',
            'txt_pen_seller_arm_height' => 'arm_height',
            'txt_pen_seller_inside_seat_depth' => 'inside_seat_depth',
            'txt_pen_seller_depth' => 'depth',
            'txt_pro_curator_commission' => 'curator_commission',
            'txt_pro_curator_name' => 'curator_name',
            '_max_tlv_pro_price' => 'tlv_suggested_price_max',
            '_min_tlv_pro_price' => 'tlv_suggested_price_min',
            'delivery_information' => 'delivery_option',
            'estimated_retail_price' => 'price',
            'tlv_str_price_m' => 'storage_pricing',
//            'txt_pen_seller_flat_rate_en' => 'wp_flat_rate',
            '_date_tlv_pro_renew_exp' => 'wp_product_expire_date',
            'seller_to_drop_off' => 'seller_to_drop_off',
            'tlv_pro_shipping_calculator' => 'shipping_calculator'

        );


        $wp_product_id = $request->id;


        $product_quotation_get = $this->product_quotation_repo->getProductInfoFromWpProductIds($wp_product_id);


        if (count($product_quotation_get) > 0) {

            $product = $this->product_repo->ProductOfId($product_quotation_get['0']['id']);




            if (isset($request->name)) {
                $data_product['name'] = $request->name;
            }
            if (isset($request->sku)) {
                $data_product['sku'] = $request->sku;
            }
            if (isset($request->short_description)) {
                $data_product['description'] = $request->short_description;
            }

            if (isset($request->regular_price)) {
                $data_product['tlv_price'] = $request->regular_price;
            }




            foreach ($request->meta_data as $key => $meta_data_val) {

                if (array_key_exists($meta_data_val['key'], $meta_data)) {

                    $colum = $meta_data[$meta_data_val['key']];

                    $data_product[$colum] = $meta_data_val['value'];
                }
            }

            foreach ($request->categories as $key => $categories_val) {

                $cat_product = $this->sub_category_repo->SubCategoryOfWpId($categories_val['id']);

                if (count($cat_product) > 0) {
                    $data_product['product_category'][] = $cat_product;
                }


                // Log::info($this->sub_category_repo->SubCategoryOfWpId($categories_val->id));
            }


            $this->product_repo->update($product, $data_product);




            $product_quot = $this->product_quotation_repo->ProductQuotationOfWpProductId($wp_product_id);


            foreach ($request->meta_data as $key => $meta_data_val1) {

                if (array_key_exists($meta_data_val1['key'], $meta_data_pro_quo)) {

                    $colum1 = $meta_data_pro_quo[$meta_data_val1['key']];

                    if (is_array($meta_data_val1['value'])) {
                        $data_product_quo[$colum1] = implode(', ', $meta_data_val1['value']);
                    } else {
                        $data_product_quo[$colum1] = $meta_data_val1['value'];
                    }

                }

                if ($meta_data_val1['key'] == 'txt_pen_seller_flat_rate_en') {
                    if ($meta_data_val1['value'] == '') {
                        $data_product_quo['wp_flat_rate'] = '0';
                    } else {
                        $data_product_quo['wp_flat_rate'] = $meta_data_val1['value'];
                    }
                }


            }



            if (isset($request->description)) {
                $data_product_quo['dimension_description'] = $request->description;
            }

            if (isset($request->tax_status)) {
                $data_product_quo['tax_status'] = $request->tax_status;
            }

            if (isset($request->tax_class)) {
                $data_product_quo['tax_class'] = $request->tax_class;
            }

            if (isset($request->manage_stock)) {
                $data_product_quo['wp_manage_stock'] = $request->manage_stock;
            }

            if (isset($request->stock_quantity)) {
                $data_product_quo['wp_stock_quantity'] = $request->stock_quantity;
            }

            if (isset($request->stock_status)) {
                $data_product_quo['wp_stock_status'] = $request->stock_status;
            }

            if (isset($request->shipping_class)) {
                $data_product_quo['shipping_class'] = $request->shipping_class;
            }


            if (isset($request->weight)) {
                $data_product_quo['weight'] = $request->weight;
            }

            if (isset($request->dimensions['length'])) {
                $data_product_quo['length'] = $request->dimensions['length'];
            }

            if (isset($request->dimensions['width'])) {
                $data_product_quo['width'] = $request->dimensions['width'];
            }

            if (isset($request->dimensions['height'])) {
                $data_product_quo['height'] = $request->dimensions['height'];
            }



            if (isset($request->regular_price)) {
                $data_product_quo['tlv_price'] = $request->regular_price;
            }

            if (isset($request->sale_price)) {
                $data_product_quo['wp_sale_price'] = $request->sale_price;
            }

            if (isset($request->date_created)) {
                $data_product_quo['wp_published_date'] = new \DateTime(date('Y-m-d H:i:s', strtotime($request->date_created)));
            }

//            if (isset($request->seller_to_drop_off)) {
//                $data_product_quo['seller_to_drop_off'] = $request->seller_to_drop_off;
//            }
//
//
            //  Log::info(json_encode($data_product_quo));

            if ($this->product_quotation_repo->update($product_quot, $data_product_quo)) {

                return response()->json('Product Updated Successfully', 200);
            }
        }
    }

}
