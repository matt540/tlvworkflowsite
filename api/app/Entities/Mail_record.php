<?php

namespace App\Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ORM\Table(name="mail_records")
 */
class Mail_record
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

    protected $subject;



    /**

     * 

     * @ORM\Column(type="text")

     */

    protected $message;



    /**

     * 

     * @ORM\Column(type="string")

     */

    protected $file_name;



    /**

     * 

     * @ORM\Column(type="string")

     */

    protected $file_path;



    /**

     * 

     * @ORM\Column(type="string")

     */

    protected $from_state;



    /**

     *

     * @ORM\ManyToOne(targetEntity="Seller",inversedBy="Mail_record")

     * @ORM\JOinColumn(name="seller_id",referencedColumnName="id")

     */

    protected $seller_id;



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

        $this->file_name = isset($data['file_name']) ? $data['file_name'] : '';

        $this->file_path = isset($data['file_path']) ? $data['file_path'] : '';

        $this->seller_id = isset($data['seller_id']) ? $data['seller_id'] : NULL;

        $this->created_by = isset($data['created_by']) ? $data['created_by'] : NULL;

        $this->from_state = isset($data['from_state']) ? $data['from_state'] : '';

        $this->message = isset($data['message']) ? $data['message'] : '';

        $this->subject = isset($data['subject']) ? $data['subject'] : '';

    }



    public function getId()

    {

        return $this->id;

    }



    function getSubject()

    {

        return $this->subject;

    }



    function setSubject()

    {

        return $this->subject;

    }



    function getMessage()

    {

        return $this->message;

    }



    function setMessage($value)

    {

        $this->message = $value;

    }



    function getFromState()

    {

        return $this->from_state;

    }



    function setFromState($name)

    {

        $this->from_state = $name;

    }



    function getFileName()

    {

        return $this->file_name;

    }



    function setFileName($name)

    {

        $this->file_name = $name;

    }



    function getFilePath()

    {

        return $this->file_path;

    }



    function setFilePath($name)

    {

        $this->file_path = $name;

    }



    function getSellerId()

    {

        return $this->seller_id;

    }



    function setSellerId($name)

    {

        $this->seller_id = $name;

    }



    function getCreatedBy()

    {

        return $this->created_by;

    }



    function setCreatedBy($name)

    {

        $this->created_by = $name;

    }



}

