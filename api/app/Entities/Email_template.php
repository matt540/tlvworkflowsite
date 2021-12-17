<?php

namespace App\Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ORM\Table(name="email_template")
 */
class Email_template
{
    /**

     * @ORM\Id

     * @ORM\GeneratedValue

     * @ORM\Column(type="integer")

     */

    protected $id;



    /**

     * @ORM\Column(type="string" , nullable=true)

     */

    protected $name;



    /**

     * @ORM\Column(type="string" , nullable=true)

     */

    protected $subject;

    

    /**

     * @ORM\Column(type="text" , nullable=true)

     */

    protected $description;



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

     * @param $firstname

     * @param $lastname

     */

    public function __construct($data)

    {



        $this->name = $data['name'];

        $this->subject = $data['subject'];

        $this->description = $data['description'];

    }



    public function getId()

    {

        return $this->id;

    }



    public function getName()

    {

        return $this->name;

    }



    public function setName($name)

    {

        $this->name = $name;

    }



    function getSubject()

    {

        return $this->subject;

    }



    function getDescription()

    {

        return $this->description;

    }



    function setSubject($subject)

    {

        $this->subject = $subject;

    }



    function setDescription($description)

    {

        $this->description = $description;

    }





}

