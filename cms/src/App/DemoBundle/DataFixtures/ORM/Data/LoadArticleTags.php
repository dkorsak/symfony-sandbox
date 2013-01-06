<?php

namespace App\DemoBundle\DataFixtures\ORM\Data;

use Doctrine\Common\Persistence\ObjectManager;
use App\DemoBundle\Entity\ArticleTag;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use App\GeneralBundle\DataFixtures\ORM\YamlFixtures;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class LoadArticleTags extends YamlFixtures implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager) 
    {
        $data = $this->getModelFixtures();
        foreach ($data as $reference => $item) {
            $object = new ArticleTag();
            $this->fromArray($object, $item);
            $manager->persist($object);
            $this->addReference($reference, $object);
        }
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1000;
    }

    /**
     * {@inheritdoc}
     */
    public function getModelFile()
    {
        return 'article_tags';
    }

    /**
     * @{inheritdoc}
     */
    protected function getBundle()
    {
        return 'AppDemoBundle';
    } 
}