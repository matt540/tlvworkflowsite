<?php

namespace App\Repository;

use App\Entities\Images;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class ImagesRepository extends EntityRepository
{

    /**
     * @var string
     */
    private $class = 'App\Entities\Images';

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function create(Images $option)
    {

        $this->em->persist($option);
        $this->em->flush();
        return $option->getId();
    }

    public function update(Images $option, $data)
    {
        if (isset($data['name']))
        {
            $option->setName($data['name']);
        }
        if (isset($data['priority']))
        {
            $option->setPriority($data['priority']);
        }

        $this->em->persist($option);
        $this->em->flush();
        return 1;
    }

    public function ImageOfId($id)
    {
        return $this->em->getRepository($this->class)->findOneBy([
                    'id' => $id
        ]);
    }

    public function getImageById($id)
    {
        $query = $this->em->createQueryBuilder()
                ->select('u')
                ->from('App\Entities\Images', 'u')
                ->where('u.id = :id')
                ->setParameter('id', $id)
                ->getQuery();
        $data = $query->getResult(Query::HYDRATE_ARRAY);

        return $data[0];
    }

    public function delete(Images $option)
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
        return new Images($data);
    }
}

?>