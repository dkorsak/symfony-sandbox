<?php

namespace App\GeneralBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Loading fixtures from YML file
 *
 */
abstract class YamlFixtures extends AbstractFixture implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * YML file name
     * 
     * @return string
     */
    abstract function getModelFile();

    /**
     * @{inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load fixtures from file
     * 
     * @return array
     */
    public function getModelFixtures()
    {
        $fixturesPath = $this->container->get('kernel')->getBundle($this->getBundle())->getPath();
        $fixturesPath .= '/Resources/fixtures';
        $fixtures = Yaml::parse(file_get_contents($fixturesPath. '/'. $this->getModelFile(). '.yml'));
        return $fixtures;
    }

    /**
     * Set object variables from given array
     * 
     * @param object $object
     * @param array $array
     */
    protected function fromArray($object, $array)
    {
        foreach ($array as $key => $value) {
            $method = 'set' . str_replace(" ", "", ucfirst(str_replace("_", " ", $key)));
            if (method_exists($object, $method)) {
                $object->$method($value);
            }
        }
    }

    /**
     * @return string`
     */
    protected function getBundle()
    {
        return 'AppGeneralBundle';
    }
} 