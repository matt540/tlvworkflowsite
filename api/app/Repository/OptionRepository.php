<?phpnamespace App\Repository;use App\Entities\Option_master;use Doctrine\ORM\EntityManager;use Doctrine\ORM\EntityRepository;use Doctrine\ORM\Query;class OptionRepository extends EntityRepository {    /**     * @var string     */    private $class = 'App\Entities\Option_master';    /**     * @var EntityManager     */    private $em;    public function __construct(EntityManager $em) {        $this->em = $em;    }    public function create(Option_master $option) {        $this->em->persist($option);        $this->em->flush();        return $option;    }    public function update(Option_master $option, $data) {        if (isset($data['value_text'])) {            $option->setValueText($data['value_text']);        }        if (isset($data['key_text'])) {            $option->setKeyText($data['key_text']);        }        if (isset($data['select_id'])) {            $option->setSelectId($data['select_id']);        }        $this->em->persist($option);        $this->em->flush();    }    public function OptionOfId($id) {        return $this->em->getRepository($this->class)->findOneBy([                    'id' => $id        ]);    }    public function OptionById($id) {        $query = $this->em->createQueryBuilder()                ->select('u')                ->from('App\Entities\Option_master', 'u')                ->where('u.id = :id')                ->setParameter('id', $id)                ->getQuery();        $data = $query->getResult(Query::HYDRATE_ARRAY);        return $data[0];    }    public function delete(Option_master $option) {        $this->em->remove($option);        $this->em->flush();    }    /**     * create Theory     * @return Theory     */    public function prepareData($data) {        return new Option_master($data);    }    public function get_all_of_select_id($id) {        $query = $this->em->createQueryBuilder();        $query->select('o')                ->from('App\Entities\Option_master', 'o')                ->leftJoin('o.select_id', 's')                ->where('s.id=:id')                ->setParameter('id', $id);        $qb = $query->getQuery();        $data = $qb->getResult(Query::HYDRATE_ARRAY);        return $data;    }    public function get_all_of_select_id_seller_id($select_id, $seller_id) {        $soft = $this->em->getFilters()->enable('soft-deleteable');        //disable sotfdelete        $soft->disableForEntity('App\Entities\Seller');        $query = $this->em->createQueryBuilder();        $query->select('o,s,ss')                ->from('App\Entities\Option_master', 'o')                ->leftJoin('o.select_id', 's')                ->leftJoin('o.seller_id', 'ss')                ->where(                        $query->expr()->andX(                                $query->expr()->eq('s.id', ':select_id')                        ), $query->expr()->orX(                                $query->expr()->eq('o.seller_id', ':seller_id')                                , $query->expr()->isNull('o.seller_id')                ))                ->setParameter('select_id', $select_id)                ->setParameter('seller_id', $seller_id);        $qb = $query->getQuery();        $data = $qb->getResult(Query::HYDRATE_ARRAY);        //enable sotfdelete        $soft->enableForEntity('App\Entities\Users');        return $data;    }    public function get_all_of_select_id_seller_idMobileApi($select_id, $seller_id) {        $query = $this->em->createQueryBuilder();        $query->select('o.id,o.value_text,o.key_text')                ->from('App\Entities\Option_master', 'o')                ->leftJoin('o.select_id', 's')                ->leftJoin('o.seller_id', 'ss')                ->where(                        $query->expr()->andX(                                $query->expr()->eq('s.id', ':select_id')                        ), $query->expr()->orX(                                $query->expr()->eq('o.seller_id', ':seller_id')                                , $query->expr()->isNull('o.seller_id')                ))                ->setParameter('select_id', $select_id)                ->setParameter('seller_id', $seller_id);        $qb = $query->getQuery();        $data = $qb->getResult(Query::HYDRATE_ARRAY);        return $data;    }    public function get_combo_total($filter, $type) {        $query = $this->em->createQueryBuilder();        $query->select($query->expr()->count('o.id'))                ->from('App\Entities\Option_master', 'o')                ->where(                        $query->expr()->andX(                                $query->expr()->eq('IDENTITY(o.select_id)', ':type')                        ), $query->expr()->orX(                                $query->expr()->like('o.value_text', ':filter'), $query->expr()->like('o.key_text', ':filter')                ))                ->setParameter('type', $type)                ->setParameter('filter', '%' . $filter['search']['value'] . '%');        return $query->getQuery()->getSingleScalarResult();    }    public function get_combo_options($filter, $type) {        $query = $this->em->createQueryBuilder();        $query->select($query->expr()->count('o.id'))                ->from('App\Entities\Option_master', 'o')                ->where(                        $query->expr()->andX(                                $query->expr()->eq('IDENTITY(o.select_id)', ':type')                ))                ->setParameter('type', $type);        $total = $query->getQuery()->getSingleScalarResult();        $query = $this->em->createQueryBuilder();        if (!empty($filter)) {            $query->select('o')                    ->from('App\Entities\Option_master', 'o')                    ->setMaxResults($filter['length'])                    ->setFirstResult($filter['start'])                    ->where(                            $query->expr()->andX(                                    $query->expr()->eq('IDENTITY(o.select_id)', ':type')                            ), $query->expr()->orX(                                    $query->expr()->like('o.value_text', ':filter'), $query->expr()->like('o.key_text', ':filter')                    ))                    ->setParameter('type', $type)                    ->setParameter('filter', '%' . $filter['search']['value'] . '%');        } else {            $query->select('o')                    ->from('App\Entities\Option_master', 'o')                    ->where(                            $query->expr()->andX(                                    $query->expr()->eq('IDENTITY(o.select_id)', ':type')                    ))                    ->setParameter('type', $type);        }        $qb = $query->getQuery();        $data = $qb->getResult(Query::HYDRATE_ARRAY);        return array('data' => $data, 'total' => $total);    }    public function get_independent_badges($type) {        $query = $this->em->createQueryBuilder();        $query->select('o')                ->from('App\Entities\Option_master', 'o')                ->where(                        $query->expr()->andX(                                $query->expr()->eq('o.parent_id', ':parent_id')                        ), $query->expr()->orX(                                $query->expr()->eq('IDENTITY(o.select_id)', ':type1')                ))                ->setParameter('type1', $type)                ->setParameter('parent_id', '');        $qb = $query->getQuery();        $data = $qb->getResult(Query::HYDRATE_ARRAY);        return $data;    }    public function get_all_crops() {        $query = $this->em->createQueryBuilder();        $query->select('o')                ->from('App\Entities\Option_master', 'o')                ->where(                        $query->expr()->andX(                                $query->expr()->eq('o.is_active', ':status')                        ), $query->expr()->orX(                                $query->expr()->eq('IDENTITY(o.select_id)', ':type1'), $query->expr()->eq('IDENTITY(o.select_id)', ':type2'), $query->expr()->eq('IDENTITY(o.select_id)', ':type3')                ))                ->setParameter('type1', 1)                ->setParameter('type2', 8)                ->setParameter('type3', 9)                ->setParameter('status', 1);        $qb = $query->getQuery();        $data = $qb->getResult(Query::HYDRATE_ARRAY);        return $data;    }    public function getOptionsByParentId($parent_id) {        $query = $this->em->createQueryBuilder();        $query->select('o')                ->from('App\Entities\Option_master', 'o')                ->where(                        $query->expr()->orX(                                $query->expr()->like('o.parent_id', ':filter1'), $query->expr()->like('o.parent_id', ':filter2'), $query->expr()->like('o.parent_id', ':filter3'), $query->expr()->like('o.parent_id', ':filter4')                ))                ->setParameter('filter4', $parent_id)                ->setParameter('filter1', '%,' . $parent_id . '%')                ->setParameter('filter2', '%' . $parent_id . ',%')                ->setParameter('filter3', '%,' . $parent_id . ',%');        $qb = $query->getQuery();        $data = $qb->getResult(Query::HYDRATE_ARRAY);        return $data;    }    public function getOptionsByCrops($crop_id) {        $query = $this->em->createQueryBuilder();        $query->select('o')                ->from('App\Entities\Option_master', 'o')                ->where(                        $query->expr()->orX(                                $query->expr()->like('o.crops', ':filter1'), $query->expr()->like('o.crops', ':filter2'), $query->expr()->like('o.crops', ':filter3'), $query->expr()->like('o.crops', ':filter4')                ))                ->setParameter('filter4', $crop_id)                ->setParameter('filter1', '%,' . $crop_id . '%')                ->setParameter('filter2', '%' . $crop_id . ',%')                ->setParameter('filter3', '%,' . $crop_id . ',%');        $qb = $query->getQuery();        $data = $qb->getResult(Query::HYDRATE_ARRAY);        return $data;    }    public function get_OptionByKey($key) {        $query = $this->em->createQueryBuilder()                ->select('u')                ->from('App\Entities\Option_master', 'u')                ->where('u.key_text = :key')                ->setParameter('key', $key)                ->getQuery();        $data = $query->getResult(Query::HYDRATE_ARRAY);        return $data;    }    public function getOptionsBySelectId($id) {        $query = $this->em->createQueryBuilder()                ->select('u')                ->from('App\Entities\Option_master', 'u')                ->where('u.select_id = :id')                ->setParameter('id', $id)                ->getQuery();        $data = $query->getResult(Query::HYDRATE_ARRAY);        return $data;    }}?>