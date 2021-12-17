<?php

namespace App\Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ORM\Table(name="seller")
 */
class Seller {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", unique=true)
     */
    protected $wp_seller_id;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $displayname;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $lastname;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $shopname;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $password;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $shopurl;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $address;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $phone;

    /**
     * @ORM\Column(type="integer")
     */
    protected $last_sku;

    /**
     * @ORM\Column(type="integer")
     */
    protected $in_queue;

    /**
     * @ORM\Column(type="string")
     */
    protected $last_product_file_name;

    /**
     * @ORM\Column(type="string")
     */
    protected $last_product_file_name_base;

    /**
     * @ORM\Column(type="string")
     */
    protected $last_proposal_file_name;

    /**
     * @ORM\Column(type="string")
     */
    protected $last_proposal_file_name_base;

    /**
     * @ORM\ManyToMany(targetEntity="Option_master")
     * @ORM\JoinTable(name="seller_roles",
     *      joinColumns={@ORM\JoinColumn(name="seller_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="option_id", referencedColumnName="id")}
     *      )
     */
    private $seller_roles;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $is_seller_agreement;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    protected $seller_agreement_json;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    protected $seller_agreement_signature;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $seller_agreement_pdf;

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

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $stripe_customer_id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Users",inversedBy="Seller")
     * @ORM\JOinColumn(name="assign_agent_id",referencedColumnName="id")
     */
    protected $assign_agent_id;


    public function __construct($data) {

        $this->wp_seller_id = isset($data['wp_seller_id']) ? $data['wp_seller_id'] : 0;

        $this->displayname = isset($data['displayname']) ? $data['displayname'] : '';

        $this->firstname = isset($data['firstname']) ? $data['firstname'] : '';

        $this->lastname = isset($data['lastname']) ? $data['lastname'] : '';

        $this->shopname = isset($data['shopname']) ? $data['shopname'] : '';

        $this->shopurl = isset($data['shopurl']) ? $data['shopurl'] : '';

        $this->email = isset($data['email']) ? $data['email'] : '';

        $this->address = isset($data['address']) ? $data['address'] : '';

        $this->phone = isset($data['phone']) ? $data['phone'] : '';

        $this->last_sku = isset($data['last_sku']) ? $data['last_sku'] : 0;

        $this->in_queue = isset($data['in_queue']) ? $data['in_queue'] : 0;

        $this->last_product_file_name = isset($data['last_product_file_name']) ? $data['last_product_file_name'] : '';

        $this->last_product_file_name_base = isset($data['last_product_file_name_base']) ? $data['last_product_file_name_base'] : '';

        $this->last_proposal_file_name = isset($data['last_proposal_file_name']) ? $data['last_proposal_file_name'] : '';

        $this->last_proposal_file_name_base = isset($data['last_proposal_file_name_base']) ? $data['last_proposal_file_name_base'] : '';



        $this->seller_agreement_json = isset($data['seller_agreement_json']) ? $data['seller_agreement_json'] : '';

        $this->seller_agreement_pdf = isset($data['seller_agreement_pdf']) ? $data['seller_agreement_pdf'] : '';

        $this->seller_agreement_signature = isset($data['seller_agreement_signature']) ? $data['seller_agreement_signature'] : '';

        $this->is_seller_agreement = isset($data['is_seller_agreement']) ? $data['is_seller_agreement'] : 0;

        if (isset($data['password'])) {

            $this->password = $data['password'];
        }

        $this->seller_roles = isset($data['seller_roles']) ? $data['seller_roles'] : null;
        $this->stripe_customer_id = isset($data['stripe_customer_id']) ? $data['stripe_customer_id'] : '';

        $this->assign_agent_id = isset($data['assign_agent_id']) ? $data['assign_agent_id'] : null;
    }

    public function getId() {

        return $this->id;
    }

    function getLastProposalFileNameBase() {

        return $this->last_proposal_file_name_base;
    }

