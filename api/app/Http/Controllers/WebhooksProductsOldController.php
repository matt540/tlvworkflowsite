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
use App\Repository\OptionRepository as option_repo;
use App\Repository\SellerRepository as seller_repo;
use App\Repository\ImagesRepository as image_repo;
use \Image;

class WebhooksProductsOldController extends Controller {

    public function __construct(product_quotation_repo $product_quotation_repo, product_repo $product_repo, sub_category_repo $sub_category_repo, option_repo $option_repo, seller_repo $seller_repo, image_repo $image_repo) {

        $this->product_quotation_repo = $product_quotation_repo;
        $this->product_repo = $product_repo;
        $this->sub_category_repo = $sub_category_repo;
        $this->option_repo = $option_repo;
        $this->seller_repo = $seller_repo;
        $this->image_repo = $image_repo;
    }

    public function webhooks_products_old(Request $request) {

      //  Log::info(json_encode($request->all()));
       // Log::info(date('Y-m-d H:i:s'));

        $data_product = array();
        $data_product_quo = array();
        $data_pro_quot = array();
        

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
            'txt_pen_seller_shipping_cat' => 'ship_cat',
        );

        $meta_data_pro_quo = array(
            'txt_pro_commission' => 'commission',
            'txt_pen_seller_units' => 'units',
            'txt_pen_seller_seat_height' => 'seat_height',
            'txt_pen_seller_arm_height' => 'arm_height',
            'txt_pen_seller_depth' => 'depth',
            'txt_pro_curator_commission' => 'curator_commission',
            'txt_pro_curator_name' => 'curator_name',
            '_max_tlv_pro_price' => 'tlv_suggested_price_max',
            '_min_tlv_pro_price' => 'tlv_suggested_price_min',
            'delivery_information' => 'delivery_option',
            'estimated_retail_price' => 'price',
            'tlv_str_price_m' => 'storage_pricing',
            '_date_tlv_pro_renew_exp' => 'wp_product_expire_date',
            'seller_to_drop_off' => 'seller_to_drop_off',
            'tlv_pro_shipping_calculator' => 'shipping_calculator'
        );


        $wp_product_id = $request->id;


        $product_quotation_get = $this->product_quotation_repo->getProductInfoFromWpProductIds($wp_product_id);


