<?php

namespace App\GeneralBundle\Tests\Twig\Extension;

use App\GeneralBundle\Twig\Extension\HtmlExtension;

class HtmlExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HtmlExtension
     */
    protected $extension;

    public function setUp()
    {
        parent::setUp();
        $this->extension = new HtmlExtension();
    }

    public function testExtractCurrentController()
    {
        $controller = 'App\\BundleName\\Controller\\FooController::barAction';
        $result = $this->extension->extractCurrentController($controller);
        $this->assertEquals("appbundlename foocontroller baraction", $result);
    }
}
