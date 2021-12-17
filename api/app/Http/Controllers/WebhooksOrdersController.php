<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Entities\Users;
use App\Entities\Role;
use App\Repository\OrdersRepository as orders_repo;
use Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;

class WebhooksOrdersController extends Controller {

    public function __construct(orders_repo $orders_repo) {


        $this->orders_repo = $orders_repo;
    }

    public function WebhooksOrders(Request $request) {




        $data = array();


        // $orders = $this->orders_repo->getOrderSelect($request->id);

        if ($request->id != 0 && $request->id != null) {

            Log::info(json_encode($request->all()));

            $data['order_id'] = $request->id;
            $data['parent_id'] = $request->parent_id;
            $data['order_number'] = $request->number;
            $data['order_key'] = $request->order_key;
            $data['created_via'] = $request->created_via;
            $data['status'] = $request->status;
            $data['currency'] = $request->currency;
            $data['date_created'] = new \DateTime(date('Y-m-d H:i:s', strtotime($request->date_created)));
            $data['date_modified'] = new \DateTime(date('Y-m-d H:i:s', strtotime($request->date_modified)));
            $data['discount_total'] = $request->discount_total;
            $data['discount_tax'] = $request->discount_tax;
            $data['shipping_total'] = $request->shipping_total;
            $data['shipping_tax'] = $request->shipping_tax;
            $data['cart_tax'] = $request->cart_tax;
            $data['total'] = $request->total;
            $data['total_tax'] = $request->total_tax;
            $data['prices_include_tax'] = $request->prices_include_tax;
            $data['customer_id'] = $request->customer_id;
            $data['customer_note'] = $request->customer_note;
            $data['billing'] = json_encode($request->billing);
            $data['shipping'] = json_encode($request->shipping);
            $data['payment_method'] = $request->payment_method;
            $data['payment_method_title'] = $request->payment_method_title;
            $data['transaction_id'] = $request->transaction_id;
            $data['date_paid'] = new \DateTime(date('Y-m-d H:i:s', strtotime($request->date_paid)));
            $data['date_completed'] = new \DateTime(date('Y-m-d H:i:s', strtotime($request->date_completed)));
            $data['cart_hash'] = $request->cart_hash;
            $data['meta_data'] = json_encode($request->meta_data);
            $data['line_items'] = json_encode($request->line_items);
            $data['tax_lines'] = json_encode($request->tax_lines);
            $data['shipping_lines'] = json_encode($request->shipping_lines);
            $data['fee_lines'] = json_encode($request->fee_lines);
            $data['coupon_lines'] = json_encode($request->coupon_lines);
            $data['stores'] = json_encode($request->stores);
            $data['refunds'] = json_encode($request->refunds);
            $data['currency_symbol'] = $request->currency_symbol;
            $data['order_list'] = json_encode($request->order['tlv_custom_meta']);
            $data['buyer_user_role'] = $request->buyer_user_role;
            $data['tlv_make_an_offer'] = $request->tlv_make_an_offer;
            $data['customer_username'] = $request->customer_username;

            if ($request->tlv_child_count > 0) {
                $orders = $this->orders_repo->getOrderSelect($request->id);
                $data['product_id'] = 0;

                $data['line_items_product'] = json_encode($request->line_items);

                if ($orders != '') {

                    $orders_product = $this->orders_repo->getProductOrderSelect($request->id, $request->line_items[0]['product_id']);

                    $this->orders_repo->update($orders_product, $data);
                } else {

                    $prepared_data = $this->orders_repo->prepareData($data);
                    $this->orders_repo->create($prepared_data);
                }
            } else {
   
                foreach ($request->line_items as $key => $line_items) {                    
                    
                        $orders_product = $this->orders_repo->getProductOrderSelect($request->id, $line_items['product_id']);
                        $data['product_id'] = $line_items['product_id'];
                        $data['line_items_product'] = json_encode($line_items);
                        if ($orders_product != '') {
                            $this->orders_repo->update($orders_product, $data);
                        } else {
                            $prepared_data = $this->orders_repo->prepareData($data);

                            $this->orders_repo->create($prepared_data);
                        }
                   
                }
            }
        }
    }

}
