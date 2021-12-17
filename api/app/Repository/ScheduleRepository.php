<?php

namespace App\Repository;

use App\Entities\Schedule;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class ScheduleRepository extends EntityRepository
{

    /**
     * @var string
     */
    private $class = 'App\Entities\Schedule';

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function create(Schedule $option)
    {

        $this->em->persist($option);
        $this->em->flush();
        return $option;
    }

    public function update(Schedule $option, $data)
    {

        if (isset($data['date']))
        {
            $option->setDate($data['date']);
        }

        if (isset($data['time']))
        {
            $option->setTime($data['time']);
        }

        $this->em->persist($option);
        $this->em->flush();
        return 1;
    }

    public function ScheduleOfProductId($id)
    {
        return $this->em->getRepository($this->class)->findOneBy([
                    'product_id' => $id
        ]);
    }

    public function ScheduleOfProductQuotId($id)
    {
        return $this->em->getRepository($this->class)->findOneBy([
                    'product_quot_id' => $id
        ]);
    }

    public function ScheduleOfId($id)
    {
        return $this->em->getRepository($this->class)->findOneBy([
                    'id' => $id
        ]);
    }

    public function getAllSchedulesOfProductQuotationId($product_quot_id)
    {
        $query = $this->em->createQueryBuilder()
                ->select('u.id')
                ->from('App\Entities\Schedule', 'u')
                ->leftJoin('u.product_quot_id', 'pq')
                ->where('pq.id = :id')
                ->setParameter('id', $product_quot_id)
                ->getQuery();
        $data = $query->getResult(Query::HYDRATE_ARRAY);
        return $data;
    }

    public function getScheduleById($data)
    {
        $query = $this->em->createQueryBuilder();
        $query->select('u,pq')
                ->from('App\Entities\Schedule', 'u')
                ->leftJoin('u.product_quot_id', 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->leftJoin('p.sellerid', 's')
                ->where(
                        $query->expr()->andX(
                                $query->expr()->eq('s.wp_seller_id', ':id')
                                , $query->expr()->eq('u.date', ':date')
                                , $query->expr()->eq('u.time', ':time')
                ))
                ->setParameter('date', $data['date'])
                ->setParameter('time', $data['time'])
                ->setParameter('id', $data['id'])
                ->groupBy('u.date, u.time, p.sellerid');
        
        $qb = $query->getQuery();
        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return $data[0];
    }

    public function getScheduleByProductId($id)
    {
        $query = $this->em->createQueryBuilder()
                ->select('u,p')
                ->from('App\Entities\Schedule', 'u')
                ->leftJoin('u.product_id', 'p')
                ->where('p.id = :id')
                ->setParameter('id', $id)
                ->getQuery();
        $data = $query->getResult(Query::HYDRATE_ARRAY);

        return $data;
    }

    public function getScheduleByProductQuotId($id)
    {
        $query = $this->em->createQueryBuilder()
                ->select('u,pq,pp')
                ->from('App\Entities\Schedule', 'u')
                ->leftJoin('u.product_id', 'pp')
                ->leftJoin('u.product_quot_id', 'pq')
                ->where('pq.id = :id')
                ->setParameter('id', $id)
                ->getQuery();
        $data = $query->getResult(Query::HYDRATE_ARRAY);

        return $data;
    }

    public function delete(Schedule $option)
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
        return new Schedule($data);
    }

    public function getSchedulesTotal($filter)
    {
        $query = $this->em->createQueryBuilder();
        $query->select($query->expr()->count('u.id'))
                ->from('App\Entities\Schedule', 'u')
                ->leftjoin('u.product_id', 'pp')
                ->leftjoin('u.product_quot_id', 'pq')
                ->leftjoin('pq.product_id', 'pqp')
                ->where(
                        $query->expr()->orX(
                                $query->expr()->like('u.date', ':filter')
                                , $query->expr()->like('u.time', ':filter')
                                , $query->expr()->like('u.seller_name', ':filter')
                ))
                ->setParameter('filter', '%' . $filter['search']['value'] . '%');
        return $query->getQuery()->getSingleScalarResult();
    }

    public function getSchedules($filter)
    {
        $orderbyclm = 'u.created_at';

        $query = $this->em->createQueryBuilder();
        $query->select($query->expr()->count('u.id'))
                ->from('App\Entities\Schedule', 'u');
//                ->groupBy('u.date, u.time');
        $total = $query->getQuery()->getSingleScalarResult();

        $query = $this->em->createQueryBuilder();
        $query->select(array('u.id', 'u.date as schedule_date', 'u.time as schedule_time', 'u.seller_name', 'u.seller_id', 'pqp.name as product_name'))
                ->from('App\Entities\Schedule', 'u')
                ->leftjoin('u.product_id', 'pp')
                ->leftjoin('u.product_quot_id', 'pq')
                ->leftjoin('pq.product_id', 'pqp')
                ->setMaxResults($filter['length'])
                ->setFirstResult($filter['start'])
                ->where(
                        $query->expr()->orX(
                                $query->expr()->like('u.date', ':filter')
                                , $query->expr()->like('u.time', ':filter')
//                                , $query->expr()->like('pp.name', ':filter')
                                , $query->expr()->like('u.seller_name', ':filter')
                ))
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->orderBy($orderbyclm, $filter['order'][0]['dir'])
                ->groupBy('u.date, u.time, pqp.sellerid');

        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);
        return array('data' => $data, 'total' => $total);
    }

    public function getSellerAllSchedule($data)
    {
        $query = $this->em->createQueryBuilder();
        $query->select(array('u.id', 'pqp.id as product_id', 'pq.id as product_quote_id'))
                ->from('App\Entities\Schedule', 'u')
                ->leftjoin('u.product_quot_id', 'pq')
                ->leftjoin('pq.product_id', 'pqp')
                ->leftjoin('pqp.sellerid', 's')
                ->where(
                        $query->expr()->andX(
                                $query->expr()->eq('s.wp_seller_id', ':id')
                                , $query->expr()->eq('u.date', ':date')
                                , $query->expr()->eq('u.time', ':time')
                ))
                ->setParameter('date', $data['date'])
                ->setParameter('time', $data['time'])
                ->setParameter('id', $data['id']);
        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return $data;
    }

}

?>