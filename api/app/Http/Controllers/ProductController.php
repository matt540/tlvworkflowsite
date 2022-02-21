<?php

namespace App\Http\Controllers;

use Log;
//use App\Library\Log as MailLog;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Entities\Users;
use App\Entities\Role;
use App\Repository\UserRepository as user_repo;
use App\Repository\RoleRepository as role_repo;
use App\Repository\OptionRepository as option_repo;
use App\Repository\ProductsRepository as product_repo;
use App\Repository\SellRepository as sell_repo;
use App\Repository\SubCategoryRepository as sub_category_repo;
use App\Repository\EmailTemplateRepository as email_template_repo;
use App\Repository\ProductsApprovedRepository as product_approved_repo;
use App\Repository\ImagesRepository as image_repo;
use App\Repository\ProductsQuotationRepository as product_quotation_repo;
use App\Repository\ScheduleRepository as schedule_repo;
use App\Repository\SellerRepository as seller_repo;
use App\Repository\MailRecordRepository as mail_record_repo;
use App\Repository\ProductQuoteAgreementRepository as product_quote_agreement_repo;
use Auth;
use \Image;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProductController extends Controller {

    public function __construct(product_quote_agreement_repo $product_quote_agreement_repo, mail_record_repo $mail_record_repo, seller_repo $seller_repo, schedule_repo $schedule_repo, product_quotation_repo $product_quotation_repo, image_repo $image_repo, product_approved_repo $product_approved_repo, email_template_repo $email_template_repo, option_repo $option_repo, sub_category_repo $sub_category_repo, sell_repo $sell_repo, product_repo $product_repo, user_repo $user_repo, role_repo $role_repo) {





        $this->mail_record_repo = $mail_record_repo;

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

        $this->schedule_repo = $schedule_repo;

        $this->seller_repo = $seller_repo;

        $this->product_quote_agreement_repo = $product_quote_agreement_repo;
    }

    public function deleteUser(Request $request) {

        $user = $this->user_repo->UserOfId($request->id);

        $this->user_repo->delete($user);
    }

    public function getProductsRejectedCount(Request $request) {

        return $this->product_repo->getProductsRejectedCount();
    }

    public function getAllArchivedProductsCount(Request $request) {

        return $this->product_repo->getAllArchivedProductsCount();
    }

    public function getProductsArchivedCount(Request $request) {

        return $this->product_repo->getProductsArchivedCount();
    }

    public function getProductsForReviewCount(Request $request) {

        return $this->product_repo->getProductsForReviewCount();
    }

    public function saveProduct(Request $request) {

        $data = $request->all();



        if ($request->id) {

            unset($data['sell_id']);



            $details = $this->product_repo->ProductOfId($request->id);

            if (isset($data['pick_up_location'])) {

                $data['pick_up_location'] = $this->option_repo->OptionOfId($data['pick_up_location']);
            }

            if (isset($data['brand'])) {

                $data['brand'] = $this->sub_category_repo->SubCategoryOfId($data['brand']);
            }
            if (isset($data['local_drop_off'])) {

                $data['local_drop_off'] = $data['local_drop_off'];
            }
            if (isset($data['local_drop_off_city'])) {
                if ($data['local_drop_off'] == 1) {
                    $data['local_drop_off_city'] = $data['local_drop_off_city'];
                } else {
                    $data['local_drop_off_city'] = NULL;
                }
            }


            if ($this->product_repo->update($details, $data)) {

                return response()->json('Product Updated Successfully', 200);
            }
        } else {

            $data_sell = array();

            $data_sell['user_id'] = JWTAuth::parseToken()->authenticate();

//            $data_sell['name'] = $data['sell_name'];
//            $prepared_data_sell = $this->sell_repo->prepareData($data_sell);
//            $sell = $this->sell_repo->create($prepared_data_sell);
//            if ($sell)
//            {
//            $data_product['sell_id'] = $this->sell_repo->SellOfId($sell);

            $data_product['status'] = $this->option_repo->OptionOfId(6);



            foreach ($data['products'] as $key => $value) {

                $data_product['name'] = $value['name'];

                if (isset($value['note'])) {

                    $data_product['note'] = $value['note'];
                }

                if (isset($value['price'])) {

                    $data_product['price'] = $value['price'];
                }

                // not checking for tlv priceing as adding product wont have that price

                $data_product['description'] = $value['description'];

                $data_product['quantity'] = $value['quantity'];

                if (isset($value['tlv_suggested_price_min'])) {

                    $data_product['tlv_suggested_price_min'] = $value['tlv_suggested_price_min'];
                }

                if (isset($value['tlv_suggested_price_max'])) {

                    $data_product['tlv_suggested_price_max'] = $value['tlv_suggested_price_max'];
                }

                if (isset($value['pick_up_location'])) {

                    $data_product['pick_up_location'] = $this->option_repo->OptionOfId($value['pick_up_location']);
                }

                if (isset($value['pet_free'])) {

                    $data_product['pet_free'] = $value['pet_free'];
                }

                if (isset($value['state'])) {

                    $data_product['state'] = $value['state'];
                }

                if (isset($value['city'])) {

                    $data_product['city'] = $value['city'];
                }


                if (isset($value['location'])) {

                    $data_product['location'] = $value['location'];
                }

                if (isset($value['local_drop_off'])) {

                    $data_product['local_drop_off'] = $value['local_drop_off'];
                }
                if (isset($value['local_drop_off_city'])) {
                    if ($value['local_drop_off'] == 1) {
                        $data_product['local_drop_off_city'] = $value['local_drop_off_city'];
                    } else {
                        $data_product['local_drop_off_city'] = NULL;
                    }
                }

                if (isset($value['category_local'])) {

                    $data_product['category_local'] = $value['category_local'];
                }

                if (isset($value['brand_local'])) {

                    $data_product['brand_local'] = $value['brand_local'];
                }

                if (isset($value['item_type_local'])) {

                    $data_product['item_type_local'] = $value['item_type_local'];
                }

                if (isset($value['age'])) {

                    $data_product['age'] = $this->sub_category_repo->SubCategoryOfId($value['age']);
                }

                if (isset($value['brand'])) {

                    $data_product['brand'] = $this->sub_category_repo->SubCategoryOfId($value['brand']);
                }



                if (isset($value['condition_local'])) {

                    $data_product['condition_local'] = $value['condition_local'];
                }







                $data_product['sellerid'] = $this->seller_repo->SellerOfId($data['sellerid']);



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





                $data_product['sku'] = $this->cleanString($data_product['sku'] . $data_product['sellerid']->getWp_seller_id() . $sku_number);



                $data_product['product_pending_images'] = array();



                if (isset($value['images'])) {

                    foreach ($value['images'] as $key_item => $value_item) {

                        $data_product['product_pending_images'][] = $this->image_repo->ImageOfId($value_item);
                    }
                }

                foreach ($value['cat'] as $x => $y) {



                    if ($y != '') {

//                        if ($x == 'Condition')
//                        {
//                            $data_product['con'] = $this->sub_category_repo->SubCategoryOfId($y);
//                        }
//                        else

                        if ($x == 'Collection') {

                            foreach ($y as $y_key => $y_value) {

                                $data_product['product_collection'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                            }
                        } else if ($x == 'Room') {

                            foreach ($y as $y_key => $y_value) {

                                $data_product['product_room'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                            }
                        } else if ($x == 'Look') {

                            foreach ($y as $y_key => $y_value) {

                                $data_product['product_look'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                            }
                        } else if ($x == 'Color') {

                            foreach ($y as $y_key => $y_value) {

                                $data_product['product_color'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                            }
                        } else if ($x == 'Sub Category') {

                            foreach ($y as $y_key => $y_value) {

                                $data_product['product_category'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                            }
                        } else if ($x == 'Condition') {

                            foreach ($y as $y_key => $y_value) {

                                $data_product['product_con'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                            }
                        } else {

                            $data_product[strtolower($x)] = $this->sub_category_repo->SubCategoryOfId($y);
                        }
                    }
                }



                $prepared_data = $this->product_repo->prepareData($data_product);

                $product = $this->product_repo->create($prepared_data);



                $introLines = array();

                $introLines[0] = "A new product sell request has been added to the TLV Workflow.";

                $introLines[1] = "Please review here: https://tlv-workflowapp.com/seller/product";

                $myViewData = \View::make('emails.new_product_email', ['level' => 'success', 'outroLines' => [0 => ''], 'introLines' => $introLines])->render();

                $option = $this->option_repo->OptionOfId(79);

                if (app('App\Http\Controllers\EmailController')->sendMail($option->getValueText(), 'TLV Workflow: Product Sell Request ', $myViewData)) {

                }

//                    if ($product)
//                    {
//                        foreach ($value['images'] as $key => $value)
//                        {
//                            $details_temp = $this->product_approved_repo->ProductApprovedOfId($details['product_id']);
//                            $this->product_approved_repo->update($details_temp, $data_product);
//                        }
//                    }
//                }
            }

            return response()->json('Product saved Successfully', 200);
        }
    }

    public function saveWpProduct(Request $request) {

        $data = $request->all();



        Log::info(json_encode($data));

        $productDetails = array();

        $productDetails['wp_product_id'] = $data['pending-sell-id'];

        if (isset($data['image_src'])) {

            $productDetails['wp_image_url'] = $data['image_src'];
        }

        if (isset($data['petfree'])) {

            $productDetails['pet_free'] = $data['petfree'];
        }

//        if (isset($data['product_brand'])) {
//
//            $productDetails['brand_local'] = $data['product_brand'];
//        }
//
//        if (isset($data['product_condition'])) {
//
//            if ($data['product_condition'] == "4361") {
//
//                $productDetails['condition_local'] = "Excellent";
//            } else if ($data['product_condition'] == "4815") {
//
//                $productDetails['condition_local'] = "Very Good";
//            } else if ($data['product_condition'] == "4363") {
//
//                $productDetails['condition_local'] = "Good";
//            } else if ($data['product_condition'] == "4364") {
//
//                $productDetails['condition_local'] = "Fair";
//            }
//        }

        if (isset($data['product_cat'])) {

            if ($data['product_cat'] == "2926") {

                $productDetails['category_local'] = "Seating";
            } else if ($data['product_cat'] == "2929") {

                $productDetails['category_local'] = "Tables";
            } else if ($data['product_cat'] == "2941") {

                $productDetails['category_local'] = "Storage";
            } else if ($data['product_cat'] == "2935") {

                $productDetails['category_local'] = "Lighting";
            } else if ($data['product_cat'] == "2977") {

                $productDetails['category_local'] = "Rugs";
            } else if ($data['product_cat'] == "2932") {

                $productDetails['category_local'] = "Accessories";
            }
        }


        if (isset($data['product_condition_cat'])) {

            $productDetails['product_con'] = $this->sub_category_repo->SubCategoryOfId($data['product_condition_cat']);
        }

        if (isset($data['product_color_cat'])) {

            $productDetails['product_color'] = $this->sub_category_repo->SubCategoryOfId($data['product_color_cat']);
        }

        if (isset($data['product_material_cat'])) {

            $productDetails['product_materials'] = $this->sub_category_repo->SubCategoryOfId($data['product_material_cat']);
        }


        if (isset($data['loc_type'])) {

            if ($data['loc_type'] == 'sellerhome') {

                $productDetails['pick_up_location'] = $this->option_repo->OptionOfId(22);
            }

//            else if ($data['loc_type'] == 'sellerhome')
//            {
//                 $productDetails['pick_up_location'] = $this->option_repo->OptionOfId(22);
//            }
        }



        $productDetails['product_pending_images'] = array();

        if (isset($data['image_src'])) {

            $filename_main = str_random(25) . '.' . pathinfo($data['image_src'], PATHINFO_EXTENSION);

            $destinationPath_main = public_path() . '/../../Uploads/product/' . $filename_main;

            copy($data['image_src'], $destinationPath_main);





            //create thumb start

            @mkdir(public_path() . '/../../Uploads/product/' . 'thumb', 0777);

            $img = Image::make($destinationPath_main);

            $img->resize(150, 150);

            $img->save(public_path() . '/../../Uploads/product/' . 'thumb/' . $filename_main);

            //create thumb end



            $imageData_main = array();

            $imageData_main['name'] = $filename_main;

            $preparedData_main = $this->image_repo->prepareData($imageData_main);





            $imageid_main = $this->image_repo->create($preparedData_main);

            $productDetails['product_pending_images'][] = $this->image_repo->ImageOfId($imageid_main);
        }



        if (count(json_decode($data['gallery_imgs'])) > 0) {

            foreach (json_decode($data['gallery_imgs']) as $key => $value) {

                $filename = str_random(25) . '.' . pathinfo($value, PATHINFO_EXTENSION);

                $destinationPath = public_path() . '/../../Uploads/product/' . $filename;

                copy($value, $destinationPath);



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

                $productDetails['product_pending_images'][] = $this->image_repo->ImageOfId($imageid);
            }
        }





        if (array_key_exists('pending-sell-title', $data)) {

            $productDetails['name'] = $data['pending-sell-title'];
        }

        if (array_key_exists('pending-sell-price', $data)) {

            $productDetails['price'] = $data['pending-sell-price'];
        }

        if (array_key_exists('pending-sell-quan', $data)) {

            $productDetails['quantity'] = $data['pending-sell-quan'];
        }

        if (array_key_exists('pending-sell-desc', $data)) {

            $productDetails['description'] = $data['pending-sell-desc'];
        }

        if (isset($data['pending-sell-state'])) {

            $productDetails['state'] = $data['pending-sell-state'];
        }

        if (isset($data['pending-sell-city'])) {

            $productDetails['city'] = $data['pending-sell-city'];
        }

        if (isset($data['pending_sell_measurment'])) {

            $productDetails['pending_sell_measurment'] = $data['pending_sell_measurment'];
        }

        if (isset($data['pending-sell-ship-size'])) {

            $productDetails['ship_size'] = $data['pending-sell-ship-size'];
        }

        if (isset($data['pending-sell-ship-material'])) {
            if ($data['pending-sell-ship-material'] == 'on') {

                $productDetails['ship_material'] = 1;
            } else {

                $productDetails['ship_material'] = 0;
            }
        }

//        $productDetails['room'] = $this->sub_category_repo->SubCategoryOfWpId($data['product_room_tax']);
//        $productDetails['look'] = $this->sub_category_repo->SubCategoryOfWpId($data['product_look_tax']);
//        $productDetails['color'] = $this->sub_category_repo->SubCategoryOfWpId($data['product_color']);
//        $productDetails['brand'] = $this->sub_category_repo->SubCategoryOfWpId($data['product_brand']);
        if (isset($data['product_cat'])) {
            $productDetails['category'] = $this->sub_category_repo->SubCategoryOfWpId($data['product_cat']);
        }
//        $productDetails['collection'] = $this->sub_category_repo->SubCategoryOfWpId($data['product_coll_tax']);
        if (isset($data['product_condition'])) {
            $productDetails['con'] = $this->sub_category_repo->SubCategoryOfWpId($data['product_condition']);
        }

        if (isset($data['product_brand'])) {
            $productDetails['brand'] = $this->sub_category_repo->SubCategoryOfWpId($data['product_brand']);
        }


        $productDetails['status'] = $this->option_repo->OptionOfId(6);

        $productDetails['sellerid'] = $this->seller_repo->SellerOfWpId($data['seller_id']);

        if ($productDetails['sellerid']->getFirstname() != '' && $productDetails['sellerid']->getLastname() != '') {

            $productDetails['sku'] = substr($productDetails['sellerid']->getFirstname(), 0, 3) . substr($productDetails['sellerid']->getLastname(), 0, 3);
        } else {

            $productDetails['sku'] = substr($productDetails['sellerid']->getDisplayname(), 0, 3);
        }

        $sku_number = $productDetails['sellerid']->getLastSku();

        $sku_number += 1;

        $seller_updated_sku['is_update_last_sku'] = 1;

        $seller_updated_sku['last_sku'] = $sku_number;

        $this->seller_repo->update($productDetails['sellerid'], $seller_updated_sku);



//        $productDetails['sku'] = $productDetails['sku'] . rand(1000, 100000);

        if ($sku_number < 100) {

            if ($sku_number < 10) {

                $sku_number = '00' . $sku_number;
            } else {

                $sku_number = '0' . $sku_number;
            }
        }

        $productDetails['sku'] = $this->cleanString($productDetails['sku'] . $productDetails['sellerid']->getWp_seller_id() . $sku_number);



        $prepared_data = $this->product_repo->prepareData($productDetails);



        $product = $this->product_repo->create($prepared_data);



//        $introLines = array();
//        $introLines[0] = "A new product sell request has been added to the TLV Workflow.";
//        $introLines[1] = "Please review here: http://tlv-test.com/seller/product";
//        $myViewData = \View::make('emails.new_product_email', ['level' => 'success', 'outroLines' => [0 => ''], 'introLines' => $introLines])->render();
//        $option = $this->option_repo->OptionOfId(79);
//        if (app('App\Http\Controllers\EmailController')->sendMail($option->getValueText(), 'TLV Workflow: Product Sell Request ', $myViewData))
//        {
//
//        }

        return response()->json($productDetails['sku'], 200);
    }

    public function editProduct(Request $request) {

        $data = $request->all();



        $sell_data = array();

//        $sell_data['name'] = $data['sell_name'];
//        $details = $this->sell_repo->SellOfId($data['sell_id']['id']);



        $data_product['status'] = $this->option_repo->OptionOfId($data['status']);

        $data_product['sellerid'] = $this->seller_repo->SellerOfId($data['sellerid']);

//        if ($this->sell_repo->update($details, $sell_data))
//        {

        $data_product['name'] = $data['name'];

        $data_product['note'] = $data['note'];

        if (isset($data['state'])) {

            $data_product['state'] = $data['state'];
        }

        if (isset($data['city'])) {

            $data_product['city'] = $data['city'];
        }



        if (isset($data['location'])) {

            $data_product['location'] = $data['location'];
        }



        if (isset($data['category_local'])) {



            $data_product['category_local'] = $data['category_local'];
        }

        if (isset($data['brand_local'])) {

            $data_product['brand_local'] = $data['brand_local'];
        }



//        $data_product['item_type_local'] = $data['item_type_local'];

        if (isset($data['age'])) {

            $data_product['age'] = $this->sub_category_repo->SubCategoryOfId($data['age']);
        }

        if (isset($data['brand'])) {

            $data_product['brand'] = $this->sub_category_repo->SubCategoryOfId($data['brand']);
        }



        if (isset($data['condition_local'])) {

            $data_product['condition_local'] = $data['condition_local'];
        }


        if (isset($data['local_drop_off'])) {

            $data_product['local_drop_off'] = $data['local_drop_off'];
        }
        if (isset($data['local_drop_off_city'])) {
            if ($data['local_drop_off'] == 1) {
                $data_product['local_drop_off_city'] = $data['local_drop_off_city'];
            } else {
                $data_product['local_drop_off_city'] = NULL;
            }
        }


        if (isset($data['price'])) {

            $data_product['price'] = $data['price'];
        }

        $data_product['description'] = $data['description'];

        $data_product['quantity'] = $data['quantity'];

        if (isset($data['tlv_suggested_price_min'])) {

            $data_product['tlv_suggested_price_min'] = $data['tlv_suggested_price_min'];
        }

        if (isset($data['tlv_suggested_price_max'])) {

            $data_product['tlv_suggested_price_max'] = $data['tlv_suggested_price_max'];
        }

        if (isset($data['pick_up_location'])) {

            $data_product['pick_up_location'] = $this->option_repo->OptionOfId($data['pick_up_location']);
        }

//        $data_product['pick_up_location'] = $data['pick_up_location'];

        if (isset($data['pet_free'])) {

            $data_product['pet_free'] = $data['pet_free'];
        }

        $data_product['sellerid'] = $this->seller_repo->SellerOfId($data['sellerid']);



        foreach ($data['cat'] as $x => $y) {

            if ($y != '') {

//                if ($x == 'Condition')
//                {
//                    $data_product['con'] = $this->sub_category_repo->SubCategoryOfId($y);
//                }
//                else

                if ($x == 'Collection') {

                    foreach ($y as $y_key => $y_value) {

                        $data_product['product_collection'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                    }
                } else if ($x == 'Room') {

                    foreach ($y as $y_key => $y_value) {

                        $data_product['product_room'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                    }
                } else if ($x == 'Look') {

                    foreach ($y as $y_key => $y_value) {

                        $data_product['product_look'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                    }
                } else if ($x == 'Color') {

                    foreach ($y as $y_key => $y_value) {

                        $data_product['product_color'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                    }
                } else if ($x == 'Sub Category') {

                    foreach ($y as $y_key => $y_value) {

                        $data_product['product_category'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                    }
                } else if ($x == 'Condition') {

                    foreach ($y as $y_key => $y_value) {

                        $data_product['product_con'][] = $this->sub_category_repo->SubCategoryOfId($y_value);
                    }
                } else {

                    $data_product[strtolower($x)] = $this->sub_category_repo->SubCategoryOfId($y);
                }
            }
        }

        foreach ($data['product_pending_images'] as $key_item => $value_item) {

            $data_product['product_pending_images'][] = $this->image_repo->ImageOfId($value_item);
        }



        unset($data['sell_id']);

        $details = $this->product_repo->ProductOfId($data['id']);

        if ($this->product_repo->update($details, $data_product)) {

            if ($data['status'] == 7) {

                $data2['product_id'] = $details;

                $data2['price'] = $details->getPrice();

                $data2['quantity'] = $details->getQuantity();

                $data2['note'] = $details->getNote();

                $data2['images_from'] = 1;

                $data2['is_updated_details'] = 1;

                $data2['tlv_suggested_price_min'] = $details->getTlv_suggested_price_min();

                $data2['tlv_suggested_price_max'] = $details->getTlv_suggested_price_max();

//                $data2['sort_description'] = $details->getDescription();

                $data2['dimension_description'] = $details->getDescription();

                $production_quotation_prepared = $this->product_quotation_repo->prepareData($data2);

                $this->product_quotation_repo->create($production_quotation_prepared);

//                if (count($details->getProductPendingImages()) > 0)
//                {
//                    $data_product['images_from'] = 0;
//                }
//                else
//                {
//                    $data_product['images_from'] = 1;
//                }
//                $details2 = $details;
//                $this->product_repo->update($details2, $data);
            }





            return response()->json('Product Updated Successfully', 200);
        } else {

            return response()->json('Oops! Something went wrong', 500);
        }

//        }
//        else
//        {
//            return response()->json('Oops! Something went wrong', 500);
//        }
    }

    public function getProduct(Request $request) {

        $product = $this->product_repo->getProductById($request->id);

        if ($product['is_touched'] == 0) {

            $data['is_touched'] = 1;

            $product_obj = $this->product_repo->ProductOfId($request->id);

            $this->product_repo->update($product_obj, $data);

            $product['is_touched'] = 1;
        }

        return $product;
    }

    public function getAllUsers() {

        return $this->user_repo->getAllUsers();
    }

    public function getProducts(Request $request) {

        $filter = $request->all();



        $data['draw'] = $filter['draw'];



//        if (JWTAuth::parseToken()->authenticate()->getRoles()[0]->getId() == 1)
//        {

        $users_data_total = $this->product_repo->getProducts($filter);

        $data['data'] = $users_data_total['data'];



        $data['recordsTotal'] = $users_data_total['total'];

        $data['recordsFiltered'] = $this->product_repo->getProductsTotal($filter);

//        }
//        else
//        {
//            $filter['userid'] = JWTAuth::parseToken()->authenticate()->getId();
//            $users_data_total = $this->product_repo->getTLVStaffProducts($filter);
//            $data['data'] = $users_data_total['data'];
//
//            $data['recordsTotal'] = $users_data_total['total'];
//            $data['recordsFiltered'] = $this->product_repo->getTLVStaffProductsTotal($filter);
//        }



        return response()->json($data, 200);
    }

    public function getAllProductsWithStatus(Request $request) {

        $filter = $request->all();



        $data['draw'] = $filter['draw'];



        $data_total = $this->product_repo->getAllProductsWithStatus($filter);

        $data['data'] = $data_total['data'];



        $data['recordsTotal'] = $data_total['total'];

        $data['recordsFiltered'] = $this->product_repo->getAllProductsWithStatusTotal($filter);



        return response()->json($data, 200);
    }

    public function getArchivedProducts(Request $request) {

        $filter = $request->all();



        $data['draw'] = $filter['draw'];



        $data_total = $this->product_repo->getArchivedProducts($filter);

        $data['data'] = $data_total['data'];



        $data['recordsTotal'] = $data_total['total'];

        $data['recordsFiltered'] = $this->product_repo->getArchivedProductsTotal($filter);



        return response()->json($data, 200);
    }

    public function deleteProduct(Request $request) {

        $quotations = $this->product_quotation_repo->getAllQuotationsOfProductId($request->id);

        if (count($quotations) > 0) {

            foreach ($quotations as $key => $value) {

                $quot = $this->product_quotation_repo->ProductQuotationOfId($value['id']);

                $this->product_quotation_repo->delete($quot);

                $schedules = array();

                $schedules = $this->schedule_repo->getAllSchedulesOfProductQuotationId($value['id']);

                if (count($schedules) > 0) {

                    foreach ($schedules as $key => $value2) {

                        $schedule = $this->schedule_repo->SchedulesOfId($value2['id']);

                        $this->schedule_repo->delete($schedule);
                    }
                }
            }
        }



        return $this->product_repo->delete($this->product_repo->ProductOfId($request->id));
    }

    public function changeProductStatusToReject(Request $request) {

        $temp = $request->all();

        $rejected_products_text = '';

        $products_reject = array();

        $products_approve = array();

        if (count($temp['product_status']) > 0) {

            $seller = $this->product_repo->ProductOfId($temp['product_status'][0]['product_id'])->getSellerid();
        }

        foreach ($temp['product_status'] as $key => $data) {

            if ($data['product_status_id'] == 8) {

                $details = $this->product_repo->ProductOfId($data['product_id']);



                $products_reject[] = $details;

//                $rejected_products_text .= $details->getName();
//                if ($key == (count($temp['product_status']) - 1))
//                {
//
//                }
//                else
//                {
//                    $rejected_products_text .= ',';
//                }
            }
        }

        $detailss = $this->product_repo->ProductOfId($temp['product_status'][0]['product_id']);

        if ($detailss->getSellerid()->getEmail() != '') {

            if ((count($products_approve) > 0 || count($products_reject) > 0) && $seller != '') {

                $greeting = "Dear " . $seller->getFirstName() . ',';

                $introLines = array();

                if ($temp['is_referral'] == 1) {

                    $introLines[0] = "Thank you so much for sending over the photo of your items. While they are lovely, I do not think we have the proper audience for them at this point. We do have a partnership with Blackrock Galleries in Greenwich and Bridgeport and I think they may be a good option for you. They are an auction based model. Please let me know if you would like an introduction.";
                } else {

                    $introLines[0] = "Thank you so much for submitting your Item(s) on TLV!  While they are lovely, we do not believe we currently have the proper audience for them. Sometimes we have to make these tough decisions based upon sales history and the buying preferences of our TLV audience. Please don’t let this news keep you from reaching out to us in the future regarding other Items! We wish you the best of luck and thank you for thinking of us!";
//                    $introLines[0] = "Thank you so much for sending over photos of your items. While they are lovely, we do not believe we currently have the audience for them. We wish you the best of luck and thank you for thinking of us.";
                }

                $myViewData = \View::make('emails.product_for_review_status_reject', ['products_reject' => $products_reject, 'greeting' => $greeting, 'seller' => $seller, 'level' => 'success', 'outroLines' => [0 => ''], 'introLines' => $introLines])->render();

                if (app('App\Http\Controllers\EmailController')->sendMail($seller->getEmail(), 'Thank you for reaching out to us at TLV!', $myViewData)) {

                }
            }

//            $myViewData = \View::make('emails.product_status_change', ['level' => 'success', 'outroLines' => [0 => ''], 'introLines' => [0 => 'Your Product ' . $rejected_products_text . ' has been rejected.']])->render();
//
//            if (app('App\Http\Controllers\EmailController')->sendMail($detailss->getSellerid()->getEmail(), 'Product Rejected', $myViewData))
//            {
//                return 1;
//            }
        }

        foreach ($temp['product_status'] as $key => $data) {





            $details = $this->product_repo->ProductOfId($data['product_id']);



            if ($data['product_status_id'] == 8) {



//                if ($details->getSellerid()->getEmail() != '')
//                {
//                    $myViewData = \View::make('emails.product_status_change', ['level' => 'success', 'outroLines' => [0 => ''], 'introLines' => [0 => 'Your Product ' . $details->getName() . ' has been rejected.']])->render();
//
//                    if (app('App\Http\Controllers\EmailController')->sendMail('smit.vora1@gmail.com', 'Product Rejected', $myViewData))
//                    {
//                        return 1;
//                    }
//                }

                $data['status'] = $this->option_repo->OptionOfId($data['product_status_id']);

                $this->product_repo->update($details, $data);
            }
        }
    }

    public function changeProductStatusToDelete(Request $request) {

        $temp = $request->all();

        $rejected_products_text = '';

        $products_reject = array();

        $products_approve = array();

        if (count($temp['product_status']) > 0) {

            $seller = $this->product_repo->ProductOfId($temp['product_status'][0]['product_id'])->getSellerid();
        }

        foreach ($temp['product_status'] as $key => $data) {

            if ($data['product_status_id'] == 85) {

                $details = $this->product_repo->ProductOfId($data['product_id']);

                $this->product_repo->delete($details);
            }
        }
    }

    public function sendProposalMail(Request $request) {

        $data = $request->all();

        $data['seller_id'] = $this->seller_repo->SellerOfId($data['seller_id']);

        $data['file_name'] = $data['seller_id']->getLastProductFileName();

        $filename = $data['file_name'];

//        $file_data = 'https://drive.google.com/file/d/' . $file['path'] . '/view'
//                . '';

        $file_data = config('app.url') . 'api/' . $data['seller_id']->getLastProductFileNameBase();

//        $file_data = 'https://drive.google.com/file/d/' . $data['seller_id']->getLastProductFileNameBase() . '/view'
//                . '';
//        return Storage::cloud()->get($file['path']);

        $data['from_state'] = 'product_for_review';

        $data['file_path'] = $data['seller_id']->getLastProductFileNameBase();

        $myViewData = \View::make('emails.product_status_change', ['level' => 'success', 'outroLines' => [0 => ''], 'introLines' => [0 => $data['message'], 1 => $file_data]])->render();



        if (app('App\Http\Controllers\EmailController')->sendMail($data['seller_id']->getEmail(), $data['subject'], $myViewData)) {

        }

        $prepared = $this->mail_record_repo->prepareData($data);

        $this->mail_record_repo->create($prepared);
    }

//Generate Proposal

    public function changeProductStatusToApprove(Request $request) {

        $temp = $request->all();



        foreach ($temp['products'] as $key => $data) {



            $data['status'] = $this->option_repo->OptionOfId($data['product_status_id']);

            $details = $this->product_repo->ProductOfId($data['product_id']);



            if ($data['product_status_id'] == 7) {

                $data['is_set_approved_date'] = 1;
            }

            $this->product_repo->update($details, $data);



            //insert data into approved product



            if ($data['product_status_id'] == 7) {

                $data2['product_id'] = $this->product_repo->ProductOfId($data['product_id']);

                $data2['price'] = $data2['product_id']->getPrice();

                $data2['quantity'] = $data2['product_id']->getQuantity();

                $data2['note'] = $data2['product_id']->getNote();

                $data2['images_from'] = 1;

                $data2['is_updated_details'] = 1;

                $data2['tlv_suggested_price_min'] = $data2['product_id']->getTlv_suggested_price_min();

                $data2['tlv_suggested_price_max'] = $data2['product_id']->getTlv_suggested_price_max();

//                $data2['sort_description'] = $data2['product_id']->getDescription();

                $data2['dimension_description'] = $data2['product_id']->getDescription();

                $production_quotation_prepared = $this->product_quotation_repo->prepareData($data2);

                $this->product_quotation_repo->create($production_quotation_prepared);

                if (count($data2['product_id']->getProductPendingImages()) > 0) {

                    $data_product['images_from'] = 0;
                } else {

                    $data_product['images_from'] = 1;
                }

                $details2 = $this->product_repo->ProductOfId($data['product_id']);

                $this->product_repo->update($details2, $data);
            }
        }

        $file_name = app('App\Http\Controllers\ExportController')->downloadProductWord($request);

        return $file_name;
    }

    public function changeProductStatusToArchive(Request $request) {

        $temp = $request->all();

        foreach ($temp['product_status'] as $key => $data) {



            $data['status'] = $this->option_repo->OptionOfId($data['product_status_id']);

            $details = $this->product_repo->ProductOfId($data['product_id']);

            $this->product_repo->update($details, $data);
        }

        return 1;
    }

    public function changeProductStatus(Request $request) {

        $temp = $request->all();
        $products_approve = array();
        $products_reject = array();
        $seller = '';

        if (count(($temp['product_status'])) > 0) {
            $seller = $this->product_repo->ProductOfId($temp['product_status'][0]['product_id'])->getSellerid();
        }

        //change 22-08-2018
        $product_quot_ids = [];

        foreach ($temp['product_status'] as $key => $data) {

            $data['status'] = $this->option_repo->OptionOfId($data['product_status_id']);

            $details = $this->product_repo->ProductOfId($data['product_id']);

            if ($data['product_status_id'] == 7) {
                $data['is_set_approved_date'] = 1;
            }

            $this->product_repo->update($details, $data);
            //add to products array
            if ($data['product_status_id'] == 7) {
                //7 for approve
                $products_approve[] = $details;
            } else if ($data['product_status_id'] == 8) {
                //7 for approve
                $products_reject[] = $details;
            }

            //insert data into approved product

            if ($data['product_status_id'] == 7) {

                $data2['product_id'] = $this->product_repo->ProductOfId($data['product_id']);
                $data2['price'] = $data2['product_id']->getPrice();
                $data2['quantity'] = $data2['product_id']->getQuantity();
                $data2['note'] = $data2['product_id']->getNote();
                $data2['images_from'] = 1;
                $data2['is_updated_details'] = 1;

//                $data2['tlv_suggested_price_min'] = $data2['product_id']->getTlv_suggested_price_min();
//                $data2['tlv_suggested_price_max'] = $data2['product_id']->getTlv_suggested_price_max();
//                $data2['sort_description'] = $data2['product_id']->getDescription();
//                todo add TLV price when told
                $data2['commission'] = 60;
                $data2['dimension_description'] = $data2['product_id']->getDescription();
                $production_quotation_prepared = $this->product_quotation_repo->prepareData($data2);
                $quote = $this->product_quotation_repo->create($production_quotation_prepared);


//                change 22-08-2018
//                $product_quot_ids[] = $quote->getId();

                if (count($data2['product_id']->getProductPendingImages()) > 0) {
                    $data_product['images_from'] = 0;
                } else {
                    $data_product['images_from'] = 1;
                }

                $details2 = $this->product_repo->ProductOfId($data['product_id']);

                $this->product_repo->update($details2, $data);


//            $data_product['sell_id'] = $this->sell_repo->SellOfId($details->getSell_id());
//            $data_product['product_id'] = $details;
//            $data_product['status'] = $this->option_repo->OptionOfId(11);
//
//            $data_product['name'] = $details->getName();
//            $data_product['price'] = $details->getPrice();
//            $data_product['description'] = $details->getDescription();
//            $data_product['quantity'] = $details->getQuantity();
//
////            $data_product['sku'] = $details->getSeller_firstname() . $details->getSeller_lastname();
//            $data_product['sku'] = $details->getSku();
//
//            $data_product['seller_id'] = $details->getSeller_id();
//
//            $data_product['room'] = $details->getRoom();
//            $data_product['look'] = $details->getLook();
//            $data_product['color'] = $details->getColor();
//            $data_product['brand'] = $details->getBrand();
//            $data_product['category'] = $details->getCategory();
//            $data_product['collection'] = $details->getCollection();
//            $data_product['con'] = $details->getCondition();
//            if (count($details->getProductPendingImages()) > 0)
//            {
//                foreach ($details->getProductPendingImages() as $key => $value)
//                {
//
//                    $data_product['product_images'][] = $value;
//                }
//                $data_product['images_from'] = 0;
//            }
//            else
//            {
//                $data_product['images_from'] = 1;
//                $data_product['product_images'] = array();
//            }
//
//            $prepared_data = $this->product_approved_repo->prepareData($data_product);
//            $this->product_approved_repo->create($prepared_data);
            }
        }

        if ((count($products_approve) > 0 || count($products_reject) > 0) && $seller != '') {

            if (isset($request->is_send_mail) && $request->is_send_mail == 'yes') {

                $product_quote_agreement = [];
                $product_quote_agreement['is_form_filled'] = 0;
                $product_quote_agreement['seller_id'] = $seller;
                $product_quote_agreement['pdf'] = '';
                $product_quote_agreement['quote_ids_json'] = json_encode($product_quot_ids);

                $product_quote_agreement_prepared_data = $this->product_quote_agreement_repo->prepareData($product_quote_agreement);
                $product_quote_agreement_obj = $this->product_quote_agreement_repo->create($product_quote_agreement_prepared_data);


                $product_quote_agreement_enc_id = \Crypt::encrypt($product_quote_agreement_obj->getId());
                $agreement_link = config('app.url') . 'seller_agreement/' . $product_quote_agreement_enc_id;

                $greeting = "Dear " . $seller->getFirstName() . ',';

                $introLines = array();
                $introLines_new = array();

//                old content 25-06-2019
//                $introLines[0] = "Thank you so much for sending over the photos and details of your lovely piece(s). We would be delighted to list them on The Local Vault! Just to give you a quick overview of our selling terms, we ask for the following:";
//                $introLines_new[] = 'TLV requires a 90-day exclusive contract to sell your items';
//                $introLines_new[] = '60/40 commission split (you keep 60%)';
//                $introLines_new[] = 'TLV does not take possession of items, they remain with you until sold (we do have an affordable storage option for sellers who require it)';
//                $introLines_new[] = '$150 production fee if you would like TLV to come to your home to photograph and catalog your items';
//                $introLines_new[]='Items will be priced based upon condition, market trends, and TLV sales data. We will present you with a pricing catalog before scheduling a photo shoot.';
//                old content 26-06-2019
//                $introLines[0] = "Thank you so much for sending over the photos and details of your lovely piece(s). We would be delighted to list them on The Local Vault!";
//                $introLines[1] = "Just to give you a quick overview of our selling terms, we ask for the following:";
//                $introLines_new[] = 'TLV requires a 4-month exclusive contract to sell your items.';
//                $introLines_new[] = 'Seller receives 60% of the sale price less the transaction fee.';
//                $introLines_new[] = 'Items remain with the seller until they have sold. Once sold, our logistics team will arrange a date for the item\'s pick up.';
//                $introLines_new[] = 'TLV has affordable storage for sellers who require it. Please let us know if you are interested in a quote!';
//                $introLines_new[] = 'Items will be priced based upon condition, market trends, and TLV sales data. We will present you with a pricing proposal before scheduling a photo shoot.';
//                $introLines_new[] = 'Once we have agreed upon pricing and a signed agreement, TLV will arrange an appointment to photograph and catalog the items.';
//                $introLines_new[] = 'There is a one-time production fee of $50 for the first 10 items and $5 for each additional item listed.';
//                $introLines_new[] = 'Payment will be processed after a sold item is received by the buyer.If these terms sound like the right fit for you, please reply to this email so we can get started selling your items!';

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

//                $line1 = 'If these terms sound like the right fit for you, please ';
//                $line2 = 'reply to this email ';
//                $line3 = 'so we can get started selling your Items!';
//                $introLines_reject = array();
//                $introLines_reject[0] = "While the remaining items listed below are lovely, we do not believe we have the audience for them at this point: ";

                $introLines_if_any_approved = array();

//                $introLines_if_any_approved[0] = 'We will be back in touch within the next week with a pricing proposal. Should you have any questions in the interim, feel free to contact us. ';
//              $folder_path = '../Uploads/mail/';
//                $seller_id_encrypt = \Crypt::encrypt($seller->getId());
//                $agree_terms_url = config('app.url') . 'api/seller/agree_terms/' . $seller_id_encrypt;

                $myViewData = \View::make('emails.product_for_review_status_change', [
                            'agreement_link' => $agreement_link,
                            'introLines_if_any_approved' => $introLines_if_any_approved,
//                            'line1' => $line1,
//                            'line2' => $line2,
//                            'line3' => $line3,
//                            'introLines_reject' => $introLines_reject,
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

//                $attachments[] = 'TLV Client Sale Agreement 7_31_17.docx.pdf';
//                $attachments[] = 'TLV_SelfPhotographyGuide_Final_22_03_18.pdf';
//                $attachments[] = 'TLV_SelfPhotographyGuide_06_02_2019.pdf';

                $ccs = [];

                $ccs[] = 'sell@thelocalvault.com';

                $other_emails = [];

                $other_emails[] = 'thelocalvaultcomproduction@thelocalvault.freshdesk.com';

                if (app('App\Http\Controllers\EmailController')->sendMail($seller->getEmail(), 'Consignment with The Local Vault: ' . $seller->getLastname(), $myViewData, $attachments, $bccs, $ccs, $other_emails)) {

                }
            }
        }

        return 1;
    }

    public function changeProductStatus_30_01_2018(Request $request) {

        $temp = $request->all();



        $products_approve = array();

        $products_reject = array();

        $seller = '';

        if (count(($temp['product_status'])) > 0) {

            $seller = $this->product_repo->ProductOfId($temp['product_status'][0]['product_id'])->getSellerid();
        }



        foreach ($temp['product_status'] as $key => $data) {



            $data['status'] = $this->option_repo->OptionOfId($data['product_status_id']);

            $details = $this->product_repo->ProductOfId($data['product_id']);





            if ($data['product_status_id'] == 7) {

                $data['is_set_approved_date'] = 1;
            }

            $this->product_repo->update($details, $data);

            //add to products array

            if ($data['product_status_id'] == 7) {

                //7 for approve

                $products_approve[] = $details;
            } else if ($data['product_status_id'] == 8) {

                //7 for approve

                $products_reject[] = $details;
            }



            //insert data into approved product



            if ($data['product_status_id'] == 7) {

                $data2['product_id'] = $this->product_repo->ProductOfId($data['product_id']);

                $data2['price'] = $data2['product_id']->getPrice();

                $data2['quantity'] = $data2['product_id']->getQuantity();

                $data2['note'] = $data2['product_id']->getNote();

                $data2['images_from'] = 1;

                $data2['is_updated_details'] = 1;

                $data2['tlv_suggested_price_min'] = $data2['product_id']->getTlv_suggested_price_min();

                $data2['tlv_suggested_price_max'] = $data2['product_id']->getTlv_suggested_price_max();

//                $data2['sort_description'] = $data2['product_id']->getDescription();

                $data2['dimension_description'] = $data2['product_id']->getDescription();

                $production_quotation_prepared = $this->product_quotation_repo->prepareData($data2);

                $this->product_quotation_repo->create($production_quotation_prepared);

                if (count($data2['product_id']->getProductPendingImages()) > 0) {

                    $data_product['images_from'] = 0;
                } else {

                    $data_product['images_from'] = 1;
                }

                $details2 = $this->product_repo->ProductOfId($data['product_id']);

                $this->product_repo->update($details2, $data);





//            $data_product['sell_id'] = $this->sell_repo->SellOfId($details->getSell_id());
//            $data_product['product_id'] = $details;
//            $data_product['status'] = $this->option_repo->OptionOfId(11);
//
//            $data_product['name'] = $details->getName();
//            $data_product['price'] = $details->getPrice();
//            $data_product['description'] = $details->getDescription();
//            $data_product['quantity'] = $details->getQuantity();
//
////            $data_product['sku'] = $details->getSeller_firstname() . $details->getSeller_lastname();
//            $data_product['sku'] = $details->getSku();
//
//            $data_product['seller_id'] = $details->getSeller_id();
//
//            $data_product['room'] = $details->getRoom();
//            $data_product['look'] = $details->getLook();
//            $data_product['color'] = $details->getColor();
//            $data_product['brand'] = $details->getBrand();
//            $data_product['category'] = $details->getCategory();
//            $data_product['collection'] = $details->getCollection();
//            $data_product['con'] = $details->getCondition();
//            if (count($details->getProductPendingImages()) > 0)
//            {
//                foreach ($details->getProductPendingImages() as $key => $value)
//                {
//
//                    $data_product['product_images'][] = $value;
//                }
//                $data_product['images_from'] = 0;
//            }
//            else
//            {
//                $data_product['images_from'] = 1;
//                $data_product['product_images'] = array();
//            }
//
//            $prepared_data = $this->product_approved_repo->prepareData($data_product);
//            $this->product_approved_repo->create($prepared_data);
            }
        }



        if ((count($products_approve) > 0 || count($products_reject) > 0) && $seller != '') {

            if (isset($request->is_send_mail) && $request->is_send_mail == 'yes') {







                $greeting = "Dear " . $seller->getFirstName() . ',';

                $introLines = array();

                $introLines[0] = "Thank you for submitting your pieces to The Local Vault! We would be delighted to move forward with the below items: ";



//                $introLines_new_subject_before = 'In order to price and title your collection correctly, it would be very helpful for us to gather from you any provenance, manufacturer or original retail information you might have.  Once we receive, we will work on our suggested pricing and be back in touch with our pricing proposal.';
//                $introLines_new_subject_before = 'In order to price and name your items accurately, it would be very helpful if you could share any provenance, manufacturer or original retail information.';

                $introLines_new_subject_before = 'In order to price and name your items accurately, it would be very helpful if you could share any receipts, provenance, manufacture or original retail information.';

                $introLines_new_subject_before2 = 'Once we receive, we will begin working on our suggested pricing and be back in touch with a pricing proposal.';



                $introLines_new_subject = 'Just to give you a quick overview of our selling terms:';

                $introLines_new = array();

                $introLines_new[] = 'TLV requires a 90-day exclusive contract to sell your items';

                $introLines_new[] = '60/40 commission split (you keep 60%)';

//                $introLines_new[] = 'TLV does not take possession of items, they remain with you until sold (we do offer affordable storage for a fee and would be happy to get you a quote.)';

                $introLines_new[] = 'TLV does not take possession of items, they remain with you until sold';

                $introLines_new[] = '$150 photography fee if you would like TLV to come to your home and photograph your items';

//                $introLines_new[] = 'In order to price and title your collection correctly, it would be very helpful for us to gather from you any provenance, manufacturer information or original retail information you might have. Once we receive, we will work on our suggested pricing and be back in touch with our pricing proposal.';
//                $introLines_new_after = 'Please let us know if you have any questions at all.';

                $introLines_new_after = 'We look forward to working with you!';



                $introLines_reject = array();

                $introLines_reject[0] = "While the remaining items listed below are lovely, we do not believe we have the audience for them at this point: ";



                $introLines_if_any_approved = array();

//                $introLines_if_any_approved[0] = 'We will be back in touch within the next week with a pricing proposal. Should you have any questions in the interim, feel free to contact us. ';
//              $folder_path = '../Uploads/mail/';

                $myViewData = \View::make('emails.product_for_review_status_change', ['introLines_if_any_approved' => $introLines_if_any_approved, 'introLines_reject' => $introLines_reject, 'introLines_new' => $introLines_new, 'introLines_new_subject_before' => $introLines_new_subject_before, 'introLines_new_subject_before2' => $introLines_new_subject_before2, 'introLines_new_after' => $introLines_new_after, 'introLines_new_subject' => $introLines_new_subject, 'greeting' => $greeting, 'seller' => $seller, 'products_approve' => $products_approve, 'products_reject' => $products_reject, 'level' => 'success', 'outroLines' => [0 => ''], 'introLines' => $introLines])->render();

                $bccs = [];

                $attachments = [];

//                $bccs[] = 'sell@thelocalvault.com';
//                $bccs[] = 'support@thelocalvault.freshdesk.com';

                $ccs = [];

                $ccs[] = 'sell@thelocalvault.com';

                $other_emails = [];

                $other_emails[] = 'thelocalvaultcomproduction@thelocalvault.freshdesk.com';

                if (app('App\Http\Controllers\EmailController')->sendMail($seller->getEmail(), 'Products for Sale Acknowledgement: ' . $seller->getLastname(), $myViewData, $attachments, $bccs, $ccs, $other_emails)) {

                }
            }
        }



        return 1;
    }

    public function get_all_product_status(Request $request) {

        $all_order_status = $this->option_repo->get_all_of_select_id(3);

        return $all_order_status;
    }

    public function getEmailTemplate(Request $request) {

        return $this->email_template_repo->getEmailTemplateById($request->id);
    }

    public function saveEmailTemplate(Request $request) {

        $data = $request->all();

        $et = $this->email_template_repo->EmailTemplateOfId($request->id);

        if ($this->email_template_repo->update($et, $data)) {

            return response()->json('Email Template Updated Successfully', 200);
        } else {

            return response()->json('Oops! some thing went wrong', 500);
        }
    }

    public function reopenProduct(Request $request) {

        $data = $request->all();



        foreach ($data as $key => $value) {

            $product_data = array();

            if ($value['quotation_id']) {

                $product_data['is_archived'] = 0;

                $product = $this->product_quotation_repo->ProductQuotationOfId($value['quotation_id']);

                $this->product_quotation_repo->update($product, $product_data);
            } else {

                $product_data['status'] = $this->option_repo->OptionOfId(6);

                $product = $this->product_repo->ProductOfId($value['id']);

                $this->product_repo->update($product, $product_data);
            }
        }



        return response()->json('Updated Successfully', 200);
    }

    public function skuGenerate() {

//        $host = 'https://localvault.staging.wpengine.com/wp-content/themes/thelocalvault/tlvotherinfo.php?skey=tlvesbyat&user_id=' . $data['sellerid'];
        $host = env('WP_URL').'/wp-content/themes/thelocalvault/tlvotherinfo.php?skey=tlvesbyat&user_id=' . $data['sellerid'];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $host);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

        curl_setopt($ch, CURLOPT_HEADER, false);

        //temp_stop

        $temp = curl_exec($ch);

        $temp = json_decode($temp, true);



        if ($temp['fname'] != '' && $temp['lname'] != '') {

            $data_product['sku'] = substr($temp['fname'], 0, 3) . substr($temp['lname'], 0, 3);
        } else {

            $data_product['sku'] = substr($temp['user_login'], 0, 3);
        }



        if ($temp['total_pro'] == 0) {

            $data_product['sku'] = $data_product['sku'] . '001';
        } else if ($temp['total_pro'] < 9) {

            $data_product['sku'] = $data_product['sku'] . '00' . $temp['total_pro'];
        } else if ($temp['total_pro'] > 9 && $temp['total_pro'] < 99) {

            $data_product['sku'] = $data_product['sku'] . '0' . $temp['total_pro'];
        }
    }

    public function cleanString($string) {



        return preg_replace('/[^A-Za-z0-9-]/', '', $string); // Removes special chars.
    }

    public function syncCityAndStateOfProducts() {

        ini_set('max_execution_time', 0);





        $products = $this->product_repo->getAllProductsTemp();



//        echo "<pre>";
//        print_r($products);
//        die;
//        $wp_sellers = json_decode(file_get_contents('https://localvault.staging.wpengine.com/wp-content/themes/thelocalvault/seller-city-state.php'));
        $wp_sellers = json_decode(file_get_contents(env('WP_URL').'/wp-content/themes/thelocalvault/seller-city-state.php'));



        foreach ($products as $key => $product) {

            if (isset($product['pick_up_location'])) {

                foreach ($wp_sellers as $key_seller => $seller) {

                    if ($seller->ID == $product['sellerid']['wp_seller_id']) {

                        $update = [];

                        $update['city'] = $seller->city;

                        $update['state'] = $seller->state;

                        $product_obj = $this->product_repo->ProductOfId($product['id']);

                        $this->product_repo->update($product_obj, $update);

                        echo "<pre>";

                        print_r($product['id'] . '<br>');

                        break;
                    }
                }
            }
        }
    }

    public function getProductsWithAssignedAgents(Request $request) {
        $filters = $request->all();
        $response = [];
        $response['draw'] = $filters['draw'];
        $data = $this->product_quotation_repo->getProductsWithAssignedAgents($filters);
        $response['data'] = $data['data'];
        $response['recordsTotal'] = $data['total'];
        $response['recordsFiltered'] = $this->product_quotation_repo->getProductsWithAssignedAgentsTotal($filters);
        return response()->json($response, 200);
    }

//        $products = $this->product_repo->getAllProducts();
//        foreach ($products as $key => $product)
//        {
//            if (isset($product['pick_up_location']['key_text']) && $product['id']>5055)
//            {
//                $details_location = json_decode($product['pick_up_location']['key_text']);
//                if (count($details_location) > 0)
//                {
//                    $update=[];
//                    if (isset($details_location[0]->city))
//                    {
//                        $update['city'] = $details_location[0]->city;
//                    }
//                    if (isset($details_location[0]->state))
//                    {
//                        $update['state'] = $details_location[0]->state;
//                    }
//                    echo "<pre>";
//                    print_r($product['id'].'<br>');
//
//                    $product_obj=$this->product_repo->ProductOfId($product['id']);
//                    $this->product_repo->update($product_obj,$update);
//
//                }
//            }
//
//        }
//        return preg_replace('/[^A-Za-z0-9-]/', '', $string); // Removes special chars.
//    }
}
