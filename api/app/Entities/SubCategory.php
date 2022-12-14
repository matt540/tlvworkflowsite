<?php

namespace App\Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ORM\Table(name="subcategory")
 */
class SubCategory {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Category",inversedBy="subcategory")
     * @ORM\JOinColumn(name="category_id",referencedColumnName="id")
     */
    protected $category_id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="SubCategory",inversedBy="SubCategory")
     * @ORM\JOinColumn(name="parent_id",referencedColumnName="id")
     */
    protected $parent_id;

    /**
     * @ORM\Column(type="string")
     */
    protected $wp_term_id;

    /**
     * @ORM\Column(type="string")
     */
    protected $sub_category_name;

    /**
     * @ORM\Column(type="integer")
     */
    protected $order_value;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $is_enable;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $category_storage_price;

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

        $this->sub_category_name = isset($data['sub_category_name']) ? $data['sub_category_name'] : '';

        $this->category_id = isset($data['category_id']) ? $data['category_id'] : NULL;

//        $this->status = isset($data['status']) ? $data['status'] : '';

        $this->wp_term_id = isset($data['wp_term_id']) ? $data['wp_term_id'] : '';

        $this->parent_id = isset($data['parent_id']) ? $data['parent_id'] : NULL;

        $this->order_value = isset($data['order_value']) ? $data['order_value'] : '';
        
        $this->category_storage_price = isset($data['category_storage_price']) ? $data['category_storage_price'] : '';
        
        $this->is_enable = isset($data['is_enable']) ? $data['is_enable'] : 1;
    }

    public function getId() {

        return $this->id;
    }

    public function setParentId($value) {

        $this->parent_id = $value;
    }

    public function getParentId() {

        return $this->parent_id;
    }

    public function setSubCategoryName($value) {

        $this->sub_category_name = $value;
    }

    public function getSubCategoryName() {

        return $this->sub_category_name;
    }

    public function setStatus($value) {

        $this->status = $value;
    }

    public function getStatus() {

        return $this->status;
    }

    public function setCategoryId($value) {

        $this->category_id = $value;
    }

    public function getCategoryId() {

        return $this->category_id;
    }

    function getOrder_value() {

        return $this->order_value;
    }

    function setOrder_value($order_value) {

        $this->order_value = $order_value;
    }

    function getCategory_storage_price() {
        return $this->category_storage_price;
    }

    function setCategory_storage_price($category_storage_price) {
        $this->category_storage_price = $category_storage_price;
    }
    
    function getIs_enable() {
        return $this->is_enable;
    }

    function setIs_enable($is_enable) {
        $this->is_enable = $is_enable;
    }

}
