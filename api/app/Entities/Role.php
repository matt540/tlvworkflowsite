<?php



namespace App\Entities;



use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;

use LaravelDoctrine\ACL\Mappings as ACL;

use LaravelDoctrine\ACL\Contracts\Role as RoleContract;

use LaravelDoctrine\ACL\Permissions\HasPermissions;

// use LaravelDoctrine\ACL\Contracts\HasPermissions as HasPermissionContract;



/**

 * @ORM\Entity()

 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)

 */

class Role implements RoleContract

{



    use HasPermissions;



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

     * @ACL\HasPermissions

     */

    public $permissions;



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

     * @return int

     */

    public function getId()

    {

        return $this->id;

    }



    public function __construct($data = null)

    {

        if (!$data)

            return;

        $this->name = $data['name'];

        $this->permissions = $data['permissions'];

    }



    /**

     * @return string

     */

    public function getName()

    {

        return $this->name;

    }



    public function setName($name)

    {

        $this->name = $name;

    }



    public function getPermissions()

    {

        return $this->permissions;

    }

    public function addPermission($permission)

    {

        

        return $this->permissions->add($permission);

    }

    public function removePermission($permission)

    {

        

        return $this->permissions->removeElement($permission);

    }

}

