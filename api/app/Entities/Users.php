<?php



namespace App\Entities;



use Gedmo\Mapping\Annotation as Gedmo;

use Doctrine\ORM\Mapping as ORM;

use LaravelDoctrine\ACL\Roles\HasRoles;

use LaravelDoctrine\ACL\Mappings as ACL;

use LaravelDoctrine\ACL\Contracts\HasRoles as HasRolesContract;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Contracts\Auth\CanResetPassword;

use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;

use Illuminate\Notifications\Notifiable;

use Tymon\JWTAuth\Contracts\JWTSubject;

/**

 * @ORM\Entity()

 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)

 */

class Users extends Authenticatable implements HasRolesContract, JWTSubject

{



    use HasRoles,

        CanResetPasswordTrait,

        Notifiable;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**

     * @ORM\Column(type="integer")

     * @ORM\Id

     * @ORM\GeneratedValue(strategy="AUTO")

     */

    public $id;



    /**

     * @ORM\Column(type="string")

     */

    protected $firstname;



    /**

     * @ORM\Column(type="string")

     */

    protected $lastname;



    /**

     * @ORM\Column(type="string")

     */

    protected $username;



    /**

     * @ORM\Column(type="string")

     */

    protected $email;



    /**

     * @ORM\Column(type="string", nullable=true)

     */

    protected $password;



    /**

     *

     * @ORM\ManyToOne(targetEntity="Option_master",inversedBy="users")

     * @ORM\JOinColumn(name="status",referencedColumnName="id")

     */

    protected $status;



    /**

     * @ACL\HasRoles()

     * @var \Doctrine\Common\Collections\ArrayCollection|\LaravelDoctrine\ACL\Contracts\Role[]

     */

    protected $roles;



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

     * @ORM\Column(type="string")

     */

    protected $remember_token;



    /**

     * @ORM\Column(type="string", nullable=true)

     */

    protected $phone;



    /**

     * @ORM\Column(type="string",nullable=true)

     *

     */

    protected $profile_image;



    public function __construct($data = null)

    {



        if (!$data)

            return;



        $this->companyname = isset($data['companyname']) ? $data['companyname'] : '';

        $this->firstname = isset($data['firstname']) ? $data['firstname'] : '';

        $this->lastname = isset($data['lastname']) ? $data['lastname'] : '';

        $this->email = isset($data['email']) ? $data['email'] : '';

        $this->status = isset($data['status']) ? $data['status'] : '';

        $this->profile_image = isset($data['profile_image']) ? $data['profile_image'] : '';

        $this->payment_type = isset($data['payment_type']) ? $data['payment_type'] : null;



        $this->phone = isset($data['phone']) ? $data['phone'] : '';



        $this->username = isset($data['username']) ? $data['username'] : '';

        $this->alternate_phone = isset($data['alternate_phone']) ? $data['alternate_phone'] : '';



        $this->cc = isset($data['cc']) ? $data['cc'] : '';

        $this->card_name = isset($data['card_name']) ? $data['card_name'] : '';

        $this->card_type = isset($data['card_type']) ? $data['card_type'] : '';

        $this->sec_code = isset($data['sec_code']) ? $data['sec_code'] : '';

        $this->exp = isset($data['exp']) ? $data['exp'] : '';



        $this->note = isset($data['note']) ? $data['note'] : '';



        if (isset($data['password']))

        {

            $this->password = $data['password'];

        }

        $this->roles = isset($data['role']) ? $data['role'] : '';

        $this->remember_token = uniqid(mt_rand(), true);

    }



    public function getPassword()

    {

        return $this->password;

    }



    public function getRoles()

    {

        return $this->roles;

    }



    public function getPaymentType()

    {

        return $this->payment_type;

    }



    public function setPaymentType($value)

    {

        $this->payment_type = $value;

    }



    public function getId()

    {

        return $this->id;

    }



    //UserEntity

    public function getUsername()

    {

        return (string) $this->id;

    }



    public function getCompanyName()

    {

        return $this->companyname;

    }



    public function setCompanyName($value)

    {

        $this->companyname = $value;

    }



    public function getContactName()

    {

        return $this->contactname;

    }



    public function setContactName($value)

    {

        $this->contactname = $value;

    }



    public function getEmail()

    {

        return $this->email;

    }



    public function getProfileImage()

    {

        return $this->profile_image;

    }



    public function getRememberToken()

    {

        return $this->remember_token;

    }



    public function setRememberToken($remember_token)

    {

        $this->remember_token = $remember_token;

    }



    public function setPassword($password)

    {

        $this->password = $password;

    }



    public function setProfileImage($profile_image)

    {

        $this->profile_image = $profile_image;

    }



    public function setEmail($email)

    {

        $this->email = $email;

    }



    public function setPhone($phone)

    {

        $this->phone = $phone;

    }



    public function setStatus($status)

    {

        $this->status = $status;

    }



    public function getStatus()

    {

        return $this->status;

    }



    public function getAuthIdentifier()

    {

        return $this->id;

    }



    public function setRoles($role)

    {

        $this->roles = $role;

    }



    public function getPhone()

    {

        return $this->phone;

    }



    public function getCreated_at()

    {

        return $this->created_at->format('Y-m-d H:i:s');

    }



    function getAlternate_phone()

    {

        return $this->alternate_phone;

    }



    function getCc()

    {

        return $this->cc;

    }



    function getCard_name()

    {

        return $this->card_name;

    }



    function getCard_type()

    {

        return $this->card_type;

    }



    function getSec_code()

    {

        return $this->sec_code;

    }



    function getExp()

    {

        return $this->exp;

    }



    function setAlternate_phone($alternate_phone)

    {

        $this->alternate_phone = $alternate_phone;

    }



    function setCc($cc)

    {

        $this->cc = $cc;

    }



    function setCard_name($card_name)

    {

        $this->card_name = $card_name;

    }



    function setCard_type($card_type)

    {

        $this->card_type = $card_type;

    }



    function setSec_code($sec_code)

    {

        $this->sec_code = $sec_code;

    }



    function setExp($exp)

    {

        $this->exp = $exp;

    }



    public function setUserName($value)

    {

        $this->username = $value;

    }



    public function setNote($value)

    {

        $this->note = $value;

    }



    public function getNote()

    {

        return $this->note;

    }



    function getFirstname()

    {

        return $this->firstname;

    }



    function getLastname()

    {

        return $this->lastname;

    }



    function setFirstname($firstname)

    {

        $this->firstname = $firstname;

    }



    function setLastname($lastname)

    {

        $this->lastname = $lastname;

    }



//    public function getUserName()

//    {

//        return $this->username;

//    }

}

