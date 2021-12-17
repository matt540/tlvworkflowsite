<?php

namespace App\Repository;

use App\Entities\Seller;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Tymon\JWTAuth\Facades\JWTAuth;

class SellerRepository extends EntityRepository {

    /**

     * @var string

     */
    private $class = 'App\Entities\Seller';

    /**

     * @var EntityManager

     */
    private $em;

    public function __construct(EntityManager $em) {

        $this->em = $em;
    }

    public function create(Seller $option) {



        $this->em->persist($option);

        $this->em->flush();

        return $option->getId();
    }

    public function update(Seller $option, $data) {



//        if (isset($data['update_seller_roles']))
//        {
//            $option->setSellerRoles($data['update_seller_roles']);
//        }

        if (isset($data['last_product_file_name_base'])) {

            $option->setLastProductFileNameBase($data['last_product_file_name_base']);
        }

        if (isset($data['in_queue'])) {

            $option->setIn_queue($data['in_queue']);
        }

        if (isset($data['last_proposal_file_name_base'])) {

            $option->setLastProposalFileNameBase($data['last_proposal_file_name_base']);
        }

        if (isset($data['last_product_file_name'])) {

            $option->setLastProductFileName($data['last_product_file_name']);
        }

        if (isset($data['last_proposal_file_name'])) {

            $option->setLastProposalFileName($data['last_proposal_file_name']);
        }

        if (isset($data['firstname'])) {

            $option->setFirstname($data['firstname']);
        }

        if (isset($data['is_update_last_sku'])) {

            if (isset($data['last_sku'])) {

                $option->setLastSku($data['last_sku']);
            }
        }

        if (isset($data['lastname'])) {

            $option->setLastname($data['lastname']);
        }

        if (isset($data['shopname'])) {

            $option->setShopname($data['shopname']);
        }

        if (isset($data['shopurl'])) {

            $option->setShopurl($data['shopurl']);
        }

        if (isset($data['email'])) {

            $option->setEmail($data['email']);
        }

        if (isset($data['address'])) {

            $option->setAddress($data['address']);
        }

        if (isset($data['phone'])) {

            $option->setPhone($data['phone']);
        }

        if (isset($data['password'])) {

            $option->setPassword($data['password']);
        }

        if (isset($data['wp_seller_id'])) {

            $option->setWp_seller_id($data['wp_seller_id']);
        }

        if (isset($data['display_name'])) {

            $option->setDisplayname($data['display_name']);
        }

        if (isset($data['is_seller_agreement'])) {

            $option->setIs_seller_agreement($data['is_seller_agreement']);
        }

        if (isset($data['seller_agreement_json'])) {

            $option->setSeller_agreement_json($data['seller_agreement_json']);
        }

        if (isset($data['seller_agreement_signature'])) {

            $option->setSeller_agreement_signature($data['seller_agreement_signature']);
        }

        if (isset($data['seller_agreement_pdf'])) {

            $option->setSeller_agreement_pdf($data['seller_agreement_pdf']);
        }
        if (isset($data['stripe_customer_id'])) {

            $option->setStripe_customer_id($data['stripe_customer_id']);
        }

        if (isset($data['assign_agent_id'])) {

            $option->setAssign_agent_id($data['assign_agent_id']);
        }
        
        $this->em->persist($option);
        
        $this->em->flush();

        $soft = $this->em->getFilters()->enable('soft-deleteable');

        $soft->enableForEntity('App\Entities\Seller');



        return 1;
    }

    public function removeDeletedAt(Seller $option) {

        $option->setDeletedAtNull(NULL);

        $this->em->persist($option);

        $this->em->flush();
    }

    public function SellerOfId($id) {

        return $this->em->getRepository($this->class)->findOneBy([
                    'id' => $id
        ]);
    }

    public function SellerOfWpId($id) {

        $soft = $this->em->getFilters()->enable('soft-deleteable');

        $soft->disableForEntity('App\Entities\Seller');

        $seller = $this->em->getRepository($this->class)->findOneBy([
            'wp_seller_id' => $id
        ]);



        if ($seller) {
            
        } else {

            $soft->enableForEntity('App\Entities\Seller');
        }

        return $seller;
    }

    public function SellerById($id) {

        $query = $this->em->createQueryBuilder()
                ->select('s', 'agent')
                ->from('App\Entities\Seller', 's')
                ->leftJoin('s.assign_agent_id', 'agent')
                ->where('s.id = :id')
                ->setParameter('id', $id)
                ->getQuery();

        $data = $query->getResult(Query::HYDRATE_ARRAY);

        return $data[0];
    }

    public function SellerByWpIdMobileApi($id) {

        $query = $this->em->createQueryBuilder()
                ->select('s')
                ->from('App\Entities\Seller', 's')
                ->where('s.wp_seller_id = :id')
                ->setParameter('id', $id)
                ->getQuery();

        $data = $query->getResult(Query::HYDRATE_ARRAY);

        if (!isset($data[0])) {
            return null;
        }

        return $data[0];
    }

    public function getAllSellers() {

        $query = $this->em->createQueryBuilder();

        $query->select('s')
                ->from('App\Entities\Seller', 's');

        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return $data;
    }

    public function getAllSellersMobileApi() {

        $query = $this->em->createQueryBuilder();

//        $query->select('s.id,s.firstname,s.lastname,s.displayname,s.wp_seller_id')
        $query->select('s.id,s.displayname')
                ->from('App\Entities\Seller', 's')
                ->orderBy('s.firstname', 'asc');

        $qb = $query->getQuery();



        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return $data;
    }

    public function delete(Seller $option) {

        $this->em->remove($option);

        $this->em->flush();
    }

    /**

     * create Theory

     * @return Theory

     */
    public function prepareData($data) {

        return new Seller($data);
    }

    public function getSellersTotal($filter) {

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('Distinct s.id'))
                ->from('App\Entities\Seller', 's')
                ->leftJoin('s.seller_roles', 'sr')

//                ->groupBy('s.id')
                ->where(
                        $query->expr()->orX(
                                $query->expr()->like('s.firstname', ':filter')
                                , $query->expr()->like('sr.value_text', ':filter')
                                , $query->expr()->like('s.lastname', ':filter')
                                , $query->expr()->like('s.email', ':filter')
                                , $query->expr()->like('s.displayname', ':filter')
                ))
                ->setParameter('filter', '%' . $filter['search']['value'] . '%');

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getSellers($filter) {

        $orderbyclm = 's.id';



        if ($filter['order'][0]['column'] == 0) {

            $orderbyclm = 's.firstname';
        }



        if ($filter['order'][0]['column'] == 1) {

            $orderbyclm = 's.lastname';
        }

        if ($filter['order'][0]['column'] == 2) {

            $orderbyclm = 's.email';
        }

        if ($filter['order'][0]['column'] == 3) {

            $orderbyclm = 's.displayname';
        }

        if ($filter['order'][0]['column'] == 4) {

            $orderbyclm = 's.address';
        }







        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('s.id'))
                ->from('App\Entities\Seller', 's');

        $total = $query->getQuery()->getSingleScalarResult();



        $query = $this->em->createQueryBuilder();

