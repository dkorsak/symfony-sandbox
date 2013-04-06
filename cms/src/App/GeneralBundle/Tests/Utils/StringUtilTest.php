<?php

namespace App\GeneralBundle\Tests\Utils;

use App\GeneralBundle\Utils\StringUtil;

class StringUtilTest extends \PHPUnit_Framework_TestCase
{
    public function testExtractDetailsFromControllerName()
    {
        $controller = 'App\\BundleName\\Controller\\FooController::barAction';
        $result = StringUtil::extractDetailsFromControllerName($controller);
        $this->assertEquals('App\\BundleName\\Controller', $result['namespace']);
        $this->assertEquals('AppBundleName', $result['bundle']);
        $this->assertEquals('FooController', $result['controller']);
        $this->assertEquals('barAction', $result['action']);

        $controller = 'App\\Bundle\\BundleName\\Controller\\FooController::barAction';
        $result = StringUtil::extractDetailsFromControllerName($controller);
        $this->assertEquals('App\\Bundle\\BundleName\\Controller', $result['namespace']);
        $this->assertEquals('AppBundleName', $result['bundle']);
        $this->assertEquals('FooController', $result['controller']);
        $this->assertEquals('barAction', $result['action']);
    }

    public function testGenerateRandomPassword()
    {
        $passwords = array();
        for ($i=0; $i<=100; $i++) {
            $password = StringUtil::generateRandomPassword();
            $this->assertFalse(in_array($password, $passwords));
            $passwords[] = $password;
        }
    }
}
