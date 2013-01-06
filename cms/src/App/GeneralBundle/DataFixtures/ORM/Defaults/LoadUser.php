<?php

namespace App\GeneralBundle\DataFixtures\ORM\Defaults;

use App\GeneralBundle\DataFixtures\ORM\YamlFixtures;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadUser extends YamlFixtures implements OrderedFixtureInterface, ContainerAwareInterface
{
    public function load(ObjectManager $manager)
    {
        $data = $this->getModelFixtures();
        $userManager = $this->container->get('fos_user.user_manager');
        foreach ($data as $reference => $item) {
            $user = $userManager->createUser();
            $this->fromArray($user, $item);
            if (isset($item['group_list'])) {
                foreach ($item['group_list'] as $groupName) {
                    $user->addGroup($this->getReference($groupName));
                }
            }
            $userManager->updateUser($user, false);
            $this->addReference($reference, $user);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 10;
    }

    public function getModelFile()
    {
        return 'user';
    }
}