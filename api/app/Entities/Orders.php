<?php


namespace App\Entities;

use Gedmo\Mapping\Annotation as Gedmo;

use Doctrine\ORM\Mapping AS ORM;



/**

 * @ORM\Entity

 * @ORM\Table(name="orders")

 */

class Orders

{

    /**

     * @ORM\Id

     * @ORM\GeneratedValue

     * @ORM\Column(type="integer")

     */

    protected $id;


    /**

     * @ORM\Column(type="integer", nullable=true)

     */

    protected $order_id;

     /**

     * @ORM\Column(type="string", nullable=true)

     */

    protected $parent_id;


    /**

     * @ORM\Column(type="string", nullable=true)

     */

    protected $order_number;

    /**

     * @ORM\Column(type="string", nullable=true)

     */

    protected $order_key;

    /**

     * @ORM\Column(type="string", nullable=true)

     */

    protected $created_via;


    /**

     * @ORM\Column(type="integer", nullable=true)

     */

    protected $product_id;


    /**

     * @ORM\Column(type="string", nullable=true)

     */

    protected $status;



    /**

     * @ORM\Column(type="string", nullable=true)

     */

    protected $currency;

    /**

     * @ORM\Column(type="datetime", nullable=true)

     */

    protected $date_created;

    /**

     * @ORM\Column(type="datetime", nullable=true)

     */

    protected $date_modified;

    /**

     * @ORM\Column(type="string", nullable=true)

    */

    protected $discount_total;

    /**

     * @ORM\Column(type="string", nullable=true)

    */

    protected $discount_tax;

    /**

     * @ORM\Column(type="string", nullable=true)

    */

    protected $shipping_total;

    /**

     * @ORM\Column(type="string", nullable=true)

    */

    protected $shipping_tax;

    /**

     * @ORM\Column(type="string", nullable=true)

    */

    protected $cart_tax;

    /**

     * @ORM\Column(type="string", nullable=true)

    */

    protected $total;

    /**

     * @ORM\Column(type="string", nullable=true)

    */

    protected $total_tax;

    /**

     * @ORM\Column(type="string", nullable=true)

    */

    protected $prices_include_tax;

    /**

     * @ORM\Column(type="string", nullable=true)

    */

    protected $customer_id;

    /**

     * @ORM\Column(type="string", nullable=true)

    */

    protected $customer_note;

    /**

     * @ORM\Column(type="text", nullable=true)

    */

    protected $billing;

    /**

     * @ORM\Column(type="text", nullable=true)

    */

    protected $shipping;

    /**

     * @ORM\Column(type="string", nullable=true)

    */

    protected $payment_method;

    /**

     * @ORM\Column(type="string", nullable=true)

    */

    protected $payment_method_title;

    /**

     * @ORM\Column(type="string", nullable=true)

    */

    protected $transaction_id;

    /**

     * @ORM\Column(type="datetime", nullable=true)

     */

    protected $date_paid;

    /**

     * @ORM\Column(type="datetime", nullable=true)

     */

    protected $date_completed;

    /**

     * @ORM\Column(type="string", nullable=true)

     */

    protected $cart_hash;

    /**

     * @ORM\Column(type="text", nullable=true)

     */

    protected $meta_data;

    /**

     * @ORM\Column(type="text", nullable=true)

     */

    protected $line_items;

    /**

     * @ORM\Column(type="text", nullable=true)

     */

    protected $line_items_product;

    /**

     * @ORM\Column(type="text", nullable=true)

     */

    protected $tax_lines;

    /**

     * @ORM\Column(type="text", nullable=true)

     */

    protected $shipping_lines;

    /**

     * @ORM\Column(type="text", nullable=true)

     */

    protected $fee_lines;

    /**

     * @ORM\Column(type="text", nullable=true)

     */

    protected $coupon_lines;

    /**

     * @ORM\Column(type="text", nullable=true)

    */

    protected $stores;

    /**

     * @ORM\Column(type="text", nullable=true)

    */

    protected $refunds;

    /**

     * @ORM\Column(type="string", nullable=true)

    */

    protected $currency_symbol;

     /**

     * @ORM\Column(type="text", nullable=true)

    */

    protected $order_list;





    /**

     * @var \DateTime $created

     * @Gedmo\Timestampable(on="create")

     * @ORM\Column(type="datetime")

     */

    protected $created_at;



    /**

     * @var \DateTime $updated

     *

     * @Gedmo\Timestampable(on="update")

     * @ORM\Column(type="datetime")

     */

    protected $updated_at;


     /**

     * @ORM\Column(type="string", nullable=true)

    */

    protected $buyer_user_role;
    
     /**

     * @ORM\Column(type="string", nullable=true)

    */

    protected $tlv_make_an_offer;

    /**

     * @ORM\Column(type="string", nullable=true)

    */

    protected $customer_username;




    public function __construct($data)

