<?php

namespace App\Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ORM\Table(name="email_send_records")
 */
class Email_send_record
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * 
     * @ORM\Column(type="text")
     */
    protected $email;

    /**
     * 
     * @ORM\Column(type="text")
     */
    protected $subject;

    /**
     * 
     * @ORM\Column(type="text")
     */
    protected $body;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Users",inversedBy="Mail_record")
     * @ORM\JOinColumn(name="created_by",referencedColumnName="id")
     */
    protected $created_by;

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

    public function __construct($data)
    {
        $this->email = isset($data['email']) ? $data['email'] : '';

        $this->subject = isset($data['subject']) ? $data['subject'] : '';

        $this->body = isset($data['body']) ? $data['body'] : NULL;

        $this->created_by = isset($data['created_by']) ? $data['created_by'] : NULL;
    }



    public function getId()
    {
        return $this->id;
    }

    function getEmail()
    {
        return $this->email;
    }

    function setEmail($value)
    {
        $this->email = $value;
    }

    function getSubject()
    {
        return $this->subject;
    }

    function setSubject($value)
    {
        $this->subject = $value;
    }

    function getBody()
    {
        return $this->body;
    }

    function setBody($value)
    {
        $this->body = $value;
    }

    function getCreatedBy()
    {

        return $this->created_by;
    }



    function setCreatedBy($value)

    {

        $this->created_by = $value;
    }
}
