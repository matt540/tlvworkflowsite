<?php

namespace App\Repository;

use App\Entities\Orders;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class OrdersRepository extends EntityRepository {

    /**

     * @var string

     */
    private $class = 'App\Entities\Orders';

    /**

     * @var EntityManager

     */
    private $em;

    public function __construct(EntityManager $em) {

        $this->em = $em;
    }

    public function prepareData($data)

    {

        return new Orders($data);

    }
    
    public function create(Orders $orders)

    {

        $this->em->persist($orders);

        $this->em->flush();

        return $orders->getId();

    }
    
    
    public function getOrderSelect($orderid) {
        
       
        
        $data = $this->em->getRepository($this->class)->findOneBy([
                    'order_id' => $orderid
        ]);
        
        
        
        return $data;
    }
    
    public function update(Orders $orders, $data)
    {
        
        if (isset($data['product_id'])) {

            $orders->setProduct_id($data['product_id']);
        }
        
        if (isset($data['order_id'])) {

            $orders->setOrder_id($data['order_id']);
        }
        
        if (isset($data['parent_id'])) {

            $orders->setParent_id($data['parent_id']);
        }
        
        if (isset($data['order_number'])) {

            $orders->setOrder_number($data['order_number']);
        }
        
        if (isset($data['order_key'])) {

            $orders->setOrder_key($data['order_key']);
        }
        
        if (isset($data['created_via'])) {

            $orders->setCreated_via($data['created_via']);
        }
        
        if (isset($data['status'])) {

            $orders->setStatus($data['status']);
        }
        
        if (isset($data['currency'])) {

            $orders->setCurrency($data['currency']);
        }
              
        if (isset($data['date_created'])) {

            $orders->setDate_created($data['date_created']);
        }
        
        if (isset($data['date_modified'])) {

            $orders->setDate_modified($data['date_modified']);
        }
        
        if (isset($data['discount_total'])) {

            $orders->setDiscount_total($data['discount_total']);
        }
        
        if (isset($data['discount_tax'])) {

            $orders->setDiscount_tax($data['discount_tax']);
        }
        
        if (isset($data['shipping_total'])) {

            $orders->setShipping_total($data['shipping_total']);
        }
        
        if (isset($data['shipping_tax'])) {

            $orders->setShipping_tax($data['shipping_tax']);
        }
        
        if (isset($data['cart_tax'])) {

            $orders->setCart_tax($data['cart_tax']);
        }
        
        if (isset($data['total'])) {

            $orders->setTotal($data['total']);
        }
        
        if (isset($data['total_tax'])) {

            $orders->setTotal_tax($data['total_tax']);
        }
        
        if (isset($data['prices_include_tax'])) {

            $orders->setPrices_include_tax($data['prices_include_tax']);
        }
        
        if (isset($data['customer_id'])) {

            $orders->setCustomer_id($data['customer_id']);
        }
        
        if (isset($data['customer_note'])) {

            $orders->setCustomer_note($data['customer_note']);
        }
        
        if (isset($data['billing'])) {

            $orders->setBilling($data['billing']);
        }
        
        if (isset($data['shipping'])) {

            $orders->setShipping($data['shipping']);
        }
        
        if (isset($data['payment_method'])) {

            $orders->setPayment_method($data['payment_method']);
        }
        
        if (isset($data['payment_method_title'])) {

            $orders->setPayment_method_title($data['payment_method_title']);
        }
        
        if (isset($data['transaction_id'])) {

            $orders->setTransaction_id($data['transaction_id']);
        }
        
        if (isset($data['date_paid'])) {

            $orders->setDate_paid($data['date_paid']);
        }
        
        if (isset($data['date_completed'])) {

            $orders->setDate_completed($data['date_completed']);
        }
        
        if (isset($data['cart_hash'])) {

            $orders->setCart_hash($data['cart_hash']);
        }
        
        if (isset($data['meta_data'])) {

            $orders->setMeta_data($data['meta_data']);
        }
        
        if (isset($data['line_items'])) {

            $orders->setLine_items($data['line_items']);
        }
        
        if (isset($data['line_items_product'])) {

            $orders->setLine_items_product($data['line_items_product']);
        }
        
        if (isset($data['tax_lines'])) {

            $orders->setTax_lines($data['tax_lines']);
        }
        
        if (isset($data['shipping_lines'])) {

            $orders->setShipping_lines($data['shipping_lines']);
        }
        
        if (isset($data['fee_lines'])) {

            $orders->setFee_lines($data['fee_lines']);
        }
        
        if (isset($data['coupon_lines'])) {

            $orders->setCoupon_lines($data['coupon_lines']);
        }
        
        if (isset($data['stores'])) {

            $orders->setStores($data['stores']);
        }
        
        if (isset($data['refunds'])) {

            $orders->setRefunds($data['refunds']);
        }
        
        if (isset($data['currency_symbol'])) {

            $orders->setCurrency_symbol($data['currency_symbol']);
        }
        
        if (isset($data['order_list'])) {

            $orders->setOrder_list($data['order_list']);
        }
        
        if (isset($data['buyer_user_role'])) {
        
            $orders->setBuyer_user_role($data['buyer_user_role']);
        }
        
        if (isset($data['tlv_make_an_offer'])) {

            $orders->setTlv_make_an_offer($data['tlv_make_an_offer']);
        }
        
        if (isset($data['customer_username'])) {
        
            $orders->setCustomer_username($data['customer_username']);
        }
        
        
        
        $this->em->persist($orders);

        $this->em->flush();

        return $orders;

    }
    
    public function getProductOrderSelect($orderid, $product_id) {
                       
        $data = $this->em->getRepository($this->class)->findOneBy([
                    'order_id' => $orderid, 'product_id' => $product_id
        ]);
        
        return $data;
        
        exit();
    }
        
}

?>