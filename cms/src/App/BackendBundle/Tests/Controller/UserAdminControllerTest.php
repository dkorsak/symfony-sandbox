<?php

namespace App\BackendBundle\Tests\Controller;

use App\GeneralBundle\Entity\User;
use App\GeneralBundle\Tests\BasePHPUnitTest;
use Symfony\Component\BrowserKit\Client;

class UserAdminControllerTest extends BasePHPUnitTest
{
    public function testCreateUser()
    {
        $client = static::createClient();
        $crawler = $this->goToUserPage($client);
        //$link = $crawler->selectLink('Add new')->link();
        $link = $crawler->selectLink('Dodaj')->link();
        $crawler = $client->click($link);
        //$form = $crawler->selectButton('Update and close')->form();
        $form = $crawler->selectButton('Zapisz zmiany i zamknij')->form();
        $formParams = array(
            'user[firstname]' => 'John',
            'user[lastname]' => 'Doe',
            'user[email]' => 'foo@bar.com',
            'user[enabled]' => '1',
            'user[singleRole]' => User::ROLE_ADMIN,
        );
        $client->submit($form, $formParams);

        $this->assertTrue($client->getResponse()->isRedirect());
        $mailCollector = $client->getProfile()->getCollector('swiftmailer');

        $crawler = $client->followRedirect();

        // check if email was sent
        $this->assertEquals(1, $mailCollector->getMessageCount());

        $this->assertRegExp('/John Doe/', $client->getResponse()->getContent());
    }

    public function testDeleteUser()
    {
        $client = static::createClient();
        $crawler = $this->goToUserPage($client);

        $this->doDeleteRecord($crawler, $client, 'John Doe');
        $deleted = strpos($client->getResponse()->getContent(), 'John Doe') === false;
        $this->assertTrue($deleted);
    }

    protected function goToUserPage(Client $client)
    {
        $this->doAdminLogin($client);

        $crawler = $client->request('GET', '/admin/users/list');
        $this->assertTrue($client->getResponse()->isSuccessful());

        return $crawler;
    }
}
