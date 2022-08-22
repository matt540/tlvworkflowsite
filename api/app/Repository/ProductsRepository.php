<?php

namespace App\Repository;

use App\Entities\Products;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class ProductsRepository extends EntityRepository {

    /**

     * @var string

     */
    private $class = 'App\Entities\Products';

    /**

     * @var EntityManager

     */
    private $em;

    public function __construct(EntityManager $em) {

        $this->em = $em;
    }

    public function create(Products $option) {



        $this->em->persist($option);

        $this->em->flush();

        return $option->getId();
    }

    public function update(Products $option, $data) {

        if (isset($data['is_set_approved_date'])) {

            $option->setApprovedDate(new \DateTime());
        }

        if (isset($data['category_local'])) {

            $option->setCategoryLocal($data['category_local']);
        }

        if (isset($data['brand_local'])) {



            $option->setBrandLocal($data['brand_local']);
        }

        if (isset($data['item_type_local'])) {

            $option->setItemTypeLocal($data['item_type_local']);
        }

        if (isset($data['condition_local'])) {

            $option->setConditionLocal($data['condition_local']);
        }

        if (isset($data['location'])) {

            $option->setLocation($data['location']);
        }

        if (isset($data['tlv_suggested_price_min'])) {

            $option->setTlv_suggested_price_min($data['tlv_suggested_price_min']);
        }

        if (isset($data['tlv_suggested_price_max'])) {

            $option->setTlv_suggested_price_max($data['tlv_suggested_price_max']);
        }



        if (isset($data['name'])) {

            $option->setName($data['name']);
        }

        if (isset($data['note'])) {

            $option->setNote($data['note']);
        }



        if (isset($data['description'])) {

            $option->setDescription($data['description']);
        }



        if (isset($data['sell_id'])) {

            $option->setSell_id($data['sell_id']);
        }



        if (isset($data['seller_id'])) {

            $option->setSeller_id($data['seller_id']);
        }

        if (isset($data['sellerid'])) {

            $option->setSellerid($data['sellerid']);
        }

        if (isset($data['seller_firstname'])) {

            $option->setSeller_firstname($data['seller_firstname']);
        }

        if (isset($data['seller_lastname'])) {

            $option->setSeller_lastname($data['seller_lastname']);
        }

        if (isset($data['product_pending_images'])) {

            $option->setProductPendingImages($data['product_pending_images']);
        }



        if (isset($data['price'])) {

            $option->setPrice($data['price']);
        }

        if (isset($data['tlv_price'])) {

            $option->setTLVPrice($data['tlv_price']);
        }



        if (isset($data['product_room'])) {

            $option->setRoom($data['product_room']);
        }

        if (isset($data['product_look'])) {

            $option->setLook($data['product_look']);
        }

//        if (isset($data['look']))
//        {
//            $option->setLook($data['look']);
//        }

        if (isset($data['product_color'])) {

            $option->setColor($data['product_color']);
        }

        if (isset($data['product_category'])) {

            $option->setProductCategory($data['product_category']);
        }

        if (isset($data['product_con'])) {

            $option->setProductCon($data['product_con']);
        }

        if (isset($data['product_collection'])) {

            $option->setProductCollection($data['product_collection']);
        }









//        if (isset($data['brand']))

        if (array_key_exists('brand', $data)) {

            $option->setBrand($data['brand']);
        }

        if (isset($data['category'])) {

            $option->setCategory($data['category']);
        }


        if (isset($data['product_material'])) {
            $option->set_product_material($data['product_material']);
        }

        if (isset($data['product_materials'])) {
            $option->set_product_materials($data['product_materials']);
        }

        if (isset($data['collection'])) {

            $option->setCollection($data['collection']);
        }

        if (isset($data['con'])) {

            $option->setCondition($data['con']);
        }

        if (isset($data['age'])) {

            $option->setAge($data['age']);
        }

        if (isset($data['status'])) {

            $option->setStatus($data['status']);
        }

        if (isset($data['state'])) {

            $option->setState($data['state']);
        }

        if (isset($data['city'])) {

            $option->setCity($data['city']);
        }

        if (isset($data['zipcode'])) {
            $option->setZipcode($data['zipcode']);
        }

        if (isset($data['region'])) {
            $option->setRegion($data['region']);
        }

        if (isset($data['pick_up_location'])) {

            $option->setPick_up_location($data['pick_up_location']);
        }

        if (isset($data['pet_free'])) {

            $option->setPet_free($data['pet_free']);
        }

        if (isset($data['is_touched'])) {

            $option->setIsTouched($data['is_touched']);
        }

        if (isset($data['quantity'])) {

            $option->setQuantity($data['quantity']);
        }

        if (isset($data['ship_size'])) {

            $option->setShip_size($data['ship_size']);
        }

        if (isset($data['ship_material'])) {

            $option->setShip_material($data['ship_material']);
        }

        if (isset($data['local_pickup_available'])) {

            $option->setLocal_pickup_available($data['local_pickup_available']);
        }



        if (isset($data['ship_cat'])) {

            $option->setShip_cat($data['ship_cat']);
        }

        if (isset($data['flat_rate_packaging_fee'])) {

            $option->setFlat_rate_packaging_fee($data['flat_rate_packaging_fee']);
        }

        if (isset($data['local_drop_off']))
        {
            $option->setLocal_drop_off($data['local_drop_off']);
        }

        if (isset($data['local_drop_off_city']))
        {
            $option->setLocal_drop_off_city($data['local_drop_off_city']);
        }

        if (isset($data['pending_sell_measurment']))
        {
            $option->setPending_sell_measurment($data['pending_sell_measurment']);
        }

        $this->em->persist($option);

        $this->em->flush();

        return 1;
    }

    public function ProductOfId($id) {

        return $this->em->getRepository($this->class)->findOneBy([
                    'id' => $id
        ]);
    }

    public function ProductOfSku($sku) {

        return $this->em->getRepository($this->class)->findOneBy([
                    'sku' => $sku
        ]);
    }

    public function deleteAllProductsOfSellerId($seller_id) {

        $query = $this->em->createQueryBuilder();

        $query->delete('App\Entities\Products', 'p')
                ->where('p.sellerid = :seller_id')
                ->setParameter('seller_id', $seller_id);
    }

    public function getAllProducts() {

        $query = $this->em->createQueryBuilder()
                ->select('u,collection_old,look_old,pick_up_location')
                ->from('App\Entities\Products', 'u')

//                ->leftJoin('u.sell_id', 'sell')
//                ->leftJoin('u.sellerid', 'seller')
//                ->leftJoin('u.product_room', 'room')
//                ->leftJoin('u.product_pending_images', 'pi')
//                ->leftJoin('u.look', 'look')
//                ->leftJoin('u.product_color', 'color')
//                ->leftJoin('u.product_category', 'category')
//                ->leftJoin('u.product_con', 'product_con')
//                ->leftJoin('u.product_collection', 'collection')
                ->leftJoin('u.collection', 'collection_old')
                ->leftJoin('u.look', 'look_old')

//                ->leftJoin('u.brand', 'brand')
//                ->leftJoin('u.category', 'category')
//                ->leftJoin('u.con', 'con')
//                ->leftJoin('u.collection', 'collection')
//                ->leftJoin('u.age', 'age')
                ->leftJoin('u.pick_up_location', 'pick_up_location')

//                ->leftJoin('u.status', 'status')
//                ->where('u.id = :id')
//                ->setParameter('id', $id)
                ->getQuery();

        $data = $query->getResult(Query::HYDRATE_ARRAY);



        return $data;
    }

    public function getAllProductsTemp() {

        $query = $this->em->createQueryBuilder()
                ->select('u,pick_up_location,sellerid')
                ->from('App\Entities\Products', 'u')

//                ->leftJoin('u.collection', 'collection_old')
//                ->leftJoin('u.look', 'look_old')
                ->leftJoin('u.sellerid', 'sellerid')

//                ->leftJoin('u.category', 'category')
//                ->leftJoin('u.con', 'con')
//                ->leftJoin('u.collection', 'collection')
//                ->leftJoin('u.age', 'age')
                ->leftJoin('u.pick_up_location', 'pick_up_location')

//                ->leftJoin('u.status', 'status')
                ->where('pick_up_location.id = :id')
                ->setParameter('id', 22)
                ->getQuery();

        $data = $query->getResult(Query::HYDRATE_ARRAY);



        return $data;
    }

    public function getProductById($id) {

        $query = $this->em->createQueryBuilder()
                ->select('u,seller,pi,sell,room,look,color,brand,category,product_con,collection,age,pick_up_location,status')
                ->from('App\Entities\Products', 'u')
                ->leftJoin('u.sell_id', 'sell')
                ->leftJoin('u.sellerid', 'seller')
                ->leftJoin('u.product_room', 'room')
                ->leftJoin('u.product_pending_images', 'pi')

//                ->leftJoin('u.look', 'look')
                ->leftJoin('u.product_look', 'look')
                ->leftJoin('u.product_color', 'color')
                ->leftJoin('u.product_category', 'category')
                ->leftJoin('u.product_con', 'product_con')
                ->leftJoin('u.product_collection', 'collection')
                ->leftJoin('u.brand', 'brand')

//                ->leftJoin('u.category', 'category')
//                ->leftJoin('u.con', 'con')
//                ->leftJoin('u.collection', 'collection')
                ->leftJoin('u.age', 'age')
                ->leftJoin('u.pick_up_location', 'pick_up_location')
                ->leftJoin('u.status', 'status')
                ->where('u.id = :id')
                ->orderBy('pi.priority', 'asc')
                ->setParameter('id', $id)
                ->getQuery();

        $data = $query->getResult(Query::HYDRATE_ARRAY);



        return $data[0];
    }

    public function delete(Products $option) {

        $this->em->remove($option);

        $this->em->flush();
    }

    /**

     * create Theory

     * @return Theory

     */
    public function prepareData($data) {

        return new Products($data);
    }

    public function getProductsReport($filter) {





        $pending = 6;

        $rejectstatus = 8;

        $archivedstatus = 31;



        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('u.id'))
                ->from('App\Entities\Products', 'u')
                ->leftjoin('u.status', 'status')
                ->where('u.sellerid = ' . $filter['id']);



        $status = [6, 8];



        $query->andWhere(
                        $query->expr()->in('status.id', ':status')
                )
                ->setParameter('status', $status);

        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                    $query->expr()->between('u.created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }

        $total = $query->getQuery()->getSingleScalarResult();



        $query = $this->em->createQueryBuilder();

        $query->select(array('u.name', 'u.note', 'u.quantity', 'u.tlv_suggested_price_min', 'u.tlv_suggested_price_max', 'u.sku', 'u.price', ' GROUP_CONCAT(images.name) image', 'u.approved_date', 'u.created_at', 's.name as sell_name', 'u.id', 'status.id as status_id', 'status.value_text as status_value'))
                ->from('App\Entities\Products', 'u')
                ->leftjoin('u.sell_id', 's')
                ->leftjoin('u.status', 'status')
                ->leftjoin('u.product_pending_images', 'images')
                ->where('u.sellerid = ' . $filter['id'])
                ->groupBy('u.id');







        $status = [6, 8];



        $query->andWhere(
                        $query->expr()->in('status.id', ':status')
                )
                ->setParameter('status', $status);



        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                    $query->expr()->between('u.created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }

        $qb = $query->getQuery();



        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getProductsTotalReport($filter) {

        $pending = 6;

        $rejectstatus = 8;

        $archivedstatus = 31;



        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('u.id'))
                ->from('App\Entities\Products', 'u')
                ->leftjoin('u.sell_id', 's')
                ->leftjoin('u.status', 'status')
                ->where('u.sellerid = ' . $filter['id']);



//        if (isset($filter['pass_from']) && $filter['pass_from'] == 'report')
//        {

        $status = [6, 8];

//        }
//        else
//        {
//            $status = [6];
//        }

        $query->andWhere(
                        $query->expr()->in('status.id', ':status')
                )
                ->setParameter('status', $status);



        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                    $query->expr()->between('u.created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }



        return $query->getQuery()->getSingleScalarResult();
    }

    public function getProductsTotal($filter) {

        $pending = 6;

        $rejectstatus = 8;

        $archivedstatus = 31;



        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('u.id'))
                ->from('App\Entities\Products', 'u')
                ->leftjoin('u.sell_id', 's')
                ->leftjoin('u.status', 'status')
                ->where('u.sellerid = ' . $filter['id'])

//                ->andWhere('status.id = ' . $pending)
//                ->andWhere('status.id != ' . $rejectstatus)
//                ->andWhere('status.id != ' . $archivedstatus)
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->like('u.name', ':filter')
                                , $query->expr()->like('u.sku', ':filter')
                                , $query->expr()->like('s.name', ':filter')
                                , $query->expr()->like('u.price', ':filter')
                                , $query->expr()->like('status.value_text', ':filter')
                ))
                ->setParameter('filter', '%' . $filter['search']['value'] . '%');



        if (isset($filter['pass_from']) && $filter['pass_from'] == 'report') {

            $status = [6, 8];
        } else {

            $status = [6];
        }

        $query->andWhere(
                        $query->expr()->in('status.id', ':status')
                )
                ->setParameter('status', $status);



        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                    $query->expr()->between('u.created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }



        return $query->getQuery()->getSingleScalarResult();
    }

    public function getTLVStaffProductsTotal($filter) {

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

    public function getProductForReviewPendingCountBySellerId($seller_id) {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('u.id'))
                ->from('App\Entities\Products', 'u')
                ->leftjoin('u.sell_id', 's')
                ->leftjoin('u.status', 'status')
                ->leftjoin('u.sellerid', 'seller')
                ->where('status.id = 6')
                ->andWhere('seller.id = ' . $seller_id);

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getProductsForReviewCount() {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('u.id'))
                ->from('App\Entities\Products', 'u')
                ->leftjoin('u.status', 'status')
                ->innerjoin('u.sellerid', 'seller')
                ->where('u.status = 6');

//                ->andWhere('seller.deleted_at is NULL');

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getProductsRejectedCount() {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('u.id'))
                ->from('App\Entities\Products', 'u')
                ->leftjoin('u.sell_id', 's')
                ->innerjoin('u.sellerid', 'seller')
                ->leftjoin('u.status', 'status')
                ->where('status.id = 8');

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getProductsArchivedCount() {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('u.id'))
                ->from('App\Entities\Products', 'u')
                ->leftjoin('u.sell_id', 's')
                ->innerjoin('u.sellerid', 'seller')
                ->leftjoin('u.status', 'status')
                ->where('status.id = 31');

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getProducts($filter) {



        if ($filter['order'][0]['column'] == 1) {

            $orderbyclm = 'u.sku';
        }

        if ($filter['order'][0]['column'] == 2) {

            $orderbyclm = 'u.name';
        }

        if ($filter['order'][0]['column'] == 3) {

            $orderbyclm = 'u.price';
        }

        if ($filter['order'][0]['column'] == 4) {

            $orderbyclm = 'u.created_at';
        }

        if ($filter['order'][0]['column'] == 5) {

            $orderbyclm = 'u.created_at';
        }



//        if ($filter['order'][0]['column'] == 2)
//        {
//            $orderbyclm = 'u.price';
//        }

        if ($filter['order'][0]['column'] == 4) {

            $orderbyclm = 'status.id';
        }



//        if ($filter['order'][0]['column'] == 4)
//        {
//            $orderbyclm = 's.value_text';
//        }



        $pending = 6;

        $rejectstatus = 8;

        $archivedstatus = 31;



        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('u.id'))
                ->from('App\Entities\Products', 'u')
                ->leftjoin('u.status', 'status')
                ->where('u.sellerid = ' . $filter['id']);

//                ->andWhere('status.id = ' . $pending);





        if (isset($filter['pass_from']) && $filter['pass_from'] == 'report') {

            $status = [6, 8];
        } else {

            $status = [6];
        }

        $query->andWhere(
                        $query->expr()->in('status.id', ':status')
                )
                ->setParameter('status', $status);

        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                    $query->expr()->between('u.created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }

        $total = $query->getQuery()->getSingleScalarResult();



        $query = $this->em->createQueryBuilder();

        $query->select(array('u.name', 'u.sku', 'u.price', 'u.tlv_suggested_price_min', 'u.tlv_suggested_price_max', ' GROUP_CONCAT(images.name) image', 'u.approved_date', 'u.created_at', 's.name as sell_name', 'u.id', 'status.id as status_id', 'status.value_text as status_value'))
                ->from('App\Entities\Products', 'u')
                ->leftjoin('u.sell_id', 's')
                ->leftjoin('u.status', 'status')
                ->leftjoin('u.product_pending_images', 'images')
                ->setMaxResults($filter['length'])
                ->setFirstResult($filter['start'])
                ->where('u.sellerid = ' . $filter['id'])

//                ->andWhere('status.id = ' . $pending)
//                ->andWhere('status.id != ' . $rejectstatus)
//                ->andWhere('status.id != ' . $archivedstatus)
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->like('u.name', ':filter')
                                , $query->expr()->like('u.sku', ':filter')
                                , $query->expr()->like('u.price', ':filter')
                                , $query->expr()->like('s.name', ':filter')
                                , $query->expr()->like('status.value_text', ':filter')
                ))
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->orderBy($orderbyclm, $filter['order'][0]['dir'])
                ->groupBy('u.id');





        if (isset($filter['pass_from']) && $filter['pass_from'] == 'report') {

            $status = [6, 8];
        } else {

            $status = [6];
        }

        $query->andWhere(
                        $query->expr()->in('status.id', ':status')
                )
                ->setParameter('status', $status);



        if (isset($filter['start_date_updated']) && isset($filter['end_date_updated'])) {

            $query->andWhere(
                    $query->expr()->between('u.created_at', ':start', ':end'));

            $query->setParameter('start', $filter['start_date_updated']);

            $query->setParameter('end', $filter['end_date_updated'] . ' 23:59:59');
        }

        $qb = $query->getQuery();



        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getTLVStaffProducts($filter) {



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
                ->orderBy($orderbyclm, $filter['order'][0]['dir']);

        ;





        $qb = $query->getQuery();



        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getAllProductsReport($filter) {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('u.id'))
                ->from('App\Entities\Products', 'u')
                ->leftjoin('u.status', 'status')
                ->where('u.sellerid = ' . $filter['id']);

        $total = $query->getQuery()->getSingleScalarResult();





        $query = $this->em->createQueryBuilder();

        $query->select(array('u,qt,status,status_quot'))
                ->from('App\Entities\Products', 'u')
                ->leftjoin('u.sell_id', 's')
                ->leftjoin('u.status', 'status');

        $query->leftjoin(
                'App\Entities\Products_quotation', 'pq', \Doctrine\ORM\Query\Expr\Join::WITH, 'pq.product_id = u.id'
        );

        $query->leftjoin('pq.status_quot', 'status_quot');

        $qb = $query->getQuery();



        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getAllProductsWithStatus($filter) {



        if ($filter['order'][0]['column'] == 0) {

            $orderbyclm = 'u.name';
        }

        if ($filter['order'][0]['column'] == 1) {

            $orderbyclm = 'u.name';
        }



        if ($filter['order'][0]['column'] == 2) {

            $orderbyclm = 'u.price';
        }

        if ($filter['order'][0]['column'] == 3) {

            $orderbyclm = 'status.id';
        }

        $status_quot_rejected = 19;

        $status_quot_approved = 18;

        $status_reject = 8;

        $status = 31;

        $archived = 1;

        $archived_temp = null;



        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('u.id'))
                ->from('App\Entities\Products', 'u')
                ->leftjoin('u.status', 'status')
                ->where('u.sellerid = ' . $filter['id']);



        $query->leftjoin(
                'App\Entities\Products_quotation', 'pq', \Doctrine\ORM\Query\Expr\Join::WITH, 'pq.product_id = u.id'
        );



        $query->leftjoin('pq.status_quot', 'status_quot');





        $query->andWhere(
                        $query->expr()->orX(
                                $query->expr()->andX(
                                        $query->expr()->neq('status.id', ':status_reject')
                                        , $query->expr()->neq('status.id', ':status_archived')
                                        , $query->expr()->isNull('pq.is_archived')
                                )
                                , $query->expr()->andX(
                                        $query->expr()->isNotNull('pq.product_id')
                                        , $query->expr()->neq('pq.is_archived', ':archived')
                                        , $query->expr()->neq('status_quot.id', ':status_quot_approved')
                                        , $query->expr()->neq('status_quot.id', ':status_quot_rejected')
                                        , $query->expr()->neq('pq.is_product_for_production', 2)
                                        , $query->expr()->neq('pq.is_copyright', 2)
                                        , $query->expr()->neq('pq.is_send_mail', 2)
                                ), $query->expr()->andX(
                                        $query->expr()->isNotNull('pq.product_id')
                                        , $query->expr()->isNull('pq.status_quot')
                                        , $query->expr()->neq('pq.is_product_for_production', 2)
                                        , $query->expr()->neq('pq.is_copyright', 2)
                                        , $query->expr()->neq('pq.is_send_mail', 2)
                                )
                        )
                        , $query->expr()->andX(
                        )
                )
                ->setParameter('status_reject', $status_reject)
                ->setParameter('status_quot_approved', $status_quot_approved)
                ->setParameter('status_quot_rejected', $status_quot_rejected)
                ->setParameter('status_archived', $status)
                ->setParameter('archived', $archived);

        $total = $query->getQuery()->getSingleScalarResult();









        $query = $this->em->createQueryBuilder();

        $query->select(array('u.name', 'u.created_at', 'u.price', 'pq.is_archived as is_archived', 'status_quot.id as status_quot_id', 'status_quot.value_text status_quot_value', 's.name as sell_name', 'u.id', 'status.id as status_id', 'status.value_text as status_value', 'pq.is_send_mail', 'pq.is_copyright', 'pq.is_product_for_production', 'pq.id as quotation_id'))
                ->from('App\Entities\Products', 'u')
                ->leftjoin('u.sell_id', 's')
                ->leftjoin('u.status', 'status');

        $query->leftjoin(
                'App\Entities\Products_quotation', 'pq', \Doctrine\ORM\Query\Expr\Join::WITH, 'pq.product_id = u.id'
        );

        $query->leftjoin('pq.status_quot', 'status_quot');

        $query->setMaxResults($filter['length'])
                ->setFirstResult($filter['start'])
                ->where('u.sellerid = ' . $filter['id'])
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->andX(
                                        $query->expr()->neq('status.id', ':status_reject')
                                        , $query->expr()->neq('status.id', ':status_archived')
                                        , $query->expr()->isNull('pq.is_archived')
                                )
                                , $query->expr()->andX(
                                        $query->expr()->isNotNull('pq.product_id')
                                        , $query->expr()->neq('pq.is_archived', ':archived')
                                        , $query->expr()->neq('status_quot.id', ':status_quot_approved')
                                        , $query->expr()->neq('status_quot.id', ':status_quot_rejected')
                                        , $query->expr()->neq('pq.is_product_for_production', 2)
                                        , $query->expr()->neq('pq.is_copyright', 2)
                                        , $query->expr()->neq('pq.is_send_mail', 2)
                                ), $query->expr()->andX(
                                        $query->expr()->isNotNull('pq.product_id')
                                        , $query->expr()->isNull('pq.status_quot')
                                        , $query->expr()->neq('pq.is_product_for_production', 2)
                                        , $query->expr()->neq('pq.is_copyright', 2)
                                        , $query->expr()->neq('pq.is_send_mail', 2)
                                )
                        )
                        , $query->expr()->andX(
                        )
                )
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->like('u.name', ':filter')
                                , $query->expr()->like('u.price', ':filter')
                                , $query->expr()->like('s.name', ':filter')
                                , $query->expr()->like('status.value_text', ':filter')
                ))
                ->setParameter('status_reject', $status_reject)
                ->setParameter('status_quot_approved', $status_quot_approved)
                ->setParameter('status_quot_rejected', $status_quot_rejected)
                ->setParameter('status_archived', $status)
                ->setParameter('archived', $archived)