        $query->select('s.id', 's.firstname', 's.lastname', 's.email', 's.displayname', 's.address', "group_concat(sr.value_text) as roles", 'CONCAT(agent.firstname,\' \',agent.lastname) AS agent_name')
                ->from('App\Entities\Seller', 's')
                ->leftJoin('s.seller_roles', 'sr')
                ->leftJoin('s.assign_agent_id', 'agent')
                ->setMaxResults($filter['length'])
                ->setFirstResult($filter['start'])
                ->where(
                        $query->expr()->orX(
                                $query->expr()->like('s.firstname', ':filter')

//                                ,$query->expr()->like('s.id', ':filter')
                                , $query->expr()->like('sr.value_text', ':filter')
                                , $query->expr()->like('s.lastname', ':filter')
                                , $query->expr()->like('s.email', ':filter')
                                , $query->expr()->like('s.displayname', ':filter')
                ))
                ->groupBy('s.id')
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->orderBy($orderbyclm, $filter['order'][0]['dir']);

        ;





        $qb = $query->getQuery();



        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getSellerProductsTotal($filter) {

        $pending = 6;

        $query = $this->em->createQueryBuilder();

        $query->select('s.id')
                ->from('App\Entities\Products', 'p')
                ->leftjoin('p.status', 'status')
                ->innerJoin('p.sellerid', 's')
                ->leftJoin('s.assign_agent_id', 'agent')
                ->where(
                        $query->expr()->orX(
                                $query->expr()->like('s.firstname', ':filter')
                                , $query->expr()->like('s.lastname', ':filter')
                                , $query->expr()->like('s.email', ':filter')
                                , $query->expr()->like('s.displayname', ':filter')
                ))
                ->andWhere('status.id = ' . $pending)
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->groupBy('s.id');



        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);



        return count($data);
    }

    public function getSellerProducts($filter) {





        if ($filter['order'][0]['column'] == 0) {

            $orderbyclm = 's.firstname';
        }



        if ($filter['order'][0]['column'] == 1) {

            $orderbyclm = 's.lastname';
        }

        if ($filter['order'][0]['column'] == 2) {

            $orderbyclm = 's.email';
        }

        if ($filter['order'][0]['column'] == 3) {

            $orderbyclm = 's.displayname';
        }

        $pending = 6;

        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('distinct s.id'))
                ->from('App\Entities\Products', 'p')
                ->innerJoin('p.sellerid', 's')
                ->leftJoin('s.assign_agent_id', 'agent')
                ->leftjoin('p.status', 'status')
                ->andWhere('status.id = ' . $pending);

//                ->leftJoin('p.sellerid', 's')
//                ->groupBy('s.id');



        $total = $query->getQuery()->getSingleScalarResult();

//        $total=count($datas);
//        $data = $qb->getResult(Query::HYDRATE_ARRAY);





        $query = $this->em->createQueryBuilder();

        $query->select('p.id as product_id, s.firstname, s.lastname, s.email, s.displayname, s.id', 'CONCAT(agent.firstname,\' \',agent.lastname) AS agent_name')
                ->from('App\Entities\Products', 'p')
                ->leftJoin('p.sellerid', 's')
                ->leftJoin('s.assign_agent_id', 'agent')
                ->leftjoin('p.status', 'status')
                ->setMaxResults($filter['length'])
                ->setFirstResult($filter['start'])
                ->where(
                        $query->expr()->orX(
                                $query->expr()->like('s.firstname', ':filter')
                                , $query->expr()->like('s.lastname', ':filter')
                                , $query->expr()->like('s.email', ':filter')
                                , $query->expr()->like('s.displayname', ':filter')
                ))
                ->andWhere('status.id = ' . $pending)
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->orderBy($orderbyclm, $filter['order'][0]['dir'])
                ->groupBy('s.id');





        $qb = $query->getQuery();



        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getSellerProposalsTotal($filter) {

        $query = $this->em->createQueryBuilder();

        $query->select('s.id')
                ->from('App\Entities\Products_quotation', 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->leftJoin('p.sellerid', 's')
                ->leftJoin('s.assign_agent_id', 'agent')
                ->where(
                        $query->expr()->orX(
                                $query->expr()->like('s.firstname', ':filter')
                                , $query->expr()->like('s.lastname', ':filter')
                                , $query->expr()->like('s.email', ':filter')
                                , $query->expr()->like('s.displayname', ':filter')
                ))
                ->andwhere('pq.status_quot IS NULL')
                ->andWhere('pq.is_archived = 0')
                ->andWhere('pq.is_send_mail = 0')
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->groupBy('s.id');



        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);



        return count($data);
    }

    public function getSellerProposals($filter) {



        if ($filter['order'][0]['column'] == 0) {

            $orderbyclm = 's.firstname';
        }



        if ($filter['order'][0]['column'] == 1) {

            $orderbyclm = 's.lastname';
        }

        if ($filter['order'][0]['column'] == 2) {

            $orderbyclm = 's.email';
        }

        if ($filter['order'][0]['column'] == 3) {

            $orderbyclm = 's.displayname';
        }



        $query = $this->em->createQueryBuilder();

        $query->select('s.id')
                ->from('App\Entities\Products_quotation', 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->innerJoin('p.sellerid', 's')
                ->leftJoin('s.assign_agent_id', 'agent')
                ->where('pq.status_quot IS NULL')
                ->andWhere('pq.is_archived = 0')
                ->andWhere('pq.is_send_mail = 0')
                ->groupBy('s.id');



        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);



        $total = count($data);



        $query = $this->em->createQueryBuilder();

        $query->select('pq.id as product_quotation_id, s.firstname, s.lastname, s.email, s.displayname, s.id', 'CONCAT(agent.firstname,\' \',agent.lastname) AS agent_name')
                ->from('App\Entities\Products_quotation', 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->leftJoin('p.sellerid', 's')
                ->leftJoin('s.assign_agent_id', 'agent')
//                ->where('pq.is_send_mail = 0')
                ->where('pq.status_quot IS NULL')
                ->andWhere('pq.is_archived = 0')
                ->andWhere('pq.is_send_mail = 0')
                ->setMaxResults($filter['length'])
                ->setFirstResult($filter['start'])
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->like('s.firstname', ':filter')
                                , $query->expr()->like('s.lastname', ':filter')
                                , $query->expr()->like('s.email', ':filter')
                                , $query->expr()->like('s.displayname', ':filter')
                ))
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->orderBy($orderbyclm, $filter['order'][0]['dir'])
                ->groupBy('s.id');





        $qb = $query->getQuery();



        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getSellerProductForProductionTotal($filter) {

        $query = $this->em->createQueryBuilder();

        $query->select('s.id')
                ->from('App\Entities\Products_quotation', 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->leftJoin('p.sellerid', 's')
                ->leftJoin('s.assign_agent_id', 'agent')
                ->where('pq.is_send_mail = 1')
                ->andWhere('pq.is_product_for_production = 0')
                ->andWhere('pq.is_archived = 0')
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->like('s.firstname', ':filter')
                                , $query->expr()->like('s.lastname', ':filter')
                                , $query->expr()->like('s.email', ':filter')
                                , $query->expr()->like('s.displayname', ':filter')
                        ), $query->expr()->andX(
//                                $query->expr()->eq('pq.is_copyright', ':is_copyright')
//                                , $query->expr()->eq('pq.is_product_for_production', ':is_product_for_production')
                ))

