<?php

namespace App\Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ORM\Table(name="agents_logs")
 */
class AgentLog {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Users",inversedBy="agents_logs")
     * @ORM\JOinColumn(name="agent_id",referencedColumnName="id")
     */
    protected $agent_id;

    /**
     * @ORM\ManyToOne(targetEntity="Seller",inversedBy="agents_logs")
     * @ORM\JOinColumn(name="seller_id",referencedColumnName="id")
     */
    protected $seller_id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $photo_shoot_location;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $photo_shoot_date;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $total_products_photographed;

    /**
     * @ORM\Column(type="text" , nullable=true)
     */
    protected $vignettes;

    /**
     * @ORM\Column(type="decimal", precision=19, scale=4)
     */
    protected $payment_total;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $invoice;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $aditional_details;

    /**
     * @ORM\Column(type="integer",nullable=true, options={"default": 0})
     */
    protected $is_archive;

    /**
     * @ORM\Column(type="integer",nullable=true, options={"default": 0})
     */
    protected $is_paid;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $payment_date;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $payment_made_by;

    /**
     * @ORM\ManyToMany(targetEntity="AgentLogImages")
     * @ORM\JoinTable(name="agent_log_invoice_images",
     *      joinColumns={@ORM\JoinColumn(name="agent_log_id",onDelete="CASCADE", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="image_id", referencedColumnName="id")}
     *      )
     */
    protected $agent_log_invoice_images;

    /**
     * @var \DateTime $created
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created_at;

    /**
     * @var \DateTime $updated
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updated_at;

    /**
     * @ORM\Column(name="deletedAt", type="datetime", nullable=true)
     */
    protected $deletedAt;

    public function __construct($data = []) {
        if (empty($data))
            return;

        $this->agent_id = isset($data['agent_id']) ? $data['agent_id'] : null;
        $this->seller_id = isset($data['seller_id']) ? $data['seller_id'] : null;
        $this->photo_shoot_date = isset($data['photo_shoot_date']) ? $data['photo_shoot_date'] : null;
        $this->photo_shoot_location = isset($data['photo_shoot_location']) ? $data['photo_shoot_location'] : null;
        $this->total_products_photographed = isset($data['total_products_photographed']) ? $data['total_products_photographed'] : 0;
        $this->payment_total = isset($data['payment_total']) ? $data['payment_total'] : 0;
        $this->invoice = isset($data['invoice']) ? $data['invoice'] : null;
        $this->aditional_details = isset($data['aditional_details']) ? $data['aditional_details'] : null;
        $this->is_archive = isset($data['is_archive']) ? $data['is_archive'] : 0;
        $this->is_paid = isset($data['is_paid']) ? $data['is_paid'] : 0;
        $this->vignettes = isset($data['vignettes']) ? $data['vignettes'] : null;
        $this->payment_date = isset($data['payment_date']) ? $data['payment_date'] : null;
        $this->payment_made_by = isset($data['payment_made_by']) ? $data['payment_made_by'] : null;
        $this->agent_log_invoice_images= isset($data['agent_log_invoice_images']) ? $data['agent_log_invoice_images'] : null;
    }

    function getId() {
        return $this->id;
    }

    function getAgent_id() {
        return $this->agent_id;
    }

    function getSeller_id() {
        return $this->seller_id;
    }

    function getPhoto_shoot_date() {
        return $this->photo_shoot_date;
    }

    function getPhoto_shoot_location() {
        return $this->photo_shoot_location;
    }

    function getPayment_total() {
        return $this->payment_total;
    }

    function getInvoice() {
        return $this->invoice;
    }

    function getCreated_at() {
        return $this->created_at;
    }

    function getUpdated_at() {
        return $this->updated_at;
    }

    function getDeletedAt() {
        return $this->deletedAt;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setAgent_id($agent_id) {
        $this->agent_id = $agent_id;
    }

    function setSeller_id($seller_id) {
        $this->seller_id = $seller_id;
    }

    function setPhoto_shoot_location($photo_shoot_location) {
        $this->photo_shoot_location = $photo_shoot_location;
    }

    function setPhoto_shoot_date($photo_shoot_date) {
        $this->photo_shoot_date = $photo_shoot_date;
    }

    function setPayment_total($payment_total) {
        $this->payment_total = $payment_total;
    }

    function setInvoice($invoice) {
        $this->invoice = $invoice;
    }

    function setCreated_at($created_at) {
        $this->created_at = $created_at;
    }

    function setUpdated_at($updated_at) {
        $this->updated_at = $updated_at;
    }

    function setDeletedAt($deletedAt) {
        $this->deletedAt = $deletedAt;
    }

    function getTotal_products_photographed() {
        return $this->total_products_photographed;
    }

    function setTotal_products_photographed($total_products_photographed) {
        $this->total_products_photographed = $total_products_photographed;
    }

    function getAditional_details() {
        return $this->aditional_details;
    }

    function setAditional_details($aditional_details) {
        $this->aditional_details = $aditional_details;
    }

    function get_is_archive() {
        return $this->is_archive;
    }

    function get_is_paid() {
        return $this->is_paid;
    }

    function set_is_archive($is_archive) {
        $this->is_archive = $is_archive;
    }

    function set_is_paid($is_paid) {
        $this->is_paid = $is_paid;
    }

    function getVignettes() {
        return $this->vignettes;
    }

    function setVignettes($vignettes): void {
        $this->vignettes = $vignettes;
    }

    function getPayment_date() {
        return $this->payment_date;
    }

    function getPayment_made_by() {
        return $this->payment_made_by;
    }

    function setPayment_date($payment_date): void {
        $this->payment_date = $payment_date;
    }

    function setPayment_made_by($payment_made_by): void {
        $this->payment_made_by = $payment_made_by;
    }

    function getAgent_log_invoice_images() {
        return $this->agent_log_invoice_images;
    }

    function setAgent_log_invoice_images($agent_log_invoice_images): void {
        $this->agent_log_invoice_images = $agent_log_invoice_images;
    }

}
