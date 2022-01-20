<?php



namespace App\Entities;



use Gedmo\Mapping\Annotation as Gedmo;

use Doctrine\ORM\Mapping AS ORM;



/**

 * @ORM\Entity

 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)

 * @ORM\Table(name="products")

 */

class Products

{



    /**

     * @ORM\Id

     * @ORM\GeneratedValue

     * @ORM\Column(type="integer")

     */

    protected $id;



    /**

     *

     * @ORM\ManyToOne(targetEntity="Sell",inversedBy="products")

     * @ORM\JOinColumn(name="sell_id",referencedColumnName="id")

     */

    protected $sell_id;



    /**

     * @ORM\ManyToMany(targetEntity="Images")

     * @ORM\JoinTable(name="product_pending_images",

     *      joinColumns={@ORM\JoinColumn(name="product_pending_id",onDelete="CASCADE", referencedColumnName="id")},

     *      inverseJoinColumns={@ORM\JoinColumn(name="image_id", referencedColumnName="id")}

     *      )

     */

    protected $product_pending_images;



    /**

     *

     * @ORM\Column(type="string", nullable=true)

     */

    protected $name;



    /**

     *

     * @ORM\Column(type="text", nullable=true)

     */

    protected $note;



    /**

     *

     * @ORM\Column(type="string", nullable=true)

     */

    protected $image;



    /**

     *

     * @ORM\Column(type="string", nullable=true)

     */

    protected $quantity;



    /**

     * @ORM\Column(type="text", nullable=true)

     */

    protected $description;



    /**

     * @ORM\Column(type="string")

     */

    protected $price;

    /**

     * @ORM\Column(type="string", nullable=true)

     */

    protected $tlv_price;



    /**

     *

     * @ORM\ManyToOne(targetEntity="Seller",inversedBy="products")

     * @ORM\JOinColumn(name="sellerid",referencedColumnName="id")

     */

    protected $sellerid;



    /**

     * @ORM\Column(type="string")

     */

    protected $seller_firstname;



    /**

     * @ORM\Column(type="string")

     */

    protected $seller_lastname;



    /**

     * @ORM\Column(type="string")

     */

    protected $sku;



    /**

     * @ORM\Column(type="string", nullable=true)

     */

    protected $state;



    /**

     * @ORM\Column(type="string", nullable=true)

     */

    protected $city;



    /**

     * @ORM\Column(type="string", nullable=true)

     */

    protected $ship_size;



    /**

     * @ORM\Column(type="boolean")

     */

    protected $ship_material;



    /**

     * @ORM\Column(type="boolean")

     */

    protected $local_pickup_available;



    /**

     * @ORM\Column(type="string", nullable=true)

     */

    protected $ship_cat;

    /**

     * @ORM\Column(type="string", nullable=true)

     */

    protected $flat_rate_packaging_fee;



    /**

     * @ORM\Column(type="string", nullable=true)

     */

    protected $location;



    /**

     * @ORM\Column(type="string", nullable=true)

     */

    protected $category_local;



    /**

     * @ORM\Column(type="string", nullable=true)

     */

    protected $brand_local;



    /**

     * @ORM\Column(type="string", nullable=true)

     */

    protected $item_type_local;



    /**

     * @ORM\Column(type="string", nullable=true)

     */

    protected $condition_local;



    /**

     * @ORM\Column(type="string", nullable=true)

     */

    protected $wp_product_id;



    /**

     * @ORM\Column(type="string", nullable=true)

     */

    protected $wp_image_url;



    /**

     *

     * @ORM\ManyToOne(targetEntity="Option_master",inversedBy="Products")

     * @ORM\JOinColumn(name="pick_up_location",referencedColumnName="id")

     */

    protected $pick_up_location;



    /**

     * @ORM\Column(type="string", nullable=true)

     */

    protected $pet_free;



