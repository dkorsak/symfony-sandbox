<?php

namespace App\GeneralBundle\Tests\Services;

use App\GeneralBundle\Tests\BasePHPUnitTest;
use App\GeneralBundle\Services\Mailer;

class MailerTest extends BasePHPUnitTest
{
    /**
     * @var Mailer
     */
    protected $service;

    public function setUp()
    {
        parent::setUp();
        $this->service = new Mailer($this->container->get('mailer'));
        $this->service->setFrom("foo@bar.com");
        $this->service->setSender("John Doe");
    }

    public function testSend()
    {
        $this->assertTrue((boolean)$this->service->send("Title", "Body", "foo@bar.com"));
    }
}