    {

        $this->order_id = isset($data['order_id']) ? $data['order_id'] : '';
        $this->parent_id = isset($data['parent_id']) ? $data['parent_id'] : '';
        $this->order_number = isset($data['order_number']) ? $data['order_number'] : '';
        $this->order_key = isset($data['order_key']) ? $data['order_key'] : '';
        $this->created_via = isset($data['created_via']) ? $data['created_via'] : '';
        $this->product_id = isset($data['product_id']) ? $data['product_id'] : '';
        $this->status = isset($data['status']) ? $data['status'] : '';
        $this->currency = isset($data['currency']) ? $data['currency'] : '';
        $this->date_created = isset($data['date_created']) ? new \DateTime() : NULL;
        $this->date_modified = isset($data['date_modified']) ? new \DateTime() : NULL;
        $this->discount_total = isset($data['discount_total']) ? $data['discount_total'] : '';
        $this->discount_tax = isset($data['discount_tax']) ? $data['discount_tax'] : '';
        $this->shipping_total = isset($data['shipping_total']) ? $data['shipping_total'] : '';
        $this->shipping_tax = isset($data['shipping_tax']) ? $data['shipping_tax'] : '';
        $this->cart_tax = isset($data['cart_tax']) ? $data['cart_tax'] : '';
        $this->total = isset($data['total']) ? $data['total'] : '';
        $this->total_tax = isset($data['total_tax']) ? $data['total_tax'] : '';
        $this->prices_include_tax = isset($data['prices_include_tax']) ? $data['prices_include_tax'] : '';
        $this->customer_id = isset($data['customer_id']) ? $data['customer_id'] : '';
        $this->customer_note = isset($data['customer_note']) ? $data['customer_note'] : '';
        $this->billing = isset($data['billing']) ? $data['billing'] : '';
        $this->shipping = isset($data['shipping']) ? $data['shipping'] : '';
        $this->payment_method = isset($data['payment_method']) ? $data['payment_method'] : '';
        $this->payment_method_title = isset($data['payment_method_title']) ? $data['payment_method_title'] : '';
        $this->transaction_id = isset($data['transaction_id']) ? $data['transaction_id'] : '';
        $this->date_paid = isset($data['date_paid']) ? new \DateTime() : NULL;
        $this->date_completed = isset($data['date_completed']) ? new \DateTime() : NULL;
        $this->cart_hash = isset($data['cart_hash']) ? $data['cart_hash'] : '';
        $this->meta_data = isset($data['meta_data']) ? $data['meta_data'] : '';
        $this->line_items = isset($data['line_items']) ? $data['line_items'] : '';
        $this->line_items_product = isset($data['line_items_product']) ? $data['line_items_product'] : '';
        $this->tax_lines = isset($data['tax_lines']) ? $data['tax_lines'] : '';
        $this->shipping_lines = isset($data['shipping_lines']) ? $data['shipping_lines'] : '';
        $this->fee_lines = isset($data['fee_lines']) ? $data['fee_lines'] : '';
        $this->coupon_lines = isset($data['coupon_lines']) ? $data['coupon_lines'] : '';
        $this->stores = isset($data['stores']) ? $data['stores'] : '';
        $this->refunds = isset($data['refunds']) ? $data['refunds'] : '';
        $this->currency_symbol = isset($data['currency_symbol']) ? $data['currency_symbol'] : '';
        $this->order_list = isset($data['order_list']) ? $data['order_list'] : '';
        $this->buyer_user_role = isset($data['buyer_user_role']) ? $data['buyer_user_role'] : null;
        $this->tlv_make_an_offer = isset($data['tlv_make_an_offer']) ? $data['tlv_make_an_offer'] : null;
        $this->customer_username = isset($data['customer_username']) ? $data['customer_username'] : null;



    }

    public function getId()
    {

        return $this->id;

    }



    function getOrder_id()
    {

        return $this->order_id;

    }

    function setOrder_id($value)
    {

        $this->order_id = $value;

    }




    function getParent_id()
    {

        return $this->parent_id;

    }

    function setParent_id($value)
    {

        $this->parent_id = $value;

    }

    function getOrder_number()
    {

        return $this->order_number;

    }

    function setOrder_number($value)
    {

        $this->order_number = $value;

    }

    function getOrder_key()
    {

        return $this->order_key;

    }

    function setOrder_key($value)
    {

        $this->order_key = $value;

    }

    function getCreated_via()
    {

        return $this->created_via;

    }

    function setCreated_via($value)
    {

        $this->created_via = $value;

    }

    function getProduct_id()
    {

        return $this->product_id;

    }

    function setProduct_id($value)
    {

        $this->product_id = $value;

    }

    function getStatus()
    {

        return $this->status;

    }

    function setStatus($value)
    {

        $this->status = $value;

    }

    function getCurrency()
    {

        return $this->currency;

    }

    function setCurrency($value)
    {

        $this->currency = $value;

    }

    function getDate_created()
    {

        return $this->date_created;

    }

    function setDate_created($value)
    {

        $this->date_created = $value;

    }

    function getDate_modified()
    {

        return $this->date_modified;

    }

