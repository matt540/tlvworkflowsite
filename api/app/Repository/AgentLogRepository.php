<?php

namespace App\Repository;

use App\Entities\AgentLog;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Tymon\JWTAuth\Facades\JWTAuth;

class AgentLogRepository extends EntityRepository {

    private $class = 'App\Entities\AgentLog';
    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function create(AgentLog $agent_log) {
        $this->em->persist($agent_log);
        $this->em->flush();
        return $agent_log->getId();
    }

    public function prepareData($data) {
        return new AgentLog($data);
    }

    public function update(AgentLog $agentLog, $data) {

        if (isset($data['agent_id'])) {
            $agentLog->setAgent_id($data['agent_id']);
        }

        if (isset($data['seller_id'])) {
            $agentLog->setSeller_id($data['seller_id']);
        }

        if (isset($data['photo_shoot_date'])) {
            $agentLog->setPhoto_shoot_date($data['photo_shoot_date']);
        }

        if (isset($data['photo_shoot_location'])) {
            $agentLog->setPhoto_shoot_location($data['photo_shoot_location']);
        }

        if (isset($data['total_products_photographed'])) {
            $agentLog->setTotal_products_photographed($data['total_products_photographed']);
        }

        if (isset($data['payment_total'])) {
            $agentLog->setPayment_total($data['payment_total']);
        }

        if (isset($data['invoice'])) {
            $agentLog->setInvoice($data['invoice']);
        }

        if (isset($data['aditional_details'])) {
            $agentLog->setAditional_details($data['aditional_details']);
        }

        if (isset($data['is_archive'])) {
            $agentLog->set_is_archive($data['is_archive']);
        }

        if (isset($data['is_paid'])) {
            $agentLog->set_is_paid($data['is_paid']);
        }

        if (isset($data['vignettes'])) {
            $agentLog->setVignettes($data['vignettes']);
        }

        if (isset($data['payment_date'])) {
            $agentLog->setPayment_date($data['payment_date']);
        }

        if (isset($data['payment_made_by'])) {
            $agentLog->setPayment_made_by($data['payment_made_by']);
        }

        if (isset($data['agent_log_invoice_images'])) {
            $agentLog->setAgent_log_invoice_images($data['agent_log_invoice_images']);
        }

        $this->em->persist($agentLog);
        $this->em->flush();
        return $agentLog;
    }

    public function delete(AgentLog $agentLog) {
        $this->em->remove($agentLog);
        $this->em->flush();
    }

