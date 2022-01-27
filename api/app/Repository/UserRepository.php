<?php

namespace App\Repository;

use App\Entities\Users;
use App\Entities\Session_log;
use App\Entities\Role;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class UserRepository extends EntityRepository {

    /**

     * @var string

     */
    private $class = 'App\Entities\Users';

    /**

     * @var EntityManager

     */
    private $em;

    public function __construct(EntityManager $em) {

        $this->em = $em;
    }

    public function create(Users $user) {



        $this->em->persist($user);

        $this->em->flush();



        return $user->getId();
    }

    public function prepareData($data) {

        return new Users($data);
    }

    public function checkUserExistByEmail($email) {


        return $this->em->getRepository($this->class)->findOneBy(array('email' => $email));
    }

    public function UserOfId($id) {

        return $this->em->getRepository($this->class)->findOneBy([
                    'id' => $id
        ]);
    }

    public function UserByRole($role) {

        $query = $this->em->createQueryBuilder()
                ->select('u')
                ->from('App\Entities\Users', 'u')
                ->where(':role MEMBER OF u.roles')
                ->setParameter('role', $role)
                ->getQuery();

        $data = $query->getResult();



        return $data;
    }

    public function getAllCopywriters() {

        //5 for Copywriter

        $query = $this->em->createQueryBuilder()
                ->select('u')
                ->from('App\Entities\Users', 'u')
                ->where(':role MEMBER OF u.roles')
                ->setParameter('role', 5)
                ->getQuery();

        $data = $query->getResult(Query::HYDRATE_ARRAY);



        return $data;
    }

    public function getAllCopywritersAndAdmins() {

        //5 for Copywriter

        $ids = [1, 5];

        $query = $this->em->createQueryBuilder()
                ->select('u')
                ->from('App\Entities\Users', 'u')
                ->leftjoin('u.roles', 'r')
                ->where('r.id IN (:ids)')
                ->setParameter('ids', $ids)
                ->getQuery();

        $data = $query->getResult(Query::HYDRATE_ARRAY);



        return $data;
    }

    public function getAllAgents() {
        $ids = [3];

        // 43 - Kyla Sullivan
        // 25 - Anna Brown
        // 6 - Betsy Perry
        // 7 - Patricia Espinosa
        // 50 - Molly Blankenship

        $user_ids = [43, 25, 6, 7, 50];

        $query = $this->em->createQueryBuilder()
                ->select('u')
                ->from('App\Entities\Users', 'u')
                ->leftjoin('u.roles', 'r')
                ->where('r.id IN (:ids)')
                ->orWhere('u.id IN (:user_ids)')
                ->setParameter('ids', $ids)
                ->setParameter('user_ids', $user_ids)
                ->getQuery();

        $data = $query->getResult(Query::HYDRATE_ARRAY);
        return $data;
    }

    public function UserOfCredentials($credentials) {

        return $this->em->getRepository($this->class)->findOneBy($credentials);
    }

    public function get_users_count() {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('u.id'))
                ->from('App\Entities\Users', 'u');



        return $query->getQuery()->getSingleScalarResult();
    }

    public function getUsersTotal($filter) {



        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('u.id'))
                ->from('App\Entities\Users', 'u')
                ->leftjoin('u.roles', 'r')
                ->leftjoin('u.status', 's')
                ->where(
                        $query->expr()->orX(
                                $query->expr()->like("concat(u.firstname,' ',u.lastname)", ':filter')
                                , $query->expr()->like("concat(u.lastname,' ',u.firstname)", ':filter')
                                , $query->expr()->like('u.firstname', ':filter')
                                , $query->expr()->like('u.lastname', ':filter')
                                , $query->expr()->like('u.email', ':filter')
                                , $query->expr()->like('s.value_text', ':filter')
                                , $query->expr()->like('r.name', ':filter')
                ))
                ->setParameter('filter', '%' . $filter['search']['value'] . '%');

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getUsers($filter) {



        if ($filter['order'][0]['column'] == 1) {

            $orderbyclm = "concat(u.firstname,' ',u.lastname)";
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
                ->from('App\Entities\Users', 'u');

        $total = $query->getQuery()->getSingleScalarResult();



        $query = $this->em->createQueryBuilder();

        $query->select(array('u.firstname', 'u.lastname', 'r.name', 'u.email', 's.value_text as status', 'u.profile_image', 'u.id'))
                ->from('App\Entities\Users', 'u')
                ->leftjoin('u.roles', 'r')
                ->leftjoin('u.status', 's')
                ->setMaxResults($filter['length'])
                ->setFirstResult($filter['start'])
                ->where(
                        $query->expr()->orX(
                                $query->expr()->like("concat(u.firstname,' ',u.lastname)", ':filter')
                                , $query->expr()->like("concat(u.lastname,' ',u.firstname)", ':filter')
                                , $query->expr()->like('u.firstname', ':filter')
                                , $query->expr()->like('u.lastname', ':filter')
                                , $query->expr()->like('u.email', ':filter')
                                , $query->expr()->like('s.value_text', ':filter')
                                , $query->expr()->like('r.name', ':filter')
                ))
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->orderBy($orderbyclm, $filter['order'][0]['dir']);

        ;





        $qb = $query->getQuery();



        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getUserById($id) {

        $query = $this->em->createQueryBuilder()
                ->select('u,r,s')
                ->from('App\Entities\Users', 'u')
                ->leftjoin('u.roles', 'r')
                ->leftjoin('u.status', 's')
                ->where('u.id = :id')
                ->setParameter('id', $id)
                ->getQuery();

        return $query->getResult(Query::HYDRATE_ARRAY)[0];
    }

    public function update_agreement($user_type) {

        $qb = $this->em->createQueryBuilder();

        $q = $qb->update('App\Entities\Users', 'u')
                        ->set('u.is_agreed', 0)
                        ->where(':role MEMBER OF u.roles')
                        ->setParameter('role', $user_type)->getQuery();



        $p = $q->execute();
    }

    public function delete(Users $user) {

        $this->em->remove($user);

        $this->em->flush();
    }

    public function update(Users $user, $data) {



        if (isset($data['companyname'])) {

            $user->setCompanyName($data['companyname']);
        }

        if (isset($data['firstname'])) {

            $user->setFirstname($data['firstname']);
        }

        if (isset($data['lastname'])) {

            $user->setLastname($data['lastname']);
        }

        if (isset($data['username'])) {

            $user->setUserName($data['username']);
        }

        if (isset($data['payment_type'])) {

            $user->setPaymentType($data['payment_type']);
        }

        if (isset($data['phone'])) {

            $user->setPhone($data['phone']);
        }

        if (isset($data['alternate_phone'])) {

            $user->setAlternate_phone($data['alternate_phone']);
        }



        if (isset($data['email'])) {

            $user->setEmail($data['email']);
        }

        if (isset($data['status'])) {

            $user->setStatus($data['status']);
        }

        if (isset($data['remember_token'])) {

            $user->setRememberToken($data['remember_token']);
        }

        if (isset($data['password'])) {

            $user->setPassword($data['password']);
        }

        if (isset($data['profile_image'])) {

            $user->setProfileImage($data['profile_image']);
        }

        if (isset($data['cc'])) {

            $user->setCc($data['cc']);
        }

        if (isset($data['card_name'])) {

            $user->setCard_name($data['card_name']);
        }

        if (isset($data['card_type'])) {

            $user->setCard_type($data['card_type']);
        }

        if (isset($data['sec_code'])) {

            $user->setSec_code($data['sec_code']);
        }

        if (isset($data['exp'])) {

            $user->setExp($data['exp']);
        }



        $this->em->persist($user);

        $this->em->flush();



        return $user;
    }

    public function getAllUsers() {

        $query = $this->em->createQueryBuilder();

        $query->select(array('company.company_name', 'company.id as company_id', 'u.firstname', 'u.lastname', 'u.phone', 'r.name', 'u.email', 'status.value_text as status', 'u.id', 'pt.value_text as payment_type'))
                ->from('App\Entities\Users', 'u')
                ->leftjoin('u.roles', 'r')
                ->leftjoin('u.status', 'status')
                ->leftjoin('u.companyname', 'company')
                ->leftjoin('u.payment_type', 'pt');



        $qb = $query->getQuery();



        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return $data;
    }

    public function getAllCompany() {

        $query = $this->em->createQueryBuilder();

        $query->select(array('u.companyname'))
                ->from('App\Entities\Users', 'u')
                ->groupBy('u.companyname');



        $qb = $query->getQuery();



        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return $data;
    }

    public function getPermissions($id) {

        $query = $this->em->createQueryBuilder()
                ->select('Distinct p.name')
                ->from('App\Entities\Users', 'u')
                ->leftjoin('u.roles', 'r')
                ->leftjoin('r.permissions', 'p')
                ->where('u.id = :id')
                ->setParameter('id', $id)
                ->getQuery();

        $result = $query->getResult();

        $permissions = array_map(function($value) {

            return $value['name'];
        }, $result);

        return $permissions;
    }

    public function getUserByEmail($email) {


        $query = $this->em->createQueryBuilder()
                ->select('u', 'r')
                ->from('App\Entities\Users', 'u')
                ->leftjoin('u.roles', 'r')
                ->leftjoin('u.status', 's')
                ->where('u.email = :email')
                ->setParameter('email', $email)
                ->getQuery();


        $data = $query->getResult(Query::HYDRATE_ARRAY);

        if (count($data) > 0) {

            return $data[0];
        } else {

            return array();
        }
    }

}

?>
