<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Entities\Users;
use App\Entities\Role;
use App\Repository\UserRepository as user_repo;
use App\Repository\RoleRepository as role_repo;
use App\Repository\ProductsRepository as product_repo;
use App\Repository\SellRepository as sell_repo;
use Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class SellController extends Controller {

    public function __construct(sell_repo $sell_repo, product_repo $product_repo, user_repo $user_repo, role_repo $role_repo) {
        $this->product_repo = $product_repo;
        $this->user_repo = $user_repo;
        $this->role_repo = $role_repo;
        $this->sell_repo = $sell_repo;
    }

    public function deleteSell(Request $request) {
        $user = $this->sell_repo->SellOfId($request->id);
        $this->user_repo->delete($user);
    }

    public function saveProduct(Request $request) {
        $data = $request->all();

        if ($request->id) {
            unset($data['sell_id']);
            $details = $this->product_repo->ProductOfId($request->id);
            if ($this->product_repo->update($details, $data)) {
                return response()->json('Product Updated Successfully', 200);
            }
        } else {
            $data_sell = array();
            $data_sell['user_id'] = JWTAuth::parseToken()->authenticate();
            $data_sell['name'] = $data['sell_name'];

            $prepared_data_sell = $this->sell_repo->prepareData($data_sell);
            $sell = $this->sell_repo->create($prepared_data_sell);
            if ($sell) {

                $data_product['sell_id'] = $this->sell_repo->SellOfId($sell);

                foreach ($data['products'] as $key => $value) {
                    $data_product['name'] = $value['name'];
                    $data_product['price'] = $value['price'];
                    $data_product['description'] = $value['description'];
                    $prepared_data = $this->product_repo->prepareData($data_product);
                    $this->product_repo->create($prepared_data);
                }

                return response()->json('Product saved Successfully', 200);
            }
        }
    }

    public function getSell(Request $request) {
        return $this->sell_repo->getSellById($request->id);
    }

    public function getAllSells() {
        return $this->sell_repo->getAllSells();
    }

    public function getSells(Request $request) {
        $filter = $request->all();

        $data['draw'] = $filter['draw'];

        $users_data_total = $this->sell_repo->getSells($filter);
        $data['data'] = $users_data_total['data'];

        $data['recordsTotal'] = $users_data_total['total'];
        $data['recordsFiltered'] = $this->sell_repo->getSellsTotal($filter);
        return response()->json($data, 200);
    }
}
