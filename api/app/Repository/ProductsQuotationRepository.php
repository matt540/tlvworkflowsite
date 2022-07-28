<?php

namespace App\Repository;

use App\Entities\Products_quotation;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProductsQuotationRepository extends EntityRepository
{

    /**
     * @var string
     */
    private $class = 'App\Entities\Products_quotation';

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {

        $this->em = $em;
    }

    public function create(Products_quotation $option)
    {

        $this->em->persist($option);

        $this->em->flush();

        return $option;
    }

    public function deleteAllProductQuotesOfSellerId($seller_id)
    {

        $query = $this->em->createQueryBuilder();

        $query->delete('App\Entities\Products_quotation', 'pq')
            ->leftJoin('pq.product_id', 'p')
            ->where('p.sellerid = :seller_id')
            ->setParameter('seller_id', $seller_id);
    }

    public function update(Products_quotation $product_quotation, $data)
    {


        if (isset($data['curator_name'])) {

            $product_quotation->setCuratorName($data['curator_name']);
        }

        if (isset($data['curator_commission'])) {

            $product_quotation->setCuratorCommission($data['curator_commission']);
        }


        if (isset($data['copywriter_id'])) {

            $product_quotation->setCopywriterId($data['copywriter_id']);
        }

        if (isset($data['in_queue'])) {

            $product_quotation->setInQueue($data['in_queue']);
        }

        if (isset($data['delivery_option'])) {

            $product_quotation->setDeliveryOption($data['delivery_option']);
        }

        if (isset($data['delivery_description'])) {

            $product_quotation->setDeliveryDescription($data['delivery_description']);
        }

        if (isset($data['condition_note'])) {

            $product_quotation->setConditionNote($data['condition_note']);
        }

        if (isset($data['commission'])) {

            $product_quotation->setCommission($data['commission']);
        }

        if (isset($data['dimension_description'])) {

            $product_quotation->setDimensionDescription($data['dimension_description']);
        }

        if (isset($data['is_for_production_create_date'])) {

            $product_quotation->setForProductionCreatedAt(new \DateTime());
        }

        if (isset($data['for_pricing_created_at'])) {

            $product_quotation->setFor_pricing_created_at(new \DateTime());
        }

        if (isset($data['for_proposal_for_production_created_at'])) {

            $product_quotation->setFor_proposal_for_production_created_at(new \DateTime());
        }

        if (isset($data['for_awaiting_contract_created_at'])) {

            $product_quotation->setFor_awaiting_contract_created_at(new \DateTime());
        }

        if (isset($data['is_copyright_create_date'])) {

            $product_quotation->setCopyrightCreatedAt(new \DateTime());
        }

        if (isset($data['is_approved_create_date'])) {

            $product_quotation->setApprovedCreatedAt(new \DateTime());
        }


        if (isset($data['is_product_for_production'])) {

            $product_quotation->setIsProductForProduction($data['is_product_for_production']);
        }


        if (isset($data['is_storage_proposal'])) {

            $product_quotation->setIs_storage_proposal($data['is_storage_proposal']);
        }

        if (isset($data['is_product_for_pricing'])) {

            $product_quotation->setIs_product_for_pricing($data['is_product_for_pricing']);
        }
        if (isset($data['is_proposal_for_production'])) {

            $product_quotation->setIs_proposal_for_production($data['is_proposal_for_production']);
        }
        if (isset($data['is_awaiting_contract'])) {

            $product_quotation->setIs_awaiting_contract($data['is_awaiting_contract']);
        }


        if (isset($data['is_copyright'])) {

            $product_quotation->setIsCopyright($data['is_copyright']);
        }

        if (isset($data['is_archived'])) {

            $product_quotation->setIs_archived($data['is_archived']);
        }

        if (isset($data['product_id'])) {

            $product_quotation->setProductId($data['product_id']);
        }

        if (isset($data['note'])) {

            $product_quotation->setNote($data['note']);
        }

        if (isset($data['price'])) {

            $product_quotation->setPrice($data['price']);
        }

        if (isset($data['tlv_price'])) {

            $product_quotation->setTLVPrice($data['tlv_price']);
        }

        if (isset($data['wp_tlv_price'])) {

            $product_quotation->setWp_tlv_price($data['wp_tlv_price']);
        }

        if (isset($data['is_updated_details'])) {

            $product_quotation->setIsUpdatedDetails($data['is_updated_details']);
        }

        if (isset($data['is_scheduled'])) {

            $product_quotation->setIsScheduled($data['is_scheduled']);
        }

        if (isset($data['is_send_mail'])) {

            $product_quotation->setIsSendMail($data['is_send_mail']);
        }

        if (isset($data['quantity'])) {

            $product_quotation->setQuantity($data['quantity']);
        }


        if (isset($data['tlv_suggested_price'])) {

            $product_quotation->setTlvSuggestedPrice($data['tlv_suggested_price']);
        }

        if (isset($data['tlv_suggested_price_min'])) {

            $product_quotation->setTlv_suggested_price_min($data['tlv_suggested_price_min']);
        }

        if (isset($data['tlv_suggested_price_max'])) {

            $product_quotation->setTlv_suggested_price_max($data['tlv_suggested_price_max']);
        }

        if (isset($data['wp_sale_price'])) {

            $product_quotation->setWp_sale_price($data['wp_sale_price']);
        }

        if (isset($data['wp_flat_rate'])) {

            $product_quotation->setWp_flat_rate($data['wp_flat_rate']);
        }

        if (isset($data['wp_published_date'])) {

            $product_quotation->setWp_published_date($data['wp_published_date']);
        }


//        if (isset($data['materials']))
//        {
//            $product_quotation->setMaterials($data['materials']);
//        }


        if (isset($data['dimensions'])) {

            $product_quotation->setDimensions($data['dimensions']);
        }

        if (isset($data['images_from'])) {

            $product_quotation->setImagesFrom($data['images_from']);
        }

        if (isset($data['status_quot'])) {

            $product_quotation->setStatusQuot($data['status_quot']);
        }

        if (isset($data['sort_description'])) {

            $product_quotation->setSortDescription($data['sort_description']);
        }


        if (isset($data['menu_order'])) {

            $product_quotation->setMenu_order($data['menu_order']);
        }

        if (isset($data['stock_status'])) {

            $product_quotation->setStock_status($data['stock_status']);
        }

        if (isset($data['manage_stock'])) {

            $product_quotation->setManage_stock($data['manage_stock']);
        }

        if (isset($data['weight'])) {

            $product_quotation->setWeight($data['weight']);
        }

        if (isset($data['height'])) {

            $product_quotation->setHeight($data['height']);
        }

        if (isset($data['length'])) {

            $product_quotation->setLength($data['length']);
        }

        if (isset($data['width'])) {

            $product_quotation->setWidth($data['width']);
        }

        if (isset($data['depth'])) {

            $product_quotation->setDepth($data['depth']);
        }

        if (isset($data['seat_height'])) {

            $product_quotation->setSeat_height($data['seat_height']);
        }

        if (isset($data['arm_height'])) {

            $product_quotation->setArm_height($data['arm_height']);
        }

        if (isset($data['inside_seat_depth'])) {

            $product_quotation->setInside_seat_depth($data['inside_seat_depth']);
        }

        if (isset($data['units'])) {

            $product_quotation->setUnits($data['units']);
        }

        if (isset($data['tax_status'])) {

            $product_quotation->setTax_status($data['tax_status']);
        }

        if (isset($data['tax_class'])) {

            $product_quotation->setTax_class($data['tax_class']);
        }

        if (isset($data['shipping_class'])) {

            $product_quotation->setShipping_class($data['shipping_class']);
        }

        if (isset($data['wp_product_id'])) {

            $product_quotation->setWp_product_id($data['wp_product_id']);
        }

        if (isset($data['assign_agent_id']) && !is_array($data['assign_agent_id'])) {

            $product_quotation->setAssign_agent_id($data['assign_agent_id']);
        }
        if (isset($data['storage_pricing'])) {

            $product_quotation->setStorage_pricing($data['storage_pricing']);
        }
        if (isset($data['stripe_subscriptions_id'])) {

            $product_quotation->setStripe_subscriptions_id($data['stripe_subscriptions_id']);
        }

        if (isset($data['stripe_plan_id'])) {

            $product_quotation->setStripe_plan_id($data['stripe_plan_id']);
        }


        if (isset($data['reject_to_auction'])) {

            $product_quotation->setReject_to_auction($data['reject_to_auction']);
        }

        if (isset($data['wp_manage_stock'])) {

            $product_quotation->setWp_manage_stock($data['wp_manage_stock']);
        }

        if (isset($data['wp_stock_quantity'])) {

            $product_quotation->setWp_stock_quantity($data['wp_stock_quantity']);
        }

        if (isset($data['wp_stock_status'])) {

            $product_quotation->setWp_stock_status($data['wp_stock_status']);
        }

        if (isset($data['wp_product_expire_date'])) {

            $product_quotation->setWp_product_expire_date($data['wp_product_expire_date']);
        }

        if (isset($data['seller_to_drop_off'])) {

            $product_quotation->setSeller_to_drop_off($data['seller_to_drop_off']);
        }

        if (isset($data['shipping_calculator'])) {

            $product_quotation->setShipping_calculator($data['shipping_calculator']);
        }


        $this->em->persist($product_quotation);

        $this->em->flush();

        return 1;
    }

    public function ProductQuotationOfId($id)
    {

        return $this->em->getRepository($this->class)->findOneBy([
            'id' => $id
        ]);
    }

    public function ProductQuotationOfProductId($product_id)
    {

        return $this->em->getRepository($this->class)->findOneBy([
            'product_id' => $product_id
        ]);
    }

    public function ProductQuotationOfWpProductId($wp_product_id)
    {


        return $this->em->getRepository($this->class)->findOneBy([
            'wp_product_id' => $wp_product_id
        ]);
    }

    public function getAllSyncProductsOfSellerHome()
    {

        $query = $this->em->createQueryBuilder()
            ->select('p.id as product_quote_id,pick_up.id as pick_up_location_id,p.wp_product_id as wp_product_id')
            ->from('App\Entities\Products_quotation', 'p')
            ->leftJoin('p.product_id', 'u')
            ->leftJoin('u.status', 's')
            ->leftJoin('u.pick_up_location', 'pick_up')
            ->where('pick_up.id = :pick_up_location')
            ->andWhere('p.wp_product_id != :wp_id')
            ->setParameter('pick_up_location', 22)
            ->setParameter('wp_id', '')
            ->getQuery();

        $data = $query->getResult(Query::HYDRATE_ARRAY);

        return $data;
    }

    public function getAllProductQuotationsOfSeller($seller_id)
    {

        $query = $this->em->createQueryBuilder()
            ->select('p.id as product_quot_id')
            ->from('App\Entities\Products_quotation', 'p')
            ->leftJoin('p.product_id', 'u')
            ->leftJoin('u.status', 's')
            ->where('u.sellerid = :id')
            ->andWhere('s.value_text = :status')
            ->setParameter('id', $seller_id)
            ->setParameter('status', 'Approved')
            ->getQuery();

        $data = $query->getResult(Query::HYDRATE_ARRAY);

        return $data;
    }

    public function getAllQuotationsOfProductId($id)
    {

        $query = $this->em->createQueryBuilder()
            ->select('p.id')
            ->from('App\Entities\Products_quotation', 'p')
            ->leftJoin('p.product_id', 'u')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        $data = $query->getResult(Query::HYDRATE_ARRAY);

        return $data;
    }

    public function getProductQuotationById($id)
    {

        $query = $this->em->createQueryBuilder()
            ->select('p,agent,u,pi,room,look,color,brand,category,con,collection,product_category,product_con,age,s,location,pm,pms')
            ->from('App\Entities\Products_quotation', 'p')
            ->leftJoin('p.product_id', 'u')
            ->leftJoin('p.assign_agent_id', 'agent')
            ->leftJoin('u.product_room', 'color')
            ->leftJoin('u.product_category', 'product_category')
            ->leftJoin('u.product_con', 'product_con')
            ->leftJoin('u.pick_up_location', 'location')
            ->leftJoin('u.product_color', 'room')
            ->leftJoin('u.product_collection', 'collection')
            ->leftJoin('u.sellerid', 's')
            ->leftJoin('u.product_pending_images', 'pi')

//                ->leftJoin('u.look', 'look')
            ->leftJoin('u.product_look', 'look')

//                ->leftJoin('u.color', 'color')
            ->leftJoin('u.brand', 'brand')
            ->leftJoin('u.category', 'category')
            ->leftJoin('u.con', 'con')

//                ->leftJoin('u.collection', 'collection')
            ->leftJoin('u.age', 'age')
            ->leftJoin('u.product_material', 'pm')
            ->leftJoin('u.product_materials', 'pms')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->orderBy('pi.priority,pi.id', 'asc')

//                ->orderBy('pi.id', 'asc')
            ->getQuery();

        $data = $query->getResult(Query::HYDRATE_ARRAY);


        return $data[0];
    }

    public function getProductQuotationQueueByOptionId($id, $seller_id)
    {

        $query = $this->em->createQueryBuilder()
            ->select('p.id')
            ->from('App\Entities\Products_quotation', 'p')
            ->leftJoin('p.product_id', 'u')
            ->leftJoin('p.status_quot', 'pqs')

//                ->leftJoin('u.product_room', 'color')
//                ->leftJoin('u.product_category', 'product_category')
//                ->leftJoin('u.product_con', 'product_con')
//                ->leftJoin('u.pick_up_location', 'location')
//                ->leftJoin('u.product_color', 'room')
            ->leftJoin('u.sellerid', 's')

//                ->leftJoin('u.product_pending_images', 'pi')
//                ->leftJoin('u.look', 'look')
////                ->leftJoin('u.color', 'color')
//                ->leftJoin('u.brand', 'brand')
//                ->leftJoin('u.category', 'category')
//                ->leftJoin('u.con', 'con')
//                ->leftJoin('u.collection', 'collection')
//                ->leftJoin('u.age', 'age')
            ->setMaxResults(1)
            ->where('pqs.id = :id')
            ->andWhere('p.in_queue = :in_queue')
            ->andWhere('s.id = :seller_id')
            ->setParameter('id', $id)
            ->setParameter('seller_id', $seller_id)
            ->setParameter('in_queue', 0)
            ->groupBy("p.id")
            ->getQuery();

        $temp_Data = $query->getResult(Query::HYDRATE_ARRAY);

        $ids = [];

        foreach ($temp_Data as $key => $value) {

            $ids[] = $value['id'];
        }


        $query = $this->em->createQueryBuilder()
            ->select('p,u,pi,room,look,color,brand,category,con,collection,product_category,product_con,age,s,location,product_material,product_materials')
            ->from('App\Entities\Products_quotation', 'p')
            ->leftJoin('p.product_id', 'u')
            ->leftJoin('p.status_quot', 'pqs')
            ->leftJoin('u.product_room', 'color')
            ->leftJoin('u.product_category', 'product_category')
            ->leftJoin('u.product_con', 'product_con')
            ->leftJoin('u.pick_up_location', 'location')
            ->leftJoin('u.product_color', 'room')
            ->leftJoin('u.product_collection', 'collection')
            ->leftJoin('u.sellerid', 's')
            ->leftJoin('u.product_pending_images', 'pi')

//                ->leftJoin('u.look', 'look')
            ->leftJoin('u.product_look', 'look')

//                ->leftJoin('u.color', 'color')
            ->leftJoin('u.brand', 'brand')
            ->leftJoin('u.category', 'category')
            ->leftJoin('u.con', 'con')
            ->leftJoin('u.product_material', 'product_material')
            ->leftJoin('u.product_materials', 'product_materials')
//                ->leftJoin('u.collection', 'collection')
            ->leftJoin('u.age', 'age')
            ->where('pqs.id = :id')
            ->andWhere('p.in_queue = :in_queue')
            ->andWhere('p.id in (:ids)')
            ->setParameter('id', $id)
            ->setParameter('ids', $ids)
            ->setParameter('in_queue', 0)
            ->orderBy('pi.priority,pi.id', 'asc')

//                ->orderBy('pi.priority', 'asc')
            ->getQuery();

        $data = $query->getResult(Query::HYDRATE_ARRAY);


        return $data;
    }

    public function getAllPendingProductQuotation()
    {

        $query = $this->em->createQueryBuilder()
            ->select('p,u,s,v')
            ->from('App\Entities\Products_quotation', 'p')
            ->leftJoin('p.status_quot', 'v')
            ->leftJoin('p.product_id', 'u')
            ->leftJoin('u.sellerid', 's')
            ->where('v.id = :id')
            ->setParameter('id', 17)
            ->getQuery();

        $data = $query->getResult(Query::HYDRATE_ARRAY);


        return $data;
    }

//    public function getProductById($id)
//    {
//        $query = $this->em->createQueryBuilder()
//                ->select('u,pi,sell,room,look,color,brand,category,con,collection')
//                ->from('App\Entities\Products', 'u')
//                ->leftJoin('u.sell_id', 'sell')
//                ->leftJoin('u.room', 'room')
//                ->leftJoin('u.product_pending_images', 'pi')
//                ->leftJoin('u.look', 'look')
//                ->leftJoin('u.color', 'color')
//                ->leftJoin('u.brand', 'brand')
//                ->leftJoin('u.category', 'category')
//                ->leftJoin('u.con', 'con')
//                ->leftJoin('u.collection', 'collection')
//                ->where('u.id = :id')
//                ->setParameter('id', $id)
//                ->getQuery();
//        $data = $query->getResult(Query::HYDRATE_ARRAY);
//
//        return $data[0];
//    }


    public function delete(Products_quotation $option)
    {

        $this->em->remove($option);

        $this->em->flush();
    }

    /**
     * create Theory
     * @return Theory
     */
    public function prepareData($data)
    {

        return new Products_quotation($data);
    }

    public function getAllApprovalProducts()
    {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('p.product_id', 'pp')
            ->leftjoin('pp.sellerid', 'seller')
            ->where('p.is_product_for_pricing = 1')
            ->andWhere('p.is_awaiting_contract = 1')
            ->andWhere('p.is_proposal_for_production = 1')
            ->andWhere('status.id = 17')
            ->andWhere('p.is_archived = 0');

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getAllProductsInProductionBySellerId($seller_id)
    {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('p.product_id', 'pp')
            ->leftjoin('pp.sellerid', 'seller')
            ->where(
                $query->expr()->andX(
                    $query->expr()->eq('p.is_product_for_pricing', '1')
                    , $query->expr()->eq('p.is_awaiting_contract', '1')
                    , $query->expr()->eq('p.is_proposal_for_production', '1')
                    , $query->expr()->eq('p.is_archived', '0')
                    , $query->expr()->orX(
                    $query->expr()->eq('status.id', '17')
                    , $query->expr()->eq('status.id', '83')
                )
                )
            )
            ->andWhere('seller.id = ' . $seller_id);

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getProposalInProposalProduction()
    {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->innerjoin('u.sellerid', 'seller')

//                ->leftjoin('p.status_quot', 'status')
//                ->where('p.is_send_mail = 1')
            ->where($query->expr()->andX(
                $query->expr()->eq('p.is_archived', '0')
                , $query->expr()->eq('p.is_proposal_for_production', '0')
                , $query->expr()->eq('p.is_product_for_pricing', '0')
                , $query->expr()->eq('p.is_awaiting_contract', '1')
            )
            );


//                ->andWhere('p.is_product_for_production = 0')
//                ->andWhere('p.is_copyright = 0')
//                ->andWhere('p.is_archived = 0');
//                ->andWhere('status.id = 17');

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getProductsInProduction()
    {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->innerjoin('u.sellerid', 'seller')

//                ->leftjoin('p.status_quot', 'status')
            ->where('p.is_send_mail = 1')
            ->andWhere('p.is_product_for_production = 0')
            ->andWhere('p.is_copyright = 0')
            ->andWhere('p.is_archived = 0');

//                ->andWhere('status.id = 17');

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getProductsInCopyright()
    {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->innerjoin('u.sellerid', 'seller')
            ->leftjoin('p.status_quot', 'status')
            ->where('p.is_send_mail = 1')
            ->andWhere('p.is_product_for_production = 1')
            ->andWhere('p.is_archived = 0')
            ->andWhere('p.is_copyright = 0');

//                ->andWhere('status.id = 17');

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getAllPendingProductForProductionCountOfSellerId($seller_id)
    {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'pp')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('pp.sellerid', 'seller')
            ->where('p.is_send_mail = 1')
            ->andWhere('p.is_copyright = 0')
            ->andWhere('p.is_product_for_production = 0')
            ->andWhere('p.is_archived = 0')
            ->andWhere('seller.id = ' . $seller_id);

//                ->andWhere('status.id = 17');

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getAllPendingProductForProductionCountOfSellerIdMobileApi($seller_id, $filter)
    {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'pp')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('pp.sellerid', 'seller')
            ->leftjoin('seller.assign_agent_id', 'agent')
            ->where(
                $query->expr()->andX(
                    $query->expr()->neq('p.is_send_mail', '2')
                    , $query->expr()->eq('p.is_archived', ':is_archived')
                    , $query->expr()->eq('p.is_proposal_for_production', ':is_proposal_for_production')
                    , $query->expr()->eq('p.is_awaiting_contract', ':is_awaiting_contract')
                )
            )
            ->setParameter('is_archived', 0)
            ->setParameter('is_proposal_for_production', 0)
            ->setParameter('is_awaiting_contract', 1)
            ->andWhere('seller.id = ' . $seller_id);

        if (isset($filter['role_id']) && $filter['role_id'] == 3) {
            $query->andWhere('seller.assign_agent_id = :agent_user_id');
//            $query->andWhere('p.assign_agent_id = :agent_user_id');
            $query->setParameter('agent_user_id', $filter['user_id']);
        }

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getAllPendingCopyrightsCountOfSellerId($seller_id)
    {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'pp')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('pp.sellerid', 'seller')
            ->where('p.is_send_mail = 1')
            ->andWhere('p.is_copyright = 0')
            ->andWhere('p.is_product_for_production = 1')
            ->andWhere('seller.id = ' . $seller_id);


        $this->copyWriterFilter($query);


//                ->andWhere('status.id = 17');

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getProductForProductions($filter)
    {

        $archived = 0;


        if ($filter['order'][0]['column'] == 1) {

            $orderbyclm = 'u.sku';
        }

        if ($filter['order'][0]['column'] == 2) {

            $orderbyclm = 'u.name';
        }

//        if ($filter['order'][0]['column'] == 3)
//        {
//            $orderbyclm = 'p.price';
//        }


        if ($filter['order'][0]['column'] == 4) {

            $orderbyclm = 'p.for_production_created_at';
        }

        if ($filter['order'][0]['column'] == 5) {

            $orderbyclm = 'p.for_production_created_at';
        }

        if ($filter['order'][0]['column'] == 6) {

            $orderbyclm = 'status.id';
        }

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('p.product_id', 'u')
            ->where('p.is_send_mail=1')

//                ->andWhere('p.is_product_for_production=0')
            ->andWhere('p.is_archived =' . $archived)
            ->andWhere('u.sellerid=' . $filter['id']);


        if (isset($filter['pass_from']) && $filter['pass_from'] == 'report') {

            //2 for reject

            $status = [0, 2];
        } else {

            $status = [0];
        }

        $query->andWhere(
            $query->expr()->in('p.is_product_for_production', ':status')
        )
            ->setParameter('status', $status);


        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                $query->expr()->between('p.for_production_created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }

        $total = $query->getQuery()->getSingleScalarResult();


        $query = $this->em->createQueryBuilder();

        $query->select(array('u.name', 'u.sku', 'p.price', ' GROUP_CONCAT(images.name) image', 'p.for_production_created_at', 'p.copyright_created_at', 'p.approved_created_at', 'p.is_product_for_production', 'p.is_copyright', 'p.tlv_suggested_price_max', 'p.tlv_suggested_price_min', 'p.id', 'p.is_send_mail', 'p.images_from', 'status.id as status_id', 'status.value_text as status_value'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('u.product_pending_images', 'images')
            ->setMaxResults($filter['length'])
            ->setFirstResult($filter['start'])
            ->where('p.is_archived =' . $archived)
            ->andWhere(
                $query->expr()->orX(
                    $query->expr()->like('u.name', ':filter')
                    , $query->expr()->like('u.sku', ':filter')

//                                , $query->expr()->like('p.price', ':filter')
                    , $query->expr()->like("concat(p.tlv_suggested_price_max,'/',p.tlv_suggested_price_min)", ':filter')
                    , $query->expr()->like('status.value_text', ':filter')
                ), $query->expr()->andX(
                $query->expr()->eq('p.is_send_mail', '1')

//                                , $query->expr()->eq('p.is_product_for_production', '0')
//                                , $query->expr()->eq('p.is_product_for_production', '0')
//                                , $query->expr()->eq('p.is_copyright', '0')
                , $query->expr()->eq('u.sellerid', $filter['id'])

//                                , $query->expr()->orX(
//                                        $query->expr()->eq('status.id', '17')
//                                        , $query->expr()->eq('status.id', '18'))
            )
            )
            ->setParameter('filter', '%' . $filter['search']['value'] . '%')
            ->orderBy($orderbyclm, $filter['order'][0]['dir'])
            ->groupBy('p.id');


        if (isset($filter['pass_from']) && $filter['pass_from'] == 'report') {

            //2 for reject

            $status = [0, 2];
        } else {

            $status = [0];
        }

        $query->andWhere(
            $query->expr()->in('p.is_product_for_production', ':status')
        )
            ->setParameter('status', $status);


        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                $query->expr()->between('p.for_production_created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }


        $qb = $query->getQuery();


        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getProductForProductionsTotal($filter)
    {

        $archived = 0;

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->where('p.is_archived =' . $archived)
            ->andWhere(
                $query->expr()->orX(
                    $query->expr()->like('u.name', ':filter')
                    , $query->expr()->like('u.sku', ':filter')

//                                , $query->expr()->like('u.price', ':filter')
                    , $query->expr()->like("concat(p.tlv_suggested_price_max,'/',p.tlv_suggested_price_min)", ':filter')
                    , $query->expr()->like('status.value_text', ':filter')
                ), $query->expr()->andX(
                $query->expr()->eq('p.is_send_mail', '1')

//                                , $query->expr()->eq('p.is_product_for_production', '0')
//                                , $query->expr()->eq('p.is_product_for_production', '0')
//                                , $query->expr()->eq('p.is_copyright', '0')
                , $query->expr()->eq('u.sellerid', $filter['id'])
            ))
            ->setParameter('filter', '%' . $filter['search']['value'] . '%');


        if (isset($filter['pass_from']) && $filter['pass_from'] == 'report') {

            //2 for reject

            $status = [0, 2];
        } else {

            $status = [0];
        }

        $query->andWhere(
            $query->expr()->in('p.is_product_for_production', ':status')
        )
            ->setParameter('status', $status);


        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                $query->expr()->between('p.for_production_created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getCopyrights($filter)
    {

        $archived = 0;


        if ($filter['order'][0]['column'] == 1) {

            $orderbyclm = 'u.sku';
        }

        if ($filter['order'][0]['column'] == 2) {

            $orderbyclm = 'u.name';
        }

//        if ($filter['order'][0]['column'] == 3)
//        {
//            $orderbyclm = 'p.price';
//        }


        if ($filter['order'][0]['column'] == 4) {

            $orderbyclm = 'p.copyright_created_at';
        }

//        if ($filter['order'][0]['column'] == 3)
//        {
//            $orderbyclm = 'p.copyright_created_at';
//        }

        if ($filter['order'][0]['column'] == 5) {

            $orderbyclm = 'status.id';
        }

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('p.product_id', 'u')
            ->where('p.is_send_mail=1')
            ->andWhere('p.is_product_for_production=1')
            ->andWhere('u.sellerid=' . $filter['id']);

//                ->andWhere('p.is_copyright=0');


        if (isset($filter['pass_from']) && $filter['pass_from'] == 'report') {

            //2 for reject

            $status = [0, 2];
        } else {

            $status = [0];
        }

        $query->andWhere(
            $query->expr()->in('p.is_copyright', ':status')
        )
            ->setParameter('status', $status);


        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                $query->expr()->between('p.copyright_created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }


        $this->copyWriterFilter($query);


        $total = $query->getQuery()->getSingleScalarResult();


        $query = $this->em->createQueryBuilder();

        $query->select(array('u.name', 'u.sku', 'p.created_at as quot_created_at', ' GROUP_CONCAT(images.name) image', 'p.for_production_created_at', 'p.copyright_created_at', 'p.approved_created_at', 'p.price', 'p.is_copyright', 'p.tlv_suggested_price_max', 'p.tlv_suggested_price_min', 'p.id', 'p.is_send_mail', 'p.images_from', 'status.id as status_id', 'status.value_text as status_value'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('u.product_pending_images', 'images')
            ->setMaxResults($filter['length'])
            ->setFirstResult($filter['start'])
            ->where('p.is_archived =' . $archived)
            ->andWhere(
                $query->expr()->orX(
                    $query->expr()->like('u.name', ':filter')
                    , $query->expr()->like('u.sku', ':filter')

//                                , $query->expr()->like('p.price', ':filter')
                    , $query->expr()->like("concat(p.tlv_suggested_price_max,'/',p.tlv_suggested_price_min)", ':filter')
                    , $query->expr()->like('status.value_text', ':filter')
                ), $query->expr()->andX(
                $query->expr()->eq('p.is_send_mail', '1')
                , $query->expr()->eq('p.is_product_for_production', '1')
                , $query->expr()->eq('u.sellerid', $filter['id'])

//                                , $query->expr()->eq('p.is_copyright', 0)
//                                , $query->expr()->orX(
//                                        $query->expr()->eq('status.id', '17')
//                                        , $query->expr()->eq('status.id', '18'))
            )
            )
            ->setParameter('filter', '%' . $filter['search']['value'] . '%')
            ->orderBy($orderbyclm, $filter['order'][0]['dir'])
            ->groupBy('p.id');


        if (isset($filter['pass_from']) && $filter['pass_from'] == 'report') {

            //2 for reject

            $status = [0, 2];
        } else {

            $status = [0];
        }

        $query->andWhere(
            $query->expr()->in('p.is_copyright', ':status')
        )
            ->setParameter('status', $status);


        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                $query->expr()->between('p.copyright_created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }

        $this->copyWriterFilter($query);


        $qb = $query->getQuery();


        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getCopyrightsTotal($filter)
    {

        $archived = 0;

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->where('p.is_archived =' . $archived)
            ->andWhere(
                $query->expr()->orX(
                    $query->expr()->like('u.name', ':filter')
                    , $query->expr()->like('u.sku', ':filter')

//                                , $query->expr()->like('u.price', ':filter')
                    , $query->expr()->like("concat(p.tlv_suggested_price_max,'/',p.tlv_suggested_price_min)", ':filter')
                    , $query->expr()->like('status.value_text', ':filter')
                ), $query->expr()->andX(
                $query->expr()->eq('p.is_send_mail', '1')
                , $query->expr()->eq('p.is_product_for_production', '1')
                , $query->expr()->eq('u.sellerid', $filter['id'])

//                                , $query->expr()->eq('p.is_copyright', 0)
            ))
            ->setParameter('filter', '%' . $filter['search']['value'] . '%');


        if (isset($filter['pass_from']) && $filter['pass_from'] == 'report') {

            //2 for reject

            $status = [0, 2];
        } else {

            $status = [0];
        }

        $query->andWhere(
            $query->expr()->in('p.is_copyright', ':status')
        )
            ->setParameter('status', $status);


        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                $query->expr()->between('p.copyright_created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }


        $this->copyWriterFilter($query);


        return $query->getQuery()->getSingleScalarResult();
    }

    public function getAllApprovedProductsQuotationWithStatusNull()
    {


        $query = $this->em->createQueryBuilder();

        $query->select(array('u.name', 'p.is_product_for_production', 'p.is_copyright', 'u.sku', 'p.price', ' GROUP_CONCAT(images.name) image', ' GROUP_CONCAT(images.priority) priority', 'p.updated_at', 'p.created_at as quote_created_at', 'p.for_production_created_at', 'p.copyright_created_at', 'p.approved_created_at', 'p.tlv_suggested_price_max', 'p.tlv_suggested_price_min', 'p.id', 'p.is_send_mail', 'p.images_from', 'status.id as status_id', 'status.value_text as status_value'))

//        $query->select('p.id','p.is_product_for_production')
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('u.product_pending_images', 'images')
            ->where('p.is_archived = 0')
            ->andWhere('p.is_send_mail=1')
            ->andWhere('p.is_product_for_production=1')

//                ->andWhere('p.is_copyright=1')
            ->groupBy('p.id');


        $query->andWhere(
//                        $query->expr()->in('status.id', ':status')

            $query->expr()->isNull('status.id')
        );


        $qb = $query->getQuery();


        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return $data;
    }

    public function getProductQuotationsFinal($filter)
    {

        if ($filter['order'][0]['column'] == 1) {

            $orderbyclm = 'u.sku';
        }

        if ($filter['order'][0]['column'] == 2) {

            $orderbyclm = 'u.name';
        }

//        if ($filter['order'][0]['column'] == 3)
//        {
//            $orderbyclm = 'p.price';
//        }
//        if ($filter['order'][0]['column'] == 4)
//        {
//            $orderbyclm = 'u.tlv_suggested_price_max';
//        }


        if ($filter['order'][0]['column'] == 4) {

            $orderbyclm = 'p.approved_created_at';
        }

        if ($filter['order'][0]['column'] == 5) {

            $orderbyclm = 'p.approved_created_at';
        }

        if ($filter['order'][0]['column'] == 6) {

            $orderbyclm = 'status.id';
        }

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('p.assign_agent_id', 'a')
            ->where('p.is_awaiting_contract=1')
            ->andWhere('p.is_proposal_for_production=1')
            ->andWhere('p.is_product_for_pricing=1')
            ->andWhere('p.is_archived=0')
            ->andWhere('p.is_send_mail != 2')
            ->andWhere('u.sellerid=' . $filter['id']);

//                ->andWhere('status.id=17');


        if (isset($filter['pass_from']) && $filter['pass_from'] == 'report') {

            //19 for reject

            $status = [17, 19];
        } else {

            $status = [17, 83];
        }

        $query->andWhere(
            $query->expr()->in('status.id', ':status')
        )
            ->setParameter('status', $status);


        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                $query->expr()->between('p.approved_created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }

//                ->groupBy('p.id');

        $total = $query->getQuery()->getSingleScalarResult();


        $query = $this->em->createQueryBuilder();

        $query->select(array('u.name', 'u.sku', 'p.price', 'p.tlv_price', 'p.storage_pricing', ' GROUP_CONCAT(images.name) image', ' GROUP_CONCAT(images.priority) priority', 'p.updated_at', 'p.created_at as quote_created_at', 'p.for_production_created_at', 'p.copyright_created_at', 'p.approved_created_at', 'p.tlv_suggested_price_max', 'p.tlv_suggested_price_min', 'p.id', 'p.is_send_mail', 'p.images_from', 'status.id as status_id', 'status.value_text as status_value', 'CONCAT(a.firstname,\' \',a.lastname) AS agent_name'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('u.product_pending_images', 'images')
            ->leftjoin('p.assign_agent_id', 'a')
            ->setMaxResults($filter['length'])
            ->setFirstResult($filter['start'])
            ->where('p.is_archived = 0')
            ->andWhere(
                $query->expr()->orX(
                    $query->expr()->like('u.name', ':filter')
                    , $query->expr()->like('u.sku', ':filter')

//                                , $query->expr()->like('p.price', ':filter')
                    , $query->expr()->like("concat(p.tlv_suggested_price_max,'/',p.tlv_suggested_price_min)", ':filter')
                    , $query->expr()->like('status.value_text', ':filter')
                ), $query->expr()->andX(
                $query->expr()->neq('p.is_send_mail', '2')
                , $query->expr()->eq('p.is_product_for_pricing', '1')
                , $query->expr()->eq('p.is_awaiting_contract', '1')
                , $query->expr()->eq('p.is_proposal_for_production', '1')
                , $query->expr()->eq('p.is_archived', '0')
                , $query->expr()->eq('u.sellerid', $filter['id'])

//                                , $query->expr()->eq('status.id', '17')
//                                , $query->expr()->orX(
//                                        $query->expr()->eq('status.id', '17')
//                                        , $query->expr()->eq('status.id', '18'))
            )
            )
            ->setParameter('filter', '%' . $filter['search']['value'] . '%')
            ->orderBy($orderbyclm, $filter['order'][0]['dir'])
            ->groupBy('p.id');


        if (isset($filter['pass_from']) && $filter['pass_from'] == 'report') {

            //19 for reject

            $status = [17, 19];
        } else {

            $status = [17, 83];
        }

        $query->andWhere(
            $query->expr()->in('status.id', ':status')
        )
            ->setParameter('status', $status);


        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                $query->expr()->between('p.approved_created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }


        $qb = $query->getQuery();


        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getProductQuotationsFinalTotal($filter)
    {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('p.assign_agent_id', 'a')
            ->where('p.is_archived = 0')
            ->andWhere(
                $query->expr()->orX(
                    $query->expr()->like('u.name', ':filter')
                    , $query->expr()->like('u.sku', ':filter')

//                                , $query->expr()->like('u.price', ':filter')
                    , $query->expr()->like("concat(p.tlv_suggested_price_max,'/',p.tlv_suggested_price_min)", ':filter')
                    , $query->expr()->like('status.value_text', ':filter')
                ), $query->expr()->andX(
                $query->expr()->neq('p.is_send_mail', '2')
                , $query->expr()->eq('p.is_product_for_pricing', '1')
                , $query->expr()->eq('p.is_awaiting_contract', '1')
                , $query->expr()->eq('p.is_proposal_for_production', '1')
                , $query->expr()->eq('p.is_archived', '0')
                , $query->expr()->eq('u.sellerid', $filter['id'])

//                                , $query->expr()->eq('status.id', '17')
            ))
            ->setParameter('filter', '%' . $filter['search']['value'] . '%');


        if (isset($filter['pass_from']) && $filter['pass_from'] == 'report') {

            //19 for reject

            $status = [17, 19];
        } else {

            $status = [17, 83];
        }

        $query->andWhere(
            $query->expr()->in('status.id', ':status')
        )
            ->setParameter('status', $status);


        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                $query->expr()->between('p.approved_created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getProductQuotationsFinalSynced($filter)
    {

        if (isset($filter['order'])) {

            if ($filter['order'][0]['column'] == 1) {

                $orderbyclm = 'u.sku';
            }

            if ($filter['order'][0]['column'] == 2) {

                $orderbyclm = 'u.name';
            }


            if ($filter['order'][0]['column'] == 3) {

                $orderbyclm = 'u.tlv_suggested_price_max';
            }


            if ($filter['order'][0]['column'] == 4) {

                $orderbyclm = 'p.approved_created_at';
            }

            if ($filter['order'][0]['column'] == 5) {

                $orderbyclm = 'p.approved_created_at';
            }

            if ($filter['order'][0]['column'] == 6) {

                $orderbyclm = 'status.id';
            }

            $orderby = $filter['order'][0]['dir'];
        } else {

            $orderbyclm = 'status.id';

            $orderby = 'Asc';
        }

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->where('p.is_send_mail=1')
            ->andWhere('p.is_copyright=1')
            ->andWhere('p.is_archived=0')
            ->andWhere('u.sellerid=' . $filter['id'])
            ->andWhere('status.id=18');

        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                $query->expr()->between('p.updated_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }

//                ->groupBy('p.id');

        $total = $query->getQuery()->getSingleScalarResult();


        $query = $this->em->createQueryBuilder();

        $query->select(array('u.name', 'u.sku', 'p.price', ' GROUP_CONCAT(images.name) image', 'p.updated_at', 'p.created_at as quote_created_at', 'p.for_production_created_at', 'p.copyright_created_at', 'p.approved_created_at', 'p.tlv_suggested_price_max', 'p.id', 'p.is_send_mail', 'p.images_from', 'status.id as status_id', 'status.value_text as status_value'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('u.product_pending_images', 'images');


        if (isset($filter['length']) && isset($filter['start'])) {

            $query->setMaxResults($filter['length'])
                ->setFirstResult($filter['start']);
        }


        $query->where('p.is_archived = 0')
            ->andWhere(
                $query->expr()->orX(
                    $query->expr()->like('u.name', ':filter')
                    , $query->expr()->like('u.sku', ':filter')
                    , $query->expr()->like('p.price', ':filter')
                    , $query->expr()->like('status.value_text', ':filter')
                ), $query->expr()->andX(
                $query->expr()->eq('p.is_send_mail', '1')
                , $query->expr()->eq('p.is_copyright', '1')
                , $query->expr()->eq('u.sellerid', $filter['id'])
                , $query->expr()->eq('status.id', '18')

//                                , $query->expr()->orX(
//                                        $query->expr()->eq('status.id', '17')
//                                        , $query->expr()->eq('status.id', '18'))
            )
            )
            ->setParameter('filter', '%' . $filter['search']['value'] . '%')
            ->orderBy($orderbyclm, $orderby)
            ->groupBy('p.id');


        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                $query->expr()->between('p.updated_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }


        $qb = $query->getQuery();


        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getProductQuotationsFinalSyncedTotal($filter)
    {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->where('p.is_archived = 0')
            ->andWhere(
                $query->expr()->orX(
                    $query->expr()->like('u.name', ':filter')
                    , $query->expr()->like('u.sku', ':filter')
                    , $query->expr()->like('u.price', ':filter')
                    , $query->expr()->like('status.value_text', ':filter')
                ), $query->expr()->andX(
                $query->expr()->eq('p.is_send_mail', '1')
                , $query->expr()->eq('p.is_copyright', '1')
                , $query->expr()->eq('u.sellerid', $filter['id'])
                , $query->expr()->eq('status.id', '18')
            ))
            ->setParameter('filter', '%' . $filter['search']['value'] . '%');

        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                $query->expr()->between('p.updated_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getProductProposalsInProgress()
    {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->innerjoin('u.sellerid', 'seller')
            ->leftjoin('u.status', 'status')
            ->leftjoin('p.status_quot', 'sq')
            ->andWhere('p.status_quot IS NULL')
            ->where('p.is_send_mail = 0')
            ->andWhere('p.is_archived = 0');

//                ->andWhere(
//                        $query->expr()->andX(
//                                $query->expr()->isNull('sq.id')
//                        )
//        );


        $qb = $query->getQuery();


        return $query->getQuery()->getSingleScalarResult();
    }

    public function getProposalPendingCountBySellerId($seller_id)
    {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('u.sellerid', 's')
            ->leftjoin('u.status', 'status')
            ->leftjoin('p.status_quot', 'sq')
            ->where('p.is_send_mail = 0')
            ->andWhere('p.status_quot IS NULL')
            ->andWhere('p.is_archived = 0')
            ->andWhere(
                $query->expr()->andX(
//                                $query->expr()->isNull('sq.id')

                    $query->expr()->eq('s.id', $seller_id)
                ));

        $qb = $query->getQuery();

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getProductQuotations($filter)
    {


        if ($filter['order'][0]['column'] == 1) {

            $orderbyclm = 'u.sku';
        }

        if ($filter['order'][0]['column'] == 2) {

            $orderbyclm = 'u.name';
        }


//        if ($filter['order'][0]['column'] == 3)
//        {
//            $orderbyclm = 'p.price';
//        }

        if ($filter['order'][0]['column'] == 4) {

            $orderbyclm = 'p.created_at';
        }

        if ($filter['order'][0]['column'] == 5) {

            $orderbyclm = 'p.is_send_mail';
        }


        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->where('u.sellerid =' . $filter['id'])
            ->andWhere('p.status_quot IS NULL')
            ->andWhere('p.is_archived = 0');

//                ->andWhere('p.is_send_mail = 0');


        if (isset($filter['pass_from']) && $filter['pass_from'] == 'report') {

            //2 for reject

            $status = [0, 2];
        } else {

            $status = [0];
        }

        $query->andWhere(
            $query->expr()->in('p.is_send_mail', ':status')
        )
            ->setParameter('status', $status);


        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                $query->expr()->between('p.created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }

        $total = $query->getQuery()->getSingleScalarResult();


        $query = $this->em->createQueryBuilder();

        $query->select(array('u.name', 'u.sku', 'p.for_production_created_at', ' GROUP_CONCAT(images.name) image', 'p.copyright_created_at', 'p.approved_created_at', 'p.created_at as quote_created_at', 'p.price', 'p.id', 'p.tlv_suggested_price_min', 'p.tlv_suggested_price_max', 'p.is_send_mail', 'p.is_updated_details', 'p.is_scheduled', 'p.images_from', 'sq.id as status_id', 'sq.value_text as status_value'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('u.status', 'status')
            ->leftjoin('p.status_quot', 'sq')
            ->leftjoin('u.product_pending_images', 'images')
            ->setMaxResults($filter['length'])
            ->setFirstResult($filter['start'])
            ->where('u.sellerid =' . $filter['id'])
            ->andWhere('p.status_quot IS NULL')
            ->andWhere('p.is_archived = 0')

//                ->andWhere('p.is_send_mail = 0')
            ->andWhere(
                $query->expr()->orX(
                    $query->expr()->like('u.sku', ':filter')
                    , $query->expr()->like('u.name', ':filter')

//                                , $query->expr()->like('p.price', ':filter')
                    , $query->expr()->like("concat(p.tlv_suggested_price_max,'/',p.tlv_suggested_price_min)", ':filter')
                    , $query->expr()->like('status.value_text', ':filter')
                ))
            ->setParameter('filter', '%' . $filter['search']['value'] . '%')
            ->orderBy($orderbyclm, $filter['order'][0]['dir'])
            ->groupBy('p.id');


        if (isset($filter['pass_from']) && $filter['pass_from'] == 'report') {

            //2 for reject

            $status = [0, 2];
        } else {

            $status = [0];
        }

        $query->andWhere(
            $query->expr()->in('p.is_send_mail', ':status')
        )
            ->setParameter('status', $status);


        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                $query->expr()->between('p.created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }


        $qb = $query->getQuery();


        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getProductQuotationsTotal($filter)
    {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('u.status', 'status')
            ->where('u.sellerid =' . $filter['id'])
            ->andWhere('p.status_quot IS NULL')
            ->andWhere('p.is_archived = 0')

//                ->andWhere('p.is_send_mail != 2')
            ->andWhere(
                $query->expr()->orX(
                    $query->expr()->like('u.name', ':filter')
                    , $query->expr()->like('u.sku', ':filter')

//                                , $query->expr()->like('u.price', ':filter')
                    , $query->expr()->like("concat(p.tlv_suggested_price_max,'/',p.tlv_suggested_price_min)", ':filter')
                    , $query->expr()->like('status.value_text', ':filter')
                ))
            ->setParameter('filter', '%' . $filter['search']['value'] . '%');


        if (isset($filter['pass_from']) && $filter['pass_from'] == 'report') {

            //2 for reject

            $status = [0, 2];
        } else {

            $status = [0];
        }

        $query->andWhere(
            $query->expr()->in('p.is_send_mail', ':status')
        )
            ->setParameter('status', $status);


        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                $query->expr()->between('p.created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getTLVStaffProductsTotal($filter)
    {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('u.id'))
            ->from('App\Entities\Products', 'u')
            ->leftjoin('u.sell_id', 's')
            ->leftjoin('s.user_id', 'user')
            ->where(
                $query->expr()->orX(
                    $query->expr()->eq('user.id', ':userid')
                ), $query->expr()->orX(
                $query->expr()->like('u.name', ':filter')
                , $query->expr()->like('s.name', ':filter')
                , $query->expr()->like('u.price', ':filter')
            ))
            ->setParameter('userid', $filter['userid'])
            ->setParameter('filter', '%' . $filter['search']['value'] . '%');

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getProducts($filter)
    {


        if ($filter['order'][0]['column'] == 0) {

            $orderbyclm = 'u.name';
        }


        if ($filter['order'][0]['column'] == 1) {

            $orderbyclm = 'u.price';
        }

        if ($filter['order'][0]['column'] == 2) {

            $orderbyclm = 'status.id';
        }


//        if ($filter['order'][0]['column'] == 4)
//        {
//            $orderbyclm = 's.value_text';
//        }


        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('u.id'))
            ->from('App\Entities\Products', 'u');

        $total = $query->getQuery()->getSingleScalarResult();


        $query = $this->em->createQueryBuilder();

        $query->select(array('u.name', 'u.price', 's.name as sell_name', 'u.id', 'status.id as status_id', 'status.value_text as status_value'))
            ->from('App\Entities\Products', 'u')
            ->leftjoin('u.sell_id', 's')
            ->leftjoin('u.status', 'status')
            ->setMaxResults($filter['length'])
            ->setFirstResult($filter['start'])
            ->where(
                $query->expr()->orX(
                    $query->expr()->like('u.name', ':filter')
                    , $query->expr()->like('u.price', ':filter')
                    , $query->expr()->like('s.name', ':filter')
                    , $query->expr()->like('status.value_text', ':filter')
                ))
            ->setParameter('filter', '%' . $filter['search']['value'] . '%')
            ->orderBy($orderbyclm, $filter['order'][0]['dir']);;


        $qb = $query->getQuery();


        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getTLVStaffProducts($filter)
    {


        if ($filter['order'][0]['column'] == 1) {

            $orderbyclm = 'u.name';
        }


        if ($filter['order'][0]['column'] == 2) {

            $orderbyclm = 'u.email';
        }

        if ($filter['order'][0]['column'] == 3) {

            $orderbyclm = 'r.name';
        }


        if ($filter['order'][0]['column'] == 4) {

            $orderbyclm = 's.value_text';
        }


        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('u.id'))
            ->from('App\Entities\Products', 'u');

        $total = $query->getQuery()->getSingleScalarResult();


        $query = $this->em->createQueryBuilder();

        $query->select(array('u.name', 'u.price', 's.name as sell_name', 'u.id'))
            ->from('App\Entities\Products', 'u')
            ->leftjoin('u.sell_id', 's')
            ->leftjoin('s.user_id', 'user')
            ->setMaxResults($filter['length'])
            ->setFirstResult($filter['start'])
            ->where(
                $query->expr()->orX(
                    $query->expr()->eq('user.id', ':userid')
                ), $query->expr()->orX(
                $query->expr()->like('u.name', ':filter')
                , $query->expr()->like('u.price', ':filter')
                , $query->expr()->like('s.name', ':filter')
            ))
            ->setParameter('userid', $filter['userid'])
            ->setParameter('filter', '%' . $filter['search']['value'] . '%')
            ->orderBy($orderbyclm, $filter['order'][0]['dir']);;


        $qb = $query->getQuery();


        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

//    report start


    public function getProductQuotationsReport($filter)
    {


        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->where('u.sellerid =' . $filter['id'])
            ->andWhere('p.status_quot IS NULL')
            ->andWhere('p.is_product_for_pricing = 1')
            ->andWhere('p.is_archived = 0');

//                ->andWhere('p.is_send_mail = 0');
        //2 for reject

        $status = [0, 2];


        $query->andWhere(
            $query->expr()->in('p.is_send_mail', ':status')
        )
            ->setParameter('status', $status);


        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                $query->expr()->between('p.created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }

        $total = $query->getQuery()->getSingleScalarResult();


        $query = $this->em->createQueryBuilder();

        $query->select(array('u.name', 'p.note', 'u.quantity', 'u.sku', 'p.for_production_created_at', ' GROUP_CONCAT(images.name) image', 'p.copyright_created_at', 'p.approved_created_at', 'p.created_at as quote_created_at', 'p.price', 'p.id', 'p.tlv_suggested_price_min', 'p.tlv_suggested_price_max', 'p.is_send_mail', 'p.is_updated_details', 'p.is_scheduled', 'p.images_from', 'sq.id as status_id', 'sq.value_text as status_value'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('u.status', 'status')
            ->leftjoin('p.status_quot', 'sq')
            ->leftjoin('u.product_pending_images', 'images')
            ->where('u.sellerid =' . $filter['id'])
            ->andWhere('p.status_quot IS NULL')
            ->andWhere('p.is_product_for_pricing = 1')
            ->andWhere('p.is_archived = 0')
            ->groupBy('p.id');


        //2 for reject

        $status = [0, 2];


        $query->andWhere(
            $query->expr()->in('p.is_send_mail', ':status')
        )
            ->setParameter('status', $status);


        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                $query->expr()->between('p.created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }


        $qb = $query->getQuery();


        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getProductQuotationsTotalReport($filter)
    {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('u.status', 'status')
            ->where('u.sellerid =' . $filter['id'])
            ->andWhere('p.status_quot IS NULL')
            ->andWhere('p.is_product_for_pricing = 1')
            ->andWhere('p.is_archived = 0');


        //2 for reject

        $status = [0, 2];


        $query->andWhere(
            $query->expr()->in('p.is_send_mail', ':status')
        )
            ->setParameter('status', $status);


        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                $query->expr()->between('p.created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getProductForProductionsReport($filter)
    {

        $archived = 0;

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('p.product_id', 'u')
            ->where('p.is_archived =' . $archived)
            ->andWhere(
                $query->expr()->andX(
                    $query->expr()->eq('p.is_archived', '0')
                    , $query->expr()->eq('p.is_proposal_for_production', '0')
                    , $query->expr()->eq('p.is_product_for_pricing', '0')
                    , $query->expr()->eq('p.is_awaiting_contract', '1')
                    , $query->expr()->eq('u.sellerid', $filter['id'])
                ));


        //2 for reject
//        $status = [0, 2];
//
//        $query->andWhere(
//                        $query->expr()->in('p.is_product_for_production', ':status')
//                )
//                ->setParameter('status', $status);

        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {
            $query->andWhere(
                $query->expr()->between('p.for_production_created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }

        $total = $query->getQuery()->getSingleScalarResult();

        $query = $this->em->createQueryBuilder();

        $query->select(array('u.name', 'p.note', 'u.sku', 'p.created_at as quote_created_at', 'p.price', 'p.quantity', ' GROUP_CONCAT(images.name) image', 'p.for_production_created_at', 'p.copyright_created_at', 'p.approved_created_at', 'p.is_product_for_production', 'p.is_copyright', 'p.tlv_suggested_price_max', 'p.tlv_suggested_price_min', 'p.id', 'p.is_send_mail', 'p.images_from', 'status.id as status_id', 'status.value_text as status_value'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('u.product_pending_images', 'images')
            ->where('p.is_archived =' . $archived)
            ->andWhere(
                $query->expr()->andX(
                    $query->expr()->eq('p.is_archived', '0')
                    , $query->expr()->in('p.is_proposal_for_production', '0')
                    , $query->expr()->in('p.is_product_for_pricing', '0')
                    , $query->expr()->in('p.is_awaiting_contract', '1')
                    , $query->expr()->eq('u.sellerid', $filter['id'])
                ))
            ->groupBy('p.id');


        //2 for reject
//
//        $status = [0, 2];
//
//
//
//        $query->andWhere(
//                        $query->expr()->in('p.is_product_for_production', ':status')
//                )
//                ->setParameter('status', $status);


        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                $query->expr()->between('p.for_production_created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }


        $qb = $query->getQuery();


        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getProductForProductionsTotalReport($filter)
    {

        $archived = 0;

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->where('p.is_archived =' . $archived)
            ->andWhere(
                $query->expr()->andX(
                    $query->expr()->eq('p.is_archived', '0')
                    , $query->expr()->eq('p.is_proposal_for_production', '0')
                    , $query->expr()->eq('p.is_product_for_pricing', '0')
                    , $query->expr()->eq('p.is_awaiting_contract', '1')
                    , $query->expr()->eq('u.sellerid', $filter['id'])
                ));


        //2 for reject
//        $status = [0, 2];
//
//
//
//        $query->andWhere(
//                        $query->expr()->in('p.is_product_for_production', ':status')
//                )
//                ->setParameter('status', $status);


        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                $query->expr()->between('p.for_production_created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }

        return $query->getQuery()->getSingleScalarResult();
    }

    //copyright

    public function getCopyrightsReport($filter)
    {

        $archived = 0;


        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('p.product_id', 'u')
            ->where('p.is_send_mail=1')
            ->andWhere('p.is_product_for_production=1')
            ->andWhere('u.sellerid=' . $filter['id']);

//                ->andWhere('p.is_copyright=0');
        //2 for reject

        $status = [0, 2];


        $query->andWhere(
            $query->expr()->in('p.is_copyright', ':status')
        )
            ->setParameter('status', $status);


        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                $query->expr()->between('p.copyright_created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }


        $total = $query->getQuery()->getSingleScalarResult();


        $query = $this->em->createQueryBuilder();

        $query->select(array('u.name', 'p.note', 'u.sku', 'p.quantity', 'p.tlv_suggested_price_min', 'p.created_at as quot_created_at', ' GROUP_CONCAT(images.name) image', 'p.for_production_created_at', 'p.copyright_created_at', 'p.approved_created_at', 'p.price', 'p.is_copyright', 'p.tlv_suggested_price_max', 'p.id', 'p.is_send_mail', 'p.images_from', 'status.id as status_id', 'status.value_text as status_value'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('u.product_pending_images', 'images')
            ->where('p.is_archived =' . $archived)
            ->andWhere(
                $query->expr()->andX(
                    $query->expr()->eq('p.is_send_mail', '1')
                    , $query->expr()->eq('p.is_product_for_production', '1')
                    , $query->expr()->eq('u.sellerid', $filter['id'])

//                                , $query->expr()->eq('p.is_copyright', 0)
//                                , $query->expr()->orX(
//                                        $query->expr()->eq('status.id', '17')
//                                        , $query->expr()->eq('status.id', '18'))
                )
            )
            ->groupBy('p.id');


        //2 for reject

        $status = [0, 2];


        $query->andWhere(
            $query->expr()->in('p.is_copyright', ':status')
        )
            ->setParameter('status', $status);


        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                $query->expr()->between('p.copyright_created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }


        $qb = $query->getQuery();


        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getCopyrightsTotalReport($filter)
    {

        $archived = 0;

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->where('p.is_archived =' . $archived)
            ->andWhere(
                $query->expr()->andX(
                    $query->expr()->eq('p.is_send_mail', '1')
                    , $query->expr()->eq('p.is_product_for_production', '1')
                    , $query->expr()->eq('u.sellerid', $filter['id'])

//                                , $query->expr()->eq('p.is_copyright', 0)
                ));


        //2 for reject

        $status = [0, 2];


        $query->andWhere(
            $query->expr()->in('p.is_copyright', ':status')
        )
            ->setParameter('status', $status);


        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                $query->expr()->between('p.copyright_created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }

        return $query->getQuery()->getSingleScalarResult();
    }

    //approval

    public function getProductQuotationsFinalReport($filter)
    {


        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->where('u.sellerid=' . $filter['id']);

//                ->andWhere('status.id=17');
        //19 for reject

        $status = [17, 19];


        $query->andWhere(
            $query->expr()->eq('p.is_archived', '0')
            , $query->expr()->in('p.is_proposal_for_production', '1')
            , $query->expr()->in('p.is_product_for_pricing', '1')
            , $query->expr()->in('p.is_awaiting_contract', '1')
            , $query->expr()->in('status.id', ':status')
        )
            ->setParameter('status', $status);


        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                $query->expr()->between('p.approved_created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }

//                ->groupBy('p.id');

        $total = $query->getQuery()->getSingleScalarResult();


        $query = $this->em->createQueryBuilder();

        $query->select(array('u.name', 'p.note', 'u.sku', 'p.price', 'p.quantity', 'p.tlv_suggested_price_min', ' GROUP_CONCAT(images.name) image', 'p.updated_at', 'p.created_at as quote_created_at', 'p.for_production_created_at', 'p.copyright_created_at', 'p.approved_created_at', 'p.tlv_suggested_price_max', 'p.id', 'p.is_send_mail', 'p.images_from', 'status.id as status_id', 'status.value_text as status_value'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('u.product_pending_images', 'images')
            ->andWhere(
                $query->expr()->andX(
                    $query->expr()->eq('p.is_archived', '0')
                    , $query->expr()->in('p.is_proposal_for_production', '1')
                    , $query->expr()->in('p.is_product_for_pricing', '1')
                    , $query->expr()->in('p.is_awaiting_contract', '1')
                    , $query->expr()->eq('u.sellerid', $filter['id'])

//                                , $query->expr()->eq('status.id', '17')
//                                , $query->expr()->orX(
//                                        $query->expr()->eq('status.id', '17')
//                                        , $query->expr()->eq('status.id', '18'))
                )
            )
            ->groupBy('p.id');


        //19 for reject

        $status = [17, 19];


        $query->andWhere(
            $query->expr()->in('status.id', ':status')
        )
            ->setParameter('status', $status);


        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                $query->expr()->between('p.approved_created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }


        $qb = $query->getQuery();


        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getProductQuotationsFinalTotalReport($filter)
    {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->andWhere(
                $query->expr()->andX(
                    $query->expr()->eq('p.is_archived', '0')
                    , $query->expr()->in('p.is_proposal_for_production', '1')
                    , $query->expr()->in('p.is_product_for_pricing', '1')
                    , $query->expr()->in('p.is_awaiting_contract', '1')
                    , $query->expr()->eq('u.sellerid', $filter['id'])

//                                , $query->expr()->eq('status.id', '17')
                ));


        //19 for reject

        $status = [17, 19];


        $query->andWhere(
            $query->expr()->in('status.id', ':status')
        )
            ->setParameter('status', $status);


        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                $query->expr()->between('p.approved_created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }

        return $query->getQuery()->getSingleScalarResult();
    }

    //synced

    public function getProductQuotationsFinalSyncedreport($filter)
    {


        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->andWhere('p.is_archived=0')
            ->andWhere('u.sellerid=' . $filter['id'])
            ->andWhere('status.id=18');

        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                $query->expr()->between('p.updated_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }

//                ->groupBy('p.id');

        $total = $query->getQuery()->getSingleScalarResult();


        $query = $this->em->createQueryBuilder();

        $query->select(array('p.wp_product_id', 'u.name', 'p.note', 'u.sku', 'p.price', 'p.tlv_suggested_price_min', 'p.quantity', ' GROUP_CONCAT(images.name) image', 'p.updated_at', 'p.created_at as quote_created_at', 'p.for_production_created_at', 'p.copyright_created_at', 'p.approved_created_at', 'p.tlv_suggested_price_max', 'p.id', 'p.is_send_mail', 'p.images_from', 'status.id as status_id', 'status.value_text as status_value'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('u.product_pending_images', 'images');


        if (isset($filter['length']) && isset($filter['start'])) {

            $query->setMaxResults($filter['length'])
                ->setFirstResult($filter['start']);
        }


        $query->where('p.is_archived = 0')
            ->andWhere(
                $query->expr()->andX(
                    $query->expr()->eq('u.sellerid', $filter['id'])
                    , $query->expr()->eq('status.id', '18')

//                                , $query->expr()->orX(
//                                        $query->expr()->eq('status.id', '17')
//                                        , $query->expr()->eq('status.id', '18'))
                )
            )
            ->groupBy('p.id');


        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                $query->expr()->between('p.updated_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }


        $qb = $query->getQuery();


        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getProductQuotationsFinalSyncedTotalReport($filter)
    {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->where('p.is_archived = 0')
            ->andWhere(
                $query->expr()->andX(
                    $query->expr()->eq('u.sellerid', $filter['id'])
                    , $query->expr()->eq('status.id', '18')
                ));

        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                $query->expr()->between('p.updated_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }

        return $query->getQuery()->getSingleScalarResult();
    }

    // MOBILE API FUNCTIONS


    public function getProductForProductionsUsingSellerIDMobileApi($filter)
    {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('u.sellerid', 's')
            ->leftjoin('s.assign_agent_id', 'agent')
            ->where(
                $query->expr()->andX(
                    $query->expr()->neq('p.is_send_mail', '2'),
                    $query->expr()->eq('p.is_archived', ':is_archived'),
                    $query->expr()->in('p.is_proposal_for_production', ':is_proposal_for_production'),
                    $query->expr()->in('p.is_awaiting_contract', ':is_awaiting_contract')
                )
            )
            ->setParameter('is_archived', 0)
            ->setParameter('is_awaiting_contract', 1)
            ->setParameter('is_proposal_for_production', 0)
            ->andWhere('u.sellerid=' . $filter['seller_id']);

        if (isset($filter['role_id']) && $filter['role_id'] == 3) {
            $query->andWhere('s.assign_agent_id = :agent_user_id');
//            $query->andWhere('p.assign_agent_id = :agent_user_id');
            $query->setParameter('agent_user_id', $filter['user_id']);
        }

        $total = $query->getQuery()->getSingleScalarResult();


        $query = $this->em->createQueryBuilder();

        $query->select(array('u.name', 'u.sku', 'p.price', 'p.tlv_price', ' GROUP_CONCAT(images.name ORDER BY images.priority ASC) image', 'p.created_at as for_production_created_at ', 'p.copyright_created_at', 'p.approved_created_at', 'p.is_product_for_production', 'p.is_copyright', 'p.tlv_suggested_price_max', 'p.tlv_suggested_price_min', 'p.id', 'p.is_send_mail', 'p.images_from', 'status.id as status_id', 'status.value_text as status_value', 'p.weight', 'p.height', 'p.length', 'p.width', 'p.depth'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('u.product_pending_images', 'images')
            ->leftjoin('u.sellerid', 's')
            ->leftjoin('s.assign_agent_id', 'a')
            ->setMaxResults($filter['length'])
            ->setFirstResult($filter['start'])
            ->where('u.sellerid =' . $filter['seller_id'])
            ->andWhere(
                $query->expr()->andX(
                    $query->expr()->neq('p.is_send_mail', '2'),
                    $query->expr()->eq('p.is_archived', ':is_archived'),
                    $query->expr()->in('p.is_proposal_for_production', ':is_proposal_for_production'),
                    $query->expr()->in('p.is_awaiting_contract', ':is_awaiting_contract')
                )
            )
            ->andWhere(
                $query->expr()->orX(
                    $query->expr()->like('u.name', ':filter'),
                    $query->expr()->like('u.sku', ':filter'),
                    $query->expr()->like('status.value_text', ':filter')
                )
            )
            ->setParameter('filter', '%' . $filter['search'] . '%')
            ->setParameter('is_archived', 0)
            ->setParameter('is_awaiting_contract', 1)
            ->setParameter('is_proposal_for_production', 0)
            ->groupBy('p.id');

        if (isset($filter['role_id']) && $filter['role_id'] == 3) {
            $query->andWhere('s.assign_agent_id = :agent_user_id');
//            $query->andWhere('p.assign_agent_id = :agent_user_id');
            $query->setParameter('agent_user_id', $filter['user_id']);
        }

        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getProductForProductionsUsingSellerIDTotalMobileApi($filter)
    {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('u.sellerid', 's')
            ->leftjoin('s.assign_agent_id', 'a')
            ->andWhere('u.sellerid=' . $filter['seller_id'])
            ->andWhere(
                $query->expr()->andX(
                    $query->expr()->neq('p.is_send_mail', '2'),
                    $query->expr()->eq('p.is_archived', ':is_archived'),
                    $query->expr()->in('p.is_proposal_for_production', ':is_proposal_for_production'),
                    $query->expr()->in('p.is_awaiting_contract', ':is_awaiting_contract')
                )
            )
            ->andWhere(
                $query->expr()->orX(
                    $query->expr()->like('u.name', ':filter'),
                    $query->expr()->like('u.sku', ':filter'),
                    $query->expr()->like('status.value_text', ':filter')
                )
            )
            ->setParameter('filter', '%' . $filter['search'] . '%')
            ->setParameter('is_archived', 0)
            ->setParameter('is_awaiting_contract', 1)
            ->setParameter('is_proposal_for_production', 0);

        if (isset($filter['role_id']) && $filter['role_id'] == 3) {
            $query->andWhere('s.assign_agent_id = :agent_user_id');
//            $query->andWhere('p.assign_agent_id = :agent_user_id');
            $query->setParameter('agent_user_id', $filter['user_id']);
        }

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getProductQuotationByIdMobileApi($id)
    {

        $query = $this->em->createQueryBuilder()
            ->select('p,u,pi,room,look,color,brand,category,con,collection,product_category,product_con,age,s,location,pms')
            ->from('App\Entities\Products_quotation', 'p')
            ->leftJoin('p.product_id', 'u')
            ->leftJoin('u.product_room', 'color')
            ->leftJoin('u.product_category', 'product_category')
            ->leftJoin('u.product_con', 'product_con')
            ->leftJoin('u.pick_up_location', 'location')
            ->leftJoin('u.product_color', 'room')
            ->leftJoin('u.product_collection', 'collection')
            ->leftJoin('u.sellerid', 's')
            ->leftJoin('u.product_pending_images', 'pi')
            ->leftJoin('u.product_material', 'pm')
            ->leftJoin('u.product_materials', 'pms')

//                ->leftJoin('u.look', 'look')
            ->leftJoin('u.product_look', 'look')

//                ->leftJoin('u.color', 'color')
            ->leftJoin('u.brand', 'brand')
            ->leftJoin('u.category', 'category')
            ->leftJoin('u.con', 'con')

//                ->leftJoin('u.collection', 'collection')
            ->leftJoin('u.age', 'age')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->orderBy('pi.priority', 'asc')
            ->getQuery();

        $data = $query->getResult(Query::HYDRATE_ARRAY);


        return $data[0];
    }

    public function getAllPendingAwaitingContractCountOfSellerId($seller_id)
    {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'pp')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('pp.sellerid', 'seller')
            ->where($query->expr()->andX(
                $query->expr()->neq('p.is_send_mail', '2')
                , $query->expr()->eq('p.is_archived', ':is_archived')
                , $query->expr()->eq('p.is_awaiting_contract', ':is_awaiting_contract')
                , $query->expr()->isNull('p.status_quot')
            )
            )
            ->setParameter('is_archived', 0)
            ->setParameter('is_awaiting_contract', 0)

//                ->where('p.is_send_mail = 1')
//                ->andWhere('p.is_copyright = 0')
//                ->andWhere('p.is_product_for_production = 0')
//                ->andWhere('p.is_archived = 0')
            ->andWhere('seller.id = ' . $seller_id);

//                ->andWhere('status.id = 17');

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getAllPendingProposalForPricingCountOfSellerId($seller_id)
    {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'pp')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('pp.sellerid', 'seller')
            ->where(
                $query->expr()->andX(
                    $query->expr()->neq('p.is_send_mail', '2')
                    , $query->expr()->eq('p.is_archived', ':is_archived')
                    , $query->expr()->eq('p.is_proposal_for_production', ':is_proposal_for_production')
                    , $query->expr()->eq('p.is_awaiting_contract', ':is_awaiting_contract')
                    , $query->expr()->eq('p.is_product_for_pricing', ':is_product_for_pricing')
                )
            )
            ->setParameter('is_archived', 0)
            ->setParameter('is_awaiting_contract', 1)
            ->setParameter('is_proposal_for_production', 1)
            ->setParameter('is_product_for_pricing', 0)

//                ->where('p.is_send_mail = 1')
//                ->andWhere('p.is_copyright = 0')
//                ->andWhere('p.is_product_for_production = 0')
//                ->andWhere('p.is_archived = 0')
            ->andWhere('seller.id = ' . $seller_id);

//                ->andWhere('status.id = 17');

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getAllPendingProposalForProductionCountOfSellerId($seller_id)
    {

        $authUser = JWTAuth::parseToken()->authenticate();

        $role_id = $authUser->getRoles()[0]->getId();
        $user_id = $authUser->getId();


        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'pp')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('pp.sellerid', 'seller')
            ->where(
                $query->expr()->andX(
                    $query->expr()->neq('p.is_send_mail', '2')
                    , $query->expr()->eq('p.is_archived', ':is_archived')
                    , $query->expr()->eq('p.is_proposal_for_production', ':is_proposal_for_production')
                    , $query->expr()->eq('p.is_awaiting_contract', ':is_awaiting_contract')
                )
            )
            ->setParameter('is_archived', 0)
            ->setParameter('is_proposal_for_production', 0)
            ->setParameter('is_awaiting_contract', 1)
            ->andWhere('seller.id = ' . $seller_id);

        if ($role_id == 3) {
            $query->andWhere('seller.assign_agent_id = :agent_id');
//            $query->andWhere('pq.assign_agent_id = :agent_id');
            $query->setParameter('agent_id', $user_id);
        }

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getProposalForProductions($filter)
    {

        $authUser = JWTAuth::parseToken()->authenticate();

        $role_id = $authUser->getRoles()[0]->getId();
        $user_id = $authUser->getId();

        if ($filter['order'][0]['column'] == 1) {

            $orderbyclm = 'u.sku';
        }

        if ($filter['order'][0]['column'] == 2) {

            $orderbyclm = 'u.name';
        }

//        if ($filter['order'][0]['column'] == 3)
//        {
//            $orderbyclm = 'p.price';
//        }

        if ($filter['order'][0]['column'] == 4) {

            $orderbyclm = 'p.for_production_created_at';
        }

        if ($filter['order'][0]['column'] == 5) {

            $orderbyclm = 'p.for_production_created_at';
        }

        if ($filter['order'][0]['column'] == 6) {

            $orderbyclm = 'status.id';
        }

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('p.product_id', 'u')
//                ->leftjoin('p.assign_agent_id', 'a')
            ->leftjoin('u.sellerid', 's')
            ->where(
                $query->expr()->andX(
                    $query->expr()->neq('p.is_send_mail', '2'),
                    $query->expr()->eq('p.is_archived', ':is_archived'),
                    $query->expr()->in('p.is_proposal_for_production', ':is_proposal_for_production'),
                    $query->expr()->in('p.is_awaiting_contract', ':is_awaiting_contract')
                )
            )
            ->setParameter('is_archived', 0)
            ->setParameter('is_awaiting_contract', 1)
            ->setParameter('is_proposal_for_production', 0)
            ->andWhere('u.sellerid=' . $filter['id']);

        if ($role_id == 3) {
            $query->andWhere('s.assign_agent_id = :agent_id');
//            $query->andWhere('pq.assign_agent_id = :agent_id');
            $query->setParameter('agent_id', $user_id);
        }

        $total = $query->getQuery()->getSingleScalarResult();

        $query = $this->em->createQueryBuilder();

        $query->select(array('u.name', 'u.sku', 'p.price', 'p.storage_pricing', 'p.tlv_price', ' GROUP_CONCAT(images.name ORDER BY images.priority ASC) image', 'p.for_production_created_at', 'p.created_at as quote_created_at', 'p.copyright_created_at', 'p.approved_created_at', 'p.is_product_for_production', 'p.is_copyright', 'p.tlv_suggested_price_max', 'p.tlv_suggested_price_min', 'p.id', 'p.is_send_mail', 'p.images_from', 'status.id as status_id', 'status.value_text as status_value'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('u.product_pending_images', 'images')
//                ->leftjoin('p.assign_agent_id', 'a')
            ->leftjoin('u.sellerid', 's')
            ->setMaxResults($filter['length'])
            ->setFirstResult($filter['start'])
            ->where('u.sellerid =' . $filter['id'])
            ->andWhere(
                $query->expr()->andX(
                    $query->expr()->neq('p.is_send_mail', '2'),
                    $query->expr()->eq('p.is_archived', ':is_archived'),
                    $query->expr()->in('p.is_proposal_for_production', ':is_proposal_for_production'),
                    $query->expr()->in('p.is_awaiting_contract', ':is_awaiting_contract')
                )
            )
            ->andWhere(
                $query->expr()->orX(
                    $query->expr()->like('u.name', ':filter'),
                    $query->expr()->like('u.sku', ':filter'),
                    $query->expr()->like('status.value_text', ':filter')
                )
            )
            ->setParameter('filter', '%' . $filter['search']['value'] . '%')
            ->setParameter('is_archived', 0)
            ->setParameter('is_awaiting_contract', 1)
            ->setParameter('is_proposal_for_production', 0)
            ->orderBy($orderbyclm, $filter['order'][0]['dir'])
            ->groupBy('p.id');

        if ($role_id == 3) {
            $query->andWhere('s.assign_agent_id = :agent_id');
//            $query->andWhere('pq.assign_agent_id = :agent_id');
            $query->setParameter('agent_id', $user_id);
        }

        $qb = $query->getQuery();
        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getProposalForProductionsTotal($filter)
    {

        $authUser = JWTAuth::parseToken()->authenticate();

        $role_id = $authUser->getRoles()[0]->getId();
        $user_id = $authUser->getId();

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
//                ->leftjoin('p.assign_agent_id', 'a')
            ->leftjoin('u.sellerid', 's')
            ->where('u.sellerid =' . $filter['id'])
            ->andWhere(
                $query->expr()->andX(
                    $query->expr()->neq('p.is_send_mail', '2'),
                    $query->expr()->eq('p.is_archived', ':is_archived'),
                    $query->expr()->in('p.is_proposal_for_production', ':is_proposal_for_production'),
                    $query->expr()->in('p.is_awaiting_contract', ':is_awaiting_contract')
                )
            )
            ->andWhere(
                $query->expr()->orX(
                    $query->expr()->like('u.name', ':filter'),
                    $query->expr()->like('u.sku', ':filter'),
                    $query->expr()->like('status.value_text', ':filter')
                )
            )
            ->setParameter('filter', '%' . $filter['search']['value'] . '%')
            ->setParameter('is_archived', 0)
            ->setParameter('is_awaiting_contract', 1)
            ->setParameter('is_proposal_for_production', 0);

        if ($role_id == 3) {
            $query->andWhere('s.assign_agent_id = :agent_id');
//            $query->andWhere('pq.assign_agent_id = :agent_id');
            $query->setParameter('agent_id', $user_id);
        }

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getProductForAwaiting_contracts($filter)
    {

        $archived = 0;


        if ($filter['order'][0]['column'] == 1) {

            $orderbyclm = 'u.sku';
        }

        if ($filter['order'][0]['column'] == 2) {

            $orderbyclm = 'u.name';
        }

//        if ($filter['order'][0]['column'] == 3)
//        {
//            $orderbyclm = 'p.price';
//        }


        if ($filter['order'][0]['column'] == 4) {

            $orderbyclm = 'p.for_production_created_at';
        }

        if ($filter['order'][0]['column'] == 5) {

            $orderbyclm = 'p.for_production_created_at';
        }

        if ($filter['order'][0]['column'] == 6) {

            $orderbyclm = 'status.id';
        }

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('p.product_id', 'u')
            ->where(
                $query->expr()->andX(
                    $query->expr()->neq('p.is_send_mail', '2')
                    , $query->expr()->eq('p.is_archived', ':is_archived')
                    , $query->expr()->in('p.is_awaiting_contract', ':is_awaiting_contract')
                    , $query->expr()->isNull('p.status_quot')
                )
            )
            ->setParameter('is_archived', 0)
            ->setParameter('is_awaiting_contract', 0)

//                ->where('p.is_send_mail=1')
//                ->andWhere('p.is_archived =' . $archived)
            ->andWhere('u.sellerid=' . $filter['id']);


//        if (isset($filter['pass_from']) && $filter['pass_from'] == 'report')
//        {
//            //2 for reject
//            $status = [0, 2];
//        }
//        else
//        {
//            $status = [0];
//        }
//        $query->andWhere(
//                        $query->expr()->in('p.is_product_for_production', ':status')
//                )
//                ->setParameter('status', $status);
//
//        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated']))
//        {
//            $query->andWhere(
//                    $query->expr()->between('p.for_production_created_at', ':start', ':end'));
//            $query->setParameter('start', $filter['start_date_updated']);
//            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
//        }

        $total = $query->getQuery()->getSingleScalarResult();


        $query = $this->em->createQueryBuilder();

        $query->select(array('u.name', 'u.sku', 'p.stripe_subscriptions_id', 'p.storage_pricing', 'p.price', 'p.tlv_price', ' GROUP_CONCAT(images.name ORDER BY images.priority ASC) image', 'p.for_production_created_at', 'p.created_at as quote_created_at', 'p.copyright_created_at', 'p.approved_created_at', 'p.is_product_for_production', 'p.is_copyright', 'p.tlv_suggested_price_max', 'p.tlv_suggested_price_min', 'p.id', 'p.is_send_mail', 'p.images_from', 'status.id as status_id', 'status.value_text as status_value'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('u.product_pending_images', 'images')
            ->setMaxResults($filter['length'])
            ->setFirstResult($filter['start'])

//                ->where('p.is_archived =' . $archived)
            ->where('u.sellerid =' . $filter['id'])
            ->andWhere($query->expr()->andX(
                $query->expr()->neq('p.is_send_mail', '2')
                , $query->expr()->eq('p.is_archived', ':is_archived')
                , $query->expr()->in('p.is_awaiting_contract', ':is_awaiting_contract')
                , $query->expr()->isNull('p.status_quot')
            )
            )
            ->andWhere(
                $query->expr()->orX(
                    $query->expr()->like('u.name', ':filter')
                    , $query->expr()->like('u.sku', ':filter')

//                                , $query->expr()->like('p.price', ':filter')
                    , $query->expr()->like("concat(p.tlv_suggested_price_max,'/',p.tlv_suggested_price_min)", ':filter')
                    , $query->expr()->like('status.value_text', ':filter')
                )

//                        , $query->expr()->andX(
//                                $query->expr()->eq('p.is_send_mail', '1')
////                                , $query->expr()->eq('p.is_product_for_production', '0')
////                                , $query->expr()->eq('p.is_product_for_production', '0')
////                                , $query->expr()->eq('p.is_copyright', '0')
//                                , $query->expr()->eq('u.sellerid', $filter['id'])
////                                , $query->expr()->orX(
////                                        $query->expr()->eq('status.id', '17')
////                                        , $query->expr()->eq('status.id', '18'))
//                        )
            )
            ->setParameter('filter', '%' . $filter['search']['value'] . '%')
            ->setParameter('is_archived', 0)
            ->setParameter('is_awaiting_contract', 0)
            ->orderBy($orderbyclm, $filter['order'][0]['dir'])
            ->groupBy('p.id');


//        if (isset($filter['pass_from']) && $filter['pass_from'] == 'report')
//        {
//            //2 for reject
//            $status = [0, 2];
//        }
//        else
//        {
//            $status = [0];
//        }
//        $query->andWhere(
//                        $query->expr()->in('p.is_product_for_production', ':status')
//                )
//                ->setParameter('status', $status);
//
//
//
//        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated']))
//        {
//            $query->andWhere(
//                    $query->expr()->between('p.for_production_created_at', ':start', ':end'));
//            $query->setParameter('start', $filter['start_date_updated']);
//            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
//        }


        $qb = $query->getQuery();


        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getProductForAwaiting_contractsTotal($filter)
    {

        $archived = 0;

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')

//                ->where('p.is_archived =' . $archived)
            ->where('u.sellerid =' . $filter['id'])
            ->andWhere(
                $query->expr()->andX(
                    $query->expr()->neq('p.is_send_mail', '2')
                    , $query->expr()->eq('p.is_archived', ':is_archived')
                    , $query->expr()->in('p.is_awaiting_contract', ':is_awaiting_contract')
                    , $query->expr()->isNull('p.status_quot')
                )
            )
            ->andWhere(
                $query->expr()->orX(
                    $query->expr()->like('u.name', ':filter')
                    , $query->expr()->like('u.sku', ':filter')

//                                , $query->expr()->like('u.price', ':filter')
                    , $query->expr()->like("concat(p.tlv_suggested_price_max,'/',p.tlv_suggested_price_min)", ':filter')
                    , $query->expr()->like('status.value_text', ':filter')
                )

//                        , $query->expr()->andX(
//                                $query->expr()->eq('p.is_send_mail', '1')
////                                , $query->expr()->eq('p.is_product_for_production', '0')
////                                , $query->expr()->eq('p.is_product_for_production', '0')
////                                , $query->expr()->eq('p.is_copyright', '0')
//                                , $query->expr()->eq('u.sellerid', $filter['id'])
//                        )
            )
            ->setParameter('filter', '%' . $filter['search']['value'] . '%')
            ->setParameter('is_archived', 0)
            ->setParameter('is_awaiting_contract', 0);


//        if (isset($filter['pass_from']) && $filter['pass_from'] == 'report')
//        {
//            //2 for reject
//            $status = [0, 2];
//        }
//        else
//        {
//            $status = [0];
//        }
//        $query->andWhere(
//                        $query->expr()->in('p.is_product_for_production', ':status')
//                )
//                ->setParameter('status', $status);
//
//        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated']))
//        {
//            $query->andWhere(
//                    $query->expr()->between('p.for_production_created_at', ':start', ':end'));
//            $query->setParameter('start', $filter['start_date_updated']);
//            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
//        }

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getProductForPricings($filter)
    {

        $archived = 0;


        if ($filter['order'][0]['column'] == 1) {

            $orderbyclm = 'u.sku';
        }

        if ($filter['order'][0]['column'] == 2) {

            $orderbyclm = 'u.name';
        }

//        if ($filter['order'][0]['column'] == 3)
//        {
//            $orderbyclm = 'p.price';
//        }


        if ($filter['order'][0]['column'] == 4) {

            $orderbyclm = 'p.for_production_created_at';
        }

        if ($filter['order'][0]['column'] == 5) {

            $orderbyclm = 'p.for_production_created_at';
        }

        if ($filter['order'][0]['column'] == 6) {

            $orderbyclm = 'status.id';
        }

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.assign_agent_id', 'a')
            ->where($query->expr()->andX(
                $query->expr()->neq('p.is_send_mail', '2')
                , $query->expr()->eq('p.is_archived', ':is_archived')
                , $query->expr()->eq('p.is_proposal_for_production', ':is_proposal_for_production')
                , $query->expr()->eq('p.is_awaiting_contract', ':is_awaiting_contract')
                , $query->expr()->eq('p.is_product_for_pricing', ':is_product_for_pricing'))
            )
            ->setParameter('is_archived', 0)
            ->setParameter('is_product_for_pricing', 0)
            ->setParameter('is_awaiting_contract', 1)
            ->setParameter('is_proposal_for_production', 1)

//                ->where('p.is_send_mail=1')
//                ->andWhere('p.is_archived =' . $archived)
            ->andWhere('u.sellerid=' . $filter['id']);


//        if (isset($filter['pass_from']) && $filter['pass_from'] == 'report')
//        {
//            //2 for reject
//            $status = [0, 2];
//        }
//        else
//        {
//            $status = [0];
//        }
//        $query->andWhere(
//                        $query->expr()->in('p.is_product_for_production', ':status')
//                )
//                ->setParameter('status', $status);
//
//        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated']))
//        {
//            $query->andWhere(
//                    $query->expr()->between('p.for_production_created_at', ':start', ':end'));
//            $query->setParameter('start', $filter['start_date_updated']);
//            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
//        }

        $total = $query->getQuery()->getSingleScalarResult();


        $query = $this->em->createQueryBuilder();

        $query->select(array('u.name', 'u.sku', 'p.stripe_subscriptions_id', 'p.storage_pricing', 'p.price', 'p.tlv_price', ' GROUP_CONCAT(images.name ORDER BY images.priority ASC) image', 'p.for_production_created_at', 'p.created_at as quote_created_at', 'p.copyright_created_at', 'p.approved_created_at', 'p.is_product_for_production', 'p.is_copyright', 'p.tlv_suggested_price_max', 'p.tlv_suggested_price_min', 'p.id', 'p.is_send_mail', 'p.images_from', 'status.id as status_id', 'status.value_text as status_value', 'CONCAT(a.firstname,\' \',a.lastname) AS agent_name'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('u.product_pending_images', 'images')
            ->leftjoin('p.assign_agent_id', 'a')
            ->setMaxResults($filter['length'])
            ->setFirstResult($filter['start'])

//                ->where('p.is_archived =' . $archived)
            ->where('u.sellerid =' . $filter['id'])
            ->andWhere(
                $query->expr()->andX(
                    $query->expr()->neq('p.is_send_mail', '2')
                    , $query->expr()->eq('p.is_archived', ':is_archived')
                    , $query->expr()->eq('p.is_proposal_for_production', ':is_proposal_for_production')
                    , $query->expr()->eq('p.is_awaiting_contract', ':is_awaiting_contract')
                    , $query->expr()->eq('p.is_product_for_pricing', ':is_product_for_pricing')
                )
            )
            ->andWhere(
                $query->expr()->orX(
                    $query->expr()->like('u.name', ':filter')
                    , $query->expr()->like('u.sku', ':filter')

//                                , $query->expr()->like('p.price', ':filter')
                    , $query->expr()->like("concat(p.tlv_suggested_price_max,'/',p.tlv_suggested_price_min)", ':filter')
                    , $query->expr()->like('status.value_text', ':filter')
                )

//                        , $query->expr()->andX(
//                                $query->expr()->eq('p.is_send_mail', '1')
////                                , $query->expr()->eq('p.is_product_for_production', '0')
////                                , $query->expr()->eq('p.is_product_for_production', '0')
////                                , $query->expr()->eq('p.is_copyright', '0')
//                                , $query->expr()->eq('u.sellerid', $filter['id'])
////                                , $query->expr()->orX(
////                                        $query->expr()->eq('status.id', '17')
////                                        , $query->expr()->eq('status.id', '18'))
//                        )
            )
            ->setParameter('filter', '%' . $filter['search']['value'] . '%')
            ->setParameter('is_archived', 0)
            ->setParameter('is_awaiting_contract', 1)
            ->setParameter('is_proposal_for_production', 1)
            ->setParameter('is_product_for_pricing', 0)
            ->orderBy($orderbyclm, $filter['order'][0]['dir'])
            ->groupBy('p.id');


//        if (isset($filter['pass_from']) && $filter['pass_from'] == 'report')
//        {
//            //2 for reject
//            $status = [0, 2];
//        }
//        else
//        {
//            $status = [0];
//        }
//        $query->andWhere(
//                        $query->expr()->in('p.is_product_for_production', ':status')
//                )
//                ->setParameter('status', $status);
//
//
//
//        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated']))
//        {
//            $query->andWhere(
//                    $query->expr()->between('p.for_production_created_at', ':start', ':end'));
//            $query->setParameter('start', $filter['start_date_updated']);
//            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
//        }


        $qb = $query->getQuery();


        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getProductForPricingsTotal($filter)
    {

        $archived = 0;

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('p.assign_agent_id', 'a')
//                ->where('p.is_archived =' . $archived)
            ->where('u.sellerid =' . $filter['id'])
            ->andWhere(
                $query->expr()->andX(
                    $query->expr()->neq('p.is_send_mail', '2')
                    , $query->expr()->eq('p.is_archived', ':is_archived')
                    , $query->expr()->in('p.is_proposal_for_production', ':is_proposal_for_production')
                    , $query->expr()->in('p.is_product_for_pricing', ':is_product_for_pricing')
                    , $query->expr()->in('p.is_awaiting_contract', ':is_awaiting_contract')
                )
            )
            ->andWhere(
                $query->expr()->orX(
                    $query->expr()->like('u.name', ':filter')
                    , $query->expr()->like('u.sku', ':filter')

//                                , $query->expr()->like('u.price', ':filter')
                    , $query->expr()->like("concat(p.tlv_suggested_price_max,'/',p.tlv_suggested_price_min)", ':filter')
                    , $query->expr()->like('status.value_text', ':filter')
                )

//                        , $query->expr()->andX(
//                                $query->expr()->eq('p.is_send_mail', '1')
////                                , $query->expr()->eq('p.is_product_for_production', '0')
////                                , $query->expr()->eq('p.is_product_for_production', '0')
////                                , $query->expr()->eq('p.is_copyright', '0')
//                                , $query->expr()->eq('u.sellerid', $filter['id'])
//                        )
            )
            ->setParameter('filter', '%' . $filter['search']['value'] . '%')
            ->setParameter('is_archived', 0)
            ->setParameter('is_product_for_pricing', 0)
            ->setParameter('is_proposal_for_production', 1)
            ->setParameter('is_awaiting_contract', 1);


//        if (isset($filter['pass_from']) && $filter['pass_from'] == 'report')
//        {
//            //2 for reject
//            $status = [0, 2];
//        }
//        else
//        {
//            $status = [0];
//        }
//        $query->andWhere(
//                        $query->expr()->in('p.is_product_for_production', ':status')
//                )
//                ->setParameter('status', $status);
//
//        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated']))
//        {
//            $query->andWhere(
//                    $query->expr()->between('p.for_production_created_at', ':start', ':end'));
//            $query->setParameter('start', $filter['start_date_updated']);
//            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
//        }

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getProductReportForAwaitingContract($filter)
    {

        $archived = 0;

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('p.product_id', 'u')
            ->where(
                $query->expr()->andX(
                    $query->expr()->eq('p.is_archived', ':is_archived')
                    , $query->expr()->in('p.is_awaiting_contract', ':is_awaiting_contract')
                )
            )
            ->setParameter('is_archived', 0)
            ->setParameter('is_awaiting_contract', 0)
            ->andWhere('u.sellerid=' . $filter['id']);

        $total = $query->getQuery()->getSingleScalarResult();


        $query = $this->em->createQueryBuilder();

        $query->select(array('u.name', 'u.sku', 'p.quantity', 'p.note', 'p.stripe_subscriptions_id', 'p.storage_pricing', 'p.price', ' GROUP_CONCAT(images.name ORDER BY images.priority ASC) image', 'p.for_production_created_at', 'p.created_at as quote_created_at', 'p.copyright_created_at', 'p.approved_created_at', 'p.is_product_for_production', 'p.is_copyright', 'p.tlv_suggested_price_max', 'p.tlv_suggested_price_min', 'p.id', 'p.is_send_mail', 'p.images_from', 'status.id as status_id', 'status.value_text as status_value'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('u.product_pending_images', 'images')
            ->where('u.sellerid =' . $filter['id'])
            ->andWhere(
                $query->expr()->andX(
                    $query->expr()->eq('p.is_archived', ':is_archived')
                    , $query->expr()->in('p.is_awaiting_contract', ':is_awaiting_contract')
                )
            )
            ->setParameter('is_archived', 0)
            ->setParameter('is_awaiting_contract', 0)
            ->groupBy('p.id');

        $qb = $query->getQuery();
        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getProductReportForAwaitingContractTotal($filter)
    {

        $archived = 0;

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->where('u.sellerid =' . $filter['id'])
            ->andWhere($query->expr()->andX(
                $query->expr()->eq('p.is_archived', ':is_archived')
                , $query->expr()->in('p.is_awaiting_contract', ':is_awaiting_contract')
            )
            )
            ->setParameter('is_archived', 0)
            ->setParameter('is_awaiting_contract', 0);


        return $query->getQuery()->getSingleScalarResult();
    }

    public function getProductReportForPricings($filter)
    {

        $archived = 0;

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('p.product_id', 'u')
            ->where(
                $query->expr()->andX(
                    $query->expr()->eq('p.is_archived', ':is_archived')
                    , $query->expr()->in('p.is_proposal_for_production', ':is_proposal_for_production')
                    , $query->expr()->in('p.is_product_for_pricing', ':is_product_for_pricing')
                    , $query->expr()->in('p.is_awaiting_contract', ':is_awaiting_contract')
                )
            )
            ->setParameter('is_archived', 0)
            ->setParameter('is_product_for_pricing', 0)
            ->setParameter('is_proposal_for_production', 1)
            ->setParameter('is_awaiting_contract', 1)
            ->andWhere('u.sellerid=' . $filter['id']);

        $total = $query->getQuery()->getSingleScalarResult();


        $query = $this->em->createQueryBuilder();

        $query->select(array('u.name', 'u.sku', 'p.quantity', 'p.note', 'p.stripe_subscriptions_id', 'p.storage_pricing', 'p.price', ' GROUP_CONCAT(images.name ORDER BY images.priority ASC) image', 'p.for_production_created_at', 'p.created_at as quote_created_at', 'p.copyright_created_at', 'p.approved_created_at', 'p.is_product_for_production', 'p.is_copyright', 'p.tlv_suggested_price_max', 'p.tlv_suggested_price_min', 'p.id', 'p.is_send_mail', 'p.images_from', 'status.id as status_id', 'status.value_text as status_value'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('u.product_pending_images', 'images')
            ->where('u.sellerid =' . $filter['id'])
            ->andWhere(
                $query->expr()->andX(
                    $query->expr()->eq('p.is_archived', ':is_archived')
                    , $query->expr()->in('p.is_proposal_for_production', ':is_proposal_for_production')
                    , $query->expr()->in('p.is_product_for_pricing', ':is_product_for_pricing')
                    , $query->expr()->in('p.is_awaiting_contract', ':is_awaiting_contract')
                )
            )
            ->setParameter('is_archived', 0)
            ->setParameter('is_awaiting_contract', 1)
            ->setParameter('is_product_for_pricing', 0)
            ->setParameter('is_proposal_for_production', 1)
            ->groupBy('p.id');

        $qb = $query->getQuery();
        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getProductReportForPricingsTotal($filter)
    {

        $archived = 0;

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->where('u.sellerid =' . $filter['id'])
            ->andWhere($query->expr()->andX(
                $query->expr()->eq('p.is_archived', ':is_archived')
                , $query->expr()->in('p.is_proposal_for_production', ':is_proposal_for_production')
                , $query->expr()->in('p.is_product_for_pricing', ':is_product_for_pricing')
                , $query->expr()->in('p.is_awaiting_contract', ':is_awaiting_contract')
            )
            )
            ->setParameter('is_archived', 0)
            ->setParameter('is_product_for_pricing', 0)
            ->setParameter('is_proposal_for_production', 1)
            ->setParameter('is_awaiting_contract', 1);


        return $query->getQuery()->getSingleScalarResult();
    }

    public static function copyWriterFilter($query)
    {

        $auth_user = JWTAuth::parseToken()->authenticate();


        //5 for Copywriter

        if ($auth_user->getRoles()[0]->getId() == 5) {

            $query->leftJoin('p.copywriter_id', 'pc');

            $query->andWhere(
                $query->expr()->andX(
                    $query->expr()->eq('pc.id', ':copywriter_id')
                )
            );

            $query->setParameter('copywriter_id', $auth_user->getId());
        }

        return $query;
    }

    public function getStorageProductReport($filter)
    {

        if ($filter['order'][0]['column'] == 0) {
            $orderbyclm = 'seller.firstname';
        }

        if ($filter['order'][0]['column'] == 1) {
            $orderbyclm = 'u.name';
        }

        if ($filter['order'][0]['column'] == 2) {
            $orderbyclm = 'u.sku';
        }

        if ($filter['order'][0]['column'] == 3) {
            $orderbyclm = 'p.storage_pricing';
        }

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->where('p.is_archived = 0')
            ->andWhere('p.is_storage_proposal=1');

//        $query->andWhere(
//                        $query->expr()->in('status.id', ':status')
//                )
//                ->setParameter('status', 17);


        $total = $query->getQuery()->getSingleScalarResult();

        $query = $this->em->createQueryBuilder();

        $query->select(array('p.id', 'u.name', 'u.sku', 'p.storage_pricing', 'CONCAT(seller.firstname,\' \',seller.lastname) AS seller_name'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('u.sellerid', 'seller')
            ->setMaxResults($filter['length'])
            ->setFirstResult($filter['start'])
            ->where(
                $query->expr()->orX(
                    $query->expr()->like('u.name', ':filter')
                    , $query->expr()->like('u.sku', ':filter')
                    , $query->expr()->like('p.storage_pricing', ':filter')
                    , $query->expr()->like('seller.firstname', ':filter')
                    , $query->expr()->like('seller.lastname', ':filter')
                )
            )
            ->andWhere('p.is_archived = 0')
            ->andWhere('p.is_storage_proposal=1')
            ->setParameter('filter', '%' . $filter['search']['value'] . '%')
            ->orderBy($orderbyclm, $filter['order'][0]['dir']);
//
//        $query->andWhere(
//                        $query->expr()->in('status.id', ':status')
//                )
//                ->setParameter('status', 17);


        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getStorageProductReportTotal($filter)
    {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('u.sellerid', 'seller')
            ->where(
                $query->expr()->orX(
                    $query->expr()->like('u.name', ':filter')
                    , $query->expr()->like('u.sku', ':filter')
                    , $query->expr()->like('p.storage_pricing', ':filter')
                    , $query->expr()->like('seller.firstname', ':filter')
                    , $query->expr()->like('seller.lastname', ':filter')
                )
            )
            ->andWhere('p.is_archived = 0')
            ->andWhere('p.is_storage_proposal=1')
            ->setParameter('filter', '%' . $filter['search']['value'] . '%');

//        $query->andWhere(
//                        $query->expr()->in('status.id', ':status')
//                )
//                ->setParameter('status', 17);
        return $query->getQuery()->getSingleScalarResult();
    }

    public function getProductsWithAssignedAgents($filters)
    {

        switch ($filters['order'][0]['column']) {
            case 1:
                $orderby = 'seller_name';
                break;
            case 2:
                $orderby = 'product_name';
                break;
            case 3:
                $orderby = 'product_sku';
                break;
            case 0:
            default:
                $orderby = 'agent_name';
        }

        $totalQuery = $this->em->createQueryBuilder();

        $totalQuery->select($totalQuery->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('p.assign_agent_id', 'a')
            ->leftjoin('u.sellerid', 'seller')
            ->where('p.assign_agent_id IS NOT NULL')
            ->andWhere('p.is_archived = 0');

        $total = $totalQuery->getQuery()->getSingleScalarResult();

        $query = $this->em->createQueryBuilder();

        $query->select(array('p.id', 'seller.displayname as seller_name', 'CONCAT(a.firstname,\' \',a.lastname) AS agent_name', 'u.name as product_name', 'u.sku as product_sku'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('p.assign_agent_id', 'a')
            ->leftjoin('u.sellerid', 'seller')
            ->where('p.assign_agent_id IS NOT NULL')
            ->andWhere('p.is_archived = 0')
            ->andWhere(
                $query->expr()->orX(
                    $query->expr()->like('a.firstname', ':filter'),
                    $query->expr()->like('a.lastname', ':filter'),
                    $query->expr()->like('u.name', ':filter'),
                    $query->expr()->like('seller.displayname', ':filter'),
                    $query->expr()->like('u.sku', ':filter')
                )
            )
            ->setMaxResults($filters['length'])
            ->setFirstResult($filters['start'])
            ->orderBy($orderby, $filters['order'][0]['dir'])
            ->setParameter('filter', '%' . $filters['search']['value'] . '%');

        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return ['data' => $data, 'total' => $total];
    }

    public function getProductsWithAssignedAgentsTotal($filters)
    {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('p.assign_agent_id', 'a')
            ->leftjoin('u.sellerid', 'seller')
            ->where('p.assign_agent_id IS NOT NULL')
            ->andWhere('p.is_archived = 0')
            ->andWhere(
                $query->expr()->orX(
                    $query->expr()->like('a.firstname', ':filter'),
                    $query->expr()->like('a.lastname', ':filter'),
                    $query->expr()->like('u.name', ':filter'),
                    $query->expr()->like('seller.displayname', ':filter'),
                    $query->expr()->like('u.sku', ':filter')
                )
            )
            ->setParameter('filter', '%' . $filters['search']['value'] . '%');

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getProductsWithStorageAggrement()
    {
        $query = $this->em->createQueryBuilder();

        $query->select(array('p.id', 'u.name', 'u.sku', 'p.storage_pricing', 'CONCAT(seller.firstname,\' \',seller.lastname) AS seller_name'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftjoin('p.product_id', 'u')
            ->leftjoin('p.status_quot', 'status')
            ->leftjoin('u.sellerid', 'seller')
            ->where('p.is_archived = 0')
            ->andWhere('p.is_storage_proposal=1');

        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return $data;
    }

    public function getAllProductQuotationOfWpSellerOfAwaitingContractStage($wp_seller_id, $count = false)
    {

        $queryBuilder = $this->em->createQueryBuilder();

        if ($count) {
            $queryBuilder->select($queryBuilder->expr()->countDistinct('pq.id'));
        } else {
            $queryBuilder->select('pq', 'p', 'pi');
        }

        $query = $queryBuilder->from('App\Entities\Products_quotation', 'pq')
            ->leftJoin('pq.product_id', 'p')
            ->leftJoin('p.product_pending_images', 'pi')
            ->leftJoin('p.sellerid', 's')
            ->where('s.wp_seller_id = :wp_seller_id')
            ->andWhere(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->neq('pq.is_send_mail', '2'),
                    $queryBuilder->expr()->eq('pq.is_archived', ':is_archived'),
                    $queryBuilder->expr()->eq('pq.is_awaiting_contract', ':is_awaiting_contract'),
                    $queryBuilder->expr()->isNull('pq.status_quot')
                )
            )
            ->setParameter('is_archived', 0)
            ->setParameter('is_awaiting_contract', 0)
            ->setParameter('wp_seller_id', $wp_seller_id)
            ->getQuery();

        if ($count) {
            $data = $query->getSingleScalarResult();
        } else {
            $data = $query->getResult(Query::HYDRATE_ARRAY);
        }

        return $data;
    }

    public function getAllProductQuotationOfWpSellerOfForProductionStage($wp_seller_id, $count = false)
    {

        $queryBuilder = $this->em->createQueryBuilder();

        if ($count) {
            $queryBuilder->select($queryBuilder->expr()->countDistinct('pq.id'));
        } else {
            $queryBuilder->select('pq', 'p', 'pi');
        }

        $query = $queryBuilder->from('App\Entities\Products_quotation', 'pq')
            ->leftJoin('pq.product_id', 'p')
            ->leftJoin('p.product_pending_images', 'pi')
            ->leftJoin('p.sellerid', 's')
            ->where('s.wp_seller_id = :wp_seller_id')
            ->andWhere(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->neq('pq.is_send_mail', '2'),
                    $queryBuilder->expr()->eq('pq.is_archived', ':is_archived'),
                    $queryBuilder->expr()->eq('pq.is_awaiting_contract', ':is_awaiting_contract'),
                    $queryBuilder->expr()->eq('pq.is_proposal_for_production', ':is_proposal_for_production')
                )
            )
            ->setParameter('is_archived', 0)
            ->setParameter('is_awaiting_contract', 1)
            ->setParameter('is_proposal_for_production', 0)
            ->setParameter('wp_seller_id', $wp_seller_id)
            ->getQuery();

        if ($count) {
            $data = $query->getSingleScalarResult();
        } else {
            $data = $query->getResult(Query::HYDRATE_ARRAY);
        }

        return $data;
    }

    public function getAllProductQuotationOfWpSellerOfForPricingStage($wp_seller_id, $count = false)
    {

        $queryBuilder = $this->em->createQueryBuilder();

        if ($count) {
            $queryBuilder->select($queryBuilder->expr()->countDistinct('pq.id'));
        } else {
            $queryBuilder->select('pq', 'p', 'pi');
        }

        $query = $queryBuilder->from('App\Entities\Products_quotation', 'pq')
            ->leftJoin('pq.product_id', 'p')
            ->leftJoin('p.product_pending_images', 'pi')
            ->leftJoin('p.sellerid', 's')
            ->where('s.wp_seller_id = :wp_seller_id')
            ->andWhere(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->neq('pq.is_send_mail', '2'),
                    $queryBuilder->expr()->eq('pq.is_archived', ':is_archived'),
                    $queryBuilder->expr()->eq('pq.is_awaiting_contract', ':is_awaiting_contract'),
                    $queryBuilder->expr()->eq('pq.is_proposal_for_production', ':is_proposal_for_production'),
                    $queryBuilder->expr()->eq('pq.is_product_for_pricing', ':is_product_for_pricing')
                )
            )
            ->setParameter('is_archived', 0)
            ->setParameter('is_awaiting_contract', 1)
            ->setParameter('is_proposal_for_production', 1)
            ->setParameter('is_product_for_pricing', 0)
            ->setParameter('wp_seller_id', $wp_seller_id)
            ->getQuery();

        if ($count) {
            $data = $query->getSingleScalarResult();
        } else {
            $data = $query->getResult(Query::HYDRATE_ARRAY);
        }

        return $data;
    }

    public function getAllProductQuotationOfWpSellerOfApprovalStage($wp_seller_id, $count = false)
    {

        $queryBuilder = $this->em->createQueryBuilder();

        if ($count) {
            $queryBuilder->select($queryBuilder->expr()->countDistinct('pq.id'));
        } else {
            $queryBuilder->select('pq', 'p', 'pi');
        }

        $query = $queryBuilder->from('App\Entities\Products_quotation', 'pq')
            ->leftJoin('pq.product_id', 'p')
            ->leftJoin('p.product_pending_images', 'pi')
            ->leftJoin('p.sellerid', 's')
            ->where('s.wp_seller_id = :wp_seller_id')
            ->andWhere(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->neq('pq.is_send_mail', '2'),
                    $queryBuilder->expr()->eq('pq.is_archived', ':is_archived'),
                    $queryBuilder->expr()->eq('pq.is_awaiting_contract', ':is_awaiting_contract'),
                    $queryBuilder->expr()->eq('pq.is_proposal_for_production', ':is_proposal_for_production'),
                    $queryBuilder->expr()->eq('pq.is_product_for_pricing', ':is_product_for_pricing')
                )
            )
            ->andWhere($queryBuilder->expr()->in('pq.status_quot', ':status'))
            ->setParameter('is_archived', 0)
            ->setParameter('is_awaiting_contract', 1)
            ->setParameter('is_proposal_for_production', 1)
            ->setParameter('is_product_for_pricing', 1)
            ->setParameter('wp_seller_id', $wp_seller_id)
            ->setParameter('status', [17, 83])
            ->getQuery();

        if ($count) {
            $data = $query->getSingleScalarResult();
        } else {
            $data = $query->getResult(Query::HYDRATE_ARRAY);
        }

        return $data;
    }

    public function getAllProductQuotationOfWpSellerOfRejected($wp_seller_id, $count = false)
    {

        $queryBuilder = $this->em->createQueryBuilder();

        if ($count) {
            $queryBuilder->select($queryBuilder->expr()->countDistinct('pq.id'));
        } else {
            $queryBuilder->select('pq', 'p', 'pi');
        }

        $query = $queryBuilder->from('App\Entities\Products_quotation', 'pq')
            ->leftJoin('pq.product_id', 'p')
            ->leftJoin('p.product_pending_images', 'pi')
            ->leftJoin('p.sellerid', 's')
            ->where('s.wp_seller_id = :wp_seller_id')
            ->andWhere('pq.is_send_mail = :reject')
            ->andWhere('pq.is_archived = :is_archived')
            ->setParameter('reject', 2)
            ->setParameter('is_archived', 0)
            ->setParameter('wp_seller_id', $wp_seller_id)
            ->getQuery();

        if ($count) {
            $data = $query->getSingleScalarResult();
        } else {
            $data = $query->getResult(Query::HYDRATE_ARRAY);
        }

        return $data;
    }

    public function getAllProductQuotationOfWpSellerOfStorage($wp_seller_id, $count = false)
    {

        $queryBuilder = $this->em->createQueryBuilder();

        if ($count) {
            $queryBuilder->select($queryBuilder->expr()->countDistinct('pq.id'));
        } else {
            $queryBuilder->select('pq', 'p', 'pi');
        }

//        $query = $queryBuilder->from('App\Entities\Products_quotation', 'pq')
//                ->leftJoin('pq.product_id', 'p')
//                ->leftJoin('p.product_pending_images', 'pi')
//                ->leftJoin('p.sellerid', 's')
//                ->where('s.wp_seller_id = :wp_seller_id')
//                ->andWhere('pq.is_send_mail != :reject')
//                ->andWhere('pq.is_archived = :is_archived')
//                ->andWhere('pq.is_storage_proposal = :is_storage_proposal')
//                ->setParameter('reject', 2)
//                ->setParameter('is_archived', 0)
//                ->setParameter('is_storage_proposal', 1)
//                ->setParameter('wp_seller_id', $wp_seller_id)
//                ->getQuery();

        $query = $queryBuilder->from('App\Entities\Products_quotation', 'pq')
            ->leftJoin('pq.product_id', 'p')
            ->leftJoin('p.product_pending_images', 'pi')
            ->leftJoin('p.sellerid', 's')
            ->where('s.wp_seller_id = :wp_seller_id')
            ->setParameter('wp_seller_id', $wp_seller_id)
            ->getQuery();

        if ($count) {
            $data = $query->getSingleScalarResult();
        } else {
            $data = $query->getResult(Query::HYDRATE_ARRAY);
        }

        return $data;
    }

    public function getProductInfoFromWpProductIds($wpProductIds)
    {
        $queryBuilder = $this->em->createQueryBuilder();

        $query = $queryBuilder->select('pq.id', 'pq.wp_product_id', 'p.id', 'p.sku', 'p.name', 's.wp_seller_id', 's.displayname')
            ->from('App\Entities\Products_quotation', 'pq')
            ->leftJoin('pq.product_id', 'p')
            ->leftJoin('p.sellerid', 's')
            ->where('pq.wp_product_id in (:wp_product_ids)')
            ->setParameter('wp_product_ids', $wpProductIds)
            ->getQuery();

        $data = $query->getResult(Query::HYDRATE_ARRAY);
        return $data;
    }

    public function getAllSyncProduct($filter)
    {


        if (!empty($filter['order'])) {
            if ($filter['order'][0]['column'] == 0) {

                $orderbyclm = 'u.sku';
            } else if ($filter['order'][0]['column'] == 1) {

                $orderbyclm = 'u.name';
            } else if ($filter['order'][0]['column'] == 2) {

                $orderbyclm = 'sub_cat.sub_category_name';
            } else if ($filter['order'][0]['column'] == 3) {

                $orderbyclm = 'p.tlv_price';
            } else if ($filter['order'][0]['column'] == 4) {

                $orderbyclm = 'p.wp_sale_price';
            } else if ($filter['order'][0]['column'] == 5) {

                $orderbyclm = 'seller.firstname';
            } else if ($filter['order'][0]['column'] == 6) {

                $orderbyclm = 'p.wp_published_date';
            } else if ($filter['order'][0]['column'] == 7) {

                $orderbyclm = 'u.flat_rate_packaging_fee';
            } else if ($filter['order'][0]['column'] == 8) {

                $orderbyclm = 'p.wp_stock_status';
            } else {
                $orderbyclm = '';
            }
        } else {
            $orderbyclm = '';
        }


        $queryBuilder = $this->em->createQueryBuilder();

        $query = $queryBuilder->select($queryBuilder->expr()->countDistinct('p.id'))
            ->from('App\Entities\Products_quotation', 'p')
            ->leftJoin('p.status_quot', 'v')
            ->leftJoin('p.product_id', 'u')
            ->leftjoin('u.sellerid', 'seller')
            ->leftJoin('u.brand', 'b')
            ->leftJoin('u.product_category', 'sub_cat')
            ->leftJoin('App\Entities\Orders', 'o', \Doctrine\ORM\Query\Expr\Join::WITH, 'p.wp_product_id=o.product_id')
            ->where('v.id = :id')
            ->andWhere('p.wp_product_id != :wp_product_id')
            ->setParameter('wp_product_id', '')
            ->setParameter('id', 18)->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('u.sku', ':filter'),
                    $queryBuilder->expr()->like('u.name', ':filter'),
                    $queryBuilder->expr()->like('seller.firstname', ':filter'),
                    $queryBuilder->expr()->like('seller.lastname', ':filter'),
                    $queryBuilder->expr()->like('sub_cat.sub_category_name', ':filter'),
                    $queryBuilder->expr()->like('p.tlv_price', ':filter'),
                    $queryBuilder->expr()->like('p.wp_sale_price', ':filter'),
                    $queryBuilder->expr()->like('p.wp_published_date', ':filter'),
                    $queryBuilder->expr()->like('u.flat_rate_packaging_fee', ':filter'),
                    $queryBuilder->expr()->like('p.wp_stock_status', ':filter')
                )
            )
            ->setParameter('filter', '%' . $filter['search']['value'] . '%');

        if (isset($filter['sellerid']) && $filter['sellerid'] != '0') {
            $query = $query->andWhere('u.sellerid = :seller_id');

            $query = $query->setParameter('seller_id', $filter['sellerid']);
        }

        if (isset($filter['brand']) && $filter['brand'] != '0') {
            $query = $query->andWhere('u.brand = :brand');

            $query = $query->setParameter('brand', $filter['brand']);
        }

        if (isset($filter['category']) && count($filter['category']) > 0) {

            if (isset($filter['subcategoryid']) && count($filter['subcategoryid']) > 0) {

            } else {
                $query = $query->andWhere($queryBuilder->expr()->in('sub_cat.id', ':category'))
                    ->setParameter('category', $filter['category']);
            }
        }

        if (isset($filter['subcategoryid']) && count($filter['subcategoryid']) > 0) {

            $query = $query->andWhere($queryBuilder->expr()->in('sub_cat.id', ':subcategoryid'))
                ->setParameter('subcategoryid', $filter['subcategoryid']);
        }


        if (isset($filter['ship_size']) && $filter['ship_size'] != '0') {
            $query = $query->andWhere('u.ship_size = :ship_size');

            $query = $query->setParameter('ship_size', $filter['ship_size']);
        }

        if (isset($filter['wp_flat_rate']) && $filter['wp_flat_rate'] != 'all') {
            $query = $query->andWhere('p.wp_flat_rate = :wp_flat_rate');

            $query = $query->setParameter('wp_flat_rate', $filter['wp_flat_rate']);
        }

        if (isset($filter['wp_stock_status']) && $filter['wp_stock_status'] != '0') {
            $query = $query->andWhere('p.wp_stock_status = :wp_stock_status');

            $query = $query->setParameter('wp_stock_status', $filter['wp_stock_status']);
        }

        if (isset($filter['start_date_new']) && isset($filter['end_date_new']) && $filter['start_date_new'] != null && $filter['end_date_new'] != null && $filter['start_date_new'] != 'Invalid date' && $filter['end_date_new'] != 'Invalid date') {


            $query = $query->andwhere('p.wp_published_date BETWEEN :start AND :end')
                ->setParameter('start', $filter['start_date_new'])
                ->setParameter('end', $filter['end_date_new']);
        }


        if (isset($filter['start_date_order']) && isset($filter['end_date_order']) && $filter['start_date_order'] != null && $filter['end_date_order'] != null && $filter['start_date_order'] != 'Invalid date' && $filter['end_date_order'] != 'Invalid date') {

            $query = $query->andwhere('o.date_created BETWEEN :startorder AND :endorder')
                ->setParameter('startorder', $filter['start_date_order'])
                ->setParameter('endorder', $filter['end_date_order']);
        }


        $total = $query->getQuery()->getSingleScalarResult();


        $query = $this->em->createQueryBuilder()
            ->select('p.id')
            ->from('App\Entities\Products_quotation', 'p')
            ->leftJoin('p.status_quot', 'v')
            ->leftJoin('p.product_id', 'u')
            ->leftjoin('u.sellerid', 'seller')
            ->leftJoin('u.brand', 'b')
            ->leftJoin('u.product_category', 'sub_cat')
            ->leftJoin('App\Entities\Orders', 'o', \Doctrine\ORM\Query\Expr\Join::WITH, 'p.wp_product_id=o.product_id')
            ->where('v.id = :id')
            ->andWhere('p.wp_product_id != :wp_product_id')
            ->setParameter('wp_product_id', '')
            ->setParameter('id', 18)
            ->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('u.sku', ':filter'),
                    $queryBuilder->expr()->like('u.name', ':filter'),
                    $queryBuilder->expr()->like('seller.firstname', ':filter'),
                    $queryBuilder->expr()->like('seller.lastname', ':filter'),
                    $queryBuilder->expr()->like('sub_cat.sub_category_name', ':filter'),
                    $queryBuilder->expr()->like('p.tlv_price', ':filter'),
                    $queryBuilder->expr()->like('p.wp_sale_price', ':filter'),
                    $queryBuilder->expr()->like('p.wp_published_date', ':filter'),
                    $queryBuilder->expr()->like('u.flat_rate_packaging_fee', ':filter'),
                    $queryBuilder->expr()->like('p.wp_stock_status', ':filter')
                )
            )
            ->setParameter('filter', '%' . $filter['search']['value'] . '%');


        if (isset($filter['sellerid']) && $filter['sellerid'] != '0') {
            $query = $query->andWhere('u.sellerid = :seller_id');

            $query = $query->setParameter('seller_id', $filter['sellerid']);
        }

        if (isset($filter['brand']) && $filter['brand'] != '0') {
            $query = $query->andWhere('u.brand = :brand');

            $query = $query->setParameter('brand', $filter['brand']);
        }

        if (isset($filter['category']) && count($filter['category']) > 0) {

            if (isset($filter['subcategoryid']) && count($filter['subcategoryid']) > 0) {

            } else {
                $query = $query->andWhere($queryBuilder->expr()->in('sub_cat.id', ':category'))
                    ->setParameter('category', $filter['category']);
            }
        }

        if (isset($filter['subcategoryid']) && count($filter['subcategoryid']) > 0) {

            $query = $query->andWhere($queryBuilder->expr()->in('sub_cat.id', ':subcategoryid'))
                ->setParameter('subcategoryid', $filter['subcategoryid']);
        }

        if (isset($filter['ship_size']) && $filter['ship_size'] != '0') {
            $query = $query->andWhere('u.ship_size = :ship_size');

            $query = $query->setParameter('ship_size', $filter['ship_size']);
        }

        if (isset($filter['wp_flat_rate']) && $filter['wp_flat_rate'] != 'all') {

            $query = $query->andWhere('p.wp_flat_rate = :wp_flat_rate');

            $query = $query->setParameter('wp_flat_rate', $filter['wp_flat_rate']);
        }
        if (isset($filter['wp_stock_status']) && $filter['wp_stock_status'] != '0') {
            $query = $query->andWhere('p.wp_stock_status = :wp_stock_status');

            $query = $query->setParameter('wp_stock_status', $filter['wp_stock_status']);
        }


        if (isset($filter['start_date_new']) && isset($filter['end_date_new']) && $filter['start_date_new'] != null && $filter['end_date_new'] != null && $filter['start_date_new'] != 'Invalid date' && $filter['end_date_new'] != 'Invalid date') {


            $query = $query->andwhere('p.wp_published_date BETWEEN :start AND :end')
                ->setParameter('start', $filter['start_date_new'])
                ->setParameter('end', $filter['end_date_new']);
        }

        if (isset($filter['start_date_order']) && isset($filter['end_date_order']) && $filter['start_date_order'] != null && $filter['end_date_order'] != null && $filter['start_date_order'] != 'Invalid date' && $filter['end_date_order'] != 'Invalid date') {

            $query = $query->andwhere('o.date_created BETWEEN :startorder AND :endorder')
                ->setParameter('startorder', $filter['start_date_order'])
                ->setParameter('endorder', $filter['end_date_order']);
        }


        if ($orderbyclm != '') {
            $query = $query->orderBy($orderbyclm, $filter['order'][0]['dir'])
                ->setMaxResults($filter['length'])
                ->setFirstResult($filter['start'])
                ->groupBy('p.id')
                ->getQuery();
        } else {
            $query = $query->orderBy('u.created_at', 'desc')
                ->setMaxResults($filter['length'])
                ->setFirstResult($filter['start'])
                ->groupBy('p.id')
                ->getQuery();
        }


        $products = array_column($query->getResult(Query::HYDRATE_ARRAY), 'id');

//        print_r($products);

        $query = $this->em->createQueryBuilder()
            ->select('p,u,v,seller,sub_cat,b')
            ->from('App\Entities\Products_quotation', 'p')
            ->leftJoin('p.status_quot', 'v')
            ->leftJoin('p.product_id', 'u')
            ->leftjoin('u.sellerid', 'seller')
            ->leftJoin('u.brand', 'b')
            ->leftJoin('u.product_category', 'sub_cat')
//                ->andWhere('sub_cat.is_enable = :is_enable')
//                ->setParameter('is_enable', 0)
            ->where($queryBuilder->expr()->in('p.id', ':products'))
            ->setParameter('products', $products);
        if ($orderbyclm != '') {
            $query = $query->orderBy($orderbyclm, $filter['order'][0]['dir']);
        }
        $data = $query = $query->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);

//        $data = $data->sortBy(['product_id.wp_published_date', $filter['order'][0]['dir']]);


        return array('data' => $data, 'total' => $total);
    }

    public function getSyncProductOrder($wp_product_id)
    {


//        $query = $this->em->createQueryBuilder()
//                ->select('o')
//                ->from('App\Entities\Orders', 'o')
//                ->Where('o.product_id=' . $wp_product_id)
//                ->getQuery();

        $query = $this->em->createQueryBuilder()
            ->select('a.parent_id', 'a.order_number', 'a.date_created', 'a.currency_symbol', 'a.order_list', 'a.buyer_user_role', 'a.tlv_make_an_offer', 'a.line_items_product', 'a.customer_username',
                'case when a.parent_id=b.order_id then b.billing else a.billing end as billing',
                'case when a.parent_id=b.order_id then b.shipping else a.shipping end as shipping',
                'case when a.parent_id=b.order_id then b.shipping_lines else a.shipping_lines end as shipping_lines',
                'case when a.parent_id=b.order_id then b.created_at else a.created_at end as created_at',
                'case when a.parent_id=b.order_id then b.status else a.status end as status',
                'case when a.parent_id=b.order_id then b.payment_method else a.payment_method end as payment_method',
                'case when a.parent_id=b.order_id then b.payment_method_title else a.payment_method_title end as payment_method_title',
                'case when a.parent_id=b.order_id then b.transaction_id else a.transaction_id end as transaction_id'
            )
            ->from('App\Entities\Orders', 'a')
            ->leftJoin('App\Entities\Orders', 'b', \Doctrine\ORM\Query\Expr\Join::WITH, 'a.parent_id=b.order_id')
            ->Where('a.product_id=' . $wp_product_id)
            ->getQuery();


        $data = $query->getResult(Query::HYDRATE_ARRAY);


        return array('data' => $data);
    }

    public function getProductWpProductIds($wpProductIds)
    {
        $queryBuilder = $this->em->createQueryBuilder();

        $query = $queryBuilder->select('pq', 'p', 's', 'c', 'b', 'category')
            ->from('App\Entities\Products_quotation', 'pq')
            ->leftJoin('pq.product_id', 'p')
            ->leftJoin('p.sellerid', 's')
            ->leftJoin('p.category', 'c')
            ->leftJoin('p.brand', 'b')
            ->leftJoin('p.product_category', 'category')
            ->where('pq.wp_product_id in (:wp_product_ids)')
            ->setParameter('wp_product_ids', $wpProductIds)
            ->getQuery();


        $data = $query->getResult(Query::HYDRATE_ARRAY);


        if (!empty($data)) {
            return $data[0];
        } else {
            return null;
        }
    }

    public function getSyncProductReport($filter)
    {

        //$filter['sellerid'] = 4072;
//
//
//        $queryBuilder = $this->em->createQueryBuilder();
//
//        $queryBuilder = $queryBuilder->select($queryBuilder->expr()->countDistinct('p.id'));
//        $queryBuilder = $queryBuilder->from('App\Entities\Products_quotation', 'p')
//                ->leftJoin('p.status_quot', 'v')
//                ->leftJoin('p.product_id', 'u')
//                ->leftjoin('u.sellerid', 'seller')
//                ->leftJoin('u.category', 'c')
//                ->leftJoin('u.brand', 'b')
//                ->leftJoin('u.product_category', 'category')
//                ->where('v.id = :id')
//                ->setParameter('id', 18);
//
//        if (isset($filter['sellerid']) && $filter['sellerid'] != '0') {
//            $queryBuilder = $queryBuilder->andWhere('u.sellerid = :seller_id');
//
//            $queryBuilder = $queryBuilder->setParameter('seller_id', $filter['sellerid']);
//        }
//
//        if (isset($filter['brand']) && $filter['brand'] != '0') {
//            $queryBuilder = $queryBuilder->andWhere('u.brand = :brand');
//
//            $queryBuilder = $queryBuilder->setParameter('brand', $filter['brand']);
//        }
//
//        if (isset($filter['category']) && $filter['category'] != '0') {
//            $queryBuilder = $queryBuilder->andWhere('u.category = :category');
//
//            $queryBuilder = $queryBuilder->setParameter('category', $filter['category']);
//        }
//
//        if (isset($filter['ship_size']) && $filter['ship_size'] != '0') {
//            $queryBuilder = $queryBuilder->andWhere('u.ship_size = :ship_size');
//
//            $queryBuilder = $queryBuilder->setParameter('ship_size', $filter['ship_size']);
//        }
//
//        if (isset($filter['wp_flat_rate']) && $filter['wp_flat_rate'] != '0') {
//            $queryBuilder = $queryBuilder->andWhere('p.wp_flat_rate = :wp_flat_rate');
//
//            $queryBuilder = $queryBuilder->setParameter('wp_flat_rate', $filter['wp_flat_rate']);
//        }
//
//        if (isset($filter['wp_stock_status']) && $filter['wp_stock_status'] != '0') {
//            $queryBuilder = $queryBuilder->andWhere('p.wp_stock_status = :wp_stock_status');
//
//            $queryBuilder = $queryBuilder->setParameter('wp_stock_status', $filter['wp_stock_status']);
//        }
//
//
//
//        $total = $queryBuilder->getQuery()->getSingleScalarResult();


        $queryBuilder = $this->em->createQueryBuilder();
        $query = $queryBuilder->select('p,u,v,seller,sub_cat,b')
            ->from('App\Entities\Products_quotation', 'p')
            ->leftJoin('p.status_quot', 'v')
            ->leftJoin('p.product_id', 'u')
            ->leftjoin('u.sellerid', 'seller')
            ->leftJoin('u.brand', 'b')
            ->leftJoin('u.product_category', 'sub_cat')
            ->leftJoin('App\Entities\Orders', 'o', \Doctrine\ORM\Query\Expr\Join::WITH, 'p.wp_product_id=o.product_id')
            ->where('v.id = :id')
            ->setParameter('id', 18)
            ->andWhere('p.wp_product_id != :wp_product_id')
            ->setParameter('wp_product_id', '');


        if (isset($filter['sellerid']) && $filter['sellerid'] != '0') {
            $query = $query->andWhere('u.sellerid = :seller_id');

            $query = $query->setParameter('seller_id', $filter['sellerid']);
        }

        if (isset($filter['brand']) && $filter['brand'] != '0') {
            $query = $query->andWhere('u.brand = :brand');

            $query = $query->setParameter('brand', $filter['brand']);
        }

        if (isset($filter['category']) && count($filter['category']) > 0) {

            if (isset($filter['subcategoryid']) && count($filter['subcategoryid']) > 0) {

            } else {
                $query = $query->andWhere($queryBuilder->expr()->in('sub_cat.id', ':category'))
                    ->setParameter('category', $filter['category']);
            }
        }

        if (isset($filter['subcategoryid']) && count($filter['subcategoryid']) > 0) {

            $query = $query->andWhere($queryBuilder->expr()->in('sub_cat.id', ':subcategoryid'))
                ->setParameter('subcategoryid', $filter['subcategoryid']);
        }

        if (isset($filter['ship_size']) && $filter['ship_size'] != '0') {
            $query = $query->andWhere('u.ship_size = :ship_size');

            $query = $query->setParameter('ship_size', $filter['ship_size']);
        }

        if (isset($filter['wp_flat_rate']) && $filter['wp_flat_rate'] != 'all') {
            $query = $query->andWhere('p.wp_flat_rate = :wp_flat_rate');

            $query = $query->setParameter('wp_flat_rate', $filter['wp_flat_rate']);
        }

        if (isset($filter['wp_stock_status']) && $filter['wp_stock_status'] != '0') {
            $query = $query->andWhere('p.wp_stock_status = :wp_stock_status');

            $query = $query->setParameter('wp_stock_status', $filter['wp_stock_status']);
        }


        if (isset($filter['start_date_new']) && isset($filter['end_date_new']) && $filter['start_date_new'] != null && $filter['end_date_new'] != null && $filter['start_date_new'] != 'Invalid date' && $filter['end_date_new'] != 'Invalid date') {

            $query = $query->andwhere('p.wp_published_date BETWEEN :start AND :end')
                ->setParameter('start', $filter['start_date_new'])
                ->setParameter('end', $filter['end_date_new']);
        }

        if (isset($filter['start_date_order']) && isset($filter['end_date_order']) && $filter['start_date_order'] != null && $filter['end_date_order'] != null && $filter['start_date_order'] != 'Invalid date' && $filter['end_date_order'] != 'Invalid date') {

            $query = $query->andwhere('o.date_created BETWEEN :startorder AND :endorder')
                ->setParameter('startorder', $filter['start_date_order'])
                ->setParameter('endorder', $filter['end_date_order']);
        }

        $query = $query->orderBy('u.created_at', 'desc');

        $query = $query->getQuery();


        $data = $query->getResult(Query::HYDRATE_ARRAY);


        return array('data' => $data);
    }

    public function webhooks_workflow_wpproductid()
    {

        $query = $this->em->createQueryBuilder()
            ->select('u.name', 'p.wp_product_id', 'p.wp_published_date')
            ->from('App\Entities\Products_quotation', 'p')
            ->leftJoin('p.product_id', 'u')
            ->where('p.wp_product_id != :wp_product_id')
            ->setParameter('wp_product_id', '')
            ->andWhere('p.wp_published_date IS NULL')
            ->getQuery();

        $data = $query->getResult(Query::HYDRATE_ARRAY);


        return $data;
    }

    public function getConsignmentReport($filter)
    {

        if (!empty($filter['order'])) {
            if ($filter['order'][0]['column'] == 0) {
                $orderbyclm = 's.firstname';
            } else if ($filter['order'][0]['column'] == 1) {
                $orderbyclm = 's.email';
            } else if ($filter['order'][0]['column'] == 2) {
                $orderbyclm = 'p.sku';
            } else if ($filter['order'][0]['column'] == 3) {
                $orderbyclm = 'p.name';
            } else if ($filter['order'][0]['column'] == 4) {
                $orderbyclm = 'p.tlv_price';
            } else if ($filter['order'][0]['column'] == 5) {
                $orderbyclm = 'pq.wp_published_date';
            } else {
                $orderbyclm = '';
            }
        } else {
            $orderbyclm = '';
        }

        $query = $this->em->createQueryBuilder();
        $query->select($query->expr()->count('pq.id'))
            ->from('App\Entities\Products_quotation', 'pq')
            ->leftJoin('pq.product_id', 'p')
            ->where('pq.wp_product_id != :wp_product_id')
            ->setParameter('wp_product_id', '');
        $total = $query->getQuery()->getSingleScalarResult();

        $queryBuilder = $this->em->createQueryBuilder();
        $query = $queryBuilder->select("CONCAT(s.firstname, ' ', s.lastname) AS seller_name", 's.email','p.sku', 'p.name', 'p.tlv_price','pq.wp_published_date')
            ->from('App\Entities\Products_quotation', 'pq')
            ->leftJoin('pq.product_id', 'p')
            ->leftjoin('p.sellerid', 's')
            ->leftJoin('p.brand', 'b')
            ->leftJoin('p.product_category', 'sub_cat')
            ->where('pq.wp_product_id != :wp_product_id')
            ->setParameter('wp_product_id', '')
            ->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('p.sku', ':filter'),
                    $queryBuilder->expr()->like('p.name', ':filter'),
                    $queryBuilder->expr()->like('s.firstname', ':filter'),
                    $queryBuilder->expr()->like('s.lastname', ':filter'),
                    $queryBuilder->expr()->like('s.email', ':filter'),
                    $queryBuilder->expr()->like('p.price', ':filter'),
                    $queryBuilder->expr()->like('pq.wp_published_date', ':filter')
                )
            )
            ->setParameter('filter', '%' . $filter['search']['value'] . '%');

        if ($orderbyclm != '') {
            $query = $query->orderBy($orderbyclm, $filter['order'][0]['dir'])
                ->setMaxResults($filter['length'])
                ->setFirstResult($filter['start'])
                ->groupBy('p.id')
                ->getQuery();
        } else {
            $query = $query->orderBy('pq.wp_published_date', 'desc')
                ->setMaxResults($filter['length'])
                ->setFirstResult($filter['start'])
                ->groupBy('p.id')
                ->getQuery();
        }

        $data = $query->getResult(Query::HYDRATE_ARRAY);
        return array('data' => $data, 'total' => $total);
    }

//    public function getConsignmentReportTotal($filter)
//    {
//        $query = $this->em->createQueryBuilder();
//
//        $query->select($query->expr()->count('pq.id'))
//            ->from('App\Entities\Products_quotation', 'pq')
//            ->leftJoin('pq.product_id', 'p')
//            ->leftjoin('p.sellerid', 's')
//            ->leftJoin('p.brand', 'b')
//            ->leftJoin('p.product_category', 'sub_cat')
//            ->where('pq.wp_product_id != :wp_product_id')
//            ->setParameter('wp_product_id', '')
//            ->andWhere(
//                $query->expr()->orX(
//                    $query->expr()->like('p.sku', ':filter'),
//                    $query->expr()->like('p.name', ':filter'),
//                    $query->expr()->like('s.firstname', ':filter'),
//                    $query->expr()->like('s.lastname', ':filter'),
//                    $query->expr()->like('s.email', ':filter'),
//                    $query->expr()->like('p.price', ':filter'),
//                    $query->expr()->like('pq.wp_published_date', ':filter')
//                )
//            )
//            ->setParameter('filter', '%' . $filter['search']['value'] . '%')
//            ->groupBy('p.id');
//
//        $query = $query->getQuery();
//        $data = $query->getResult(Query::HYDRATE_ARRAY);
//
//        return count($data);
//    }

    public function getConsignmentReportExport()
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $query = $queryBuilder->select('pq,p,s,b,sub_cat')
            ->from('App\Entities\Products_quotation', 'pq')
            ->leftJoin('pq.product_id', 'p')
            ->leftjoin('p.sellerid', 's')
            ->leftJoin('p.brand', 'b')
            ->leftJoin('p.product_category', 'sub_cat')
            ->where('pq.wp_product_id != :wp_product_id')
            ->setParameter('wp_product_id', '')
            ->orderBy('pq.wp_published_date', 'desc')
            ->groupBy('p.id')
            ->getQuery();

        $data = $query->getResult(Query::HYDRATE_ARRAY);
        return array('data' => $data);

    }
}

?>
