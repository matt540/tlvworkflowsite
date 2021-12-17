<?php

namespace App\Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ORM\Table(name="schedule")
 */
class Schedule
{
    /**

     * @ORM\Id

     * @ORM\GeneratedValue

     * @ORM\Column(type="integer")

     */

    protected $id;



    /**

     *

     * @ORM\ManyToOne(targetEntity="Products_approved",inversedBy="Schedule")

     * @ORM\JOinColumn(name="product_id",referencedColumnName="id")

     */

    protected $product_id;

    

    /**

     * @ORM\ManyToOne(targetEntity="Products_quotation",inversedBy="Schedule")

     * @ORM\JOinColumn(name="product_quot_id",referencedColumnName="id")

     */

    protected $product_quot_id;

    

    /**

     * 

     * @ORM\Column(type="string", nullable=true)

     */

    protected $seller_id;

    

    /**

     * 

     * @ORM\Column(type="string", nullable=true)

     */

    protected $seller_name;



    /**

     * 

     * @ORM\Column(type="date", nullable=true)

     */

    protected $date;



    /**

     * @ORM\Column(type="time", nullable=true)

     */

    protected $time;



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

        $this->product_id = isset($data['product_id']) ? $data['product_id'] : NULL;

        $this->product_quot_id = isset($data['product_quot_id']) ? $data['product_quot_id'] : NULL;

        $this->date = isset($data['date']) ? $data['date'] : NULL;

        $this->time = isset($data['time']) ? $data['time'] : NULL;

        $this->seller_id = isset($data['seller_id']) ? $data['seller_id'] : '';

        $this->seller_name = isset($data['seller_name']) ? $data['seller_name'] : '';

    }



    public function getId()

    {

        return $this->id;

    }



    function getProduct_id()

    {

        return $this->product_id;

    }



    function getProductQuotId()

    {

        return $this->product_quot_id;

    }



    function setProductQuotId($value)

    {

        $this->product_quot_id = $value;

    }



    function getDate()

    {

        return $this->date;

    }



    function getTime()

    {

        return $this->time;

    }



    function setProduct_id($product_id)

    {

        $this->product_id = $product_id;

    }



    function setDate($date)

    {

        $this->date = $date;

    }



    function setTime($time)

    {

        $this->time = $time;

    }

    

    function getSeller_id()

    {

        return $this->seller_id;

    }



    function getSeller_name()

    {

        return $this->seller_name;

    }



    function setSeller_id($seller_id)

    {

        $this->seller_id = $seller_id;

    }



    function setSeller_name($seller_name)

    {

        $this->seller_name = $seller_name;

    }







}

