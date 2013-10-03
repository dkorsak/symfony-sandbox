<?php

namespace App\FrontendBundle\Tests\Controller;

use App\GeneralBundle\Tests\BasePHPUnitTest;
use Symfony\Component\BrowserKit\Client;

class HomepageControllerTest extends BasePHPUnitTest
{
    public function testIndexAction()
    {
        $client = static::createClient();
        $this->goToHomepage($client);
    }

    protected function goToHomepage(Client $client)
    {
        $this->doAdminLogin($client);

        $crawler = $client->request('GET', '/');
        $this->assertTrue($client->getResponse()->isSuccessful());

        return $crawler;
    }
}
