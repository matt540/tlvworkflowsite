<?php

namespace App\Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ORM\Table(name="product_quote_renew")
 */
class ProductQuoteRenew
{
    /**

     * @ORM\Id

     * @ORM\GeneratedValue

     * @ORM\Column(type="integer")

     */

    protected $id;



    /**

     *

     * @ORM\ManyToOne(targetEntity="Seller",inversedBy="ProductQuoteRenew")

     * @ORM\JOinColumn(name="seller_id",referencedColumnName="id")

     */

    protected $seller_id;



    /**

     *

     * @ORM\ManyToOne(targetEntity="Products_quotation",inversedBy="ProductQuoteRenew")

     * @ORM\JOinColumn(name="product_quote_id",referencedColumnName="id")

     */

    protected $product_quote_id;



    /**

     * @ORM\Column(type="string")

     */

    protected $data_json;



    /**

     * @ORM\Column(type="string")

     */

    protected $name;



    /**

     * @ORM\Column(type="string")

     */

    protected $wp_product_id;



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

        $this->seller_id = isset($data['seller_id']) ? $data['seller_id'] : NULL;

        $this->product_quote_id = isset($data['product_quote_id']) ? $data['product_quote_id'] : NULL;

        $this->data_json = isset($data['data_json']) ? $data['data_json'] : json_encode([]);

        $this->wp_product_id = isset($data['wp_product_id']) ? $data['wp_product_id'] : '';

        $this->name = isset($data['name']) ? $data['name'] : '';

    }



    public function getId()

    {

        return $this->id;

    }



    function getName()

    {

        return $this->name;

    }



    function setName($name)

    {

        $this->name = $name;

    }



    function getSellerId()

    {

        return $this->seller_id;

    }



    function getProductQuoteId()

    {

        return $this->product_quote_id;

    }



    function getDataJson()

    {

        return $this->data_json;

    }



    function getWpProductId()

    {

        return $this->wp_product_id;

    }



    function setSellerId($seller_id)

    {

        $this->seller_id = $seller_id;

    }



    function setProductQuoteId($product_quote_id)

    {

        $this->product_quote_id = $product_quote_id;

    }



    function setDataJson($data_json)

    {

        $this->data_json = $data_json;

    }



    function setWpProductId($wp_product_id)

    {

        $this->wp_product_id = $wp_product_id;

    }



}

