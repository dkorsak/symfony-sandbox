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
        if ($user->getPlainPassword() == "" || !$user->getId()) {
            return;
        }

        if ($user->getOldPassword() == "") {
            $this->context->addViolationAt('oldPassword', $constraint->messageEmptyOldPassword);
        } else {
            $encoder = $this->encoderFactory->getEncoder($user);
            if (!$encoder->isPasswordValid($user->getPassword(), $user->getOldPassword(), $user->getSalt())) {
                $this->context->addViolationAt('oldPassword', $constraint->messageInvalidOldPassword);
            }
        }

        if ($user->getRetypePassword() == "") {
            $this->context->addViolationAt('retypePassword', $constraint->messageEmptyRetypePassword);

            return;
        }

        if ($user->getRetypePassword() != $user->getPlainPassword()) {
            $this->context->addViolationAt('plainPassword', $constraint->messagePasswordsMustBeEqual);
        }
    }
}
