<?php

namespace App\Repository;

use App\Entities\Mail_record;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class MailRecordRepository extends EntityRepository
{

    /**
     * @var string
     */
    private $class = 'App\Entities\Mail_record';

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getMailRecordByBaseName($basename)
    {
        return $this->em->getRepository($this->class)->findOneBy([
                    'file_path' => $basename
        ]);
    }

    public function MailRecordOfId($id)
    {
        return $this->em->getRepository($this->class)->findOneBy([
                    'id' => $id
        ]);
    }

    public function create(Mail_record $mail_record)
    {
        $this->em->persist($mail_record);
        $this->em->flush();
        return $mail_record;
    }

    public function countOfSellerProposalType($seller_id)
    {
        $type = 'proposal';
        $query = $this->em->createQueryBuilder();
        $query->select($query->expr()->count('m.id'))
                ->from('App\Entities\Mail_record', 'm')
                ->leftJoin('m.seller_id', 's')
                ->where(
                        $query->expr()->andX(
                                $query->expr()->eq('s.id', ':seller_id')
                                , $query->expr()->eq('m.from_state', ':type')
                ))
                ->setParameter('seller_id', $seller_id)
                ->setParameter('type', $type);
        return $query->getQuery()->getSingleScalarResult();
    }

    public function countOfSellerProductForReviewType($seller_id)
    {
        $type = 'product_for_review';
        $query = $this->em->createQueryBuilder();
        $query->select($query->expr()->count('m.id'))
                ->from('App\Entities\Mail_record', 'm')
                ->leftJoin('m.seller_id', 's')
                ->where(
                        $query->expr()->andX(
                                $query->expr()->eq('s.id', ':seller_id')
                                , $query->expr()->eq('m.from_state', ':type')
                ))
                ->setParameter('seller_id', $seller_id)
                ->setParameter('type', $type);
        return $query->getQuery()->getSingleScalarResult();
    }

    public function getMailRecordsTotal($filter)
    {
        $query = $this->em->createQueryBuilder();
        $query->select($query->expr()->count('m.id'))
                ->from('App\Entities\Mail_record', 'm')
                ->where(
                        $query->expr()->orX(
//                                $query->expr()->like('c.category_name', ':filter')
                ))
                ->setParameter('filter', '%' . $filter['search']['value'] . '%');
        return $query->getQuery()->getSingleScalarResult();
    }

    public function getMailRecords($filter)
    {
        if ($filter['order'][0]['column'] == 0)
        {
            $orderbyclm = 'm.file_name';
        }
//        if ($filter['order'][0]['column'] == 1)
//        {
//            $orderbyclm = 'c.status';
//        }

        $query = $this->em->createQueryBuilder();
        $query->select($query->expr()->count('c.id'))
                ->from('App\Entities\MailRecord', 'c');
        $total = $query->getQuery()->getSingleScalarResult();

        $query = $this->em->createQueryBuilder();
        $query->select(array('m'))
                ->from('App\Entities\Mail_record', 'm')
                ->setMaxResults($filter['length'])
                ->setFirstResult($filter['start'])
                ->where(
                        $query->expr()->orX(
//                                $query->expr()->like('c.category_name', ':filter')
//                                ,$query->expr()->like('c.status', ':filter')
                ))
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->orderBy($orderbyclm, $filter['order'][0]['dir']);


        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);
        return array('data' => $data, 'total' => $total);
    }

    public function getAllMailRecords()
    {
        $query = $this->em->createQueryBuilder();
        $query->select(array('m'))
                ->from('App\Entities\Mail_record', 'm');
//                ->where('c.status = :status')
//                ->setParameter('status', 'Active');
        $qb = $query->getQuery();
        return $qb->getResult(Query::HYDRATE_ARRAY);
    }

    public function getMailRecordById($id)
    {
        $query = $this->em->createQueryBuilder()
                ->select('c')
                ->from('App\Entities\Mail_record', 'c')
                ->where('c.id = :id')
                ->setParameter('id', $id)
                ->getQuery();
        return $query->getResult(Query::HYDRATE_ARRAY)[0];
    }

    public function prepareData($data)
    {
        return new Mail_record($data);
    }

    public function update(Mail_record $mail_record, $data)
    {
        if (isset($data['subject']))
        {
            $mail_record->setSubject($data['subject']);
        }
        if (isset($data['message']))
        {
            $mail_record->setMessage($data['message']);
        }
        if (isset($data['from_state']))
        {
            $mail_record->setFromState($data['from_state']);
        }
        if (isset($data['file_name']))
        {
            $mail_record->setFileName($data['file_name']);
        }
        if (isset($data['file_path']))
        {
            $mail_record->setFilePath($data['file_path']);
        }
        if (isset($data['seller_id']))
        {
            $mail_record->setSellerId($data['seller_id']);
        }
        if (isset($data['created_by']))
        {
            $mail_record->setCreatedBy($data['created_by']);
        }

        $this->em->persist($mail_record);
        $this->em->flush();

        return $mail_record;
    }

    public function delete(Mail_record $mail_record)
    {
        $this->em->remove($mail_record);
        $this->em->flush();
    }

}