    /**

     * @ORM\ManyToMany(targetEntity="SubCategory")

     * @ORM\JoinTable(name="product_room",

     *      joinColumns={@ORM\JoinColumn(name="product_id",onDelete="CASCADE", referencedColumnName="id")},

     *      inverseJoinColumns={@ORM\JoinColumn(name="room_id", referencedColumnName="id")}

     *      )

     */

    protected $product_room;



    /**

     * @ORM\ManyToMany(targetEntity="subcategory")

     * @ORM\JoinTable(name="product_color",

     *      joinColumns={@ORM\JoinColumn(name="product_id",onDelete="CASCADE", referencedColumnName="id")},

     *      inverseJoinColumns={@ORM\JoinColumn(name="color_id", referencedColumnName="id")}

     *      )

     */

    protected $product_color;



    /**

     * @ORM\ManyToMany(targetEntity="subcategory")

     * @ORM\JoinTable(name="product_category",

     *      joinColumns={@ORM\JoinColumn(name="product_id",onDelete="CASCADE", referencedColumnName="id")},

     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}

     *      )

     */

    protected $product_category;



    /**

     * @ORM\ManyToMany(targetEntity="subcategory")

     * @ORM\JoinTable(name="product_con",

     *      joinColumns={@ORM\JoinColumn(name="product_id",onDelete="CASCADE", referencedColumnName="id")},

     *      inverseJoinColumns={@ORM\JoinColumn(name="con_id", referencedColumnName="id")}

     *      )

     */

    protected $product_con;



    /**

     * @ORM\ManyToMany(targetEntity="subcategory")

     * @ORM\JoinTable(name="product_collection",

     *      joinColumns={@ORM\JoinColumn(name="product_id",onDelete="CASCADE", referencedColumnName="id")},

     *      inverseJoinColumns={@ORM\JoinColumn(name="collection_id", referencedColumnName="id")}

     *      )

     */

    protected $product_collection;



    /**

     * @ORM\ManyToMany(targetEntity="subcategory")

     * @ORM\JoinTable(name="product_look",

     *      joinColumns={@ORM\JoinColumn(name="product_id",onDelete="CASCADE", referencedColumnName="id")},

     *      inverseJoinColumns={@ORM\JoinColumn(name="look_id", referencedColumnName="id")}

     *      )

     */

    protected $product_look;



    /**

     *

     * @ORM\ManyToOne(targetEntity="subcategory",inversedBy="products")

     * @ORM\JOinColumn(name="look",referencedColumnName="id", nullable=true)

     */

    protected $look;



    /**

     *

     * @ORM\ManyToOne(targetEntity="subcategory",inversedBy="products")

     * @ORM\JOinColumn(name="color",referencedColumnName="id", nullable=true)

     */

    protected $color;



    /**

     *

     * @ORM\ManyToOne(targetEntity="subcategory",inversedBy="products")

     * @ORM\JOinColumn(name="brand",referencedColumnName="id", nullable=true)

     */

    protected $brand;



    /**

     *

     * @ORM\ManyToOne(targetEntity="subcategory",inversedBy="products")

     * @ORM\JOinColumn(name="category",referencedColumnName="id", nullable=true)

     */

    protected $category;


    /**
     *
     * @ORM\ManyToOne(targetEntity="subcategory",inversedBy="products")
     * @ORM\JOinColumn(name="product_material",referencedColumnName="id", nullable=true)
     */
    protected $product_material;

     /**
     * @ORM\ManyToMany(targetEntity="subcategory")
     * @ORM\JoinTable(name="product_materials",
     *      joinColumns={@ORM\JoinColumn(name="product_id",onDelete="CASCADE", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="material_id", referencedColumnName="id")}
     *      )
     */

    protected $product_materials;

    /**

     *

     * @ORM\ManyToOne(targetEntity="subcategory",inversedBy="products")

     * @ORM\JOinColumn(name="collection",referencedColumnName="id", nullable=true)

     */

    protected $collection;



    /**

     *

     * @ORM\ManyToOne(targetEntity="subcategory",inversedBy="products")

     * @ORM\JOinColumn(name="con",referencedColumnName="id", nullable=true)

     */

