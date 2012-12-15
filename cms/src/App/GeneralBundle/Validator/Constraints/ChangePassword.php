<?php

namespace App\GeneralBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * 
 * @Annotation
 */
class ChangePassword extends Constraint
{
    
    public $messageEmptyOldPassword = "Please provide old password";
    
    public $messageEmptyRetypePassword = "Please retype password";
    
    public $messagePasswordsMustBeEqual = "Passwords are not equal";
    
    public $messageInvalidOldPassword = "Old password is not valid";
    
    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return Constraint::CLASS_CONSTRAINT;
    }
    
    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'app_general_change_password';
    }
}