    function setDate_modified($value)
    {

        $this->date_modified = $value;

    }

    function getDiscount_total()
    {

        return $this->discount_total;

    }

    function setDiscount_total($value)
    {

        $this->discount_total = $value;

    }

    function getDiscount_tax()
    {

        return $this->discount_tax;

    }

    function setDiscount_tax($value)
    {

        $this->discount_tax = $value;

    }

    function getShipping_total()
    {

        return $this->shipping_total;

    }

    function setShipping_total($value)
    {

        $this->shipping_total = $value;

    }

    function getShipping_tax()
    {

        return $this->shipping_tax;

    }

    function setShipping_tax($value)
    {

        $this->shipping_tax = $value;

    }

    function getCart_tax()
    {

        return $this->cart_tax;

    }

    function setCart_tax($value)
    {

        $this->cart_tax = $value;

    }

    function getTotal()
    {

        return $this->total;

    }

    function setTotal($value)
    {

        $this->total = $value;

    }

    function getTotal_tax()
    {

        return $this->total_tax;

    }

    function setTotal_tax($value)
    {

        $this->total_tax = $value;

    }

    function getPrices_include_tax()
    {

        return $this->prices_include_tax;

    }

    function setPrices_include_tax($value)
    {

        $this->prices_include_tax = $value;

    }

    function getCustomer_id()
    {

        return $this->customer_id;

    }

    function setCustomer_id($value)
    {

        $this->customer_id = $value;

    }

    function getCustomer_note()
    {

        return $this->customer_note;

    }

    function setCustomer_note($value)
    {

        $this->customer_note = $value;

    }

    function getBilling()
    {

        return $this->billing;

    }

    function setBilling($value)
    {

        $this->billing = $value;

    }

    function getShipping()
    {

        return $this->shipping;

    }

    function setShipping($value)
    {

        $this->shipping = $value;

    }

    function getPayment_method()
    {

        return $this->payment_method;

    }

    function setPayment_method($value)
    {

        $this->payment_method = $value;

    }

    function getPayment_method_title()
    {

        return $this->payment_method_title;

    }

    function setPayment_method_title($value)
    {

        $this->payment_method_title = $value;

    }

    function getTransaction_id()
    {

        return $this->transaction_id;

    }

    function setTransaction_id($value)
    {

        $this->transaction_id = $value;

    }

    function getDate_paid()
    {

        return $this->date_paid;

    }

    function setDate_paid($value)
    {

        $this->date_paid = $value;

    }

    function getDate_completed()
    {

        return $this->date_completed;

    }

    function setDate_completed($value)
    {

        $this->date_completed = $value;

    }

    function getCart_hash()
    {

        return $this->cart_hash;

    }

    function setCart_hash($value)
    {

        $this->cart_hash = $value;

    }

    function getMeta_data()
    {

        return $this->meta_data;

    }

    function setMeta_data($value)
    {

        $this->meta_data = $value;

    }

    function getLine_items()
    {

        return $this->line_items;

    }

    function setLine_items($value)
    {

        $this->line_items = $value;

    }

    function getLine_items_product()
    {

        return $this->line_items_product;

    }

    function setLine_items_product($value)
    {

        $this->line_items_product = $value;

    }

    function getTax_lines()
    {

        return $this->tax_lines;

    }

    function setTax_lines($value)
    {

        $this->tax_lines = $value;

    }

    function getShipping_lines()
    {

        return $this->shipping_lines;

    }

    function setShipping_lines($value)
    {

        $this->shipping_lines = $value;

    }

    function getFee_lines()
    {

        return $this->fee_lines;

    }

    function setFee_lines($value)
    {

        $this->fee_lines = $value;

    }

    function getCoupon_lines()
    {

        return $this->coupon_lines;

    }

    function setCoupon_lines($value)
    {

        $this->coupon_lines = $value;

    }

    function getStores()
    {

        return $this->stores;

    }

    function setStores($value)
    {

        $this->stores = $value;

    }

    function getRefunds()
    {

        return $this->refunds;

    }

    function setRefunds($value)
    {

        $this->refunds = $value;

    }

    function getCurrency_symbol()
    {

        return $this->currency_symbol;

    }

    function setCurrency_symbol($value)
    {

        $this->currency_symbol = $value;

    }

    function getOrder_list()
    {

        return $this->order_list;

    }

    function setOrder_list($value)
    {

        $this->order_list = $value;

    }



    function setCreated_at(\DateTime $created_at)

    {

        $this->created_at = $created_at;

    }

    function getBuyer_user_role()
    {

        return $this->buyer_user_role;

}

    function setBuyer_user_role($value)
    {

        $this->buyer_user_role = $value;

    }
    
    function getTlv_make_an_offer()
    {

        return $this->tlv_make_an_offer;

    }

    function setTlv_make_an_offer($value)
    {

        $this->tlv_make_an_offer = $value;

    }

    function getCustomer_username()
    {

        return $this->customer_username;

    }

    function setCustomer_username($value)
    {

        $this->customer_username = $value;

    }


}