    protected $con;



    /**

     *

     * @ORM\ManyToOne(targetEntity="subcategory",inversedBy="products")

     * @ORM\JOinColumn(name="age",referencedColumnName="id", nullable=true)

     */

    protected $age;



    /**

     *

     * @ORM\ManyToOne(targetEntity="Option_master",inversedBy="Products")

     * @ORM\JOinColumn(name="status",referencedColumnName="id")

     */

    protected $status;



    /**

     * @ORM\Column(type="string", nullable=true)

     */

    protected $tlv_suggested_price_min;



    /**

     * @ORM\Column(type="string", nullable=true)

     */

    protected $tlv_suggested_price_max;



    /**

     * @ORM\Column(type="datetime", nullable=true)

     */

    protected $approved_date;



    /**

     * @ORM\Column(type="integer")

     */

    protected $is_touched;



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

     * @ORM\Column(type="boolean")

     */

    protected $local_drop_off;

     /**

     * @ORM\Column(type="string", nullable=true)

     */

    protected $local_drop_off_city;



    public function __construct($data)

    {

        $this->tlv_suggested_price_min = isset($data['tlv_suggested_price_min']) ? $data['tlv_suggested_price_min'] : 0;

        $this->tlv_suggested_price_max = isset($data['tlv_suggested_price_max']) ? $data['tlv_suggested_price_max'] : 0;

        $this->note = isset($data['note']) ? $data['note'] : '';

        $this->sell_id = isset($data['sell_id']) ? $data['sell_id'] : null;

        $this->sellerid = isset($data['sellerid']) ? $data['sellerid'] : NULL;

        $this->seller_firstname = isset($data['seller_firstname']) ? $data['seller_firstname'] : '';

        $this->seller_lastname = isset($data['seller_lastname']) ? $data['seller_lastname'] : '';

        $this->name = isset($data['name']) ? $data['name'] : '';

        $this->description = isset($data['description']) ? $data['description'] : '';

        $this->price = isset($data['price']) ? $data['price'] : '';
        $this->tlv_price = isset($data['tlv_price']) ? $data['tlv_price'] : '';

        $this->sku = isset($data['sku']) ? $data['sku'] : '';

        $this->image = isset($data['image']) ? $data['image'] : '';

        $this->quantity = isset($data['quantity']) ? $data['quantity'] : '';



        $this->product_room = isset($data['product_room']) ? $data['product_room'] : NULL;

        $this->product_color = isset($data['product_color']) ? $data['product_color'] : NULL;

        $this->product_look = isset($data['product_look']) ? $data['product_look'] : NULL;

        $this->product_category = isset($data['product_category']) ? $data['product_category'] : NULL;

        $this->product_con = isset($data['product_con']) ? $data['product_con'] : NULL;

        $this->product_collection = isset($data['product_collection']) ? $data['product_collection'] : NULL;

//        $this->look = isset($data['look']) ? $data['look'] : NULL;

//        $this->color = isset($data['color']) ? $data['color'] : NULL;

        $this->brand = isset($data['brand']) ? $data['brand'] : NULL;

        $this->category = isset($data['category']) ? $data['category'] : NULL;

        $this->collection = isset($data['collection']) ? $data['collection'] : NULL;

        $this->con = isset($data['con']) ? $data['con'] : NULL;

        $this->age = isset($data['age']) ? $data['age'] : NULL;

        $this->status = isset($data['status']) ? $data['status'] : NULL;

        $this->product_pending_images = isset($data['product_pending_images']) ? $data['product_pending_images'] : NULL;

        $this->state = isset($data['state']) ? $data['state'] : '';

        $this->city = isset($data['city']) ? $data['city'] : '';

        $this->ship_size = isset($data['ship_size']) ? $data['ship_size'] : '';

        $this->ship_material = isset($data['ship_material']) ? $data['ship_material'] : 0;

        $this->local_pickup_available = isset($data['local_pickup_available']) ? $data['local_pickup_available'] : 0;



        $this->location = isset($data['location']) ? $data['location'] : '';



        $this->wp_image_url = isset($data['wp_image_url']) ? $data['wp_image_url'] : '';

        $this->wp_product_id = isset($data['wp_product_id']) ? $data['wp_product_id'] : '';

        $this->pick_up_location = isset($data['pick_up_location']) ? $data['pick_up_location'] : NULL;

        $this->pet_free = isset($data['pet_free']) ? $data['pet_free'] : '';





        $this->category_local = isset($data['category_local']) ? $data['category_local'] : '';

        $this->brand_local = isset($data['brand_local']) ? $data['brand_local'] : '';

        $this->item_type_local = isset($data['item_type_local']) ? $data['item_type_local'] : '';

        $this->condition_local = isset($data['condition_local']) ? $data['condition_local'] : '';



        $this->approved_date = isset($data['is_set_approved_date']) ? $data['is_set_approved_date'] : NULL;

        $this->is_touched = isset($data['is_touched']) ? $data['is_touched'] : 0;





        $this->ship_cat = isset($data['ship_cat']) ? $data['ship_cat'] : '';

        $this->flat_rate_packaging_fee = isset($data['flat_rate_packaging_fee']) ? $data['flat_rate_packaging_fee'] : '';

        $this->product_material = isset($data['product_material']) ? $data['product_material'] : null;
        $this->product_materials = isset($data['product_materials']) ? $data['product_materials'] : null;

        $this->local_drop_off = isset($data['local_drop_off']) ? $data['local_drop_off'] : 0;
        $this->local_drop_off_city = isset($data['local_drop_off_city']) ? $data['local_drop_off_city'] : NULL;

    }