//                ->setParameter('is_copyright', 0)
//                ->setParameter('is_product_for_production', 0)
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->groupBy('s.id');



        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);



        return count($data);
    }

    public function getSellerProductForProduction($filter) {



        if ($filter['order'][0]['column'] == 0) {

            $orderbyclm = 's.firstname';
        }



        if ($filter['order'][0]['column'] == 1) {

            $orderbyclm = 's.lastname';
        }

        if ($filter['order'][0]['column'] == 2) {

            $orderbyclm = 's.email';
        }

        if ($filter['order'][0]['column'] == 3) {

            $orderbyclm = 's.displayname';
        }



        $query = $this->em->createQueryBuilder();

        $query->select('s.id')
                ->from('App\Entities\Products_quotation', 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->leftJoin('p.sellerid', 's')
                ->leftJoin('s.assign_agent_id', 'agent')
                ->where('pq.is_send_mail = 1')
                ->andWhere('pq.is_product_for_production = 0')
                ->andWhere('pq.is_archived = 0')

//                ->andWhere('pq.is_copyright = 0')
                ->groupBy('s.id');



        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);



        $total = count($data);



        $query = $this->em->createQueryBuilder();

        $query->select('pq.id as product_quotation_id, s.firstname, s.lastname, s.email, s.displayname, s.id', 'CONCAT(agent.firstname,\' \',agent.lastname) AS agent_name')
                ->from('App\Entities\Products_quotation', 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->leftJoin('p.sellerid', 's')
                ->leftJoin('s.assign_agent_id', 'agent')
                ->setMaxResults($filter['length'])
                ->setFirstResult($filter['start'])
                ->where('pq.is_send_mail = 1')
                ->andWhere('pq.is_product_for_production = 0')
                ->andWhere('pq.is_archived = 0')
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->like('s.firstname', ':filter')
                                , $query->expr()->like('s.lastname', ':filter')
                                , $query->expr()->like('s.email', ':filter')
                                , $query->expr()->like('s.displayname', ':filter')
                        )

//                        , $query->expr()->andX(
//                                $query->expr()->eq('pq.is_copyright', ':is_copyright')
//                                , $query->expr()->eq('pq.is_product_for_production', ':is_product_for_production')
//                        )
                )
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')

