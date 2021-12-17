<?php

namespace App\Repository;

use App\Entities\Role;
use App\Entities\Permission;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class RoleRepository extends EntityRepository
{

    /**
     * @var string
     */
    private $class = 'App\Entities\Role';

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function create(Role $role)
    {

        $this->em->persist($role);
        $this->em->flush();
        return $role->getId();
    }

    public function update(Role $role, $data)
    {
        $role->setName($data['name']);

        $this->em->persist($role);
        $this->em->flush();
    }

    public function RoleOfId($id)
    {
        return $this->em->getRepository($this->class)->findOneBy([
                    'id' => $id
        ]);
    }

    public function RoleById($id)
    {
        $query = $this->em->createQueryBuilder();
        $query->select('r.id,r.name')
                ->from('App\Entities\Role', 'r')
                ->where(
                        $query->expr()->andX(
                                $query->expr()->eq('r.id', ':id')
                ))
                ->setParameter('id', $id);
        $qb = $query->getQuery();
        return $qb->getResult(Query::HYDRATE_ARRAY);
    }

    public function delete(Role $role)
    {
        $this->em->remove($role);
        $this->em->flush();
    }

    /**
     * create Theory
     * @return Theory
     */
    public function prepareData($data)
    {
        return new Role($data);
    }

    public function get_roles_total($filter)
    {

        $query = $this->em->createQueryBuilder();
        $query->select($query->expr()->count('r.id'))
                ->from('App\Entities\Role', 'r')
                ->where(
                        $query->expr()->orX(
                                $query->expr()->like('r.name', ':filter')
                ))
                ->setParameter('filter', '%' . $filter['search']['value'] . '%');
        return $query->getQuery()->getSingleScalarResult();
    }

    public function getAllRoles()
    {
        $query = $this->em->createQueryBuilder();
        $query->select('r.id,r.name')
                ->from('App\Entities\Role', 'r');
        $qb = $query->getQuery();

        return $qb->getResult(Query::HYDRATE_ARRAY);
    }

    public function get_roles($filter)
    {
        $query = $this->em->createQueryBuilder();
        $query->select($query->expr()->count('r.id'))
                ->from('App\Entities\Role', 'r');
        $total = $query->getQuery()->getSingleScalarResult();

        $query = $this->em->createQueryBuilder();
        $query->select('r.id,r.name')
                ->from('App\Entities\Role', 'r')
                ->where(
                        $query->expr()->orX(
                                $query->expr()->like('r.name', ':filter')
                ))
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->orderBy('r.name', $filter['order'][0]['dir']);



        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);
        return array('data' => $data, 'total' => $total);
    }

    public function get_all_roles()
    {
        $query = $this->em->createQueryBuilder();
        $query->select('r.id,r.name')
                ->from('App\Entities\Role', 'r');
        $qb = $query->getQuery();

        return $qb->getResult(Query::HYDRATE_ARRAY);
    }

    public function remove_role_permission(Role $role, Permission $permission)
    {

        $role->removePermission($permission); //make sure the removeGroup method is defined in your User model. 
        $this->em->persist($role);
        $this->em->flush();
    }

    public function add_role_permission(Role $role, Permission $permission)
    {

        $role->addPermission($permission); //make sure the removeGroup method is defined in your User model. 
        $this->em->persist($role);
        $this->em->flush();
    }

    public function getPermissionsArray($id)
    {
        $query = $this->em->createQueryBuilder();
        $query->select('p.id')
                ->from('App\Entities\Role', 'r')
                ->join('r.permissions', 'p')
                ->where('r.id =:role')
                ->setParameter('role', $id);


        $qb = $query->getQuery();

        return $qb->getResult(Query::HYDRATE_ARRAY);
    }

}

?>
