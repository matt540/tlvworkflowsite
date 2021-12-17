<?php

namespace App\Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ORM\Table(name="sell")
 */
class Sell 
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="users",inversedBy="products")
     * @ORM\JOinColumn(name="user_id",referencedColumnName="id")
     */
    protected $user_id;

    /**
     * 
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

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
        $this->user_id = isset($data['user_id']) ? $data['user_id'] : '';

        $this->name = isset($data['name']) ? $data['name'] : '';
    }

    public function getId() {
        return $this->id;
    }

    function getUser_id() {
        return $this->user_id;
    }

    function getName() {
        return $this->name;
    }

    function getCreated_at() {
        return $this->created_at;
    }

    function setUser_id($user_id) {
        $this->user_id = $user_id;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setCreated_at(\DateTime $created_at) {
        $this->created_at = $created_at;
    }
}