//                ->setParameter('is_copyright', '0')
//                ->setParameter('is_product_for_production', '0')
                ->orderBy($orderbyclm, $filter['order'][0]['dir'])
                ->groupBy('s.id');





        $qb = $query->getQuery();



        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getSellerCopyrightTotal($filter) {

        $query = $this->em->createQueryBuilder();

        $query->select('s.id')
                ->from('App\Entities\Products_quotation', 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->leftJoin('p.sellerid', 's')
                ->where('pq.is_send_mail = 1')
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->like('s.firstname', ':filter')
                                , $query->expr()->like('s.lastname', ':filter')
                                , $query->expr()->like('s.email', ':filter')
                                , $query->expr()->like('s.displayname', ':filter')
                        ), $query->expr()->andX(
                                $query->expr()->eq('pq.is_copyright', ':is_copyright')
                                , $query->expr()->eq('pq.is_product_for_production', ':is_product_for_production')
                ))
                ->setParameter('is_copyright', 0)
                ->setParameter('is_product_for_production', 1)
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->groupBy('s.id');

        $query = $this->copyWriterFilter($query);









        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);



        return count($data);
    }

    public static function copyWriterFilter($query) {

        $auth_user = JWTAuth::parseToken()->authenticate();



        //5 for Copywriter        

        if ($auth_user->getRoles()[0]->getId() == 5) {

            $query->leftJoin('pq.copywriter_id', 'pqc');

            $query->andWhere(
                    $query->expr()->andX(
                            $query->expr()->eq('pqc.id', ':copywriter_id')
                    )
            );

            $query->setParameter('copywriter_id', $auth_user->getId());
        }

        return $query;
    }

    public function getSellerCopyright($filter) {



        if ($filter['order'][0]['column'] == 0) {

            $orderbyclm = 's.firstname';
        }



        if ($filter['order'][0]['column'] == 1) {

            $orderbyclm = 's.lastname';
        }

        if ($filter['order'][0]['column'] == 2) {

            $orderbyclm = 's.email';
        }

        if ($filter['order'][0]['column'] == 3) {

            $orderbyclm = 's.displayname';
        }



        $query = $this->em->createQueryBuilder();

        $query->select('s.id')
                ->from('App\Entities\Products_quotation', 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->innerJoin('p.sellerid', 's')
                ->where('pq.is_send_mail = 1')
                ->andWhere('pq.is_copyright = 0')
                ->andWhere('pq.is_product_for_production = 1')
                ->groupBy('s.id');





        $query = $this->copyWriterFilter($query);



        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);



        $total = count($data);



        $query = $this->em->createQueryBuilder();

        $query->select('pq.id as product_quotation_id, s.firstname, s.lastname, s.email, s.displayname, s.id')
                ->from('App\Entities\Products_quotation', 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->leftJoin('p.sellerid', 's')
                ->setMaxResults($filter['length'])
                ->setFirstResult($filter['start'])
                ->where('pq.is_send_mail = 1')
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->like('s.firstname', ':filter')
                                , $query->expr()->like('s.lastname', ':filter')
                                , $query->expr()->like('s.email', ':filter')
                                , $query->expr()->like('s.displayname', ':filter')
                        ), $query->expr()->andX(
                                $query->expr()->eq('pq.is_copyright', ':is_copyright')
                                , $query->expr()->eq('pq.is_product_for_production', ':is_product_for_production')
                ))
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->setParameter('is_copyright', '0')
                ->setParameter('is_product_for_production', '1')
                ->orderBy($orderbyclm, $filter['order'][0]['dir'])
                ->groupBy('s.id');



        $query = $this->copyWriterFilter($query);

        $qb = $query->getQuery();



        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getSellerApprovedProductsTotal($filter) {

        $query = $this->em->createQueryBuilder();

        $query->select('s.id')
                ->from('App\Entities\Products_quotation', 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->leftjoin('pq.status_quot', 'status')
                ->leftJoin('p.sellerid', 's')
                ->leftJoin('s.assign_agent_id', 'agent')
                ->where(
                        $query->expr()->orX(
                                $query->expr()->like('s.firstname', ':filter')
                                , $query->expr()->like('s.lastname', ':filter')
                                , $query->expr()->like('s.email', ':filter')
                                , $query->expr()->like('s.displayname', ':filter')
                        )
                        , $query->expr()->andX(
                                $query->expr()->eq('pq.is_product_for_pricing', '1')
                                , $query->expr()->eq('pq.is_awaiting_contract', '1')
                                , $query->expr()->eq('pq.is_proposal_for_production', '1')
                                , $query->expr()->eq('pq.is_archived', '0')
                                , $query->expr()->orX(
                                        $query->expr()->eq('status.id', '17')
                                        , $query->expr()->eq('status.id', '83')
                                )
                ))
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->groupBy('s.id');



        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);



        return count($data);
    }

    public function getSellerApprovedProducts($filter) {



        if ($filter['order'][0]['column'] == 0) {

            $orderbyclm = 's.firstname';
        }



        if ($filter['order'][0]['column'] == 1) {

            $orderbyclm = 's.lastname';
        }

        if ($filter['order'][0]['column'] == 2) {

            $orderbyclm = 's.email';
        }

        if ($filter['order'][0]['column'] == 3) {

            $orderbyclm = 's.displayname';
        }



        $query = $this->em->createQueryBuilder();

        $query->select('s.id')
                ->from('App\Entities\Products_quotation', 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->leftJoin('p.sellerid', 's')
                ->leftJoin('s.assign_agent_id', 'agent')
                ->leftjoin('pq.status_quot', 'status')
                ->where(
                        $query->expr()->andX(
                                $query->expr()->eq('pq.is_product_for_pricing', '1')
                                , $query->expr()->eq('pq.is_awaiting_contract', '1')
                                , $query->expr()->eq('pq.is_proposal_for_production', '1')
                                , $query->expr()->eq('pq.is_archived', '0')
                                , $query->expr()->orX(
                                        $query->expr()->eq('status.id', '17')
                                        , $query->expr()->eq('status.id', '83')
                                )
                        )
                )
                ->groupBy('s.id');



        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);



        $total = count($data);



        $query = $this->em->createQueryBuilder();
//        $query->select('pq,s')
        $query->select('pq.id as product_quotation_id, s.firstname, s.lastname, s.email, s.displayname, s.id', 'CONCAT(agent.firstname,\' \',agent.lastname) AS agent_name')
                ->from('App\Entities\Products_quotation', 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->leftJoin('p.sellerid', 's')
                ->leftJoin('s.assign_agent_id', 'agent')
                ->leftjoin('pq.status_quot', 'status')
                ->setMaxResults($filter['length'])
                ->setFirstResult($filter['start'])
                ->where(
                        $query->expr()->orX(
                                $query->expr()->like('s.firstname', ':filter')
                                , $query->expr()->like('s.lastname', ':filter')
                                , $query->expr()->like('s.email', ':filter')
                                , $query->expr()->like('s.displayname', ':filter')
                        ),
                        $query->expr()->andX(
                                $query->expr()->eq('pq.is_product_for_pricing', '1')
                                , $query->expr()->eq('pq.is_awaiting_contract', '1')
                                , $query->expr()->eq("pq.is_proposal_for_production", '1')
                                , $query->expr()->eq('pq.is_archived', '0')
                                , $query->expr()->orX(
                                        $query->expr()->eq('status.id', '17')
                                        , $query->expr()->eq('status.id', '83')
                                )
                ))
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->orderBy($orderbyclm, $filter['order'][0]['dir'])
                ->groupBy('s.id');





        $qb = $query->getQuery();


        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getIsAnyProductTouchedOfSellerId($seller_id) {

        $is_touched = 1;

        $query = $this->em->createQueryBuilder();

        $query->select('p.is_touched')
                ->from('App\Entities\Products', 'p')
                ->leftJoin('p.sellerid', 's')
                ->where(
                        $query->expr()->andX(
                                $query->expr()->eq('s.id', ':selller_id')
                        ), $query->expr()->orX(
                                $query->expr()->eq('p.is_touched', ':is_touched')
                                , $query->expr()->neq('p.created_at', 'p.updated_at')
                        )
                )
                ->setParameter('selller_id', $seller_id)
                ->setParameter('is_touched', $is_touched)
                ->groupBy('s.id')
                ->orderBy('p.id', 'ASC');

        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        if (count($data) > 0) {

            return 1;
        } else {

            return 0;
        }
    }

    public function getFirstCreatedAtOfSellerId($seller_id) {

        $query = $this->em->createQueryBuilder();

        $query->select('p.created_at as product_created_at')
                ->from('App\Entities\Products', 'p')
                ->leftJoin('p.sellerid', 's')
                ->where(
                        $query->expr()->andX(
                                $query->expr()->eq('s.id', ':selller_id')
                        )
                )
                ->setParameter('selller_id', $seller_id)
                ->groupBy('s.id')
                ->orderBy('p.id', 'ASC');

        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        if (count($data) > 0) {

            return $data[0]['product_created_at'];
        } else {

            return null;
        }
    }

    public function getProductsInStateSellers($filter) {



        if ($filter['order'][0]['column'] == 0) {

            $orderbyclm = 's.firstname';
        }



        if ($filter['order'][0]['column'] == 1) {

            $orderbyclm = 's.lastname';
        }

        if ($filter['order'][0]['column'] == 2) {

            $orderbyclm = 's.email';
        }

        if ($filter['order'][0]['column'] == 4) {

            $orderbyclm = 's.wp_seller_id';
        }

        $status_quot_rejected = 19;

        $status_quot_approved = 18;

        $status_reject = 8;

        $status = 31;

        $archived = 1;

        $archived_temp = null;



        $query = $this->em->createQueryBuilder();

        $query->select($query->expr()->count('s.id'))
                ->from('App\Entities\Products', 'p')
                ->innerJoin('p.sellerid', 's')
                ->leftJoin('p.status', 'status');



        $query->leftjoin(
                'App\Entities\Products_quotation', 'pq', \Doctrine\ORM\Query\Expr\Join::WITH, 'pq.product_id = p.id'
        );



        $query->leftjoin('pq.status_quot', 'status_quot');



        $query->where(
                        $query->expr()->orX(
                                $query->expr()->andX(
                                        $query->expr()->neq('status.id', ':status_reject')
                                        , $query->expr()->neq('status.id', ':status_archived')

//                                        , $query->expr()->neq('status_quot.id', ':status_quot_approved')
                                        , $query->expr()->isNull('pq.is_archived')
                                )
                                , $query->expr()->andX(
                                        $query->expr()->isNotNull('pq.product_id')
                                        , $query->expr()->neq('pq.is_archived', ':archived')
                                        , $query->expr()->neq('status_quot.id', ':status_quot_approved')
                                        , $query->expr()->neq('status_quot.id', ':status_quot_rejected')
                                        , $query->expr()->neq('pq.is_product_for_production', 2)
                                        , $query->expr()->neq('pq.is_copyright', 2)
                                        , $query->expr()->neq('pq.is_send_mail', 2)
                                ), $query->expr()->andX(
                                        $query->expr()->isNotNull('pq.product_id')
                                        , $query->expr()->isNull('pq.status_quot')
                                        , $query->expr()->neq('pq.is_product_for_production', 2)
                                        , $query->expr()->neq('pq.is_copyright', 2)
                                        , $query->expr()->neq('pq.is_send_mail', 2)
                                )
                ))
                ->setParameter('status_reject', $status_reject)
                ->setParameter('status_archived', $status)
                ->setParameter('archived', $archived)
                ->setParameter('status_quot_rejected', $status_quot_rejected)
                ->setParameter('status_quot_approved', $status_quot_approved)
                ->groupBy('s.id');





        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        $total = count($data);

//        $status_id = 31;
//        $archived = 1;

        $status_quot_rejected = 19;

        $status_quot_approved = 18;

        $status_reject = 8;

        $status = 31;

        $archived = 1;

        $archived_temp = null;



        $query = $this->em->createQueryBuilder();

        $query->select('p.id as product_id, s.wp_seller_id, s.firstname, s.lastname, s.email, s.displayname, s.id')
                ->from('App\Entities\Products', 'p')
                ->leftJoin('p.sellerid', 's')
                ->leftJoin('p.status', 'status');

        $query->leftjoin(
                'App\Entities\Products_quotation', 'pq', \Doctrine\ORM\Query\Expr\Join::WITH, 'pq.product_id = p.id'
        );

        $query->leftjoin('pq.status_quot', 'status_quot');



        $query->setMaxResults($filter['length'])
                ->setFirstResult($filter['start'])

//                ->where(
//                        $query->expr()->orX(
//                                $query->expr()->neq('status.id', ':status')
//                                , $query->expr()->neq('pq.is_archived', ':archived')
//                ))
                ->where(
                        $query->expr()->orX(
                                $query->expr()->andX(
                                        $query->expr()->neq('status.id', ':status_reject')
                                        , $query->expr()->neq('status.id', ':status_archived')

//                                        , $query->expr()->neq('status_quot.id', ':status_quot_approved')
                                        , $query->expr()->isNull('pq.is_archived')
                                )
                                , $query->expr()->andX(
                                        $query->expr()->isNotNull('pq.product_id')
                                        , $query->expr()->neq('pq.is_archived', ':archived')
                                        , $query->expr()->neq('status_quot.id', ':status_quot_approved')
                                        , $query->expr()->neq('status_quot.id', ':status_quot_rejected')
                                        , $query->expr()->neq('pq.is_product_for_production', 2)
                                        , $query->expr()->neq('pq.is_copyright', 2)
                                        , $query->expr()->neq('pq.is_send_mail', 2)
                                ), $query->expr()->andX(
                                        $query->expr()->isNotNull('pq.product_id')
                                        , $query->expr()->isNull('pq.status_quot')
                                        , $query->expr()->neq('pq.is_product_for_production', 2)
                                        , $query->expr()->neq('pq.is_copyright', 2)
                                        , $query->expr()->neq('pq.is_send_mail', 2)
                                )

//                                , $query->expr()->andX(
//                                        $query->expr()->isNotNull('pq.is_archived')
//                                        , $query->expr()->neq('status_quot.id', ':status_quot_approved')
//                                )
                ))
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->like('s.firstname', ':filter')
                                , $query->expr()->like('s.lastname', ':filter')
                                , $query->expr()->like('s.email', ':filter')
                                , $query->expr()->like('s.displayname', ':filter')
                ))
                ->setParameter('status_reject', $status_reject)
                ->setParameter('status_archived', $status)
                ->setParameter('archived', $archived)
                ->setParameter('status_quot_rejected', $status_quot_rejected)
                ->setParameter('status_quot_approved', $status_quot_approved)

//                ->setParameter('status', $status_id)
//                ->setParameter('archived', $archived)
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->orderBy($orderbyclm, $filter['order'][0]['dir'])
                ->groupBy('s.id');





        $qb = $query->getQuery();



        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getProductsInStateSellersTotal($filter) {

//        $status_id = 31;
//        $archived = 1;

        $status_quot_rejected = 19;

        $status_quot_approved = 18;

        $status_reject = 8;

        $status = 31;

        $archived = 1;

        $archived_temp = null;



//        $status_reject = 8;
//        $status = 31;
//        $archived = 1;

        $query = $this->em->createQueryBuilder();

        $query->select('s.id')
                ->from('App\Entities\Products', 'p')
                ->leftJoin('p.sellerid', 's')
                ->leftJoin('p.status', 'status');



        $query->leftjoin(
                'App\Entities\Products_quotation', 'pq', \Doctrine\ORM\Query\Expr\Join::WITH, 'pq.product_id = p.id'
        );

        $query->leftjoin('pq.status_quot', 'status_quot');

//        $query->where(
//                        $query->expr()->orX(
//                                $query->expr()->neq('status.id', ':status')
//                                , $query->expr()->neq('pq.is_archived', ':archived')
//                ))
//        $query->where(
//                        $query->expr()->orX(
//                                $query->expr()->andX(
////                                        $query->expr()->neq('status.id', ':status')
//                                        $query->expr()->neq('status.id', ':status_reject')
//                                        , $query->expr()->neq('status.id', ':status_archived')
//                                        , $query->expr()->neq('pq.is_archived', ':archived')
////                                
//                                ), $query->expr()->andX(
//                                        $query->expr()->neq('status.id', ':status_reject')
//                                        , $query->expr()->neq('status.id', ':status_archived')
//                                        , $query->expr()->isNull('pq.is_archived')
//                                )
//                        )
//                )

        $query->where(
                        $query->expr()->orX(
                                $query->expr()->andX(
                                        $query->expr()->neq('status.id', ':status_reject')
                                        , $query->expr()->neq('status.id', ':status_archived')

//                                        , $query->expr()->neq('status_quot.id', ':status_quot_approved')
                                        , $query->expr()->isNull('pq.is_archived')
                                )
                                , $query->expr()->andX(
                                        $query->expr()->isNotNull('pq.product_id')
                                        , $query->expr()->neq('pq.is_archived', ':archived')
                                        , $query->expr()->neq('status_quot.id', ':status_quot_approved')
                                        , $query->expr()->neq('status_quot.id', ':status_quot_rejected')
                                        , $query->expr()->neq('pq.is_product_for_production', 2)
                                        , $query->expr()->neq('pq.is_copyright', 2)
                                        , $query->expr()->neq('pq.is_send_mail', 2)
                                ), $query->expr()->andX(
                                        $query->expr()->isNotNull('pq.product_id')
                                        , $query->expr()->isNull('pq.status_quot')
                                        , $query->expr()->neq('pq.is_product_for_production', 2)
                                        , $query->expr()->neq('pq.is_copyright', 2)
                                        , $query->expr()->neq('pq.is_send_mail', 2)
                                )

//                                , $query->expr()->andX(
//                                        $query->expr()->isNotNull('pq.is_archived')
//                                        , $query->expr()->neq('status_quot.id', ':status_quot_approved')
//                                )
                ))
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->like('s.firstname', ':filter')
                                , $query->expr()->like('s.lastname', ':filter')
                                , $query->expr()->like('s.email', ':filter')
                                , $query->expr()->like('s.displayname', ':filter')
                ))
                ->setParameter('status_reject', $status_reject)
                ->setParameter('status_archived', $status)
                ->setParameter('archived', $archived)
                ->setParameter('status_quot_rejected', $status_quot_rejected)
                ->setParameter('status_quot_approved', $status_quot_approved)
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->groupBy('s.id');



        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);



        return count($data);
    }

    public function getSellerArchivedProducts($filter) {



        if ($filter['order'][0]['column'] == 0) {

            $orderbyclm = 's.firstname';
        }



        if ($filter['order'][0]['column'] == 1) {

            $orderbyclm = 's.lastname';
        }

        if ($filter['order'][0]['column'] == 2) {

            $orderbyclm = 's.email';
        }

        if ($filter['order'][0]['column'] == 3) {

            $orderbyclm = 's.displayname';
        }



        $query = $this->em->createQueryBuilder();

        $query->select('s.id')
                ->from('App\Entities\Products', 'p')
                ->leftJoin('p.sellerid', 's')
                ->groupBy('s.id');



        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);



        $total = count($data);

        $status_id = 31;

        $archived = 1;



        $query = $this->em->createQueryBuilder();

        $query->select('p.id as product_id, s.firstname, s.lastname, s.email, s.displayname, s.id')
                ->from('App\Entities\Products', 'p')
                ->leftJoin('p.sellerid', 's')
                ->leftJoin('p.status', 'status');

        $query->leftjoin(
                'App\Entities\Products_quotation', 'pq', \Doctrine\ORM\Query\Expr\Join::WITH, 'pq.product_id = p.id'
        );



        $query->setMaxResults($filter['length'])
                ->setFirstResult($filter['start'])
                ->where(
                        $query->expr()->orX(
                                $query->expr()->eq('status.id', ':status')
                                , $query->expr()->like('pq.is_archived', ':archived')
                ))
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->like('s.firstname', ':filter')
                                , $query->expr()->like('s.lastname', ':filter')
                                , $query->expr()->like('s.email', ':filter')
                                , $query->expr()->like('s.displayname', ':filter')
                ))
                ->setParameter('status', $status_id)
                ->setParameter('archived', $archived)
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->orderBy($orderbyclm, $filter['order'][0]['dir'])
                ->groupBy('s.id');





        $qb = $query->getQuery();



        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getSellerArchivedProductsTotal($filter) {

        $status_id = 31;

        $archived = 1;

        $query = $this->em->createQueryBuilder();

        $query->select('s.id')
                ->from('App\Entities\Products', 'p')
                ->leftJoin('p.sellerid', 's')
                ->leftJoin('p.status', 'status');



        $query->leftjoin(
                'App\Entities\Products_quotation', 'pq', \Doctrine\ORM\Query\Expr\Join::WITH, 'pq.product_id = p.id'
        );



        $query->where(
                        $query->expr()->orX(
                                $query->expr()->eq('status.id', ':status')
                                , $query->expr()->like('pq.is_archived', ':archived')
                ))
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->like('s.firstname', ':filter')
                                , $query->expr()->like('s.lastname', ':filter')
                                , $query->expr()->like('s.email', ':filter')
                                , $query->expr()->like('s.displayname', ':filter')
                ))
                ->setParameter('status', $status_id)
                ->setParameter('archived', $archived)
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->groupBy('s.id');



        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);



        return count($data);
    }

    public function getAllQueueSeller() {

        $query = $this->em->createQueryBuilder();

        $query->select('s')
                ->from('App\Entities\Seller', 's')
                ->where('s.in_queue = :in_queue')
                ->setParameter('in_queue', 1);

        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return $data;
    }

    // MOBILE API FUNCTION



    public function getSellerProductForProductionMobileApi($filter) {

        $query = $this->em->createQueryBuilder();

        $query->select('s.id')
                ->from('App\Entities\Products_quotation', 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->leftJoin('p.sellerid', 's')
                ->leftJoin('s.assign_agent_id', 'agent')
                ->where(
                        $query->expr()->andX(
                                $query->expr()->neq('pq.is_send_mail', '2')
                                , $query->expr()->eq('pq.is_archived', ':is_archived')
                                , $query->expr()->eq('pq.is_proposal_for_production', ':is_proposal_for_production')
                                , $query->expr()->eq('pq.is_awaiting_contract', ':is_awaiting_contract')
                        )
                )
                ->setParameter('is_archived', 0)
                ->setParameter('is_proposal_for_production', 0)
                ->setParameter('is_awaiting_contract', 1)
                ->groupBy('s.id');

        if (isset($filter['role_id']) && $filter['role_id'] == 3) {
            $query->andWhere('s.assign_agent_id = :agent_user_id');
//            $query->andWhere('pq.assign_agent_id = :agent_user_id');
            $query->setParameter('agent_user_id', $filter['user_id']);
        }

        $qb = $query->getQuery();
        $data = $qb->getResult(Query::HYDRATE_ARRAY);
        $total = count($data);

        $query = $this->em->createQueryBuilder();

        $query->select('pq.id as product_quotation_id, s.firstname, s.lastname, s.email, s.displayname, s.id, s.phone, s.address', 'CONCAT(agent.firstname,\' \',agent.lastname) AS agent_name')
                ->from('App\Entities\Products_quotation', 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->leftJoin('p.sellerid', 's')
                ->leftJoin('s.assign_agent_id', 'agent')
                ->where(
                        $query->expr()->andX(
                                $query->expr()->neq('pq.is_send_mail', '2')
                                , $query->expr()->eq('pq.is_archived', ':is_archived')
                                , $query->expr()->eq('pq.is_proposal_for_production', ':is_proposal_for_production')
                                , $query->expr()->eq('pq.is_awaiting_contract', ':is_awaiting_contract')
                        )
                )
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->like('s.firstname', ':filter')
                                , $query->expr()->like('s.lastname', ':filter')
                                , $query->expr()->like('s.email', ':filter')
                                , $query->expr()->like('s.displayname', ':filter')
                ))
                ->setParameter('filter', '%' . $filter['search'] . '%')
                ->setParameter('is_archived', 0)
                ->setParameter('is_proposal_for_production', 0)
                ->setParameter('is_awaiting_contract', 1)
                ->groupBy('s.id');

        if (isset($filter['page_all']) && $filter['page_all'] == '') {
            $query->setMaxResults($filter['length']);
            $query->setFirstResult($filter['start']);
        }

        if (isset($filter['role_id']) && $filter['role_id'] == 3) {
            $query->andWhere('s.assign_agent_id = :agent_user_id');
//            $query->andWhere('pq.assign_agent_id = :agent_user_id');
            $query->setParameter('agent_user_id', $filter['user_id']);
        }


        $qb = $query->getQuery();
        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getSellerProductForProductionTotalMobileApi($filter) {

        $query = $this->em->createQueryBuilder();

        $query->select('s.id')
                ->from('App\Entities\Products_quotation', 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->leftJoin('p.sellerid', 's')
                ->leftJoin('s.assign_agent_id', 'agent')
                ->where(
                        $query->expr()->andX(
                                $query->expr()->neq('pq.is_send_mail', '2')
                                , $query->expr()->eq('pq.is_archived', ':is_archived')
                                , $query->expr()->eq('pq.is_proposal_for_production', ':is_proposal_for_production')
                                , $query->expr()->eq('pq.is_awaiting_contract', ':is_awaiting_contract')
                        )
                )
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->like('s.firstname', ':filter')
                                , $query->expr()->like('s.lastname', ':filter')
                                , $query->expr()->like('s.email', ':filter')
                                , $query->expr()->like('s.displayname', ':filter')
                ))
                ->setParameter('filter', '%' . $filter['search'] . '%')
                ->setParameter('is_archived', 0)
                ->setParameter('is_proposal_for_production', 0)
                ->setParameter('is_awaiting_contract', 1)
                ->groupBy('s.id');

        if (isset($filter['role_id']) && $filter['role_id'] == 3) {
            $query->andWhere('s.assign_agent_id = :agent_user_id');
//            $query->andWhere('pq.assign_agent_id = :agent_user_id');
            $query->setParameter('agent_user_id', $filter['user_id']);
        }

        $qb = $query->getQuery();
        $data = $qb->getResult(Query::HYDRATE_ARRAY);
        return count($data);
    }

    public function getSellerAwaitingContract($filter) {



        if ($filter['order'][0]['column'] == 0) {

            $orderbyclm = 's.firstname';
        }



        if ($filter['order'][0]['column'] == 1) {

            $orderbyclm = 's.lastname';
        }

        if ($filter['order'][0]['column'] == 2) {

            $orderbyclm = 's.email';
        }

        if ($filter['order'][0]['column'] == 3) {

            $orderbyclm = 's.displayname';
        }



        $query = $this->em->createQueryBuilder();

        $query->select('s.id')
                ->from('App\Entities\Products_quotation', 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->leftJoin('p.sellerid', 's')
                ->leftJoin('s.assign_agent_id', 'agent')
                ->where(
                        $query->expr()->andX(
                                $query->expr()->neq('pq.is_send_mail', '2')
                                , $query->expr()->eq('pq.is_archived', ':is_archived')
                                , $query->expr()->eq('pq.is_awaiting_contract', ':is_awaiting_contract')
                                , $query->expr()->isNull('pq.status_quot')
                        )
                )
                ->setParameter('is_archived', 0)
                ->setParameter('is_awaiting_contract', 0)



//                ->where('pq.status_quot IS NULL')
//                ->andWhere('pq.is_archived = 0')
//                ->andWhere('pq.is_send_mail = 0')
                ->groupBy('s.id');



        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);



        $total = count($data);



        $query = $this->em->createQueryBuilder();

        $query->select('pq.id as product_quotation_id, s.firstname, s.lastname, s.email, s.displayname, s.id', 'CONCAT(agent.firstname,\' \',agent.lastname) AS agent_name')
                ->from('App\Entities\Products_quotation', 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->leftJoin('p.sellerid', 's')
                ->leftJoin('s.assign_agent_id', 'agent')
//                ->where('pq.is_send_mail = 0')
//                ->where('pq.status_quot IS NULL')
//                ->andWhere('pq.is_archived = 0')
//                ->andWhere('pq.is_send_mail = 0')
                ->setMaxResults($filter['length'])
                ->setFirstResult($filter['start'])
                ->where(
                        $query->expr()->andX(
                                $query->expr()->neq('pq.is_send_mail', '2')
                                , $query->expr()->eq('pq.is_archived', ':is_archived')
                                , $query->expr()->eq('pq.is_awaiting_contract', ':is_awaiting_contract')
                                , $query->expr()->isNull('pq.status_quot')
                        )
                )
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->like('s.firstname', ':filter')
                                , $query->expr()->like('s.lastname', ':filter')
                                , $query->expr()->like('s.email', ':filter')
                                , $query->expr()->like('s.displayname', ':filter')
                ))
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->setParameter('is_archived', 0)
                ->setParameter('is_awaiting_contract', 0)
                ->orderBy($orderbyclm, $filter['order'][0]['dir'])
                ->groupBy('s.id');





        $qb = $query->getQuery();



        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getSellerProposalForPricing($filter) {



        if ($filter['order'][0]['column'] == 0) {

            $orderbyclm = 's.firstname';
        }



        if ($filter['order'][0]['column'] == 1) {

            $orderbyclm = 's.lastname';
        }

        if ($filter['order'][0]['column'] == 2) {

            $orderbyclm = 's.email';
        }

        if ($filter['order'][0]['column'] == 3) {

            $orderbyclm = 's.displayname';
        }



        $query = $this->em->createQueryBuilder();

        $query->select('s.id')
                ->from('App\Entities\Products_quotation', 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->innerJoin('p.sellerid', 's')
                ->leftJoin('s.assign_agent_id', 'agent')
                ->where(
                        $query->expr()->andX(
                                $query->expr()->neq('pq.is_send_mail', '2')
                                , $query->expr()->eq('pq.is_archived', ':is_archived')
                                , $query->expr()->eq('pq.is_proposal_for_production', ':is_proposal_for_production')
                                , $query->expr()->eq('pq.is_awaiting_contract', ':is_awaiting_contract')
                                , $query->expr()->eq('pq.is_product_for_pricing', ':is_product_for_pricing')
                        )
                )
                ->setParameter('is_archived', 0)
                ->setParameter('is_awaiting_contract', 1)
                ->setParameter('is_proposal_for_production', 1)
                ->setParameter('is_product_for_pricing', 0)



//                ->where('pq.status_quot IS NULL')
//                ->andWhere('pq.is_archived = 0')
//                ->andWhere('pq.is_send_mail = 0')
                ->groupBy('s.id');



        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);



        $total = count($data);



        $query = $this->em->createQueryBuilder();

        $query->select('pq.id as product_quotation_id, s.firstname, s.lastname, s.email, s.displayname, s.id', 'CONCAT(agent.firstname,\' \',agent.lastname) AS agent_name')
                ->from('App\Entities\Products_quotation', 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->leftJoin('p.sellerid', 's')
                ->leftJoin('s.assign_agent_id', 'agent')

//                ->where('pq.is_send_mail = 0')
//                ->where('pq.status_quot IS NULL')
//                ->andWhere('pq.is_archived = 0')
//                ->andWhere('pq.is_send_mail = 0')
                ->setMaxResults($filter['length'])
                ->setFirstResult($filter['start'])
                ->where(
                        $query->expr()->andX(
                                $query->expr()->neq('pq.is_send_mail', '2')
                                , $query->expr()->eq('pq.is_archived', ':is_archived')
                                , $query->expr()->eq('pq.is_proposal_for_production', ':is_proposal_for_production')
                                , $query->expr()->eq('pq.is_product_for_pricing', ':is_product_for_pricing')
                                , $query->expr()->eq('pq.is_awaiting_contract', ':is_awaiting_contract')
                        )
                )
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->like('s.firstname', ':filter')
                                , $query->expr()->like('s.lastname', ':filter')
                                , $query->expr()->like('s.email', ':filter')
                                , $query->expr()->like('s.displayname', ':filter')
                ))
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->setParameter('is_archived', 0)
                ->setParameter('is_awaiting_contract', 1)
                ->setParameter('is_proposal_for_production', 1)
                ->setParameter('is_product_for_pricing', 0)
                ->orderBy($orderbyclm, $filter['order'][0]['dir'])
                ->groupBy('s.id');





        $qb = $query->getQuery();



        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getSellerProposalForPricingTotal($filter) {

        $query = $this->em->createQueryBuilder();

        $query->select('s.id')
                ->from('App\Entities\Products_quotation', 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->leftJoin('p.sellerid', 's')
                ->leftJoin('s.assign_agent_id', 'agent')
                ->where($query->expr()->andX(
                                $query->expr()->neq('pq.is_send_mail', '2')
                                , $query->expr()->eq('pq.is_archived', ':is_archived')
                                , $query->expr()->eq('pq.is_proposal_for_production', ':is_proposal_for_production')
                                , $query->expr()->eq('pq.is_awaiting_contract', ':is_awaiting_contract')
                                , $query->expr()->eq('pq.is_product_for_pricing', ':is_product_for_pricing')
                        )
                )



//                ->where('pq.is_send_mail = 1')
//                ->andWhere('pq.is_product_for_production = 0')
//                ->andWhere('pq.is_archived = 0')
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->like('s.firstname', ':filter')
                                , $query->expr()->like('s.lastname', ':filter')
                                , $query->expr()->like('s.email', ':filter')
                                , $query->expr()->like('s.displayname', ':filter')
                        ), $query->expr()->andX(
//                                $query->expr()->eq('pq.is_copyright', ':is_copyright')
//                                , $query->expr()->eq('pq.is_product_for_production', ':is_product_for_production')
                ))
                ->setParameter('is_archived', 0)
                ->setParameter('is_awaiting_contract', 1)
                ->setParameter('is_proposal_for_production', 1)
                ->setParameter('is_product_for_pricing', 0)

