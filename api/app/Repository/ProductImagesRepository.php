<?php

namespace App\Repository;

use App\Entities\Product_images;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class ProductImagesRepository extends EntityRepository
{

    /**
     * @var string
     */
    private $class = 'App\Entities\Product_images';

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function create(Product_images $option)
    {
        $this->em->persist($option);
        $this->em->flush();
        return $option->getId();
    }

    public function ProductImagesOfId($id)
    {
        return $this->em->getRepository($this->class)->findOneBy([
                    'id' => $id
        ]);
    }

    public function getProductImageById($id)
    {
        $query = $this->em->createQueryBuilder()
                ->select('u,p')
                ->from('App\Entities\Product_images', 'u')
                ->leftJoin('u.product_id', 'p')
                ->where('u.id = :id')
                ->setParameter('id', $id)
                ->getQuery();
        $data = $query->getResult(Query::HYDRATE_ARRAY);

        return $data[0];
    }
    
    public function getScheduleByProductId($id)
    {
        $query = $this->em->createQueryBuilder()
                ->select('u,p')
                ->from('App\Entities\Product_images', 'u')
                ->leftJoin('u.product_id', 'p')
                ->where('p.id = :id')
                ->setParameter('id', $id)
                ->getQuery();
        $data = $query->getResult(Query::HYDRATE_ARRAY);

        return $data;
    }

    public function delete(Product_images $option)
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
        return new Product_images($data);
    }
}

?>