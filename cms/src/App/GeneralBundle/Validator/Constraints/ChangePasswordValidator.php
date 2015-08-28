<?php

/**
 * ChangePasswordValidator class.
 */
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
     * Constructor.
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
        if ($user->getPlainPassword() == '' || !$user->getId()) {
            return;
        }

        if ($user->getOldPassword() == '') {
            $this->context->buildViolation($constraint->messageEmptyOldPassword)
                ->atPath('oldPassword')
                ->addViolation();
        } else {
            $encoder = $this->encoderFactory->getEncoder($user);
            if (!$encoder->isPasswordValid($user->getPassword(), $user->getOldPassword(), $user->getSalt())) {
                $this->context->buildViolation($constraint->messageInvalidOldPassword)
                    ->atPath('oldPassword')
                    ->addViolation();
            }
        }

        if ($user->getRetypePassword() == '') {
            $this->context->buildViolation($constraint->messageEmptyRetypePassword)
                ->atPath('oldPassword')
                ->addViolation();

            return;
        }

        if ($user->getRetypePassword() != $user->getPlainPassword()) {
            $this->context->buildViolation($constraint->messagePasswordsMustBeEqual)
                ->atPath('plainPassword')
                ->addViolation();
        }
    }
}
