<?php

namespace App\Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ORM\Table(name="products_approved")
 */
class Products_approved
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="sell",inversedBy="products")
     * @ORM\JOinColumn(name="sell_id",referencedColumnName="id")
     */
    protected $sell_id;

    /**
     * 
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

    /**
     * 
     * @ORM\Column(type="string", nullable=true)
     */
    protected $quantity;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

    /**
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $is_scheduled;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $sort_description;

    /**
     * @ORM\Column(type="string")
     */
    protected $price;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $tlv_suggested_price;

    /**
     * @ORM\Column(type="integer")
     */
    protected $seller_id;

    /**
     * @ORM\Column(type="string")
     */
    protected $sku;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $materials;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $diamensions;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $images_from;

    /**
     * @ORM\ManyToMany(targetEntity="Images")
     * @ORM\JoinTable(name="product_images",
     *      joinColumns={@ORM\JoinColumn(name="product_id",onDelete="CASCADE", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="image_id", referencedColumnName="id")}
     *      )
     */
    protected $product_images;

    /**
     *
     * @ORM\ManyToOne(targetEntity="products",inversedBy="products_approved")
     * @ORM\JOinColumn(name="product_id",referencedColumnName="id", nullable=true)
     */
    protected $product_id;

    /**
     *
     * @ORM\ManyToOne(targetEntity="subcategory",inversedBy="products_approved")
     * @ORM\JOinColumn(name="room",referencedColumnName="id", nullable=true)
     */
    protected $room;

    /**
     *
     * @ORM\ManyToOne(targetEntity="subcategory",inversedBy="products_approved")
     * @ORM\JOinColumn(name="look",referencedColumnName="id", nullable=true)
     */
    protected $look;

    /**
     *
     * @ORM\ManyToOne(targetEntity="subcategory",inversedBy="products_approved")
     * @ORM\JOinColumn(name="color",referencedColumnName="id", nullable=true)
     */
    protected $color;

    /**
     *
     * @ORM\ManyToOne(targetEntity="subcategory",inversedBy="products_approved")
     * @ORM\JOinColumn(name="brand",referencedColumnName="id", nullable=true)
     */
    protected $brand;

    /**
     *
     * @ORM\ManyToOne(targetEntity="subcategory",inversedBy="products_approved")
     * @ORM\JOinColumn(name="category",referencedColumnName="id", nullable=true)
     */
    protected $category;

    /**
     *
     * @ORM\ManyToOne(targetEntity="subcategory",inversedBy="products_approved")
     * @ORM\JOinColumn(name="collection",referencedColumnName="id", nullable=true)
     */
    protected $collection;

    /**
     *
     * @ORM\ManyToOne(targetEntity="subcategory",inversedBy="products_approved")
     * @ORM\JOinColumn(name="con",referencedColumnName="id", nullable=true)
     */
    protected $con;



    /**

     *

     * @ORM\ManyToOne(targetEntity="Option_master",inversedBy="Products")

     * @ORM\JOinColumn(name="status",referencedColumnName="id")

     */

    protected $status;



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

        $this->product_id = isset($data['product_id']) ? $data['product_id'] : '';

        $this->sell_id = isset($data['sell_id']) ? $data['sell_id'] : null;

        $this->seller_id = isset($data['seller_id']) ? $data['seller_id'] : '';

        $this->name = isset($data['name']) ? $data['name'] : '';

        $this->description = isset($data['description']) ? $data['description'] : '';

        $this->price = isset($data['price']) ? $data['price'] : '';

        $this->sku = isset($data['sku']) ? $data['sku'] : '';

        $this->quantity = isset($data['quantity']) ? $data['quantity'] : '';



        $this->room = isset($data['room']) ? $data['room'] : NULL;

        $this->look = isset($data['look']) ? $data['look'] : NULL;

        $this->color = isset($data['color']) ? $data['color'] : NULL;

        $this->brand = isset($data['brand']) ? $data['brand'] : NULL;

        $this->category = isset($data['category']) ? $data['category'] : NULL;

        $this->collection = isset($data['collection']) ? $data['collection'] : NULL;

        $this->con = isset($data['con']) ? $data['con'] : NULL;

        $this->status = isset($data['status']) ? $data['status'] : NULL;



        $this->tlv_suggested_price = isset($data['tlv_suggested_price']) ? $data['tlv_suggested_price'] : '';

        $this->sort_description = isset($data['sort_description']) ? $data['sort_description'] : '';

        $this->diamensions = isset($data['diamensions']) ? $data['diamensions'] : '';

        $this->materials = isset($data['materials']) ? $data['materials'] : '';

        $this->product_images = isset($data['product_images']) ? $data['product_images'] : '';



        $this->images_from = isset($data['images_from']) ? $data['images_from'] : '';

        $this->is_scheduled = isset($data['is_scheduled']) ? $data['is_scheduled'] : 0;

    }



    public function getId()

    {

        return $this->id;

    }



    function getName()

    {

        return $this->name;

    }



    function getDescription()

    {

        return $this->description;

    }



    function getIsScheduled()

    {

        return $this->is_scheduled;

    }



    function setIsScheduled($value)

    {

        $this->is_scheduled = $value;

    }



    function getCreated_at()

    {

        return $this->created_at;

    }



    function setName($name)

    {

        $this->name = $name;

    }



    function setDescription($description)

    {

        $this->description = $description;

    }



    function setCreated_at(\DateTime $created_at)

    {

        $this->created_at = $created_at;

    }



    function getSell_id()

    {

        return $this->sell_id;

    }



    function setSell_id($sell_id)

    {

        $this->sell_id = $sell_id;

    }



    function getPrice()

    {

        return $this->price;

    }



    function setPrice($price)

    {

        $this->price = $price;

    }



    function getImage()

    {

        return $this->image;

    }



    function getQuantity()

    {

        return $this->quantity;

    }



    function getSku()

    {

        return $this->sku;

    }



    function getRoom()

    {

        return $this->room;

    }



    function getLook()

    {

        return $this->look;

    }



    function getColor()

    {

        return $this->color;

    }



    function getBrand()

    {

        return $this->brand;

    }



    function getCategory()

    {

        return $this->category;

    }



    function getCollection()

    {

        return $this->collection;

    }



    function getCondition()

    {

        return $this->con;

    }



    function setImage($image)

    {

        $this->image = $image;

    }



    function setQuantity($quantity)

    {

        $this->quantity = $quantity;

    }



    function setSku($sku)

    {

        $this->sku = $sku;

    }



    function setRoom($room)

    {

        $this->room = $room;

    }



    function setLook($look)

    {

        $this->look = $look;

    }



    function setColor($color)

    {

        $this->color = $color;

    }



    function setBrand($brand)

    {

        $this->brand = $brand;

    }



    function setCategory($category)

    {

        $this->category = $category;

    }



    function setCollection($collection)

    {

        $this->collection = $collection;

    }



    function setCondition($condition)

    {

        $this->con = $condition;

    }



    function getSeller_id()

    {

        return $this->seller_id;

    }



    function setSeller_id($seller_id)

    {

        $this->seller_id = $seller_id;

    }



    function getStatus()

    {

        return $this->status;

    }



    function setStatus($status)

    {

        $this->status = $status;

    }



    function getSort_description()

    {

        return $this->sort_description;

    }



    function getTlv_suggested_price()

    {

        return $this->tlv_suggested_price;

    }



    function getMaterials()

    {

        return $this->materials;

    }



    function getDiamensions()

    {

        return $this->diamensions;

    }



    function setSort_description($sort_description)

    {

        $this->sort_description = $sort_description;

    }



    function setTlv_suggested_price($tlv_suggested_price)

    {

        $this->tlv_suggested_price = $tlv_suggested_price;

    }



    function setMaterials($materials)

    {

        $this->materials = $materials;

    }



    function setDiamensions($diamensions)

    {

        $this->diamensions = $diamensions;

    }



    function getProduct_id()

    {

        return $this->product_id;

    }



    function setProduct_id($product_id)

    {

        $this->product_id = $product_id;

    }



    function getImages_from()

    {

        return $this->images_from;

    }



    function setImages_from($images_from)

    {

        $this->images_from = $images_from;

    }



    function getProduct_images()

    {

        return $this->product_images;

    }



    function setProduct_images($product_images)

    {

        $this->product_images = $product_images;

    }



}

