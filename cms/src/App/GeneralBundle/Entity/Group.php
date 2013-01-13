<?php

namespace App\GeneralBundle\Entity;

use FOS\UserBundle\Entity\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;

/**
 * App\GeneralBundle\Entity\Group
 * 
 * @ORM\Table(name="user_group")
 * @ORM\Entity
 */
class Group extends BaseGroup
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
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}