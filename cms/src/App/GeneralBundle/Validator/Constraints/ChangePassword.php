<?php

namespace App\GeneralBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ChangePassword extends Constraint
{
    /**
     * @var string
     */
    public $messageEmptyOldPassword = "Please provide old password";

    /**
     * @var string
     */
    public $messageEmptyRetypePassword = "Please retype password";

    /**
     * @var string
     */
    public $messagePasswordsMustBeEqual = "Passwords are not equal";

    /**
     * @var string
     */
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
        return 'app_general_constraints_change_password';
    }
}