<?php

namespace App\Repository;

use App\Entities\Select_master;
use App\Entities\Option_master;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class SelectRepository extends EntityRepository
{

    /**
     * @var string
     */
    private $class = 'App\Entities\Select_master';

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

   

    public function SelectOfId($id)
    {
        return $this->em->getRepository($this->class)->findOneBy([
                    'id' => $id
        ]);
    }

    public function create(Option_master $option)
    {

        $this->em->persist($option);
        $this->em->flush();
        return $option->getId();
    }

    public function prepareData($data)
    {
        return new Option_master($data);
    }

}