        if (count($product_quotation_get) <= 0) {


            $data_product['status'] = $this->option_repo->OptionOfId(7);


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

            if ($request->store['id'] != '') {

                $data_product['sellerid'] = $this->seller_repo->SellerOfWpId($request->store['id']);
            }

            if (isset($request->stock_quantity)) {
                $data_product['quantity'] = $request->stock_quantity;
            }


            foreach ($request->meta_data as $key => $meta_data_val) {

                if (array_key_exists($meta_data_val['key'], $meta_data)) {

                    $colum = $meta_data[$meta_data_val['key']];

                    $data_product[$colum] = $meta_data_val['value'];
                }
            }

            foreach ($request->cat_collation as $key => $cat_collation_val) {

                if ($cat_collation_val['term_id'] != '') {

                    $data_product['product_collection'][] = $this->sub_category_repo->SubCategoryOfWpId($cat_collation_val['term_id']);
                }
            }

            foreach ($request->categories as $key => $categories_val) {

                $cat_product = $this->sub_category_repo->SubCategoryOfWpId($categories_val['id']);

                if (count($cat_product) > 0) {
                    $data_product['product_category'][] = $cat_product;
                }
            }

            foreach ($request->cat_brand as $key => $cat_brand_val) {

                if ($cat_brand_val['term_id'] != '') {

                    $data_product['brand'] = $this->sub_category_repo->SubCategoryOfWpId($cat_brand_val['term_id']);
                }
            }

            foreach ($request->cat_room as $key => $cat_room_val) {

                if ($cat_room_val['term_id'] != '') {

                    $data_product['product_room'][] = $this->sub_category_repo->SubCategoryOfWpId($cat_room_val['term_id']);
                }
            }

            foreach ($request->cat_look as $key => $cat_look_val) {

                if ($cat_look_val['term_id'] != '') {

                    $data_product['product_look'][] = $this->sub_category_repo->SubCategoryOfWpId($cat_look_val['term_id']);
                }
            }

            foreach ($request->cat_color as $key => $cat_color_val) {

                if ($cat_color_val['term_id'] != '') {

                    $data_product['product_color'][] = $this->sub_category_repo->SubCategoryOfWpId($cat_color_val['term_id']);
                }
            }

            foreach ($request->cat_condition as $key => $cat_condition_val) {

                if ($cat_condition_val['term_id'] != '') {

                    $data_product['product_con'][] = $this->sub_category_repo->SubCategoryOfWpId($cat_condition_val['term_id']);
                }
            }


            foreach ($request->cat_age as $key => $cat_age_val) {

                if ($cat_age_val['term_id'] != '') {

                    $data_product['age'] = $this->sub_category_repo->SubCategoryOfWpId($cat_age_val['term_id']);
                }
            }

            foreach ($request->cat_material as $key => $cat_material_val) {

                if ($cat_material_val['term_id'] != '') {

                    $data_product['product_materials'][] = $this->sub_category_repo->SubCategoryOfWpId($cat_material_val['term_id']);
                }
            }

            $data_product['product_pending_images'] = array();

            foreach ($request->images as $key => $images_value) {

                if (file_exists($images_value['src'])) {

                    $filename = str_random(25) . '.' . pathinfo($images_value['src'], PATHINFO_EXTENSION);

                    $destinationPath = public_path() . '/../../Uploads/product/' . $filename;


                    copy($images_value['src'], $destinationPath);


                    //create thumb start

                    @mkdir(public_path() . '/../../Uploads/product/' . 'thumb', 0777);

                    $img = Image::make($destinationPath);

                    $img->resize(150, 150);


                    $img->save(public_path() . '/../../Uploads/product/' . 'thumb/' . $filename);

                    //create thumb end

                    $imageData = array();

                    $imageData['name'] = $filename;

                    $preparedData = $this->image_repo->prepareData($imageData);

                    $imageid = $this->image_repo->create($preparedData);

                    $data_product['product_pending_images'][] = $this->image_repo->ImageOfId($imageid);
                }
            }

            $data_product['is_touched'] = 1;
            $data_product['is_set_approved_date'] = 1;


            $prepared_data = $this->product_repo->prepareData($data_product);

            $product = $this->product_repo->create($prepared_data);



            // $product_quot = $this->product_quotation_repo->ProductQuotationOfWpProductId($wp_product_id);
            $product_id = $this->product_repo->ProductOfId($product);
            $data_product_quo['product_id'] = $product_id;
            $data_product_quo['status_quot'] = $this->option_repo->OptionOfId(18);
            $data_product_quo['wp_product_id'] = $wp_product_id;



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
                $data_product_quo['quantity'] = $request->stock_quantity;
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

            $data_product_quo['is_updated_details'] = 1;
            $data_product_quo['is_send_mail'] = 1;
            $data_product_quo['images_from'] = 1;
            $data_product_quo['is_copyright'] = 1;
            $data_product_quo['is_product_for_pricing'] = 1;
            $data_product_quo['is_awaiting_contract'] = 1;
            $data_product_quo['is_proposal_for_production'] = 1;
                      



            $production_quotation_prepared = $this->product_quotation_repo->prepareData($data_product_quo);
            $product_quotations = $this->product_quotation_repo->create($production_quotation_prepared);
            
            $product_quot = $this->product_quotation_repo->ProductQuotationOfId($product_quotations->getId());
            
            $data_pro_quot['is_copyright_create_date'] = 1;
            $data_pro_quot['is_approved_create_date'] = 1;
            $data_pro_quot['for_awaiting_contract_created_at'] = 1;
            $data_pro_quot['for_proposal_for_production_created_at'] = 1;
            $data_pro_quot['for_pricing_created_at'] = 1;
            
            $this->product_quotation_repo->update($product_quot, $data_pro_quot);
            
            
        }
    }
    
    
    public function webhooks_workflow_wpproductid() {
        
        $data = $this->product_quotation_repo->webhooks_workflow_wpproductid();
        
        return response()->json($data);
    }
}
