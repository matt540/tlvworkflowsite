<?php

namespace App\Repository;

use App\Entities\Users;
use App\Entities\Session_log;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class SessionRepository extends EntityRepository
{

    /**
     * @var string
     */
    private $class = 'App\Entities\Session_log';

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
        return new Session_log($data);
    }

    public function SessionLogOfUserId($userid, $ip)
    {

        return $this->em->getRepository($this->class)->findOneBy([
                    'user_id' => $userid,
                    'ip_address' => $ip
        ]);
    }
    
    public function getSessionLogFromIP($ip)
    {
        return $this->em->getRepository($this->class)->findOneBy([
                    'ip_address' => $ip
        ]);
    }

    public function create(Session_log $SessionLog)
    {

        $this->em->persist($SessionLog);
        $this->em->flush();
        return $SessionLog->getId();
    }

    public function update(Session_log $SessionLog, $data)
    {
        if (isset($data['session_id']))
        {
            $SessionLog->setSessionId($data['session_id']);
        }
        if (isset($data['user_id']))
        {
            $SessionLog->getUserId($data['user_id']);
        }
        if (isset($data['is_active']))
        {
            $SessionLog->setIsActive($data['is_active']);
        }
        if (isset($data['attempt_count']))
        {
            $SessionLog->setAttemptCount($data['attempt_count']);
        }
        if (isset($data['ip_address']))
        {
            $SessionLog->setIpAddress($data['ip_address']);
        }
        if (isset($data['device_id']))
        {
            $SessionLog->setDeviceId($data['device_id']);
        }

//        if (isset($data['created_by']))
//        {
//            $Ad->setCreatedBy($data['created_by']);
//        }
        $this->em->persist($SessionLog);
        $this->em->flush();
        return $SessionLog->getId();
    }

    public function delete(Session_log $SessionLog)
    {
        $this->em->remove($SessionLog);
        $this->em->flush();
    }

}
