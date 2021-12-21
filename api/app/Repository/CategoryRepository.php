<?php



namespace App\Repository;



use App\Entities\Category;

use Doctrine\ORM\EntityManager;

use Doctrine\ORM\EntityRepository;

use Doctrine\ORM\Query;



class CategoryRepository extends EntityRepository

{



    /**

     * @var string

     */

    private $class = 'App\Entities\Category';



    /**

     * @var EntityManager

     */

    private $em;



    public function __construct(EntityManager $em)

    {

        $this->em = $em;

    }



    public function CategoryOfId($id)

    {

        return $this->em->getRepository($this->class)->findOneBy([

                    'id' => $id

        ]);

    }



    public function create(Category $category)

    {

        $this->em->persist($category);

        $this->em->flush();

        return $category->getId();

    }



    public function getCategorysTotal($filter)

    {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('c.id'))

                ->from('App\Entities\Category', 'c')

                ->where(

                        $query->expr()->orX(

                                $query->expr()->like('c.category_name', ':filter')

                ))

                ->setParameter('filter', '%' . $filter['search']['value'] . '%');

        return $query->getQuery()->getSingleScalarResult();

    }



    public function getCategorys($filter)

    {

        if ($filter['order'][0]['column'] == 0)

        {

            $orderbyclm = 'c.category_name';

        }

//        if ($filter['order'][0]['column'] == 1)

//        {

//            $orderbyclm = 'c.status';

//        }



        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('c.id'))

                ->from('App\Entities\Category', 'c');

        $total = $query->getQuery()->getSingleScalarResult();



        $query = $this->em->createQueryBuilder();

        $query->select(array('c.category_name', 'c.id'))

                ->from('App\Entities\Category', 'c')

                ->setMaxResults($filter['length'])

                ->setFirstResult($filter['start'])

                ->where(

                        $query->expr()->orX(

                                $query->expr()->like('c.category_name', ':filter')

//                                ,$query->expr()->like('c.status', ':filter')

                ))

                ->setParameter('filter', '%' . $filter['search']['value'] . '%')

                ->orderBy($orderbyclm, $filter['order'][0]['dir']);





        $qb = $query->getQuery();



        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);

    }



    public function getAllCategorys()

    {

        $query = $this->em->createQueryBuilder();

        $query->select(array('c.category_name', 'c.id'))

                ->from('App\Entities\Category', 'c')
                ->andWhere('c.deletedAt is NULL');

//                ->where('c.status = :status')

//                ->setParameter('status', 'Active');

        $qb = $query->getQuery();

        return $qb->getResult(Query::HYDRATE_ARRAY);

    }



    public function getCategoryById($id)

    {

        $query = $this->em->createQueryBuilder()

                ->select('c')

                ->from('App\Entities\Category', 'c')

                ->where('c.id = :id')

                ->setParameter('id', $id)

                ->getQuery();

        return $query->getResult(Query::HYDRATE_ARRAY)[0];

    }



    public function prepareData($data)

    {

        return new Category($data);

    }



    public function update(Category $category, $data)

    {

        if (isset($data['category_name']))

        {

            $category->setCategoryName($data['category_name']);

        }

        if (isset($data['status']))

        {

            $category->setStatus($data['status']);

        }



        $this->em->persist($category);

        $this->em->flush();



        return $category;

    }



    public function delete(Category $category)

    {

        $this->em->remove($category);

        $this->em->flush();

    }



}

