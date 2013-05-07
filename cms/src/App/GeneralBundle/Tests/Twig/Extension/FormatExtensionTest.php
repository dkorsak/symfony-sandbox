<?php

namespace App\GeneralBundle\Tests\Twig\Extension;

use App\GeneralBundle\Twig\Extension\FormatExtension;

class FormatExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FormatExtension
     */
    protected $extension;

    public function setUp()
    {
        parent::setUp();
        $this->extension = new FormatExtension();
    }

    public function testFormatBytesFilter()
    {
        $this->assertEquals("1 kB", $this->extension->formatBytesFilter(1024));
        $this->assertEquals("1 MB", $this->extension->formatBytesFilter(1024*1024));
        $this->assertEquals("1 GB", $this->extension->formatBytesFilter(1024*1024*1024));
    }

    public function testGetPercentage()
    {
        $this->assertEquals("10%", $this->extension->getPercentage(10, 100));
        $this->assertEquals("33.33%", $this->extension->getPercentage(33, 99));
        $this->assertEquals("00.00%", $this->extension->getPercentage(33, 0));
    }
}