//                ->setParameter('is_copyright', 0)
//                ->setParameter('is_product_for_production', 0)
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->groupBy('s.id');



        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);



        return count($data);
    }

    public function getSellerAwaitingContractTotal($filter) {

        $query = $this->em->createQueryBuilder();

        $query->select('s.id')
                ->from('App\Entities\Products_quotation', 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->leftJoin('p.sellerid', 's')
                ->leftJoin('s.assign_agent_id', 'agent')
                ->where(
                        $query->expr()->andX(
                                $query->expr()->neq('pq.is_send_mail', '2')
                                , $query->expr()->eq('pq.is_archived', ':is_archived')
                                , $query->expr()->eq('pq.is_awaiting_contract', ':is_awaiting_contract')
                                , $query->expr()->isNull('pq.status_quot')
                        )
                )



//                ->where('pq.is_send_mail = 1')
//                ->andWhere('pq.is_product_for_production = 0')
//                ->andWhere('pq.is_archived = 0')
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->like('s.firstname', ':filter')
                                , $query->expr()->like('s.lastname', ':filter')
                                , $query->expr()->like('s.email', ':filter')
                                , $query->expr()->like('s.displayname', ':filter')
                        ), $query->expr()->andX(
//                                $query->expr()->eq('pq.is_copyright', ':is_copyright')
//                                , $query->expr()->eq('pq.is_product_for_production', ':is_product_for_production')
                ))
                ->setParameter('is_archived', 0)
                ->setParameter('is_awaiting_contract', 0)