    public function getAgentLogs($filters) {

        $authUser = JWTAuth::parseToken()->authenticate();

        $role_id = $authUser->getRoles()[0]->getId();
        $user_id = $authUser->getId();

        switch ($filters['order'][0]['column']) {
            case 1:
                $orderby = 'agent.lastname';
                break;
            case 3:
                $orderby = 'al.photo_shoot_location';
                break;
            case 4:
                $orderby = 'al.total_products_photographed';
                break;
            case 5:
                $orderby = 'al.payment_total';
                break;
            case 0:
            default:
                $orderby = 'agent.firstname';
        }

        $totalQuery = $this->em->createQueryBuilder();

        $totalQuery->select($totalQuery->expr()->count('al.id'))
                ->from($this->class, 'al')
                ->leftJoin('al.agent_id', 'agent')
                ->leftJoin('al.seller_id', 'seller')
                ->andWhere('al.is_archive = 0');
        
        if (isset($filters['paid'])) {
            $totalQuery->andWhere('al.is_paid = 1');
        } else {
            $totalQuery->andWhere('al.is_paid = 0');
        }

        $total = $totalQuery->getQuery()->getSingleScalarResult();

        $query = $this->em->createQueryBuilder();

        $query->select(array('al', 'agent', 'seller'))
                ->from($this->class, 'al')
                ->leftJoin('al.agent_id', 'agent')
                ->leftJoin('al.seller_id', 'seller')
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->like('agent.firstname', ':filter'),
                                $query->expr()->like('al.photo_shoot_location', ':filter'),
                                $query->expr()->like('al.payment_total', ':filter')
                        )
                )
                ->andWhere('al.is_archive = 0')
                ->setMaxResults($filters['length'])
                ->setFirstResult($filters['start'])
                ->orderBy($orderby, $filters['order'][0]['dir'])
                ->setParameter('filter', '%' . $filters['search']['value'] . '%');

        if (isset($filters['paid'])) {
            $query->andWhere('al.is_paid = 1');
        } else {
            $query->andWhere('al.is_paid = 0');
        }

        if ($role_id == 3) {
            $query->andWhere('al.agent_id = :agent_id');
            $query->setParameter('agent_id', $user_id);
        }

        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return ['data' => $data, 'total' => $total];
    }

    public function getAgentLogsTotal($filters) {

        $authUser = JWTAuth::parseToken()->authenticate();

        $role_id = $authUser->getRoles()[0]->getId();
        $user_id = $authUser->getId();

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('al.id'))
                ->from($this->class, 'al')
                ->leftjoin('al.agent_id', 'agent')
                ->leftJoin('al.seller_id', 'seller')
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->like('agent.firstname', ':filter'),
                                $query->expr()->like('al.photo_shoot_location', ':filter'),
                                $query->expr()->like('al.payment_total', ':filter')
                        )
                )
                ->andWhere('al.is_archive = 0')
                ->setParameter('filter', '%' . $filters['search']['value'] . '%');

        if (isset($filters['paid'])) {
            $query->andWhere('al.is_paid = 1');
        } else {
            $query->andWhere('al.is_paid = 0');
        }
        
        if ($role_id == 3) {
            $query->andWhere('al.agent_id = :agent_id');
            $query->setParameter('agent_id', $user_id);
        }

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getAgentLogById($id) {
        $qb = $this->em->createQueryBuilder();

        $query = $qb->select('al', 'agent', 'seller', 'invoice')
                ->from($this->class, 'al')
                ->leftjoin('al.agent_id', 'agent')
                ->leftjoin('al.seller_id', 'seller')
                ->leftjoin('al.agent_log_invoice_images', 'invoice')
                ->where('al.id = :id')
                ->setParameter('id', $id)
                ->getQuery();

        $data = $query->getResult(Query::HYDRATE_ARRAY);
        return $data;
    }

    public function getAgentLogObjById($id) {

        $qb = $this->em->createQueryBuilder();

        $query = $qb->select('al')
                ->from($this->class, 'al')
                ->where('al.id = :id')
                ->setParameter('id', $id)
                ->getQuery();

        $data = $query->getResult();

        return $data[0];
    }

    public function getAgentArchiveLogs($filters) {

        $authUser = JWTAuth::parseToken()->authenticate();

        $role_id = $authUser->getRoles()[0]->getId();
        $user_id = $authUser->getId();

        switch ($filters['order'][0]['column']) {
            case 1:
                $orderby = 'agent.lastname';
                break;
            case 3:
                $orderby = 'al.photo_shoot_location';
                break;
            case 4:
                $orderby = 'al.total_products_photographed';
                break;
            case 5:
                $orderby = 'al.payment_total';
                break;
            case 0:
            default:
                $orderby = 'agent.firstname';
        }

        $totalQuery = $this->em->createQueryBuilder();

        $totalQuery->select($totalQuery->expr()->count('al.id'))
                ->from($this->class, 'al')
                ->leftJoin('al.agent_id', 'agent')
                ->leftJoin('al.seller_id', 'seller')
                ->andWhere('al.is_archive = 1');

        $total = $totalQuery->getQuery()->getSingleScalarResult();

        $query = $this->em->createQueryBuilder();

        $query->select(array('al', 'agent', 'seller'))
                ->from($this->class, 'al')
                ->leftJoin('al.agent_id', 'agent')
                ->leftJoin('al.seller_id', 'seller')
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->like('agent.firstname', ':filter'),
                                $query->expr()->like('al.photo_shoot_location', ':filter'),
                                $query->expr()->like('al.payment_total', ':filter')
                        )
                )
                ->andWhere('al.is_archive = 1')
                ->setMaxResults($filters['length'])
                ->setFirstResult($filters['start'])
                ->orderBy($orderby, $filters['order'][0]['dir'])
                ->setParameter('filter', '%' . $filters['search']['value'] . '%');

        if ($role_id == 3) {
            $query->andWhere('al.agent_id = :agent_id');
            $query->setParameter('agent_id', $user_id);
        }

        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return ['data' => $data, 'total' => $total];
    }

    public function getAgentArchiveLogsTotal($filters) {

        $authUser = JWTAuth::parseToken()->authenticate();

        $role_id = $authUser->getRoles()[0]->getId();
        $user_id = $authUser->getId();

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('al.id'))
                ->from($this->class, 'al')
                ->leftjoin('al.agent_id', 'agent')
                ->leftJoin('al.seller_id', 'seller')
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->like('agent.firstname', ':filter'),
                                $query->expr()->like('al.photo_shoot_location', ':filter'),
                                $query->expr()->like('al.payment_total', ':filter')
                        )
                )
                ->andWhere('al.is_archive = 1')
                ->setParameter('filter', '%' . $filters['search']['value'] . '%');

        if ($role_id == 3) {
            $query->andWhere('al.agent_id = :agent_id');
            $query->setParameter('agent_id', $user_id);
        }

        return $query->getQuery()->getSingleScalarResult();
    }

}
