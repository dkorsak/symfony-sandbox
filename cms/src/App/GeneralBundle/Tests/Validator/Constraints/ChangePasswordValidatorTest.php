<?php

namespace App\GeneralBundle\Tests\Validator\Constraints;

use App\GeneralBundle\Entity\User;
use App\GeneralBundle\Validator\Constraints\ChangePassword;
use App\GeneralBundle\Tests\BasePHPUnitTest;
use App\GeneralBundle\Validator\Constraints\ChangePasswordValidator;

class ChangePasswordValidatorTest extends BasePHPUnitTest
{
    /**
     * @var App\GeneralBundle\Validator\Constraints\ChangePasswordValidator
     */
    protected $service;

    /**
     * @var Symfony\Component\Validator\Constraint
     */
    protected $constraint;

    /**
     * @var Symfony\Component\Validator\ExecutionContextInterface
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
        $this->mockContext->shouldReceive('addViolationAt')
            ->with("oldPassword", \Mockery::any())->once()->andReturn(null);

        $user = $this->createObjectWithId('App\GeneralBundle\Entity\User', 1);
        $user->setPlainPassword("password");
        $user->setRetypePassword("password");
        $this->service->validate($user, $this->constraint);
    }

    public function testShouldOnlyDisplayInvalidOldPasswordMessage()
    {
        $this->mockContext->shouldReceive('addViolationAt')
            ->with("oldPassword", \Mockery::any())->once()->andReturnNull();
        $this->encoderMock->shouldReceive('isPasswordValid')->withAnyArgs()->andReturn(false);
        $user = $this->createObjectWithId('App\GeneralBundle\Entity\User', 1);
        $user->setPlainPassword("password");
        $user->setRetypePassword("password");
        $user->setOldPassword("password invalid");
        $this->service->validate($user, $this->constraint);
    }

    public function testShouldNotDisplayInvalidOldPasswordMessage()
    {
        $this->mockContext->shouldReceive('addViolationAt')
            ->with("oldPassword", \Mockery::any())->never();

        $this->encoderMock->shouldReceive('isPasswordValid')->withAnyArgs()->andReturn(true);

        $user = $this->createObjectWithId('App\GeneralBundle\Entity\User', 1);
        $user->setPlainPassword("password");
        $user->setRetypePassword("password");
        $user->setOldPassword("password valid");
        $this->service->validate($user, $this->constraint);
    }

    public function testShouldOnlyDisplayPasswordMustMatchMessage()
    {
        $this->mockContext->shouldReceive('addViolationAt')
            ->with("plainPassword", \Mockery::any())->once()->andReturnNull();

        $this->encoderMock->shouldReceive('isPasswordValid')->withAnyArgs()->andReturn(true);

        $user = $this->createObjectWithId('App\GeneralBundle\Entity\User', 1);
        $user->setPlainPassword("password");
        $user->setRetypePassword("password retype not equal");
        $user->setOldPassword("password valid");
        $this->service->validate($user, $this->constraint);
    }

    public function testShouldOnlyDisplayRetypePasswordIsEmptyMessage()
    {
        $this->mockContext->shouldReceive('addViolationAt')
            ->with("retypePassword", \Mockery::any())->once()->andReturnNull();

        $this->encoderMock->shouldReceive('isPasswordValid')->withAnyArgs()->andReturn(true);

        $user = $this->createObjectWithId('App\GeneralBundle\Entity\User', 1);
        $user->setPlainPassword("password");
        $user->setRetypePassword("");
        $user->setOldPassword("password valid");
        $this->service->validate($user, $this->constraint);
    }

    public function testShouldDisplayOldPasswordIsEmptyAndRetypePasswordIsEmptyMessages()
    {
        $this->mockContext->shouldReceive('addViolationAt')
            ->with("oldPassword", \Mockery::any())->once()->andReturnNull();
        $this->mockContext->shouldReceive('addViolationAt')
            ->with("retypePassword", \Mockery::any())->once()->andReturnNull();

        $user = $this->createObjectWithId('App\GeneralBundle\Entity\User', 1);
        $user->setPlainPassword("password");
        $user->setRetypePassword("");
        $user->setOldPassword("");
        $this->service->validate($user, $this->constraint);
    }
}
