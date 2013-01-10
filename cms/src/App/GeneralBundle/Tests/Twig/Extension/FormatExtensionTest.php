<?php

namespace App\GeneralBundle\Tests\Twig\Extension;

use App\GeneralBundle\Twig\Extension\FormatExtension;
use App\GeneralBundle\Tests\BasePHPUnitTest;

class FormatExtensionTest extends BasePHPUnitTest
{
    /**
     * @var FormatExtension
     */
    protected $service;

    public function setUp()
    {
        parent::setUp();
        $this->service = new FormatExtension();
    }

    public function testFormatBytesFilter()
    {
        $this->assertEquals("1 kB", $this->service->formatBytesFilter(1024));
        $this->assertEquals("1 MB", $this->service->formatBytesFilter(1024*1024));
        $this->assertEquals("1 GB", $this->service->formatBytesFilter(1024*1024*1024));
    }

    public function testGetPercentage()
    {
        $this->assertEquals("10%", $this->service->getPercentage(10, 100));
        $this->assertEquals("33.33%", $this->service->getPercentage(33, 99));
    }
}