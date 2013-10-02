<?php

/**
 * User manager service
 *
 */
namespace App\GeneralBundle\Services;

use App\GeneralBundle\Entity\User;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Doctrine\UserManager as BaseUserManager;

/**
 * User manager service class
 *
 */
class UserManager extends BaseUserManager
{
    /**
     * Update user
     *
     * (non-PHPdoc)
     * @see \FOS\UserBundle\Doctrine\UserManager::updateUser()
     * @param UserInterface $user
     * @param boolean       $andFlush
     */
    public function updateUser(UserInterface $user, $andFlush = true)
    {
        // set username as email address
        $user->setUsername($user->getEmail());

        parent::updateUser($user, $andFlush);
    }
}
