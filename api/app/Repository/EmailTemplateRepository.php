<?php

namespace App\Repository;

use App\Entities\Email_template;
use App\Entities\Option_master;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class EmailTemplateRepository extends EntityRepository
{

    /**
     * @var string
     */
    private $class = 'App\Entities\Email_template';

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

   

    public function EmailTemplateOfId($id)
    {
        return $this->em->getRepository($this->class)->findOneBy([
                    'id' => $id
        ]);
    }

    public function create(Email_template $option)
    {

        $this->em->persist($option);
        $this->em->flush();
        return $option->getId();
    }

    public function prepareData($data)
    {
        return new Email_template($data);
    }

    public function getEmailTemplateById($id)
    {
        $query = $this->em->createQueryBuilder()
                ->select('c')
                ->from('App\Entities\Email_template', 'c')
                ->where('c.id = :id')
                ->setParameter('id', $id)
                ->getQuery();
        return $query->getResult(Query::HYDRATE_ARRAY)[0];
    }
    
    public function update(Email_template $category, $data)
    {
        if (isset($data['subject']))
        {
            $category->setSubject($data['subject']);
        }
        if (isset($data['description']))
        {
            $category->setDescription($data['description']);
        }

        $this->em->persist($category);
        $this->em->flush();

        return $category;
    }
}
