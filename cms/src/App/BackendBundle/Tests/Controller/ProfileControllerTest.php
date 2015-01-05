<?php

namespace App\BackendBundle\Tests\Controller;

use App\GeneralBundle\Tests\BasePHPUnitTest;

class ProfileControllerTest extends BasePHPUnitTest
{
    public function testAdministrtatorIsChangingOwnUsername()
    {
        $client = static::createClient();

        $crawler = $this->doAdminLogin($client);
        $this->assertTrue($client->getResponse()->isSuccessful());

        $crawler = $client->request('GET', '/admin/profile');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $form = $crawler->selectButton(self::LABEL_UPDATE_PROFILE)->form();

        $oldFirstName = $form->get('sonata_user_profile_form[firstname]')->getValue();
        $oldLastName = $form->get('sonata_user_profile_form[lastname]')->getValue();

        $formParams = array(
            'sonata_user_profile_form[firstname]' => 'LAMA',
            'sonata_user_profile_form[lastname]' => 'ALDONA',
        );
        $client->submit($form, $formParams);

        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();

        $crawler = $client->request('GET', '/admin/profile');
        $form = $crawler->selectButton(self::LABEL_UPDATE_PROFILE)->form();
        $formParams = array(
            'sonata_user_profile_form[firstname]' => $oldFirstName,
            'sonata_user_profile_form[lastname]' => $oldLastName,
        );
        $client->submit($form, $formParams);

        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();
    }
}
