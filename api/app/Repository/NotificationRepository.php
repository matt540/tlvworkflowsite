<?php

namespace App\Repository;

use App\Entities\Notifications;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Auth;

class NotificationRepository extends EntityRepository
{

    /**
     * @var string
     */
    private $class = 'App\Entities\Notifications';

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function create(Notifications $notification)
    {

        $this->em->persist($notification);
        $this->em->flush();
        return $notification->getId();
    }

    public function update(Notifications $notification, $data)
    {

        if (isset($data['is_read']))
        {
            $notification->setIsRead($data['is_read']);
        }
        if (isset($data['notification_url']))
        {
            $notification->setNotificationUrl($data['notification_url']);
        }

        $this->em->persist($notification);
        $this->em->flush();
    }

    public function notificationOfId($id)
    {

        return $this->em->getRepository($this->class)->findOneBy([
                    'id' => $id
        ]);
    }

    public function verify(Notifications $notification)
    {

        $this->em->persist($notification);
        $this->em->flush();
    }

    public function delete(Notifications $notification)
    {
        $this->em->remove($notification);
        $this->em->flush();
    }

    /**
     * create Theory
     * @return Theory
     */
    public function prepareData($data)
    {
        return new Notifications($data);
    }

    public function getNotificationCountById($id)
    {
        $query = $this->em->createQueryBuilder();
        $query->select($query->expr()->count('n.id'))
                ->from('App\Entities\Notifications', 'n')
                ->leftJoin('n.user_id', 'u')
                ->where(
                        $query->expr()->andX(
                                $query->expr()->eq('n.user_id', ':id'), $query->expr()->eq('n.is_read', ':is_read')
                        )
                )
                ->setParameter('id', $id)
                ->setParameter('is_read', 0);
        return $query->getQuery()->getSingleScalarResult();
    }

    public function getNotifications($id, $limit)
    {
        $query = $this->em->createQueryBuilder();
        $query->select(array('n', 'u'))
                ->from('App\Entities\Notifications', 'n')
                ->leftJoin('n.user_id', 'u')
                ->setMaxResults($limit['length'])
                ->setFirstResult($limit['start'])
                ->where(
                        $query->expr()->andX(
                                $query->expr()->eq('n.user_id', ':id'), $query->expr()->eq('n.is_read', ':is_read')
                        )
                )
                ->setParameter('id', $id)
                ->setParameter('is_read', 0)
                ->orderBy('n.id', 'DESC');
        $qb = $query->getQuery();

        return $qb->getResult(Query::HYDRATE_ARRAY);
    }

}

?>