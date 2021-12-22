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
use App\Repository\CategoryRepository as category_repo;
use App\Repository\SubCategoryRepository as sub_category_repo;
use App\Repository\SelectRepository as select_repo;
use Auth;
use Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use File;
use \Image;
use App\Repository\ImagesRepository as image_repo;
use Excel;
use PHPExcel_Worksheet_Drawing;
use PHPExcel_Style_Fill;
use Validator;
use App\Imports\ProductsImport;

class ProductsImportController extends Controller {

    public function __construct(image_repo $image_repo, select_repo $select_repo, sub_category_repo $sub_category_repo, category_repo $category_repo, option_repo $option_repo, product_quote_repo $product_quote_repo, seller_repo $seller_repo, product_repo $product_repo, user_repo $user_repo, role_repo $role_repo) {

        $this->image_repo = $image_repo;
        $this->product_quote_repo = $product_quote_repo;
        $this->product_repo = $product_repo;
        $this->user_repo = $user_repo;
        $this->role_repo = $role_repo;
        $this->seller_repo = $seller_repo;
        $this->option_repo = $option_repo;
        $this->category_repo = $category_repo;
        $this->sub_category_repo = $sub_category_repo;
        $this->select_repo = $select_repo;
    }

    public function importProduct(Request $request) {

        //  Log::info($request->all());
        ini_set('memory_limit', '20000M');
        ini_set('max_execution_time', 300000);


        $data = $request->all();



        $seller_id = $data['seller'];

        if ($data['product_file'] != 'undefined') {

            $extension = File::extension($data['product_file']->getClientOriginalName());


            if ($extension == 'xlsx' || $extension == 'xls') {


                //$data_excel = Excel::load($data['product_file']->getRealPath());
//                $data_excel = Excel::import(new ProductsImport,$data['product_file']->getRealPath());

                $productData = new ProductsImport();
                $productData->import(request()->file('product_file'));
                $pro_data = $productData->data;


                foreach ($pro_data as $key => $data) {

                    if (isset($data['product_name']) && $data['product_name'] != '') {

                        if (isset($data['product_name']) && $data['product_name'] != '') {
                            $data_product['name'] = $data['product_name'];
                        }

                        if (isset($data['quantity']) && $data['quantity'] != '') {
                            $data_product['quantity'] = $data['quantity'];
                        }

                        if (isset($data['retail_price']) && $data['retail_price'] != '') {
                            $data_product['price'] = $data['retail_price'];
                        }

                        if (isset($data['tlv_price']) && $data['tlv_price'] != '') {
                            $data_product['tlv_price'] = $data['tlv_price'];
                        }

                        if (isset($data['age']) && $data['age'] != '') {
                            $age = $this->sub_category_repo->SubCategoryOfName($data['age']);

                            if ($age != NULL || $age != '') {
                                $data_product['age'] = $age;
                            }
                        }

                        if (isset($data['brand']) && $data['brand'] != '') {
                            $brand = $this->sub_category_repo->SubCategoryOfName($data['brand']);

                            if ($brand != NULL || $brand != '') {
                                $data_product['brand'] = $brand;
                            }
                        }


                        if (isset($data['category']) && $data['category'] != '') {
                            $data_product['product_category'] = [];

                            $product_category = $this->sub_category_repo->SubCategoryOfName($data['category']);

                            if ($product_category != NULL || $product_category != '') {
                                $data_product['product_category'][] = $product_category;
                            }

                        }

                        if (isset($data['sub_category']) && $data['sub_category'] != '') {

                            $product_category = $this->sub_category_repo->SubCategoryOfName($data['sub_category']);

                            if ($product_category != NULL || $product_category != '') {
                                $data_product['product_category'][] = $product_category;
                            }
                        }

                        if (isset($data['color']) && $data['color'] != '') {
                            $data_product['product_color'] = [];
                            $product_color = $this->sub_category_repo->SubCategoryOfName($data['color']);

                            if ($product_color != NULL || $product_color != '') {
                                $data_product['product_color'][] = $product_color;
                            }
                        }

                        if (isset($data['condition']) && $data['condition'] != '') {
                            $data_product['product_con'] = [];
                            $data_product['product_con'][] = $this->sub_category_repo->SubCategoryOfName($data['condition']);
                        }

                        if (isset($data['materials']) && $data['materials'] != '') {
                            $data_product['product_materials'] = [];

                            $product_materials = $this->sub_category_repo->SubCategoryOfName($data['materials']);

                            if ($product_materials != NULL || $product_materials != '') {
                                $data_product['product_materials'][] = $product_materials;
                            }
                        }

                        if (isset($data['dimensionsproduct_details']) && $data['dimensionsproduct_details'] != '') {
                            $data_product['description'] = $data['dimensionsproduct_details'];
                        }

//                        if (isset($data['shipping_size']) && $data['shipping_size'] != '') {
//                            $data_product['ship_size'] = $data['shipping_size'];
//                        }
//                        if (isset($data['material']) && $data['material'] != '') {
//                            $data_product['ship_material'] = $data['material'];
//                        }
//                        if (isset($data['local_pickup']) && $data['local_pickup'] != '') {
//                            $data_product['local_pickup_available'] = $data['local_pickup'];
//                        }
//                        if (isset($data['shipping_category']) && $data['shipping_category'] != '') {
//                            $data_product['ship_cat'] = $data['shipping_category'];
//                        }
//                        if (isset($data['flat_rate_packaging_fee']) && $data['flat_rate_packaging_fee'] != '') {
//                            $data_product['flat_rate_packaging_fee'] = $data['flat_rate_packaging_fee'];
//                        }
//
//                        if (isset($data['is_this_a_pet_free_home']) && $data['is_this_a_pet_free_home'] != '') {
//                            $data_product['pet_free'] = $data['is_this_a_pet_free_home'];
//                        }

                        if (isset($data['city']) && $data['city'] != '') {
                            $data_product['city'] = $data['city'];
                        }

                        if (isset($data['state']) && $data['state'] != '') {
                            $data_product['state'] = $data['state'];
                        }

                        if (isset($data['internal_note']) && $data['internal_note'] != '') {
                            $data_product['note'] = $data['internal_note'];
                        }

                        $data_product['sellerid'] = $this->seller_repo->SellerOfId($seller_id);


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

                        $data_product['sku'] = $data_product['sku'] . $data_product['sellerid']->getWp_seller_id() . $sku_number;

                        $data_product['status'] = $this->option_repo->OptionOfId(7);
                        $data_product['is_set_approved_date'] = 1;



                        $product_obj = $this->product_repo->prepareData($data_product);
                        $product_id = $this->product_repo->create($product_obj);



                        $product_created = $this->product_repo->ProductOfId($product_id);


                        $data_product_quo['product_id'] = $product_created;

                        $data_product_quo['price'] = $data_product_quo['product_id']->getPrice();

                        $data_product_quo['tlv_price'] = $data_product_quo['product_id']->getTLVPrice();

                        $data_product_quo['quantity'] = $data_product_quo['product_id']->getQuantity();

                        $data_product_quo['note'] = $data_product_quo['product_id']->getNote();

                        $data_product_quo['images_from'] = 1;

                        $data_product_quo['is_updated_details'] = 1;

                        $data_product_quo['tlv_suggested_price_min'] = $data_product_quo['product_id']->getTlv_suggested_price_min();

                        $data_product_quo['tlv_suggested_price_max'] = $data_product_quo['product_id']->getTlv_suggested_price_max();

                        $data_product_quo['dimension_description'] = $data_product_quo['product_id']->getDescription();

                        $data_product_quo['is_send_mail'] = 1;

                        $data_product_quo['is_awaiting_contract'] = 1;

                        $data_product_quo['for_awaiting_contract_created_at'] = 1;


                        if (isset($data['storage_price']) && $data['storage_price'] != '') {
                            $data_product_quo['storage_pricing'] = $data['storage_price'];
                        }

                        if (isset($data['commission']) && $data['commission'] != '') {
                            $data_product_quo['commission'] = $data['commission'];
                        }

                        if (isset($data['condition_notes']) && $data['condition_notes'] != '') {
                            $data_product_quo['condition_note'] = $data['condition_notes'];
                        }

//                        if (isset($data['product_location']) && $data['product_location'] != '') {
//                            $data_product_quo['seller_to_drop_off'] = $data['product_location'];
//                        }
//                        if (isset($data['shipping_calculator']) && $data['shipping_calculator'] != '') {
//                            $data_product_quo ['shipping_calculator'] = $data['shipping_calculator'];
//                        }


                        if (isset($data['units']) && $data['units'] != '') {
                            $data_product_quo['units'] = $data['units'];
                        }

                        if (isset($data['width']) && $data['width'] != '') {
                            $data_product_quo['width'] = $data['width'];
                        }

                        if (isset($data['depth']) && $data['depth'] != '') {
                            $data_product_quo['depth'] = $data['depth'];
                        }

                        if (isset($data['height']) && $data['height'] != '') {
                            $data_product_quo['height'] = $data['height'];
                        }

                        if (isset($data['seat_height']) && $data['seat_height'] != '') {
                            $data_product_quo['seat_height'] = $data['seat_height'];
                        }

                        if (isset($data['arm_height']) && $data['arm_height'] != '') {
                            $data_product_quo['arm_height'] = $data['arm_height'];
                        }

                        if (isset($data['inside_seat_depth']) && $data['inside_seat_depth'] != '') {
                            $data_product_quo['inside_seat_depth'] = $data['inside_seat_depth'];
                        }

                        if (isset($data['dimensionsproduct_details']) && $data['dimensionsproduct_details'] != '') {
                            $data_product_quo['dimension_description'] = $data['dimensionsproduct_details'];
                        }

//                        if (isset($data['delivery_option']) && $data['delivery_option'] != '') {
//                            $data_product_quo['delivery_option'] = $data['delivery_option'];
//                        }

//                        if (isset($data['curator_or_referral_name']) && $data['curator_or_referral_name'] != '') {
//                            $data_product_quo['curator_name'] = $data['curator_or_referral_name'];
//                        }
//
//                        if (isset($data['curator_or_referral_commission']) && $data['curator_or_referral_commission'] != '') {
//                            $data_product_quo['curator_commission'] = $data['curator_or_referral_commission'];
//                        }



                        $production_quotation_prepared = $this->product_quote_repo->prepareData($data_product_quo);

                        $quote_created_obj = $this->product_quote_repo->create($production_quotation_prepared);
                    }
                }


                return response()->json('Product Import Successfully', 200);
            } else {
                return response()->json('Only Upload xlsx or xls file format', 500);
            }
        } else {
            return response()->json('Please Choose File', 500);
        }
    }

}
