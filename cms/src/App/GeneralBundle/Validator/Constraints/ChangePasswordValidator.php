<?php

namespace App\GeneralBundle\Validator\Constraints;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class ChangePasswordValidator extends ConstraintValidator
{
    /**
     * @var EncoderFactoryInterface
     */
    protected $encoderFactory;

    /**
     * Constructor
     *  
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($user, Constraint $constraint)
    {
        if ($user->getPlainPassword() == "" || $user->getId() == "") {
            return;
        }
        
        if ($user->getOldPassword() == "") {
            $this->context->addViolationAtSubPath('oldPassword', $constraint->messageEmptyOldPassword, array(), null);
        } else {
            if (!$this->encoderFactory->getEncoder($user)->isPasswordValid($user->getPassword(), $user->getOldPassword(), $user->getSalt())) {
                $this->context->addViolationAtSubPath('oldPassword', $constraint->messageInvalidOldPassword, array(), null);
            }
        }
        
        if ($user->getRetypePassword() == "") {
            $this->context->addViolationAtSubPath('retypePassword', $constraint->messageEmptyRetypePassword, array(), null);
            return;
        }
        
        if ($user->getRetypePassword() != $user->getPlainPassword()) {
            $this->context->addViolationAtSubPath('plainPassword', $constraint->messagePasswordsMustBeEqual, array(), null);
        }
    }
}