//                ->setParameter('archived_temp', $archived_temp)
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->orderBy($orderbyclm, $filter['order'][0]['dir']);



        $qb = $query->getQuery();



        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getAllProductsWithStatusTotal($filter) {

        $status_quot_rejected = 19;

        $status_quot_approved = 18;

        $status_reject = 8;

        $status = 31;

        $archived = 1;

        $archived_temp = null;

//        $status_reject = 8;
//        $status = 31;
//        $archived = 0;



        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('u.id'))
                ->from('App\Entities\Products', 'u')
                ->leftjoin('u.sell_id', 's')
                ->leftjoin('u.status', 'status');

        $query->leftjoin(
                'App\Entities\Products_quotation', 'pq', \Doctrine\ORM\Query\Expr\Join::WITH, 'pq.product_id = u.id'
        );

        $query->leftjoin('pq.status_quot', 'status_quot');

        $query->where('u.sellerid = ' . $filter['id'])
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->andX(
                                        $query->expr()->neq('status.id', ':status_reject')
                                        , $query->expr()->neq('status.id', ':status_archived')
                                        , $query->expr()->isNull('pq.is_archived')
                                )
                                , $query->expr()->andX(
                                        $query->expr()->isNotNull('pq.product_id')
                                        , $query->expr()->neq('pq.is_archived', ':archived')
                                        , $query->expr()->neq('status_quot.id', ':status_quot_approved')
                                        , $query->expr()->neq('status_quot.id', ':status_quot_rejected')
                                        , $query->expr()->neq('pq.is_product_for_production', 2)
                                        , $query->expr()->neq('pq.is_copyright', 2)
                                        , $query->expr()->neq('pq.is_send_mail', 2)
                                ), $query->expr()->andX(
                                        $query->expr()->isNotNull('pq.product_id')
                                        , $query->expr()->isNull('pq.status_quot')
                                        , $query->expr()->neq('pq.is_product_for_production', 2)
                                        , $query->expr()->neq('pq.is_copyright', 2)
                                        , $query->expr()->neq('pq.is_send_mail', 2)
                                )
                        )
                        , $query->expr()->andX(
                        )
                )
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->like('u.name', ':filter')
                                , $query->expr()->like('s.name', ':filter')
                                , $query->expr()->like('u.price', ':filter')
                                , $query->expr()->like('status.value_text', ':filter')
                ))
                ->setParameter('status_reject', $status_reject)
                ->setParameter('status_archived', $status)
                ->setParameter('status_quot_rejected', $status_quot_rejected)
                ->setParameter('archived', $archived)
                ->setParameter('status_quot_approved', $status_quot_approved)
                ->setParameter('filter', '%' . $filter['search']['value'] . '%');

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getAllArchivedProductsCount() {

        $status = 31;

        $archived = 1;

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('u.id'))
                ->from('App\Entities\Products', 'u')
                ->leftjoin('u.sell_id', 's')
                ->innerjoin('u.sellerid', 'seller')
                ->leftjoin('u.status', 'status');

        $query->leftjoin(
                'App\Entities\Products_quotation', 'pq', \Doctrine\ORM\Query\Expr\Join::WITH, 'pq.product_id = u.id'
        );

        $query->leftjoin('pq.status_quot', 'status_quot')