    function setDeletedAtNull() {

        $this->deletedAt = NULL;
    }

    function setLastProposalFileNameBase($value) {

        $this->last_proposal_file_name_base = $value;
    }

    function getLastProductFileNameBase() {

        return $this->last_product_file_name_base;
    }

    function setLastProductFileNameBase($value) {

        $this->last_product_file_name_base = $value;
    }

    function getLastProductFileName() {

        return $this->last_product_file_name;
    }

    function setLastProductFileName($value) {

        $this->last_product_file_name = $value;
    }

    function getLastProposalFileName() {

        return $this->last_proposal_file_name;
    }

    function setLastProposalFileName($value) {

        $this->last_proposal_file_name = $value;
    }

    function getLastSku() {

        return $this->last_sku;
    }

    function setLastSku($value) {

        $this->last_sku = $value;
    }

    function getFirstname() {

        return $this->firstname;
    }

    function getLastname() {

        return $this->lastname;
    }

    function getShopname() {

        return $this->shopname;
    }

    function getShopurl() {

        return $this->shopurl;
    }

    function getEmail() {

        return $this->email;
    }

    function getAddress() {

        return $this->address;
    }

    function getPhone() {

        return $this->phone;
    }

    function setFirstname($firstname) {

        $this->firstname = $firstname;
    }

    function setLastname($lastname) {

        $this->lastname = $lastname;
    }

    function setShopname($shopname) {

        $this->shopname = $shopname;
    }

    function setShopurl($shopurl) {

        $this->shopurl = $shopurl;
    }

    function setEmail($email) {

        $this->email = $email;
    }

    function setAddress($address) {

        $this->address = $address;
    }

    function setPhone($phone) {

        $this->phone = $phone;
    }

    function getPassword() {

        return $this->password;
    }

    function setPassword($password) {

        $this->password = $password;
    }

    function getWp_seller_id() {

        return $this->wp_seller_id;
    }

    function setWp_seller_id($wp_seller_id) {

        $this->wp_seller_id = $wp_seller_id;
    }

    function getDisplayname() {

        return $this->displayname;
    }

    function setDisplayname($displayname) {

        $this->displayname = $displayname;
    }

    function getSellerRoles() {

        return $this->seller_roles;
    }

    function setSellerRoles($value) {

        $this->seller_roles = $value;
    }

    public function addSellerRole(Option_master $role) {

        return $this->seller_roles[] = $role;
    }

    public function removeSellerRole($role) {

        return $this->seller_roles->removeElement($role);
    }

    function getIn_queue() {

        return $this->in_queue;
    }

    function setIn_queue($in_queue) {

        $this->in_queue = $in_queue;
    }

    function getIs_seller_agreement() {

        return $this->is_seller_agreement;
    }

    function getSeller_agreement_json() {

        return $this->seller_agreement_json;
    }

    function getSeller_agreement_signature() {

        return $this->seller_agreement_signature;
    }

    function getSeller_agreement_pdf() {

        return $this->seller_agreement_pdf;
    }

    function setIs_seller_agreement($is_seller_agreement) {

        $this->is_seller_agreement = $is_seller_agreement;
    }

    function setSeller_agreement_json($seller_agreement_json) {

        $this->seller_agreement_json = $seller_agreement_json;
    }

    function setSeller_agreement_signature($seller_agreement_signature) {

        $this->seller_agreement_signature = $seller_agreement_signature;
    }

    function setSeller_agreement_pdf($seller_agreement_pdf) {

        $this->seller_agreement_pdf = $seller_agreement_pdf;
    }

    function getStripe_customer_id() {
        return $this->stripe_customer_id;
    }

    function setStripe_customer_id($stripe_customer_id) {
        $this->stripe_customer_id = $stripe_customer_id;
    }

    function getAssign_agent_id() {
        return $this->assign_agent_id;
    }

    function setAssign_agent_id($assign_agent_id) {
        $this->assign_agent_id = $assign_agent_id;
    }


}