    public function getId()

    {

        return $this->id;

    }



    function getProductCategory()

    {

        return $this->product_category;

    }



    function setProductCategory($value)

    {

        $this->product_category = $value;

    }



    function getProductCon()

    {

        return $this->product_con;

    }



    function setProductCon($value)

    {

        $this->product_con = $value;

    }



    function getproductCollection()

    {

        return $this->product_collection;

    }



    function setProductCollection($value)

    {

        $this->product_collection = $value;

    }



    function getAge()

    {

        return $this->age;

    }



    function setAge($value)

    {

        $this->age = $value;

    }



    function getIsTouched()

    {

        return $this->is_touched;

    }



    function setIsTouched($value)

    {

        $this->is_touched = $value;

    }



    function getApprovedDate()

    {

        return $this->approved_date;

    }



    function setApprovedDate($value)

    {

        $this->approved_date = $value;

    }



    function getConditionLocal()

    {

        return $this->condition_local;

    }



    function setConditionLocal($value)

    {

        $this->condition_local = $value;

    }



    function getItemTypeLocal()

    {

        return $this->item_type_local;

    }



    function setItemTypeLocal($value)

    {

        $this->item_type_local = $value;

    }



    function getBrandLocal()

    {

        return $this->brand_local;

    }



    function setBrandLocal($value)

    {

        $this->brand_local = $value;

    }



    function getCategoryLocal()

    {

        return $this->category_local;

    }



    function setCategoryLocal($value)

    {

        $this->category_local = $value;

    }



    function getLocation()

    {

        return $this->location;

    }



    function setLocation($value)

    {

        $this->location = $value;

    }



    function getTlv_suggested_price_min()

    {

        return $this->tlv_suggested_price_min;

    }



    function getTlv_suggested_price_max()

    {

        return $this->tlv_suggested_price_max;

    }



    function setTlv_suggested_price_min($tlv_suggested_price_min)

    {

        $this->tlv_suggested_price_min = $tlv_suggested_price_min;

    }



    function setTlv_suggested_price_max($tlv_suggested_price_max)

    {

        $this->tlv_suggested_price_max = $tlv_suggested_price_max;

    }



    function getNote()

    {

        return $this->note;

    }