//                ->where('u.sellerid = ' . $filter['id'])
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->eq('status.id', ':status')
                                , $query->expr()->eq('pq.is_archived', ':archived')
                ))
                ->setParameter('status', $status)
                ->setParameter('archived', $archived);



        return $query->getQuery()->getSingleScalarResult();
    }

    public function getArchivedProducts($filter) {



        if ($filter['order'][0]['column'] == 0) {

            $orderbyclm = 'u.name';
        }



        if ($filter['order'][0]['column'] == 1) {

            $orderbyclm = 'u.sku';
        }

        if ($filter['order'][0]['column'] == 2) {

            $orderbyclm = 'u.name';
        }



        if ($filter['order'][0]['column'] == 3) {

            $orderbyclm = 'u.price';
        }

        if ($filter['order'][0]['column'] == 4) {

            $orderbyclm = 'status.id';
        }

        if ($filter['order'][0]['column'] == 5) {

            $orderbyclm = 'status.id';
        }



        $status = 31;

        $archived = 1;



        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('u.id'))
                ->from('App\Entities\Products', 'u')
                ->where('u.sellerid = ' . $filter['id']);

        $total = $query->getQuery()->getSingleScalarResult();



        $query = $this->em->createQueryBuilder();

        $query->select(array('u.name', 'u.sku', 'u.price', 'status_quot.id as status_quot_id', 's.name as sell_name', 'u.id', 'status.id as status_id', 'status.value_text as status_value', 'pq.is_send_mail', 'pq.is_copyright', 'pq.is_product_for_production', 'pq.is_product_for_pricing', 'pq.is_awaiting_contract', 'pq.id as quotation_id'))
                ->from('App\Entities\Products', 'u')
                ->leftjoin('u.sell_id', 's')
                ->leftjoin('u.status', 'status');

        $query->leftjoin(
                'App\Entities\Products_quotation', 'pq', \Doctrine\ORM\Query\Expr\Join::WITH, 'pq.product_id = u.id'
        );

        $query->leftjoin('pq.status_quot', 'status_quot')
                ->setMaxResults($filter['length'])
                ->setFirstResult($filter['start'])
                ->where('u.sellerid = ' . $filter['id'])
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->eq('status.id', ':status')
                                , $query->expr()->like('pq.is_archived', ':archived')
                ))
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->like('u.name', ':filter')
                                , $query->expr()->like('u.sku', ':filter')
                                , $query->expr()->like('u.price', ':filter')
                                , $query->expr()->like('s.name', ':filter')
                                , $query->expr()->like('status.value_text', ':filter')
                ))
                ->setParameter('status', $status)
                ->setParameter('archived', $archived)
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->orderBy($orderbyclm, $filter['order'][0]['dir']);



        $qb = $query->getQuery();



        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getArchivedProductsTotal($filter) {

        $status = 31;

        $archived = 1;



        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('u.id'))
                ->from('App\Entities\Products', 'u')
                ->leftjoin('u.sell_id', 's')
                ->leftjoin('u.status', 'status');

        $query->leftjoin(
                'App\Entities\Products_quotation', 'pq', \Doctrine\ORM\Query\Expr\Join::WITH, 'pq.product_id = u.id'
        );

        $query->where('u.sellerid = ' . $filter['id'])
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->eq('status.id', ':status')
                                , $query->expr()->eq('pq.is_archived', ':archived')
                ))
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->like('u.name', ':filter')
                                , $query->expr()->like('u.sku', ':filter')
                                , $query->expr()->like('s.name', ':filter')
                                , $query->expr()->like('u.price', ':filter')
                                , $query->expr()->like('status.value_text', ':filter')
                ))
                ->setParameter('status', $status)
                ->setParameter('archived', $archived)
                ->setParameter('filter', '%' . $filter['search']['value'] . '%');

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getAllProductOfWpSellerOfProductsForReview($wp_seller_id, $count = false) {
        $queryBuilder = $this->em->createQueryBuilder();

        if ($count) {
            $queryBuilder->select($queryBuilder->expr()->countDistinct('p.id'));
        } else {
            $queryBuilder->select('p', 'pi');
        }

        $query = $queryBuilder->from('App\Entities\Products', 'p')
                ->leftJoin('p.sellerid', 's')
                ->leftJoin('p.product_pending_images', 'pi')
                ->where($queryBuilder->expr()->in('p.status', ':status'))
                ->andWhere('s.wp_seller_id = :wp_seller_id')
                ->setParameter('wp_seller_id', $wp_seller_id)
                ->setParameter('status', [6])
                ->getQuery();

        if ($count) {
            $data = $query->getSingleScalarResult();
        } else {
            $data = $query->getResult(Query::HYDRATE_ARRAY);
        }

        return $data;
    }

    public function getAllProductQuotationOfWpSellerOfStorage($wp_seller_id, $count = false) {

        $queryBuilder = $this->em->createQueryBuilder();

        if ($count) {
            $queryBuilder->select($queryBuilder->expr()->countDistinct('p.id'));
        } else {
            $queryBuilder->select('p','pq', 'pi');
        }

        $query = $queryBuilder->from('App\Entities\Products', 'p')
                ->leftJoin('p.sellerid', 's')
                ->leftJoin('p.product_pending_images', 'pi')->leftjoin(
                'App\Entities\Products_quotation', 'pq', \Doctrine\ORM\Query\Expr\Join::WITH, 'pq.product_id = p.id'
        )
                ->andWhere('s.wp_seller_id = :wp_seller_id')
                ->setParameter('wp_seller_id', $wp_seller_id)
                ->getQuery();

        if ($count) {
            $data = $query->getSingleScalarResult();
        } else {
            $data = $query->getResult(Query::HYDRATE_ARRAY);
        }

        return $data;
    }

}

?>
