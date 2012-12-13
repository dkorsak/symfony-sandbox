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
     * @var Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * Gets model file name
     * 
     * @return string
     */
    abstract function getModelFile();
    
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\DependencyInjection.ContainerAwareInterface::setContainer()
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    /**
     * Loads fixtures from file
     * 
     * @return array
     */
    public function getModelFixtures()
    {
        $fixturesPath = $this->container->get('kernel')->getBundle('AppGeneralBundle')->getPath();    
        $fixturesPath .= '/Resources/fixtures';
        try {
            $fixtures = Yaml::parse(file_get_contents($fixturesPath. '/'. $this->getModelFile(). '.yml'));
        } catch (Exception $e) {
            throw $e;
        }
        return $fixtures;
    }
    
    /**
     * Sets object variables from given array
     * 
     * @param Doctrine Entity $object
     * @param array $array
     */
    public function fromArray($object, $array)
    {
        foreach ($array as $key => $value) {
            $method = 'set' . str_replace(" ", "", ucfirst(str_replace("_", " ", $key)));
            if (method_exists($object, $method)) {
                $object->$method($value);
            }
        }
    }
} 