    function setNote($value)

    {

        $this->note = $value;

    }



    function getProductPendingImages()

    {

        return $this->product_pending_images;

    }



    function setProductPendingImages($product_images)

    {

        $this->product_pending_images = $product_images;

    }



    function getName()

    {

        return $this->name;

    }



    function getDescription()

    {

        return $this->description;

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

    function getTLVPrice()

    {

        return $this->tlv_price;

    }


    function setTLVPrice($value)

    {

        $this->tlv_price = $value;

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

        return $this->product_room;

    }



    function getLook()

    {

        return $this->product_look;

    }



    function getColor()

    {

        return $this->product_color;

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

        $this->product_room = $room;

    }



    function setLook($look)

    {

        $this->product_look = $look;

    }



    function setColor($color)

    {

        $this->product_color = $color;

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



    function getSeller_firstname()

    {

        return $this->seller_firstname;

    }



    function getSeller_lastname()

    {

        return $this->seller_lastname;

    }



    function setSeller_firstname($seller_firstname)

    {

        $this->seller_firstname = $seller_firstname;

    }



    function setSeller_lastname($seller_lastname)

    {

        $this->seller_lastname = $seller_lastname;

    }



    function getState()

    {

        return $this->state;

    }



    function getCity()

    {

        return $this->city;

    }



    function setState($state)

    {

        $this->state = $state;

    }



    function setCity($city)

    {

        $this->city = $city;

    }



    function getSellerid()

    {

        return $this->sellerid;

    }



    function setSellerid($sellerid)

    {

        $this->sellerid = $sellerid;

    }



    function getWp_product_id()

    {

        return $this->wp_product_id;

    }



    function getWp_image_url()

    {

        return $this->wp_image_url;

    }



    function setWp_product_id($wp_product_id)

    {

        $this->wp_product_id = $wp_product_id;

    }



    function setWp_image_url($wp_image_url)

    {

        $this->wp_image_url = $wp_image_url;

    }



    function getPick_up_location()

    {

        return $this->pick_up_location;

    }



    function getPet_free()

    {

        return $this->pet_free;

    }



    function setPick_up_location($pick_up_location)

    {

        $this->pick_up_location = $pick_up_location;

    }



    function setPet_free($pet_free)

    {

        $this->pet_free = $pet_free;

    }



    function getShip_size()

    {

        return $this->ship_size;

    }



    function getShip_material()

    {

        return $this->ship_material;

    }



    function setShip_size($ship_size)

    {

        $this->ship_size = $ship_size;

    }



    function setShip_material($ship_material)

    {

        $this->ship_material = $ship_material;

    }

    function getShip_cat()

    {

        return $this->ship_cat;

    }







    function setShip_cat($ship_cat)

    {

        $this->ship_cat = $ship_cat;

    }



    function getFlat_rate_packaging_fee()

    {

        return $this->flat_rate_packaging_fee;

    }



    function setFlat_rate_packaging_fee($flat_rate_packaging_fee)

    {

        $this->flat_rate_packaging_fee = $flat_rate_packaging_fee;

    }



    function getLocal_pickup_available()

    {

        return $this->local_pickup_available;

    }



    function setLocal_pickup_available($local_pickup_available)

    {

        $this->local_pickup_available = $local_pickup_available;

    }

    function get_product_material() {
        return $this->product_material;
    }

    function set_product_material($product_material) {
        $this->product_material = $product_material;
    }

        // many to many
    function get_product_materials() {
        return $this->product_materials;
    }

    function set_product_materials($product_materials) {
        $this->product_materials = $product_materials;
    }

    function getLocal_drop_off()
    {
        return $this->local_drop_off;
}

    function setLocal_drop_off($value)
    {
        $this->local_drop_off = $value;
    }

    function getLocal_drop_off_city()
    {
        return $this->local_drop_off_city;
    }

    function setLocal_drop_off_city($value)
    {
        $this->local_drop_off_city = $value;
    }

}

