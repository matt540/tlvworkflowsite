<?php

namespace App\Repository;

use App\Entities\Email_send_record;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class EmailSendRecordRepository extends EntityRepository
{

    /**
     * @var string
     */
    private $class = 'App\Entities\Email_send_record';

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function EmailSendRecordOfId($id)
    {
        return $this->em->getRepository($this->class)->findOneBy([
                    'id' => $id
        ]);
    }

    public function create(Email_send_record $email_send_record)
    {
        $this->em->persist($email_send_record);
        $this->em->flush();
        return $email_send_record;
    }

    public function getEmailSendRecordsTotal($filter)
    {
        $query = $this->em->createQueryBuilder();
        $query->select($query->expr()->count('m.id'))
                ->from('App\Entities\Email_send_record', 'm')
                ->leftJoin('m.created_by', 'c')
                ->where(
                        $query->expr()->orX(
                                $query->expr()->like('m.subject', ':filter')
                                ,$query->expr()->like('m.email', ':filter')
                                , $query->expr()->like('c.firstname', ':filter')
                                , $query->expr()->like('c.lastname', ':filter')
                ))
                ->setParameter('filter', '%' . $filter['search']['value'] . '%');
        return $query->getQuery()->getSingleScalarResult();
    }

    public function getEmailSendRecords($filter)
    {
        $orderbyclm = 'm.subject';
        if ($filter['order'][0]['column'] == 0)
        {
            $orderbyclm = 'm.email';
        }
        if ($filter['order'][0]['column'] == 1)
        {
            $orderbyclm = 'm.subject';
        }
        if ($filter['order'][0]['column'] == 2)
        {
            $orderbyclm = 'c.firstname';
        }
        if ($filter['order'][0]['column'] == 3)
        {
            $orderbyclm = 'm.created_at';
        }
//        if ($filter['order'][0]['column'] == 1)
//        {
//            $orderbyclm = 'c.status';
//        }

        $query = $this->em->createQueryBuilder();
        $query->select($query->expr()->count('c.id'))
                ->from('App\Entities\Email_send_record', 'c');
        $total = $query->getQuery()->getSingleScalarResult();


        $query = $this->em->createQueryBuilder();
        $query->select(array('m,c'))
                ->from('App\Entities\Email_send_record', 'm')
                ->leftJoin('m.created_by', 'c')
                ->setMaxResults($filter['length'])
                ->setFirstResult($filter['start'])
                ->where(
                        $query->expr()->orX(
                                $query->expr()->like('m.email', ':filter')
                                ,$query->expr()->like('m.subject', ':filter')
                                , $query->expr()->like('c.firstname', ':filter')
                                , $query->expr()->like('c.lastname', ':filter')
//                                ,$query->expr()->like('c.status', ':filter')
                ))
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->orderBy($orderbyclm, $filter['order'][0]['dir']);


        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getEmailSendRecordById($id)
    {
        $query = $this->em->createQueryBuilder()
                ->select('m,c')
                ->from('App\Entities\Email_send_record', 'm')
                ->leftJoin('m.created_by', 'c')
                ->where('m.id = :id')
                ->setParameter('id', $id)
                ->getQuery();
        return $query->getResult(Query::HYDRATE_ARRAY)[0];
    }

    public function prepareData($data)
    {
        return new Email_send_record($data);
    }

}
