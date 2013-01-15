<?php

namespace App\GeneralBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use FOS\UserBundle\Entity\User as BaseUser;
use FOS\UserBundle\Model\GroupInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\GeneralBundle\Validator\Constraints as AppGeneralAssert;

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
 */
class User extends BaseUser
{
    /**
     * @var integer
     * 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * 
     * @ORM\Column(name="firstname", type="string", length=50, nullable=false)
     * @Assert\NotBlank()
     */
    protected $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=50, nullable=false)
     * @Assert\NotBlank()
     */
    protected $lastname;

    /**
     * @var datetime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @var datetime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * @var ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="Group")
     * @ORM\JoinTable(name="user_to_user_group",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)}
     * )
     */
    protected $groups;

    /**
     * @var string
     */
    protected $retypePassword;

    /**
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
     * @param string $firstname
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
     * @param string $lastname
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
     * Set created
     *
     * @param \DateTime $created
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
     * @param \DateTime $updated
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
     * @param GroupInterface $groups
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
     * @param GroupInterface $group
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
     * Set retypePassword
     * 
     * @param string $retypePassword
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
     * @param string $oldPassword
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
}