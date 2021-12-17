<?php

namespace App\Repository;

use App\Entities\Products_approved;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class ProductsApprovedRepository extends EntityRepository
{

    /**
     * @var string
     */
    private $class = 'App\Entities\Products_approved';

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function create(Products_approved $option)
    {

        $this->em->persist($option);
        $this->em->flush();
        return $option->getId();
    }

    public function update(Products_approved $option, $data)
    {

        if (isset($data['name']))
        {
            $option->setName($data['name']);
        }

        if (isset($data['description']))
        {
            $option->setDescription($data['description']);
        }
        if (isset($data['is_scheduled']))
        {
            $option->setIsScheduled($data['is_scheduled']);
        }
        if (isset($data['sort_description']))
        {
            $option->setSort_description($data['sort_description']);
        }

        if (isset($data['sell_id']))
        {
            $option->setSell_id($data['sell_id']);
        }

        if (isset($data['seller_id']))
        {
            $option->setSeller_id($data['seller_id']);
        }

        if (isset($data['price']))
        {
            $option->setPrice($data['price']);
        }

        if (isset($data['room']))
        {
            $option->setRoom($data['room']);
        }
        if (isset($data['look']))
        {
            $option->setLook($data['look']);
        }
        if (isset($data['color']))
        {
            $option->setColor($data['color']);
        }
        if (isset($data['brand']))
        {
            $option->setBrand($data['brand']);
        }
        if (isset($data['category']))
        {
            $option->setCategory($data['category']);
        }
        if (isset($data['collection']))
        {
            $option->setCollection($data['collection']);
        }
        if (isset($data['con']))
        {
            $option->setCondition($data['con']);
        }
        if (isset($data['status']))
        {
            $option->setStatus($data['status']);
        }

        if (isset($data['materials']))
        {
            $option->setMaterials($data['materials']);
        }
        if (isset($data['diamensions']))
        {
            $option->setDiamensions($data['diamensions']);
        }
        if (isset($data['tlv_suggested_price']))
        {
            $option->setTlv_suggested_price($data['tlv_suggested_price']);
        }

        if (isset($data['images_from']))
        {
            $option->setImages_from($data['images_from']);
        }

        if (isset($data['product_images']))
        {
            $option->setProduct_images($data['product_images']);
        }

        $this->em->persist($option);
        $this->em->flush();
        return 1;
    }

    public function ProductApprovedOfId($id)
    {
        return $this->em->getRepository($this->class)->findOneBy([
                    'id' => $id
        ]);
    }

    public function getProductById($id)
    {
        $query = $this->em->createQueryBuilder()
                ->select('u,pi,sell,room,look,color,brand,category,con,collection')
                ->from('App\Entities\Products_approved', 'u')
                ->leftJoin('u.sell_id', 'sell')
                ->leftJoin('u.product_images', 'pi')
                ->leftJoin('u.room', 'room')
                ->leftJoin('u.look', 'look')
                ->leftJoin('u.color', 'color')
                ->leftJoin('u.brand', 'brand')
                ->leftJoin('u.category', 'category')
                ->leftJoin('u.con', 'con')
                ->leftJoin('u.collection', 'collection')
                ->where('u.id = :id')
                ->setParameter('id', $id)
                ->getQuery();
        $data = $query->getResult(Query::HYDRATE_ARRAY);

        return $data[0];
    }

    public function delete(Products_approved $option)
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
        return new Products_approved($data);
    }

    public function getProductsTotal($filter)
    {
        $query = $this->em->createQueryBuilder();
        $query->select($query->expr()->count('u.id'))
                ->from('App\Entities\Products_approved', 'u')
                ->leftjoin('u.sell_id', 's')
                ->leftjoin('u.status', 'status')
                ->where(
                        $query->expr()->orX(
                                $query->expr()->like('u.name', ':filter')
                                , $query->expr()->like('s.name', ':filter')
                                , $query->expr()->like('u.price', ':filter')
                                , $query->expr()->like('status.value_text', ':filter')
                ))
                ->setParameter('filter', '%' . $filter['search']['value'] . '%');
        return $query->getQuery()->getSingleScalarResult();
    }

    public function getTLVStaffProductsTotal($filter)
    {
        $query = $this->em->createQueryBuilder();
        $query->select($query->expr()->count('u.id'))
                ->from('App\Entities\Products_approved', 'u')
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

        if ($filter['order'][0]['column'] == 0)
        {
            $orderbyclm = 'u.name';
        }
        if ($filter['order'][0]['column'] == 1)
        {
            $orderbyclm = 'u.price';
        }

        if ($filter['order'][0]['column'] == 2)
        {
            $orderbyclm = 'status.id';
        }
//        if ($filter['order'][0]['column'] == 3)
//        {
//            $orderbyclm = 'r.name';
//        }
//
//        if ($filter['order'][0]['column'] == 4)
//        {
//            $orderbyclm = 's.value_text';
//        }

        $query = $this->em->createQueryBuilder();
        $query->select($query->expr()->count('u.id'))
                ->from('App\Entities\Products_approved', 'u');
        $total = $query->getQuery()->getSingleScalarResult();

        $query = $this->em->createQueryBuilder();
        $query->select(array('u.name', 'u.images_from', 'u.price', 'u.is_scheduled', 's.name as sell_name', 'u.id', 'status.id as status_id', 'status.value_text as status_value'))
                ->from('App\Entities\Products_approved', 'u')
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
                ->orderBy($orderbyclm, $filter['order'][0]['dir']);
        ;


        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);
        return array('data' => $data, 'total' => $total);
    }

    public function getAllExportProducts($filter)
    {
        $query = $this->em->createQueryBuilder();

//        $startDate = $filter['start_date'] . '00:00:00';
//        $endDate = $filter['end_date']  . '00:00:00';
        $startDate = $filter['start_date'] . ' 00:00:00';
        $endDate = $filter['end_date'] . ' 11:59:59';
//echo "<pre>";
//print_r($startDate);
//echo "<br>";
//print_r($endDate);
//die;
        $condition1 = $query->expr()->between('u.created_at', ':start_date', ':end_date');

        $query->select('u,pi,sell,room,look,color,brand,category,con,collection')
                ->from('App\Entities\Products_approved', 'u')
                ->leftJoin('u.sell_id', 'sell')
                ->leftJoin('u.product_images', 'pi')
                ->leftJoin('u.room', 'room')
                ->leftJoin('u.look', 'look')
                ->leftJoin('u.color', 'color')
                ->leftJoin('u.brand', 'brand')
                ->leftJoin('u.category', 'category')
                ->leftJoin('u.con', 'con')
                ->leftJoin('u.collection', 'collection');

        $query->where($query->expr()->andX($condition1))
                ->setParameter('start_date', $startDate)
                ->setParameter('end_date', $endDate);

        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);
        return $data;
    }

    public function getTLVStaffProducts($filter)
    {

        if ($filter['order'][0]['column'] == 1)
        {
            $orderbyclm = 'u.name';
        }

        if ($filter['order'][0]['column'] == 2)
        {
            $orderbyclm = 'u.email';
        }
        if ($filter['order'][0]['column'] == 3)
        {
            $orderbyclm = 'r.name';
        }

        if ($filter['order'][0]['column'] == 4)
        {
            $orderbyclm = 's.value_text';
        }

        $query = $this->em->createQueryBuilder();
        $query->select($query->expr()->count('u.id'))
                ->from('App\Entities\Products_approved', 'u');
        $total = $query->getQuery()->getSingleScalarResult();

        $query = $this->em->createQueryBuilder();
        $query->select(array('u.name', 'u.price', 's.name as sell_name', 'u.id'))
                ->from('App\Entities\Products_approved', 'u')
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

}

?>