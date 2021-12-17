<?php

// declare(strict_types=1);

namespace App\Entities;



use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping AS ORM;

// use LaravelDoctrine\Extensions\Timestamps\Timestamps;

use Doctrine\Common\Collections\ArrayCollection;
// use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;


/**

 * @ORM\Entity

 * @ORM\Table(name="category")

 */

class Category
{
    // use Timestamps;

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $category_name;

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
     * @param string $name
     */
    public function __construct($name)
    {
        $this->category_name = isset($data['category_name']) ? $data['category_name'] : '';

        $this->status = isset($data['status']) ? $data['status'] : '';
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCategoryName()
    {
        return $this->category_name;
    }

    /**
     * @param string $name
     */
    public function setCategoryName($value)
    {
        $this->category_name = $value;
    }

    public function setStatus($value)
    {
        $this->status = $value;
    }

    public function getStatus()
    {
        return $this->status;
    }
}

