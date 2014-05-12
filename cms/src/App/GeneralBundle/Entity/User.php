<?php

/**
 * User entity class
 *
 *
 */
namespace App\GeneralBundle\Entity;

use Symfony\Component\Validator\ExecutionContext;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use FOS\UserBundle\Entity\User as BaseUser;
use FOS\UserBundle\Model\GroupInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\GeneralBundle\Validator\Constraints as AppGeneralAssert;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

/**
 * App\GeneralBundle\Entity\User
 *
 * @ORM\Table(name="user", indexes={
 *     @ORM\Index(name="username_idx", columns={"username"}),
 *     @ORM\Index(name="email_idx", columns={"email"})
 * })
 * @ORM\Entity(repositoryClass="App\GeneralBundle\Entity\UserRepository")
 * @UniqueEntity(fields={"email"})
 * @UniqueEntity(fields={"username"})
 * @AppGeneralAssert\ChangePassword(groups={"Profile"})
 * Assert\Callback(methods={"isGroupValid"})
 * @HasLifecycleCallbacks
 */
class User extends BaseUser
{
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    const ROLE_ADMIN = 'ROLE_ADMIN';

    const DEFAULT_THEME = 'simplex';

    /**
     * CMS themes
     *
     * @var array
     */
    public static $adminThemes = array(
        'default' => 'Default',
        'cerulean' => 'Cerulean',
        'flatly' => 'Flatly',
        'journal' => 'Journal',
        'simplex' => 'Simplex',
        'spacelab' => 'Spacelab',
        'united' => 'United',
    );

    /**
     * User roles
     *
     * @var array
     */
    public static $userRoles = array(
        'ROLE_SUPER_ADMIN' => 'Super administrator',
        'ROLE_ADMIN' => 'Administrator'
    );

    /**
     * Primary key
     *
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * User firstname
     *
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=50, nullable=false)
     * @Assert\NotBlank()
     */
    protected $firstname;

    /**
     * User lastname
     *
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=50, nullable=false)
     * @Assert\NotBlank()
     */
    protected $lastname;

    /**
     * User email
     *
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    protected $email;

    /**
     * User can customize sonata theme
     *
     * @var string
     *
     * @ORM\Column(name="admin_theme", type="string", nullable=true, length=20)
     */
    protected $adminTheme;

    /**
     * Created at
     *
     * @var datetime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * Updated at
     *
     * @var datetime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * List of groups
     *
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Group")
     * @ORM\JoinTable(name="user_to_user_group",
     *     joinColumns={
     *         @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(name="group_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     *     }
     * )
     */
    protected $groups;

    /**
     * Sometimes user has only one role.
     * This property is for managing user permissions only for one role
     *
     * @var string
     *
     * @Assert\NotBlank(groups={"Admin user"})
     */
    protected $singleRole;

    /**
     * Retype password dummy field
     *
     * @var string
     */
    protected $retypePassword;

    /**
     * Old password dummy field
     *
     * @var string
     */
    protected $oldPassword;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->groups = new ArrayCollection();
        $this->enabled = true;
        $this->groups = new ArrayCollection();
        $this->adminTheme = self::DEFAULT_THEME;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return trim($this->getName()) != "" ? $this->getName() : 'Create';
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstname
     *
     * @param  string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param  string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set adminTheme
     *
     * @param  string $adminTheme
     * @return User
     */
    public function setAdminTheme($adminTheme)
    {
        $this->adminTheme = $adminTheme;

        return $this;
    }

    /**
     * Get adminTheme
     *
     * @return string
     */
    public function getAdminTheme()
    {
        return $this->adminTheme;
    }

    /**
     * Set created
     *
     * @param  \DateTime $created
     * @return User
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param  \DateTime $updated
     * @return User
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Add groups
     *
     * @param  GroupInterface $groups
     * @return User
     */
    public function addGroup(GroupInterface $group)
    {
        parent::addGroup($group);

        return $this;
    }

    /**
     * Remove all old groups and add new one
     *
     * @param  GroupInterface $group
     * @return User
     */
    public function setGroups(GroupInterface $group)
    {
        foreach ($this->getGroups() as $oldGroup) {
            $this->removeGroup($oldGroup);
        }
        parent::addGroup($group);

        return $this;
    }

    /**
     * Remove groups
     *
     * @param GroupInterface $groups
     */
    public function removeGroup(GroupInterface $group)
    {
        parent::removeGroup($group);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroups()
    {
        return parent::getGroups();
    }

    /**
     * Get user full name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getFirstname() . ' ' . $this->getLastname();
    }

    /**
     * Set singleRole
     * Clear all user roles and set new one
     *
     * @param  string $role
     * @return User
     */
    public function setSingleRole($role)
    {
        foreach ($this->roles as $oldRole) {
            $this->removeRole($oldRole);
        }
        $this->addRole($role);
        $this->singleRole = $role;

        return $this;
    }

    /**
     * Post load doctrine event
     *
     * @ORM\PostLoad()
     */
    public function updateSingleRole()
    {
        $this->singleRole = $this->getSingleRole();
    }

    /**
     * Get singleRole
     *
     * @return string
     */
    public function getSingleRole()
    {
        $roles = $this->roles;

        return isset($roles[0]) ? $roles[0] : null;
    }

    /**
     * @return string
     */
    public function getSingleRoleName()
    {
        $constRole = $this->getSingleRole();
        if ($constRole != "" && isset(static::$userRoles[$constRole])) {
            return static::$userRoles[$constRole];
        }

        return null;
    }

    /**
     * Set retypePassword
     *
     * @param  string $retypePassword
     * @return User
     */
    public function setRetypePassword($retypePassword)
    {
        $this->retypePassword = $retypePassword;

        return $this;
    }

    /**
     * Get retypePassword
     *
     * @return string
     */
    public function getRetypePassword()
    {
        return $this->retypePassword;
    }

    /**
     * Set oldPassword
     *
     * @param  string $oldPassword
     * @return User
     */
    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    /**
     * Get oldPassword
     *
     * @return string
     */
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * @return DateTime
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * @return DateTime
     */
    public function getCredentialsExpireAt()
    {
        return $this->credentialsExpireAt;
    }

    /**
     * Validate if use group is not empty
     *
     * @param ExecutionContext $context
     */
    public function isGroupValid(ExecutionContext $context)
    {
        if ($this->groups->count() == 0) {
            $context->addViolationAt('groups', 'This value should not be blank.', array(), null);
        }
    }
}
