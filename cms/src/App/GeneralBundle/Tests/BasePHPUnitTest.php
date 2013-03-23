<?php

namespace App\GeneralBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class BasePHPUnitTest extends WebTestCase
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function setUp()
    {
        parent::setUp();
        $kernel = static::createKernel();
        $kernel->boot();
        $this->container = $kernel->getContainer();
    }

    protected function tearDown()
    {
        parent::tearDown();
        \Mockery::close();
        $refl = new \ReflectionObject($this);
        foreach ($refl->getProperties() as $prop) {
            if (!$prop->isStatic() && 0 !== strpos($prop->getDeclaringClass()->getName(), 'PHPUnit_')) {
                $prop->setAccessible(true);
                $prop->setValue($this, null);
            }
        }
    }

    /**
     * Create object from class name and set id property
     *
     * @param  string  $class
     * @param  integer $id
     * @return object
     */
    protected function createObjectWithId($class, $id)
    {
        $reflection = new \ReflectionClass($class);
        $propertyId = $reflection->getProperty('id');
        $propertyId->setAccessible(true);
        $object = new $class();
        $propertyId->setValue($object, $id);

        return $object;
    }
}
