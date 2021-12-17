<?php

namespace App\Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ORM\Table(name="option_master")
 */
class Option_master {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Select_master",inversedBy="option_master")
     * @ORM\JOinColumn(name="select_id",referencedColumnName="id")
     */
    protected $select_id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Seller",inversedBy="option_master")
     * @ORM\JOinColumn(name="seller_id",referencedColumnName="id", nullable=true)
     */
    protected $seller_id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $key_text;

    /**
     * @ORM\Column(type="string")
     */
    protected $value_text;

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

    public function __construct($data) {

        $this->select_id = isset($data['select_id']) ? $data['select_id'] : '';

        $this->key_text = isset($data['key_text']) ? $data['key_text'] : '';

        $this->value_text = isset($data['value_text']) ? $data['value_text'] : '';

        $this->seller_id = isset($data['seller_id']) ? $data['seller_id'] : NULL;
    }

    public function getId() {

        return $this->id;
    }

    public function getSellerId() {

        return $this->seller_id;
    }

    public function setSellerId($value) {

        $this->seller_id = $value;
    }

    public function getValueText() {

        return $this->value_text;
    }

    public function setValueText($value) {

        $this->value_text = $value;
    }

    public function setKeyText($value) {

        $this->key_text = $value;
    }

    public function getKeyText() {

        return $this->key_text;
    }

    public function setSelectId($value) {

        $this->select_id = $value;
    }

    public function getSelectId() {

        return $this->select_id;
    }

}
