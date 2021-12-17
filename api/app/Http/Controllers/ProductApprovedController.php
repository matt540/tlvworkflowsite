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
use App\Repository\SellRepository as sell_repo;
use App\Repository\SubCategoryRepository as sub_category_repo;
use App\Repository\EmailTemplateRepository as email_template_repo;
use App\Repository\ProductsApprovedRepository as product_approved_repo;
use App\Repository\ImagesRepository as image_repo;
use App\Repository\ScheduleRepository as schedule_repo;
use Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProductApprovedController extends Controller
{

    public function __construct(schedule_repo $schedule_repo, image_repo $image_repo, product_approved_repo $product_approved_repo, email_template_repo $email_template_repo, option_repo $option_repo, sub_category_repo $sub_category_repo, sell_repo $sell_repo, product_repo $product_repo, user_repo $user_repo, role_repo $role_repo)
    {
        $this->product_repo = $product_repo;
        $this->user_repo = $user_repo;
        $this->role_repo = $role_repo;
        $this->sell_repo = $sell_repo;
        $this->option_repo = $option_repo;
        $this->sub_category_repo = $sub_category_repo;
        $this->email_template_repo = $email_template_repo;
        $this->product_approved_repo = $product_approved_repo;
        $this->image_repo = $image_repo;
        $this->schedule_repo = $schedule_repo;
    }

    public function deleteUser(Request $request)
    {
        $user = $this->user_repo->UserOfId($request->id);
        $this->user_repo->delete($user);
    }

    public function deleteProduct(Request $request)
    {
        $schedule = $this->schedule_repo->ScheduleOfProductId($request->id);
        if (!empty($schedule))
        {
            $this->schedule_repo->delete($schedule);
        }

        return $this->product_approved_repo->delete($this->product_approved_repo->ProductApprovedOfId($request->id));
    }

    public function editProduct(Request $request)
    {
        $data = $request->all();
//echo "<pre>";
//print_r($data);
//die;
//        $sell_data = array();
//        $sell_data['name'] = $data['sell_name'];
//        $details = $this->sell_repo->SellOfId($data['sell_id']['id']);
//        if ($this->sell_repo->update($details, $sell_data))
//        {
        foreach ($data['productimages'] as $key_item => $value_item)
        {
            $data_product['product_images'][] = $this->image_repo->ImageOfId($value_item);
        }

        $data_product['name'] = $data['name'];
        $data_product['price'] = $data['price'];
        $data_product['description'] = $data['description'];
        $data_product['quantity'] = $data['quantity'];
        $data_product['sort_description'] = $data['sort_description'];
        $data_product['materials'] = $data['materials'];
        $data_product['diamensions'] = $data['diamensions'];
        $data_product['tlv_suggested_price'] = $data['tlv_suggested_price'];
        $data_product['seller_id'] = $data['seller_id'];
        $data_product['images_from'] = $data['images_from'];
//echo "<pre>";
//print_r($data_product);
//die;
        foreach ($data['cat'] as $x => $y)
        {
            if ($y != '')
            {
                if ($x == 'Condition')
                {
                    $data_product['con'] = $this->sub_category_repo->SubCategoryOfId($y);
                }
                else
                {
                    $data_product[strtolower($x)] = $this->sub_category_repo->SubCategoryOfId($y);
                }
            }
        }

        unset($data['sell_id']);
        $details = $this->product_approved_repo->ProductApprovedOfId($data['id']);
        if ($this->product_approved_repo->update($details, $data_product))
        {
            return response()->json('Product Updated Successfully', 200);
        }
        else
        {
            return response()->json('Oops! Something went wrong', 500);
        }
//        }
//        else
//        {
//            return response()->json('Oops! Something went wrong', 500);
//        }
    }

    public function getProduct(Request $request)
    {
        return $this->product_approved_repo->getProductById($request->id);
    }

    public function getProducts(Request $request)
    {
        $filter = $request->all();

        $data['draw'] = $filter['draw'];

//        if (JWTAuth::parseToken()->authenticate()->getRoles()[0]->getId() == 1)
//        {
        $users_data_total = $this->product_approved_repo->getProducts($filter);
        $data['data'] = $users_data_total['data'];

        $data['recordsTotal'] = $users_data_total['total'];
        $data['recordsFiltered'] = $this->product_approved_repo->getProductsTotal($filter);
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

    public function changeProductStatus(Request $request)
    {
        $data = $request->all();

        $data['status'] = $this->option_repo->OptionOfId($data['product_status_id']);
        $details = $this->product_approved_repo->ProductApprovedOfId($data['product_id']);

        $this->product_approved_repo->update($details, $data);
    }

    public function get_all_product_status(Request $request)
    {
        $all_order_status = $this->option_repo->get_all_of_select_id(4);
        return $all_order_status;
    }

}
