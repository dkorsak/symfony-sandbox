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

    // SonataAdmin Labels - PL
    const LABEL_LOGIN = 'Zaloguj';
    const LABEL_ADD = 'Dodaj';
    const LABEL_UPDATE_AND_CLOSE = 'Zapisz i wróć do listy';
    const LABEL_DELETE = 'Usuń';
    const LABEL_DELETE_CONFIRM = 'Tak, usuń';
    const LABEL_UPDATE_PROFILE = 'Aktualizuj profil';
    const DELETED_MSG = 'Element "%s" został pomyślnie usunięty.';

    // SonataAdmin Labels - EN
    /*
    const LABEL_LOGIN = 'Login';
    const LABEL_ADD = 'Add new';
    const LABEL_UPDATE_AND_CLOSE = 'Update and close';
    const LABEL_DELETE = 'Delete';
    const LABEL_DELETE_CONFIRM = 'Yes, delete';
    const LABEL_UPDATE_PROFILE = 'Update profile';
    const DELETED_MSG = 'Item "%s" has been deleted successfully.';
    */

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
        $this->fixturesDir = $kernel->getBundle('AppGeneralBundle')->getPath().'/Resources/phpunit/';
    }

    protected function tearDown()
    {
        if ($this->container) {
            $this->container->get('doctrine')->getConnection()->close();
        }

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
     * Create object from class name and set id property.
     *
     * @param string $class
     * @param int    $id
     *
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
     * Load fixtures file - YML file.
     *
     * @param string $file
     *
     * @return array
     */
    protected function loadFixtures($file)
    {
        $fixturesFile = $this->fixturesDir.$file;

        return Yaml::parse(file_get_contents($fixturesFile));
    }

    /**
     * @param Client $client
     * @param string $username
     * @param string $password
     *
     * @return Symfony\Component\DomCrawler\Crawler
     */
    protected function doAdminLogin(Client $client, $username = self::ADMIN_USER, $password = self::ADMIN_PASSWORD)
    {
        $crawler = $client->request('GET', '/admin/login');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $form = $crawler->selectButton(self::LABEL_LOGIN)->form();
        $formParams = array(
            '_username' => $username,
            '_password' => $password,
        );
        $client->submit($form, $formParams);
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();

        return $crawler;
    }

    /**
     * Delete row in CMS functional test.
     *
     * @param Crawler $crawler
     * @param Client  $client
     * @param string  $linkName
     */
    protected function doDeleteRecord(Crawler $crawler, Client $client, $linkName)
    {
        $link = $crawler->selectLink($linkName)->link();
        $crawler = $client->click($link);

        $link = $crawler->selectLink(self::LABEL_DELETE)->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton(self::LABEL_DELETE_CONFIRM)->form();
        $client->submit($form);

        $client->followRedirect();
        $this->assertTrue($client->getResponse()->isSuccessful());
    }
}
