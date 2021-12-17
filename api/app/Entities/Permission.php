<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\ACL\Contracts\Permission as PermissionContract;

/**
 * @ORM\Entity
 */
class Permission implements PermissionContract
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")

     * @ORM\GeneratedValue(strategy="AUTO")

     */

    protected $id;



    /**

     * @ORM\Column(type="string")

     */

    protected $name;



    /**

     * @ORM\Column(type="string")

     */

    protected $title;



    /**

     * 

     * @ORM\ManyToOne(targetEntity="Option_master",inversedBy="Permission")

     * @ORM\JOinColumn(name="category",referencedColumnName="id",nullable=true)

     */

    protected $category;



    /**

     * @param $name

     */

    public function __construct($data)

    {

        $this->name = isset($data['name']) ? $data['name'] : '';

        $this->title = isset($data['title']) ? $data['title'] : '';

        $this->category = isset($data['category']) ? $data['category'] : null;

    }



    /**

     * @return mixed

     */

    public function getId()

    {

        return $this->id;

    }



    /**

     * @return string

     */

    public function getName()

    {

        return $this->name;

    }



    /**

     * @param mixed $name

     */

    public function setName($name)

    {

        $this->name = $name;

    }

    public function setTitle($title)

    {

        $this->title = $title;

    }

    public function setCategory($category)

    {

        $this->category = $category;

    }



}

