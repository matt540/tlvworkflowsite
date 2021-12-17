<?php

namespace App\Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ORM\Table(name="consignment_agreement_with_storage")
 */
class ConsignmentAgreementWithStorage {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Seller",inversedBy="ConsignmentAgreementWithStorage")
     * @ORM\JOinColumn(name="seller_id",referencedColumnName="id")
     */
    protected $seller_id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $is_form_filled;

    /**
     * @ORM\Column(type="text")
     */
    protected $data_json;

    /**
     * @ORM\Column(type="text")
     */
    protected $quote_ids_json;

    /**
     * @ORM\Column(type="string")
     */
    protected $signature;

    /**
     * @ORM\Column(type="string")
     */
    protected $pdf;

    /**
     * @ORM\Column(type="integer")
     */
    protected $externally_filled;

    /**
     * @var \DateTime $created
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updated_at;

    /**
     * @ORM\Column(name="deletedAt", type="datetime", nullable=true)
     */
    protected $deletedAt;

    public function __construct($data) {

        $this->is_form_filled = isset($data['is_form_filled']) ? $data['is_form_filled'] : 0;
        $this->seller_id = isset($data['seller_id']) ? $data['seller_id'] : NULL;
        $this->quote_ids_json = isset($data['quote_ids_json']) ? $data['quote_ids_json'] : json_encode([]);
        $this->data_json = isset($data['data_json']) ? $data['data_json'] : json_encode([]);
        $this->pdf = isset($data['pdf']) ? $data['pdf'] : '';
        $this->signature = isset($data['signature']) ? $data['signature'] : '';
        $this->externally_filled = isset($data['externally_filled']) ? $data['externally_filled'] : 0;
    }

    public function getId() {
        return $this->id;
    }

    function getData_json() {
        return $this->data_json;
    }

    function setData_json($data_json) {
        $this->data_json = $data_json;
    }

    function getSignature() {
        return $this->signature;
    }

    function setSignature($signature) {
        $this->signature = $signature;
    }

    public function getSeller_id() {
        return $this->seller_id;
    }

    public function getIs_form_filled() {
        return $this->is_form_filled;
    }

    public function getQuote_ids_json() {
        return $this->quote_ids_json;
    }

    public function setSeller_id($seller_id) {
        $this->seller_id = $seller_id;
    }

    public function setIs_form_filled($is_form_filled) {
        $this->is_form_filled = $is_form_filled;
    }

    public function setQuote_ids_json($quote_ids_json) {
        $this->quote_ids_json = $quote_ids_json;
    }

    public function getPdf() {
        return $this->pdf;
    }

    public function setPdf($pdf) {
        $this->pdf = $pdf;
    }

    function getExternally_filled() {
        return $this->externally_filled;
    }

    function setExternally_filled($externally_filled): void {
        $this->externally_filled = $externally_filled;
    }

}