//                ->setParameter('is_copyright', 0)
//                ->setParameter('is_product_for_production', 0)
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->groupBy('s.id');



        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);



        return count($data);
    }

    public function getSellerProposalForProduction($filter) {

        $authUser = JWTAuth::parseToken()->authenticate();

        $role_id = $authUser->getRoles()[0]->getId();
        $user_id = $authUser->getId();

        if ($filter['order'][0]['column'] == 0) {

            $orderbyclm = 's.firstname';
        }

        if ($filter['order'][0]['column'] == 1) {

            $orderbyclm = 's.lastname';
        }

        if ($filter['order'][0]['column'] == 2) {

            $orderbyclm = 's.email';
        }

        if ($filter['order'][0]['column'] == 3) {

            $orderbyclm = 's.displayname';
        }


        $query = $this->em->createQueryBuilder();

        $query->select('s.id')
                ->from('App\Entities\Products_quotation', 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->innerJoin('p.sellerid', 's')
                ->leftJoin('s.assign_agent_id', 'agent')
                ->where(
                        $query->expr()->andX(
                                $query->expr()->neq('pq.is_send_mail', '2')
                                , $query->expr()->eq('pq.is_archived', ':is_archived')
                                , $query->expr()->eq('pq.is_proposal_for_production', ':is_proposal_for_production')
                                , $query->expr()->eq('pq.is_awaiting_contract', ':is_awaiting_contract')
                        )
                )
                ->setParameter('is_archived', 0)
                ->setParameter('is_proposal_for_production', 0)
                ->setParameter('is_awaiting_contract', 1)
                ->groupBy('s.id');

        if ($role_id == 3) {
            $query->andWhere('s.assign_agent_id = :agent_id');
//            $query->andWhere('pq.assign_agent_id = :agent_id');
            $query->setParameter('agent_id', $user_id);
        }


        $qb = $query->getQuery();
        $data = $qb->getResult(Query::HYDRATE_ARRAY);
        $total = count($data);

        $query = $this->em->createQueryBuilder();
        $query->select('pq.id as product_quotation_id, s.firstname, s.lastname, s.email, s.displayname, s.id', 'CONCAT(agent.firstname,\' \',agent.lastname) AS agent_name')
                ->from('App\Entities\Products_quotation', 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->leftJoin('p.sellerid', 's')
                ->leftJoin('s.assign_agent_id', 'agent')
                ->setMaxResults($filter['length'])
                ->setFirstResult($filter['start'])
                ->where(
                        $query->expr()->andX(
                                $query->expr()->neq('pq.is_send_mail', '2')
                                , $query->expr()->eq('pq.is_archived', ':is_archived')
                                , $query->expr()->eq('pq.is_proposal_for_production', ':is_proposal_for_production')
                                , $query->expr()->eq('pq.is_awaiting_contract', ':is_awaiting_contract')
                        )
                )
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->like('s.firstname', ':filter')
                                , $query->expr()->like('s.lastname', ':filter')
                                , $query->expr()->like('s.email', ':filter')
                                , $query->expr()->like('s.displayname', ':filter')
                ))
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->setParameter('is_archived', 0)
                ->setParameter('is_proposal_for_production', 0)
                ->setParameter('is_awaiting_contract', 1)
                ->orderBy($orderbyclm, $filter['order'][0]['dir'])
                ->groupBy('s.id');

        if ($role_id == 3) {
            $query->andWhere('s.assign_agent_id = :agent_id');
//            $query->andWhere('pq.assign_agent_id = :agent_id');
            $query->setParameter('agent_id', $user_id);
        }

        $qb = $query->getQuery();
        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getSellerProposalForProductionTotal($filter) {

        $authUser = JWTAuth::parseToken()->authenticate();

        $role_id = $authUser->getRoles()[0]->getId();
        $user_id = $authUser->getId();

        $query = $this->em->createQueryBuilder();

        $query->select('s.id')
                ->from('App\Entities\Products_quotation', 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->leftJoin('p.sellerid', 's')
                ->leftJoin('s.assign_agent_id', 'agent')
                ->where(
                        $query->expr()->andX(
                                $query->expr()->neq('pq.is_send_mail', '2')
                                , $query->expr()->eq('pq.is_archived', ':is_archived')
                                , $query->expr()->eq('pq.is_proposal_for_production', ':is_proposal_for_production')
                                , $query->expr()->eq('pq.is_awaiting_contract', ':is_awaiting_contract')
                        )
                )
                ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->like('s.firstname', ':filter')
                                , $query->expr()->like('s.lastname', ':filter')
                                , $query->expr()->like('s.email', ':filter')
                                , $query->expr()->like('s.displayname', ':filter')
                ))
                ->setParameter('is_archived', 0)
                ->setParameter('is_proposal_for_production', 0)
                ->setParameter('is_awaiting_contract', 1)
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->groupBy('s.id');


        if ($role_id == 3) {
            $query->andWhere('s.assign_agent_id = :agent_id');
//            $query->andWhere('pq.assign_agent_id = :agent_id');
            $query->setParameter('agent_id', $user_id);
        }

        $qb = $query->getQuery();
        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return count($data);
    }

    public function SellerByIdAgreementcheck($id) {

        $query = $this->em->createQueryBuilder()
                ->select('s')
                ->from('App\Entities\Seller', 's')
                ->where('s.id = :id')
                ->setParameter('id', $id)
                ->getQuery();

        $data = $query->getResult(Query::HYDRATE_ARRAY);

        return $data;
    }

    public function searchSeller($searchTerm) {
        $qb = $this->em->createQueryBuilder();
        $qb->select('s')
                ->from('App\Entities\Seller', 's')
                ->where(
                        $qb->expr()->orX(
                                $qb->expr()->like('s.firstname', ':filter'),
                                $qb->expr()->like('s.lastname', ':filter'),
                                $qb->expr()->like('s.email', ':filter'),
                                $qb->expr()->like('s.displayname', ':filter')
                ))
                ->setParameter('filter', '%' . $searchTerm . '%');

        $query = $qb->getQuery();

        $data = $query->getResult(Query::HYDRATE_ARRAY);
        return $data;
    }

}

?>