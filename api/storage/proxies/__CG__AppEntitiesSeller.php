<?php

namespace DoctrineProxies\__CG__\App\Entities;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Seller extends \App\Entities\Seller implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Proxy\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Proxy\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array<string, null> properties to be lazy loaded, indexed by property name
     */
    public static $lazyPropertiesNames = array (
  'assign_agent_id' => NULL,
);

    /**
     * @var array<string, mixed> default values of properties to be lazy loaded, with keys being the property names
     *
     * @see \Doctrine\Common\Proxy\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array (
  'assign_agent_id' => NULL,
);



    public function __construct(?\Closure $initializer = null, ?\Closure $cloner = null)
    {
        unset($this->assign_agent_id);

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }

    /**
     * 
     * @param string $name
     */
    public function __get($name)
    {
        if (\array_key_exists($name, self::$lazyPropertiesNames)) {
            $this->__initializer__ && $this->__initializer__->__invoke($this, '__get', [$name]);
            return $this->$name;
        }

        trigger_error(sprintf('Undefined property: %s::$%s', __CLASS__, $name), E_USER_NOTICE);

    }

    /**
     * 
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value)
    {
        if (\array_key_exists($name, self::$lazyPropertiesNames)) {
            $this->__initializer__ && $this->__initializer__->__invoke($this, '__set', [$name, $value]);

            $this->$name = $value;

            return;
        }

        $this->$name = $value;
    }

    /**
     * 
     * @param  string $name
     * @return boolean
     */
    public function __isset($name)
    {
        if (\array_key_exists($name, self::$lazyPropertiesNames)) {
            $this->__initializer__ && $this->__initializer__->__invoke($this, '__isset', [$name]);

            return isset($this->$name);
        }

        return false;
    }

    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return ['__isInitialized__', 'id', 'wp_seller_id', 'displayname', 'firstname', 'lastname', 'shopname', 'password', 'shopurl', 'email', 'address', 'phone', 'last_sku', 'in_queue', 'last_product_file_name', 'last_product_file_name_base', 'last_proposal_file_name', 'last_proposal_file_name_base', '' . "\0" . 'App\\Entities\\Seller' . "\0" . 'seller_roles', 'is_seller_agreement', 'seller_agreement_json', 'seller_agreement_signature', 'seller_agreement_pdf', 'created_at', 'updated_at', 'deletedAt', 'stripe_customer_id', 'assign_agent_id'];
        }

        return ['__isInitialized__', 'id', 'wp_seller_id', 'displayname', 'firstname', 'lastname', 'shopname', 'password', 'shopurl', 'email', 'address', 'phone', 'last_sku', 'in_queue', 'last_product_file_name', 'last_product_file_name_base', 'last_proposal_file_name', 'last_proposal_file_name_base', '' . "\0" . 'App\\Entities\\Seller' . "\0" . 'seller_roles', 'is_seller_agreement', 'seller_agreement_json', 'seller_agreement_signature', 'seller_agreement_pdf', 'created_at', 'updated_at', 'deletedAt', 'stripe_customer_id'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Seller $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy::$lazyPropertiesDefaults as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

            unset($this->assign_agent_id);
        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', []);
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', []);
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @deprecated no longer in use - generated code now relies on internal components rather than generated public API
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', []);

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function getLastProposalFileNameBase()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLastProposalFileNameBase', []);

        return parent::getLastProposalFileNameBase();
    }

    /**
     * {@inheritDoc}
     */
    public function setDeletedAtNull()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDeletedAtNull', []);

        return parent::setDeletedAtNull();
    }

    /**
     * {@inheritDoc}
     */
    public function setLastProposalFileNameBase($value)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLastProposalFileNameBase', [$value]);

        return parent::setLastProposalFileNameBase($value);
    }

    /**
     * {@inheritDoc}
     */
    public function getLastProductFileNameBase()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLastProductFileNameBase', []);

        return parent::getLastProductFileNameBase();
    }

    /**
     * {@inheritDoc}
     */
    public function setLastProductFileNameBase($value)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLastProductFileNameBase', [$value]);

        return parent::setLastProductFileNameBase($value);
    }

    /**
     * {@inheritDoc}
     */
    public function getLastProductFileName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLastProductFileName', []);

        return parent::getLastProductFileName();
    }

    /**
     * {@inheritDoc}
     */
    public function setLastProductFileName($value)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLastProductFileName', [$value]);

        return parent::setLastProductFileName($value);
    }

    /**
     * {@inheritDoc}
     */
    public function getLastProposalFileName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLastProposalFileName', []);

        return parent::getLastProposalFileName();
    }

    /**
     * {@inheritDoc}
     */
    public function setLastProposalFileName($value)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLastProposalFileName', [$value]);

        return parent::setLastProposalFileName($value);
    }

    /**
     * {@inheritDoc}
     */
    public function getLastSku()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLastSku', []);

        return parent::getLastSku();
    }

    /**
     * {@inheritDoc}
     */
    public function setLastSku($value)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLastSku', [$value]);

        return parent::setLastSku($value);
    }

    /**
     * {@inheritDoc}
     */
    public function getFirstname()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFirstname', []);

        return parent::getFirstname();
    }

    /**
     * {@inheritDoc}
     */
    public function getLastname()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLastname', []);

        return parent::getLastname();
    }

    /**
     * {@inheritDoc}
     */
    public function getShopname()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShopname', []);

        return parent::getShopname();
    }

    /**
     * {@inheritDoc}
     */
    public function getShopurl()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getShopurl', []);

        return parent::getShopurl();
    }

    /**
     * {@inheritDoc}
     */
    public function getEmail()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEmail', []);

        return parent::getEmail();
    }

    /**
     * {@inheritDoc}
     */
    public function getAddress()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAddress', []);

        return parent::getAddress();
    }

    /**
     * {@inheritDoc}
     */
    public function getPhone()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPhone', []);

        return parent::getPhone();
    }

    /**
     * {@inheritDoc}
     */
    public function setFirstname($firstname)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFirstname', [$firstname]);

        return parent::setFirstname($firstname);
    }

    /**
     * {@inheritDoc}
     */
    public function setLastname($lastname)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLastname', [$lastname]);

        return parent::setLastname($lastname);
    }

    /**
     * {@inheritDoc}
     */
    public function setShopname($shopname)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setShopname', [$shopname]);

        return parent::setShopname($shopname);
    }

    /**
     * {@inheritDoc}
     */
    public function setShopurl($shopurl)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setShopurl', [$shopurl]);

        return parent::setShopurl($shopurl);
    }

    /**
     * {@inheritDoc}
     */
    public function setEmail($email)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEmail', [$email]);

        return parent::setEmail($email);
    }

    /**
     * {@inheritDoc}
     */
    public function setAddress($address)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setAddress', [$address]);

        return parent::setAddress($address);
    }

    /**
     * {@inheritDoc}
     */
    public function setPhone($phone)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPhone', [$phone]);

        return parent::setPhone($phone);
    }

    /**
     * {@inheritDoc}
     */
    public function getPassword()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPassword', []);

        return parent::getPassword();
    }

    /**
     * {@inheritDoc}
     */
    public function setPassword($password)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPassword', [$password]);

        return parent::setPassword($password);
    }

    /**
     * {@inheritDoc}
     */
    public function getWp_seller_id()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getWp_seller_id', []);

        return parent::getWp_seller_id();
    }

    /**
     * {@inheritDoc}
     */
    public function setWp_seller_id($wp_seller_id)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setWp_seller_id', [$wp_seller_id]);

        return parent::setWp_seller_id($wp_seller_id);
    }

    /**
     * {@inheritDoc}
     */
    public function getDisplayname()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDisplayname', []);

        return parent::getDisplayname();
    }

    /**
     * {@inheritDoc}
     */
    public function setDisplayname($displayname)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDisplayname', [$displayname]);

        return parent::setDisplayname($displayname);
    }

    /**
     * {@inheritDoc}
     */
    public function getSellerRoles()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSellerRoles', []);

        return parent::getSellerRoles();
    }

    /**
     * {@inheritDoc}
     */
    public function setSellerRoles($value)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSellerRoles', [$value]);

        return parent::setSellerRoles($value);
    }

    /**
     * {@inheritDoc}
     */
    public function addSellerRole(\App\Entities\Option_master $role)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addSellerRole', [$role]);

        return parent::addSellerRole($role);
    }

    /**
     * {@inheritDoc}
     */
    public function removeSellerRole($role)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeSellerRole', [$role]);

        return parent::removeSellerRole($role);
    }

    /**
     * {@inheritDoc}
     */
    public function getIn_queue()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getIn_queue', []);

        return parent::getIn_queue();
    }

    /**
     * {@inheritDoc}
     */
    public function setIn_queue($in_queue)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setIn_queue', [$in_queue]);

        return parent::setIn_queue($in_queue);
    }

    /**
     * {@inheritDoc}
     */
    public function getIs_seller_agreement()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getIs_seller_agreement', []);

        return parent::getIs_seller_agreement();
    }

    /**
     * {@inheritDoc}
     */
    public function getSeller_agreement_json()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSeller_agreement_json', []);

        return parent::getSeller_agreement_json();
    }

    /**
     * {@inheritDoc}
     */
    public function getSeller_agreement_signature()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSeller_agreement_signature', []);

        return parent::getSeller_agreement_signature();
    }

    /**
     * {@inheritDoc}
     */
    public function getSeller_agreement_pdf()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSeller_agreement_pdf', []);

        return parent::getSeller_agreement_pdf();
    }

    /**
     * {@inheritDoc}
     */
    public function setIs_seller_agreement($is_seller_agreement)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setIs_seller_agreement', [$is_seller_agreement]);

        return parent::setIs_seller_agreement($is_seller_agreement);
    }

    /**
     * {@inheritDoc}
     */
    public function setSeller_agreement_json($seller_agreement_json)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSeller_agreement_json', [$seller_agreement_json]);

        return parent::setSeller_agreement_json($seller_agreement_json);
    }

    /**
     * {@inheritDoc}
     */
    public function setSeller_agreement_signature($seller_agreement_signature)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSeller_agreement_signature', [$seller_agreement_signature]);

        return parent::setSeller_agreement_signature($seller_agreement_signature);
    }

    /**
     * {@inheritDoc}
     */
    public function setSeller_agreement_pdf($seller_agreement_pdf)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSeller_agreement_pdf', [$seller_agreement_pdf]);

        return parent::setSeller_agreement_pdf($seller_agreement_pdf);
    }

    /**
     * {@inheritDoc}
     */
    public function getStripe_customer_id()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStripe_customer_id', []);

        return parent::getStripe_customer_id();
    }

    /**
     * {@inheritDoc}
     */
    public function setStripe_customer_id($stripe_customer_id)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setStripe_customer_id', [$stripe_customer_id]);

        return parent::setStripe_customer_id($stripe_customer_id);
    }

    /**
     * {@inheritDoc}
     */
    public function getAssign_agent_id()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAssign_agent_id', []);

        return parent::getAssign_agent_id();
    }

    /**
     * {@inheritDoc}
     */
    public function setAssign_agent_id($assign_agent_id)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setAssign_agent_id', [$assign_agent_id]);

        return parent::setAssign_agent_id($assign_agent_id);
    }

}
