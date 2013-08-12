<?php

namespace App\GeneralBundle\Tests;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\Yaml\Yaml;

abstract class BasePHPUnitTest extends WebTestCase
{
    const ADMIN_USER = 'superadmin@admin.com';
    const ADMIN_PASSWORD = 'admin';

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var string
     */
    protected $fixturesDir;

    public function setUp()
    {
        parent::setUp();
        $kernel = static::createKernel();
        $kernel->boot();
        $this->container = $kernel->getContainer();
        $this->fixturesDir = $kernel->getBundle('AppGeneralBundle')->getPath() . '/Resources/phpunit/';
    }

    protected function tearDown()
    {
        $this->container->get('doctrine')->getConnection()->close();
        \Mockery::close();
        $refl = new \ReflectionObject($this);
        foreach ($refl->getProperties() as $prop) {
            if (!$prop->isStatic() && 0 !== strpos($prop->getDeclaringClass()->getName(), 'PHPUnit_')) {
                $prop->setAccessible(true);
                $prop->setValue($this, null);
            }
        }
        parent::tearDown();
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

    /**
     * Load fixtures file - YML file
     *
     * @param  string $file
     * @return array
     */
    protected function loadFixtures($file)
    {
        $fixturesFile = $this->fixturesDir . $file;

        return Yaml::parse(file_get_contents($fixturesFile));
    }

    /**
     * @param  Client                               $client
     * @param  string                               $username
     * @param  string                               $password
     * @return Symfony\Component\DomCrawler\Crawler
     */
    protected function doAdminLogin(Client $client, $username = self::ADMIN_USER, $password = self::ADMIN_PASSWORD)
    {
        $crawler = $client->request('GET', '/admin');
        $this->assertTrue($client->getResponse()->isSuccessful());

        //$form = $crawler->selectButton('Login')->form();
        $form = $crawler->selectButton('Zaloguj')->form();
        $formParams = array(
            '_username' => $username,
            '_password' => $password
        );
        $client->submit($form, $formParams);
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();

        return $crawler;
    }

    /**
     * Delete row in CMS functional test
     *
     * @param  Crawler $crawler
     * @param  Client  $client
     * @param  string  $linkName
     * @return Crawler
     */
    protected function doDeleteRecord(Crawler $crawler, Client $client, $linkName)
    {
        $link = $crawler->selectLink($linkName)->link();
        $crawler = $client->click($link);

        //$link = $crawler->selectLink('Delete')->link();
        $link = $crawler->selectLink('Usuń')->link();
        $crawler = $client->click($link);

        //$form = $crawler->selectButton('Yes, delete')->form();
        $form = $crawler->selectButton('Tak, usuń')->form();
        $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertTrue($client->getResponse()->isSuccessful());

        return $crawler;
    }
}
