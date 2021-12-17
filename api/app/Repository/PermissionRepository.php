<?php

namespace App\Repository;

use App\Entities\Permission;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class PermissionRepository extends EntityRepository
{

    /**
     * @var string
     */
    private $class = 'App\Entities\Permission';

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function create(Permission $option)
    {

        $this->em->persist($option);
        $this->em->flush();
        return $option->getId();
    }

    public function update(Permission $permission, $data)
    {
        if (isset($data['name']))
        {
            $permission->setName($data['name']);
        }
        if (isset($data['category']))
        {
            $permission->setCategory($data['category']);
        }
        if (isset($data['title']))
        {
            $permission->setTitle($data['title']);
        }
        $this->em->persist($permission);
        $this->em->flush();
    }

    public function PermissionOfId($id)
    {
        return $this->em->getRepository($this->class)->findOneBy([
                    'id' => $id
        ]);
    }

    public function delete(Permission $option)
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
        return new Permission($data);
    }

    public function get_all_permissions()
    {
        $query = $this->em->createQueryBuilder();
        $query->select('p', 'c')
                ->from('App\Entities\Permission', 'p')
                ->leftjoin('p.category', 'c');
        $qb = $query->getQuery();

        return $qb->getResult(Query::HYDRATE_ARRAY);
    }

    public function get_permissions_total($filter)
    {

        $query = $this->em->createQueryBuilder();
        $query->select($query->expr()->count('r.id'))
                ->from('App\Entities\Permission', 'r')
                ->where(
                        $query->expr()->orX(
                                $query->expr()->like('r.name', ':filter'), $query->expr()->like('r.title', ':filter')
                ))
                ->setParameter('filter', '%' . $filter['search']['value'] . '%');
        return $query->getQuery()->getSingleScalarResult();
    }

    public function get_permissions($filter)
    {

        $query = $this->em->createQueryBuilder();
        $query->select($query->expr()->count('p.id'))
                ->from('App\Entities\Permission', 'p');
        $total = $query->getQuery()->getSingleScalarResult();

        $query = $this->em->createQueryBuilder();
        $query->select('p')
                ->from('App\Entities\Permission', 'p')
                ->where(
                        $query->expr()->orX(
                                $query->expr()->like('p.name', ':filter'), $query->expr()->like('p.title', ':filter')
                ))
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->orderBy('p.name', $filter['order'][0]['dir']);



        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);
        return array('data' => $data, 'total' => $total);
    }

    public function PermissionById($id)
    {
        $query = $this->em->createQueryBuilder();
        $query->select('p.id,p.name,p.title,o.id as category')
                ->from('App\Entities\Permission', 'p')
                ->leftjoin('p.category', 'o')
                ->where(
                        $query->expr()->andX(
                                $query->expr()->eq('p.id', ':id')
                ))
                ->setParameter('id', $id);
        $qb = $query->getQuery();
        return $qb->getResult(Query::HYDRATE_ARRAY);
    }

}

?>