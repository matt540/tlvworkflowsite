<?php

namespace App\Repository;

use App\Entities\ProductQuoteRenew;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class ProductQuoteRenewRepository extends EntityRepository
{

    /**
     * @var string
     */
    private $class = 'App\Entities\ProductQuoteRenew';

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * create Theory
     * @return Theory
     */
    public function prepareData($data)
    {
        return new ProductQuoteRenew($data);
    }

    public function create(ProductQuoteRenew $option)
    {

        $this->em->persist($option);
        $this->em->flush();
        return $option;
    }

    public function update(ProductQuoteRenew $option, $data)
    {

        if (isset($data['name']))
        {
            $option->setName($data['name']);
        }
        if (isset($data['product_quote_id']))
        {
            $option->setProductQuoteId($data['product_quote_id']);
        }
        if (isset($data['seller_id']))
        {
            $option->setSellerId($data['seller_id']);
        }
        if (isset($data['data_json']))
        {
            $option->setDataJson($data['data_json']);
        }
        if (isset($data['wp_product_id']))
        {
            $option->setWpProductId($data['wp_product_id']);
        }

        $this->em->persist($option);
        $this->em->flush();
    }

    public function ofId($id)
    {
        return $this->em->getRepository($this->class)->findOneBy([
                    'id' => $id
        ]);
    }

    public function getAllOfSellerId($seller_id, $filter = null)
    {
        $query = $this->em->createQueryBuilder();
        $query->select($query->expr()->count('pqr.id'))
                ->from('App\Entities\ProductQuoteRenew', 'pqr')
                ->leftJoin('pqr.seller_id', 's')
                ->where('s.id=:id')
                ->setParameter('id', $seller_id);
        $total = $query->getQuery()->getSingleScalarResult();

        $query = $this->em->createQueryBuilder();
        $query->select('pqr,pq,p')
                ->from('App\Entities\ProductQuoteRenew', 'pqr')
                ->leftJoin('pqr.seller_id', 's')
                ->leftJoin('pqr.product_quote_id', 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->where('s.id=:id')
                ->setParameter('id', $seller_id);


        if ($filter)
        {
            $query->setMaxResults($filter['length']);
            if (isset($filter['last']) && $filter['last'] != 0)
            {
                $query->andWhere('pqr.id > :pqr_id')
                        ->setParameter('pqr_id', $filter['last']);
            }
        }
        $qb = $query->getQuery();
        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        $final_data = [];
        $final_data['data'] = $data;
        $final_data['total'] = $total;


        return $final_data;
    }

}
