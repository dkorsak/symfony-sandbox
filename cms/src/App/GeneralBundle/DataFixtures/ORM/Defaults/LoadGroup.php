<?php

namespace App\GeneralBundle\DataFixtures\ORM\Defaults;

use App\GeneralBundle\Entity\Group;
use App\GeneralBundle\DataFixtures\ORM\YamlFixtures;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadGroup extends YamlFixtures implements OrderedFixtureInterface, ContainerAwareInterface
{
    public function load(ObjectManager $manager)
    {
        $data = $this->getModelFixtures();
        foreach ($data as $reference => $item) {
            $object = new Group($item['name']);
            $this->fromArray($object, $item);
            $manager->persist($object);
            $this->addReference($reference, $object);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }

    public function getModelFile()
    {
        return 'group';
    }
}