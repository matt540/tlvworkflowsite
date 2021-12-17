<?php

namespace App\Repository;

use App\Entities\Sell;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class SellRepository extends EntityRepository {

    /**
     * @var string
     */
    private $class = 'App\Entities\Sell';

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function create(Sell $option) {

        $this->em->persist($option);
        $this->em->flush();
        return $option->getId();
    }

    public function update(Sell $option, $data) {

        if (isset($data['name'])) {
            $option->setName($data['name']);
        }

        
        if (isset($data['user_id'])) {
            $option->setUser_id($data['user_id']);
        }
        
        $this->em->persist($option);
        $this->em->flush();
        
        return 1;
    }

    public function SellOfId($id) {
        return $this->em->getRepository($this->class)->findOneBy([
                    'id' => $id
        ]);
    }

    public function SellById($id) {
        $query = $this->em->createQueryBuilder()
                ->select('u,user')
                ->from('App\Entities\Sell', 'u')
                ->leftJoin('u.user_id', 'user')
                ->where('u.id = :id')
                ->setParameter('id', $id)
                ->getQuery();
        $data = $query->getResult(Query::HYDRATE_ARRAY);

        return $data[0];
    }

    public function delete(Sell $option) {
        $this->em->remove($option);
        $this->em->flush();
    }

    /**
     * create Theory
     * @return Theory
     */
    public function prepareData($data) {
        return new Sell($data);
    }

    public function getSellTotal($filter)
    {
        $query = $this->em->createQueryBuilder();
        $query->select($query->expr()->count('u.id'))
                ->from('App\Entities\Sell', 'u')
                ->leftjoin('u.user_id', 's')
                ->where(
                        $query->expr()->orX(
                                $query->expr()->like('u.name', ':filter')
                                , $query->expr()->like('s.contactname', ':filter')
                ))
                ->setParameter('filter', '%' . $filter['search']['value'] . '%');
        return $query->getQuery()->getSingleScalarResult();
    }

    public function getSell($filter)
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
                ->from('App\Entities\Sell', 'u');
        $total = $query->getQuery()->getSingleScalarResult();

        $query = $this->em->createQueryBuilder();
        $query->select(array('u.name','user.contactname', 'u.id'))
                ->from('App\Entities\Sell', 'u')
                ->leftjoin('u.user_id', 'user')
                ->setMaxResults($filter['length'])
                ->setFirstResult($filter['start'])
                ->where(
                        $query->expr()->orX(
                                $query->expr()->like('u.name', ':filter')
                                , $query->expr()->like('user.contactname', ':filter')
                ))
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->orderBy($orderbyclm, $filter['order'][0]['dir']);;


        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);
        return array('data' => $data, 'total' => $total);
    }

}

?>