<?php

namespace App\GeneralBundle\Tests\Validator\Constraints;

use App\GeneralBundle\Entity\User;
use App\GeneralBundle\Validator\Constraints\ChangePassword;
use App\GeneralBundle\Tests\BasePHPUnitTest;
use App\GeneralBundle\Validator\Constraints\ChangePasswordValidator;

class ChangePasswordValidatorTest extends BasePHPUnitTest
{
    /**
     * @var \App\GeneralBundle\Validator\Constraints\ChangePasswordValidator
     */
    protected $service;

    /**
     * @var \Symfony\Component\Validator\Constraint
     */
    protected $constraint;

    /**
     * @var \Symfony\Component\Validator\ExecutionContextInterface
     */
    protected $mockContext;

    public function setUp()
    {
        parent::setUp();

        $this->encoderMock = \Mockery::mock('Encoder');
        $encoderFactoryMock = \Mockery::mock('Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface');
        $encoderFactoryMock->shouldReceive('getEncoder')->andReturn($this->encoderMock);
        $this->constraint = new ChangePassword();
        $this->service = new ChangePasswordValidator($encoderFactoryMock);
        $this->mockContext = \Mockery::mock('Symfony\Component\Validator\ExecutionContextInterface');
        $this->violationBuilder = \Mockery::mock('Symfony\Component\Validator\Violation\ConstraintViolationBuilder');
        $this->violationBuilder->shouldReceive('atPath')->andReturnSelf();
        $this->violationBuilder->shouldReceive('addViolation')->andReturnSelf();
        $this->mockContext->shouldReceive('buildViolation')
            ->with(\Mockery::any())->andReturn($this->violationBuilder);
        $this->service->initialize($this->mockContext);
    }

    public function testShouldNotDisplayAnyErrorMessages()
    {
        $user = new User();
        $user->setPlainPassword("");
        $this->assertNull($this->service->validate($user, $this->constraint));

        $user->setPlainPassword("password");
        $this->assertNull($this->service->validate($user, $this->constraint));
    }

    public function testShouldOnlyDisplayOldPasswordIsRequiredMessage()
    {
        $user = $this->createObjectWithId('App\GeneralBundle\Entity\User', 1);
        $user->setPlainPassword("password");
        $user->setRetypePassword("password");
        $this->service->validate($user, $this->constraint);
    }

    public function testShouldOnlyDisplayInvalidOldPasswordMessage()
    {
        $this->encoderMock->shouldReceive('isPasswordValid')->withAnyArgs()->andReturn(false);
        $user = $this->createObjectWithId('App\GeneralBundle\Entity\User', 1);
        $user->setPlainPassword("password");
        $user->setRetypePassword("password");
        $user->setOldPassword("password invalid");
        $this->service->validate($user, $this->constraint);
    }

    public function testShouldNotDisplayInvalidOldPasswordMessage()
    {
        $this->encoderMock->shouldReceive('isPasswordValid')->withAnyArgs()->andReturn(true);

        $user = $this->createObjectWithId('App\GeneralBundle\Entity\User', 1);
        $user->setPlainPassword("password");
        $user->setRetypePassword("password");
        $user->setOldPassword("password valid");
        $this->service->validate($user, $this->constraint);
    }

    public function testShouldOnlyDisplayPasswordMustMatchMessage()
    {
        $this->encoderMock->shouldReceive('isPasswordValid')->withAnyArgs()->andReturn(true);

        $user = $this->createObjectWithId('App\GeneralBundle\Entity\User', 1);
        $user->setPlainPassword("password");
        $user->setRetypePassword("password retype not equal");
        $user->setOldPassword("password valid");
        $this->service->validate($user, $this->constraint);
    }

    public function testShouldOnlyDisplayRetypePasswordIsEmptyMessage()
    {
        $this->encoderMock->shouldReceive('isPasswordValid')->withAnyArgs()->andReturn(true);

        $user = $this->createObjectWithId('App\GeneralBundle\Entity\User', 1);
        $user->setPlainPassword("password");
        $user->setRetypePassword("");
        $user->setOldPassword("password valid");
        $this->service->validate($user, $this->constraint);
    }

    public function testShouldDisplayOldPasswordIsEmptyAndRetypePasswordIsEmptyMessages()
    {
        $user = $this->createObjectWithId('App\GeneralBundle\Entity\User', 1);
        $user->setPlainPassword("password");
        $user->setRetypePassword("");
        $user->setOldPassword("");
        $this->service->validate($user, $this->constraint);
    }
}
