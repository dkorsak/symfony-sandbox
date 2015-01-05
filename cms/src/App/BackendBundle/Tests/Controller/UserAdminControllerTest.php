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
        $link = $crawler->selectLink(self::LABEL_ADD)->link();
        $crawler = $client->click($link);
        $form = $crawler->selectButton(self::LABEL_UPDATE_AND_CLOSE)->form();
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

        $client->followRedirect();

        // check if email was sent
        $this->assertEquals(1, $mailCollector->getMessageCount());

        $this->assertRegExp('/John Doe/', $client->getResponse()->getContent());
    }

    public function testDeleteUser()
    {
        $client = static::createClient();
        $crawler = $this->goToUserPage($client);

        $this->doDeleteRecord($crawler, $client, 'John Doe');
        $deleted = strpos($client->getResponse()->getContent(), sprintf(self::DELETED_MSG, 'John Doe')) !== false;
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
