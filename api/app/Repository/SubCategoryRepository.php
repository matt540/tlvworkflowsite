<?php

namespace App\Repository;

use App\Entities\SubCategory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class SubCategoryRepository extends EntityRepository {

    /**

     * @var string

     */
    private $class = 'App\Entities\SubCategory';

    /**

     * @var EntityManager

     */
    private $em;

    public function __construct(EntityManager $em) {

        $this->em = $em;
    }

    public function SubCategoryOfId($id) {

        return $this->em->getRepository($this->class)->findOneBy([
                    'id' => $id
        ]);
    }

    public function SubCategoryOfWpId($id) {

        return $this->em->getRepository($this->class)->findOneBy([
                    'wp_term_id' => $id
        ]);
    }
    public function SubCategoryOfName($name) {

        return $this->em->getRepository($this->class)->findOneBy([
                    'sub_category_name' => $name
        ]);
    }

    public function create(SubCategory $category) {

        $this->em->persist($category);

        $this->em->flush();

        return $category->getId();
    }

    public function getSubCategorysTotal($filter) {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('c.id'))
                ->from('App\Entities\SubCategory', 'c')
                ->leftJoin('c.category_id', 'ci')
                ->where(
                        $query->expr()->orX(
                                $query->expr()->like('c.sub_category_name', ':filter')
                                , $query->expr()->like('ci.category_name', ':filter')
                ))
                ->setParameter('filter', '%' . $filter['search']['value'] . '%');

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getSubCategorys($filter) {

        if ($filter['order'][0]['column'] == 0) {

            $orderbyclm = 'c.sub_category_name';
        }

        if ($filter['order'][0]['column'] == 1) {

            $orderbyclm = 'ci.category_name';
        }

        if ($filter['order'][0]['column'] == 2) {

            $orderbyclm = 'c.status';
        }



        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('c.id'))
                ->from('App\Entities\SubCategory', 'c');

        $total = $query->getQuery()->getSingleScalarResult();



        $soft = $this->em->getFilters()->enable('soft-deleteable');

        $soft->disableForEntity('App\Entities\Category');



        $query = $this->em->createQueryBuilder();

        $query->select(array('c.sub_category_name', 'c.id', 'ci.category_name'))
                ->from('App\Entities\SubCategory', 'c')
                ->leftJoin('c.category_id', 'ci')
                ->setMaxResults($filter['length'])
                ->setFirstResult($filter['start'])
                ->where(
                        $query->expr()->orX(
                                $query->expr()->like('c.sub_category_name', ':filter')

//                                , $query->expr()->like('c.status', ':filter')
                                , $query->expr()->like('ci.category_name', ':filter')
                ))
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->orderBy($orderbyclm, $filter['order'][0]['dir']);





        $qb = $query->getQuery();



        $data = $qb->getResult(Query::HYDRATE_ARRAY);



        $soft->enableForEntity('App\Entities\Category');



        return array('data' => $data, 'total' => $total);
    }

    public function getSubCategorysIDTotal($filter) {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('c.id'))
                ->from('App\Entities\SubCategory', 'c')
                ->leftJoin('c.category_id', 'ci')
                ->where(
                        $query->expr()->orX(
                                $query->expr()->like('c.sub_category_name', ':filter')
                                , $query->expr()->like('ci.category_name', ':filter')
                ))
                ->andWhere('c.category_id = :category_id')
                ->setParameter('category_id', $filter['category_id'])
                ->setParameter('filter', '%' . $filter['search']['value'] . '%');

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getSubCategorysID($filter) {

        if ($filter['order'][0]['column'] == 0) {

            $orderbyclm = 'c.sub_category_name';
        }

        if ($filter['order'][0]['column'] == 1) {

            $orderbyclm = 'c.category_storage_price';
        }



        $query = $this->em->createQueryBuilder();
        $query->select($query->expr()->count('c.id'))
                ->from('App\Entities\SubCategory', 'c')
                ->where('c.category_id = :category_id')
                ->setParameter('category_id', $filter['category_id']);
        $total = $query->getQuery()->getSingleScalarResult();
        $soft = $this->em->getFilters()->enable('soft-deleteable');
        $soft->disableForEntity('App\Entities\Category');



        $query = $this->em->createQueryBuilder();
        $query->select(array('c.sub_category_name', 'c.id', 'ci.category_name', 'c.category_storage_price'))
                ->from('App\Entities\SubCategory', 'c')
                ->leftJoin('c.category_id', 'ci')
                ->setMaxResults($filter['length'])
                ->setFirstResult($filter['start'])
                ->where(
                        $query->expr()->orX(
                                $query->expr()->like('c.sub_category_name', ':filter')

//                                , $query->expr()->like('c.status', ':filter')
                                , $query->expr()->like('ci.category_name', ':filter')
                ))
                ->andWhere('c.category_id = :category_id')
                ->setParameter('category_id', $filter['category_id'])
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->orderBy($orderbyclm, $filter['order'][0]['dir']);
        $qb = $query->getQuery();
        $data = $qb->getResult(Query::HYDRATE_ARRAY);
        $soft->enableForEntity('App\Entities\Category');

        return array('data' => $data, 'total' => $total);
    }

    public function getAllSubCategorys() {

        $query = $this->em->createQueryBuilder();

        $query->select(array('c.sub_category_name', 'c.id', 'ci.category_name', 'ci.id as category_id', 'c.category_storage_price'))
                ->from('App\Entities\SubCategory', 'c')
                ->leftJoin('c.category_id', 'ci')
                ->addOrderBy('c.order_value', 'asc')
                ->addOrderBy('c.sub_category_name', 'asc');

//                ->where('c.status = :status')

        $qb = $query->getQuery();

        return $qb->getResult(Query::HYDRATE_ARRAY);
    }

    public function getSubCategoryById($id) {

        $query = $this->em->createQueryBuilder()
                ->select('c,ci')
                ->from('App\Entities\SubCategory', 'c')
                ->leftJoin('c.category_id', 'ci')
                ->where('c.id = :id')
                ->setParameter('id', $id)
                ->getQuery();

        return $query->getResult(Query::HYDRATE_ARRAY)[0];
    }

    public function getAllChildSubCategorysOfParentId($parent_id) {

        $query = $this->em->createQueryBuilder()
                ->select('c,ci,p')
                ->from('App\Entities\SubCategory', 'c')
                ->leftJoin('c.category_id', 'ci')
                ->leftJoin('c.parent_id', 'p')
                ->where('ci.id = :id')
                ->andWhere('c.parent_id = :parent_id')
                ->orderBy('c.order_value,c.sub_category_name')
                ->setParameter('id', 2)
                ->setParameter('parent_id', $parent_id)
                ->getQuery();

        return $query->getResult(Query::HYDRATE_ARRAY);
    }

    public function getAllChildProductMaterialSubCategorysOfParentId($parent_id) {
        $query = $this->em->createQueryBuilder()
                ->select('c,ci,p')
                ->from('App\Entities\SubCategory', 'c')
                ->leftJoin('c.category_id', 'ci')
                ->leftJoin('c.parent_id', 'p')
                ->where('c.parent_id = :parent_id')
                ->orderBy('c.order_value,c.sub_category_name')
                ->setParameter('parent_id', $parent_id)
                ->getQuery();
        return $query->getResult(Query::HYDRATE_ARRAY);
    }

    public function getAllSubCategorysOfCategoryId($category_id) {

        //if $category_id==2;

        $query = $this->em->createQueryBuilder()
                ->select('c,ci,p')
                ->from('App\Entities\SubCategory', 'c')
                ->leftJoin('c.category_id', 'ci')
                ->leftJoin('c.parent_id', 'p')
                ->where('ci.id = :id')
                ->andWhere('c.parent_id Is NULL')
                ->orderBy('c.sub_category_name')
                ->setParameter('id', $category_id)
                ->getQuery();

        return $query->getResult(Query::HYDRATE_ARRAY);
    }

    public function getAllSubCategoryByCategory($id) {

        $query = $this->em->createQueryBuilder()
                ->select('c,ci')
                ->from('App\Entities\SubCategory', 'c')
                ->leftJoin('c.category_id', 'ci')
                ->where('ci.id = :id')
                ->setParameter('id', $id)
                ->getQuery();

        return $query->getResult(Query::HYDRATE_ARRAY);
    }

    public function prepareData($data) {

        return new SubCategory($data);
    }

    public function update(SubCategory $category, $data) {

        if (isset($data['sub_category_name'])) {

            $category->setSubCategoryName($data['sub_category_name']);
        }

        if (isset($data['parent_id'])) {

            $category->setParentId($data['parent_id']);
        }

        if (isset($data['category_id'])) {

            $category->setCategoryId($data['category_id']);
        }

        if (isset($data['status'])) {

            $category->setStatus($data['status']);
        }

        if (isset($data['category_storage_price'])) {

            $category->setCategory_storage_price($data['category_storage_price']);
        }

        if (isset($data['is_enable'])) {
            $category->setIs_enable($data['is_enable']);
        }



        $this->em->persist($category);

        $this->em->flush();



        return $category;
    }

    public function delete(SubCategory $category) {

        $this->em->remove($category);

        $this->em->flush();
    }

    // MOBILE API FUNCTION

    public function getSubCategoriesByCategoryIDMobileApi($id) {

        $query = $this->em->createQueryBuilder()
                ->select('c')
                ->from('App\Entities\SubCategory', 'c')
                ->leftJoin('c.category_id', 'ci')
                ->where('ci.id = :id')
                ->setParameter('id', $id)
                ->orderBy('c.sub_category_name', 'ASC')
                ->getQuery();

        return $query->getResult(Query::HYDRATE_ARRAY);
    }

}
