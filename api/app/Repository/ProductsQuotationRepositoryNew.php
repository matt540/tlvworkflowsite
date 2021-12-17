<?php

namespace App\Repository;

use App\Entities\Products_quotation;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class ProductsQuotationRepositoryNew extends EntityRepository {
        
    private $class = 'App\Entities\Products_quotation';
    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function getProductQuotationObjFromWpProductId($wpProductId) {
        $query = $this->em->createQueryBuilder()
                ->select('pq')
                ->from($this->class, 'pq')
                ->where('pq.wp_product_id = :wp_product_id')
                ->setParameter('wp_product_id', $wpProductId)
                ->getQuery();

        $data = $query->getResult();

        if (count($data) === 0) {
            return null;
        }

        return $data[0];
    }

    public function getProductsFromWpIds($wpProductsIds) {
        $queryBuilder = $this->em->createQueryBuilder();

        $query = $queryBuilder->select('pq', 'p', 'pi')
                ->from($this->class, 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->leftJoin('p.product_pending_images', 'pi')
                ->where('pq.wp_product_id in (:wp_product_ids)')
                ->setParameter('wp_product_ids', $wpProductsIds)
                ->getQuery();

        $data = $query->getResult(Query::HYDRATE_ARRAY);
        return $data;
    }

    public function getProductQuotationById($wpProductId) {

        $query = $this->em->createQueryBuilder()
                ->select('pq', 'p', 's', 'pi')
                ->from($this->class, 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->leftJoin('p.sellerid', 's')
                ->leftJoin('p.product_pending_images', 'pi')
                ->where('pq.wp_product_id = :wp_product_id')
                ->setParameter('wp_product_id', $wpProductId)
                ->getQuery();

        $data = $query->getResult(Query::HYDRATE_ARRAY);

        if (count($data) === 0) {
            return null;
        }

        return $data[0];
    }

    public function getProductQuotationByWpProductIds($wpProductIds) {

        $query = $this->em->createQueryBuilder()
                ->select('pq', 'p', 's', 'pi')
                ->from($this->class, 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->leftJoin('p.sellerid', 's')
                ->leftJoin('p.product_pending_images', 'pi')
                ->where('pq.wp_product_id in (:wp_product_ids)')
                ->setParameter('wp_product_ids', $wpProductIds)
                ->getQuery();

        $data = $query->getResult(Query::HYDRATE_ARRAY);

        return $data;
    }

    public function setStoragePrice(Products_quotation $productQuotation, $storagePrice) {
        $productQuotation->setStorage_pricing($storagePrice);
        $this->em->persist($productQuotation);
        $this->em->flush();
        return 1;
    }

    public function setSameStoragePriceToProducts($wpProductIds, $storagePrice) {
        // todo
    }

    public function getSellerProductRejectToAuction($filter) {

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


        $sellerTotalQb = $this->em->createQueryBuilder();

        $sellerTotalQb->select('s.id')
                ->from($this->class, 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->leftJoin('p.sellerid', 's')
                ->where(
                        $sellerTotalQb->expr()->andX(
                                $sellerTotalQb->expr()->eq('pq.is_send_mail', '2'),
                                $sellerTotalQb->expr()->eq('pq.is_archived', '0'),
                                $sellerTotalQb->expr()->eq('pq.reject_to_auction', '1')
                        )
                )
                ->groupBy('s.id');

        $total = count($sellerTotalQb->getQuery()->getResult(Query::HYDRATE_ARRAY));

        $qb = $this->em->createQueryBuilder();
        $qb->select('s.firstname, s.lastname, s.email, s.displayname, s.id')
                ->from($this->class, 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->leftJoin('p.sellerid', 's')
                ->setMaxResults($filter['length'])
                ->setFirstResult($filter['start'])
                ->where(
                        $qb->expr()->andX(
                                $qb->expr()->eq('pq.is_send_mail', '2'),
                                $qb->expr()->eq('pq.is_archived', '0'),
                                $qb->expr()->eq('pq.reject_to_auction', '1')
                        )
                )
                ->andWhere(
                        $qb->expr()->orX(
                                $qb->expr()->like('s.firstname', ':filter'),
                                $qb->expr()->like('s.lastname', ':filter'),
                                $qb->expr()->like('s.email', ':filter'),
                                $qb->expr()->like('s.displayname', ':filter')
                ))
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->orderBy($orderbyclm, $filter['order'][0]['dir'])
                ->groupBy('s.id');

        $data = $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getSellerProductRejectToAuctionFilterCount($filter) {
        $qb = $this->em->createQueryBuilder();
        $qb->select('s.id')
                ->from($this->class, 'pq')
                ->leftJoin('pq.product_id', 'p')
                ->leftJoin('p.sellerid', 's')
                ->where(
                        $qb->expr()->andX(
                                $qb->expr()->eq('pq.is_send_mail', '2'),
                                $qb->expr()->eq('pq.is_archived', '0'),
                                $qb->expr()->eq('pq.reject_to_auction', '1')
                        )
                )
                ->andWhere(
                        $qb->expr()->orX(
                                $qb->expr()->like('s.firstname', ':filter'),
                                $qb->expr()->like('s.lastname', ':filter'),
                                $qb->expr()->like('s.email', ':filter'),
                                $qb->expr()->like('s.displayname', ':filter')
                ))
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->groupBy('s.id');

        return count($qb->getQuery()->getResult(Query::HYDRATE_ARRAY));
    }

    public function getRejectToAuctionProduct($filter) {
        if ($filter['order'][0]['column'] == 0) {
            $orderbyclm = 'p.sku';
        }

        if ($filter['order'][0]['column'] == 1) {
            $orderbyclm = 'p.name';
        }

        if ($filter['order'][0]['column'] == 2) {
            $orderbyclm = 'p.price';
        }

        $productTotalQb = $this->em->createQueryBuilder();

        $productTotalQb->select($productTotalQb->expr()->count('pq.id'))
                ->from('App\Entities\Products', 'p')
                ->leftJoin('p.sellerid', 's')
                ->leftjoin($this->class, 'pq',
                        \Doctrine\ORM\Query\Expr\Join::WITH, 'pq.product_id = p.id')
                ->where(
                        $productTotalQb->expr()->andX(
                                $productTotalQb->expr()->eq('pq.is_send_mail', '2'),
                                $productTotalQb->expr()->eq('pq.is_archived', '0'),
                                $productTotalQb->expr()->eq('pq.reject_to_auction', '1')
                        )
                )
                ->andWhere('s.id = :seller_id')
                ->setParameter('seller_id', $filter['id']);

        $total = $productTotalQb->getQuery()->getSingleScalarResult();

        $qb = $this->em->createQueryBuilder();
        $qb->select('p')
                ->from('App\Entities\Products', 'p')
                ->leftJoin('p.sellerid', 's')
                ->leftjoin($this->class, 'pq',
                        \Doctrine\ORM\Query\Expr\Join::WITH, 'pq.product_id = p.id')
                ->setMaxResults($filter['length'])
                ->setFirstResult($filter['start'])
                ->where(
                        $qb->expr()->andX(
                                $qb->expr()->eq('pq.is_send_mail', '2'),
                                $qb->expr()->eq('pq.is_archived', '0'),
                                $qb->expr()->eq('pq.reject_to_auction', '1')
                        )
                )
                ->andWhere(
                        $qb->expr()->orX(
                                $qb->expr()->like('p.sku', ':filter'),
                                $qb->expr()->like('p.name', ':filter')
                ))
                ->andWhere('s.id = :seller_id')
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->setParameter('seller_id', $filter['id'])
                ->orderBy($orderbyclm, $filter['order'][0]['dir']);

        $data = $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);

        return array('data' => $data, 'total' => $total);
    }

    public function getRejectToAuctionProductFilterTotal($filter) {
        $qb = $this->em->createQueryBuilder();
        $qb->select($qb->expr()->count('pq.id'))
                ->from('App\Entities\Products', 'p')
                ->leftJoin('p.sellerid', 's')
                ->leftjoin($this->class, 'pq',
                        \Doctrine\ORM\Query\Expr\Join::WITH, 'pq.product_id = p.id')
                ->where(
                        $qb->expr()->andX(
                                $qb->expr()->eq('pq.is_send_mail', '2'),
                                $qb->expr()->eq('pq.is_archived', '0'),
                                $qb->expr()->eq('pq.reject_to_auction', '1')
                        )
                )
                ->andWhere(
                        $qb->expr()->orX(
                                $qb->expr()->like('p.sku', ':filter'),
                                $qb->expr()->like('p.name', ':filter')
                ))
                ->andWhere('s.id = :seller_id')
                ->setParameter('filter', '%' . $filter['search']['value'] . '%')
                ->setParameter('seller_id', $filter['id']);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getProductsOfSellerOfAwaitingContractStage($sellerId) {
        $qb = $this->em->createQueryBuilder();

        $qb->select(['pq', 'p', 'pi'])
                ->from($this->class, 'pq')
                ->leftjoin('pq.product_id', 'p')
                ->leftjoin('p.product_pending_images', 'pi')
                ->where('p.sellerid =' . $sellerId)
                ->andWhere(
                        $qb->expr()->andX(
                                 $qb->expr()->neq('pq.is_send_mail', 2),
                                $qb->expr()->eq('pq.is_archived', 0),
                                $qb->expr()->eq('pq.is_awaiting_contract', 0),
                                $qb->expr()->isNull('pq.status_quot')
                        )
        );
            
               
        $query = $qb->getQuery();
        return $query->getResult(Query::HYDRATE_ARRAY);
    }

    public function getProductsOfSellerOfProductionStage($sellerId) {
        $qb = $this->em->createQueryBuilder();

        $qb->select(['pq', 'p', 'pi'])
                ->from($this->class, 'pq')
                ->leftjoin('pq.product_id', 'p')
                ->leftjoin('p.product_pending_images', 'pi')
                ->where('p.sellerid =' . $sellerId)
                ->andWhere(
                        $qb->expr()->andX(
                                $qb->expr()->neq('pq.is_send_mail', 2),
                                $qb->expr()->eq('pq.is_archived', 0),
                                $qb->expr()->eq('pq.is_awaiting_contract', 1),
                                $qb->expr()->eq('pq.is_proposal_for_production', 0)
                        )
        );

        $query = $qb->getQuery();
        return $query->getResult(Query::HYDRATE_ARRAY);
    }

    public function getProductsOfSellerOfPricingStage($sellerId) {
        $qb = $this->em->createQueryBuilder();

        $qb->select(['pq', 'p', 'pi'])
                ->from($this->class, 'pq')
                ->leftjoin('pq.product_id', 'p')
                ->leftjoin('p.product_pending_images', 'pi')
                ->where('p.sellerid =' . $sellerId)
                ->andWhere(
                        $qb->expr()->andX(
                                $qb->expr()->neq('pq.is_send_mail', 2),
                                $qb->expr()->eq('pq.is_archived', 0),
                                $qb->expr()->eq('pq.is_awaiting_contract', 1),
                                $qb->expr()->eq('pq.is_proposal_for_production', 1),
                                $qb->expr()->eq('pq.is_product_for_pricing', 0)
                        )
        );

        $query = $qb->getQuery();
        return $query->getResult(Query::HYDRATE_ARRAY);
    }

    public function getProductsOfSellerOfApprovalStage($sellerId) {
        $status = [17, 83];

        $qb = $this->em->createQueryBuilder();

        $qb->select(['pq', 'p', 'pi'])
                ->from($this->class, 'pq')
                ->leftjoin('pq.product_id', 'p')
                ->leftjoin('p.product_pending_images', 'pi')
                ->where('p.sellerid =' . $sellerId)
                ->andWhere(
                        $qb->expr()->andX(
                                $qb->expr()->neq('pq.is_send_mail', 2),
                                $qb->expr()->eq('pq.is_archived', 0),
                                $qb->expr()->eq('pq.is_awaiting_contract', 1),
                                $qb->expr()->eq('pq.is_proposal_for_production', 1),
                                $qb->expr()->eq('pq.is_product_for_pricing', 1)
                        )
                )
                ->andWhere($qb->expr()->in('pq.status_quot', ':status'))
                ->setParameter('status', $status);

        $query = $qb->getQuery();
        return $query->getResult(Query::HYDRATE_ARRAY);
    }

    public function getProductForReviewStageProductCount() {
        $qb = $this->em->createQueryBuilder();

        $qb->select($qb->expr()->count('p.id'))
                ->from('App\Entities\Products', 'p')
                ->leftjoin('p.status', 'status')
                ->innerJoin('p.sellerid', 's')
                ->andWhere('status.id = ' . 6); // 6 = pending

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getAwaitingContractStageProductCount() {
        $qb = $this->em->createQueryBuilder();

        $qb->select($qb->expr()->count('pq.id'))
                ->from($this->class, 'pq')
                ->andWhere(
                        $qb->expr()->andX(
                                $qb->expr()->neq('pq.is_send_mail', 2),
                                $qb->expr()->eq('pq.is_archived', 0),
                                $qb->expr()->eq('pq.is_awaiting_contract', 0),
                                $qb->expr()->isNull('pq.status_quot')
                        )
        );

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getForProductionStageProductCount() {
        $qb = $this->em->createQueryBuilder();

        $qb->select($qb->expr()->count('pq.id'))
                ->from($this->class, 'pq')
                ->andWhere(
                        $qb->expr()->andX(
                                $qb->expr()->neq('pq.is_send_mail', 2),
                                $qb->expr()->eq('pq.is_archived', 0),
                                $qb->expr()->eq('pq.is_awaiting_contract', 1),
                                $qb->expr()->eq('pq.is_proposal_for_production', 0)
                        )
        );

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getPricingStageProductCount() {
        $qb = $this->em->createQueryBuilder();

        $qb->select($qb->expr()->count('pq.id'))
                ->from($this->class, 'pq')
                ->andWhere(
                        $qb->expr()->andX(
                                $qb->expr()->neq('pq.is_send_mail', 2),
                                $qb->expr()->eq('pq.is_archived', 0),
                                $qb->expr()->eq('pq.is_awaiting_contract', 1),
                                $qb->expr()->eq('pq.is_proposal_for_production', 1),
                                $qb->expr()->eq('pq.is_product_for_pricing', 0)
                        )
        );

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getProductReviewStageProducts($sellerId) {

        $pendingStatus = 6;

        $qb = $this->em->createQueryBuilder();

        $qb->select('p')
                ->from('App\Entities\Products', 'p')
                ->leftjoin('p.sell_id', 's')
                ->leftjoin('p.status', 'status')
                ->leftjoin('p.sellerid', 'seller')
                ->where('status.id = ' . $pendingStatus)
                ->andWhere('seller.id = ' . $sellerId);

        return $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

}
