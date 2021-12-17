<?php

namespace App\Repository;

use App\Entities\ConsignmentAgreementWithStorage;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class ConsignmentAgreementWithStorageRepository extends EntityRepository {

    /**
     * @var string
     */
    private $class = 'App\Entities\ConsignmentAgreementWithStorage';

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function prepareData($data) {
        return new ConsignmentAgreementWithStorage($data);
    }

    public function create(ConsignmentAgreementWithStorage $agreement) {
        $this->em->persist($agreement);
        $this->em->flush();
        return $agreement;
    }

    public function update(ConsignmentAgreementWithStorage $agreement, $data) {

        if (isset($data['is_form_filled'])) {
            $agreement->setIs_form_filled($data['is_form_filled']);
        }

        if (isset($data['signature'])) {
            $agreement->setSignature($data['signature']);
        }

        if (isset($data['data_json'])) {
            $agreement->setData_json($data['data_json']);
        }

        if (isset($data['pdf'])) {
            $agreement->setPdf($data['pdf']);
        }

        if (isset($data['externally_filled'])) {
            $agreement->setExternally_filled($data['externally_filled']);
        }

        $this->em->persist($agreement);

        $this->em->flush();
    }

    public function ofId($id) {
        return $this->em->getRepository($this->class)->findOneBy([
                    'id' => $id
        ]);
    }

    public function getAllSellerAgreementsOfWpSellerId($wp_seller_id) {

        $query = $this->em->createQueryBuilder();

        $query->select('cas.created_at,cas.is_form_filled,(case when (cas.is_form_filled = 1) then CONCAT(\'' . config('app.url') . '\',\'Uploads/user_agreement_pdf_without_card/\', cas.pdf) else \'\' end) as pdf_link,cas.pdf')
                ->from($this->class, 'cas')
                ->leftJoin('pqa.seller_id', 's')
                ->where('s.wp_seller_id=:wp_seller_id')
                ->setParameter('wp_seller_id', $wp_seller_id);

        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return $data;
    }

    public function getAllFilled() {

        $query = $this->em->createQueryBuilder();

        $query->select('cas')
                ->from($this->class, 'cas')
                ->where('pqa.is_form_filled=:is_form_filled')
                ->setParameter('is_form_filled', 1);

        $qb = $query->getQuery();

        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        return $data;
    }

    public function getAllOfSellerId($seller_id, $filter = null) {

        $is_form_filled = 1;

        $totalQuery = $this->em->createQueryBuilder();

        $totalQuery->select($totalQuery->expr()->count('cas.id'))
                ->from($this->class, 'cas')
                ->leftJoin('cas.seller_id', 's')
                ->where('s.id=:id')
                ->andWhere('cas.is_form_filled = :pdf_file')
                ->setParameter('id', $seller_id)
                ->setParameter('pdf_file', $is_form_filled);

        $total = $totalQuery->getQuery()->getSingleScalarResult();



        $query = $this->em->createQueryBuilder();

        $query->select('cas')
                ->from($this->class, 'cas')
                ->leftJoin('cas.seller_id', 's')
                ->where('s.id=:id')
                ->andWhere('cas.is_form_filled = :pdf_file')
                ->setParameter('id', $seller_id)
                ->setParameter('pdf_file', $is_form_filled);

        if ($filter) {
            $query->setMaxResults($filter['length']);

            if (isset($filter['last']) && $filter['last'] != 0) {

                $query->andWhere('cas.id > :cas_id')
                        ->setParameter('cas_id', $filter['last']);
            }
        }

        $qb = $query->getQuery();
        $data = $qb->getResult(Query::HYDRATE_ARRAY);

        $final_data = [];
        $final_data['data'] = $data;
        $final_data['total'] = $total;
        return $final_data;
    }

    public function getAllFilledAgreementsOfSeller($seller_id) {
        $qb = $this->em->createQueryBuilder();

        $qb->select('caws')
                ->from($this->class, 'caws')
                ->where('caws.is_form_filled = 1')
                ->leftJoin('caws.seller_id', 's')
                ->where('s.id=:seller_id')
                ->andWhere('caws.is_form_filled = 1')
                ->setParameter('seller_id', $seller_id);

        return $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

}
