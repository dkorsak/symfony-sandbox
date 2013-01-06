<?php

namespace App\DemoBundle\DataFixtures\ORM\Data;

use Doctrine\Common\Persistence\ObjectManager;
use App\DemoBundle\Entity\Article;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use App\GeneralBundle\DataFixtures\ORM\YamlFixtures;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class LoadArticles extends YamlFixtures implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager) 
    {
        $data = $this->getModelFixtures();
        foreach ($data as $reference => $item) {
            $object = new Article();
            $this->fromArray($object, $item);
            $object->setArticleCategory($this->getReference($item['article_category_id']));
            if (isset($item['tags'])) {
                foreach ($item['tags'] as $tag) {
                    $object->addTag($this->getReference($tag));
                }
            }
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
        return 1002;
    }

    /**
     * {@inheritdoc}
     */
    public function getModelFile()
    {
        return 'articles';
    }

    /**
     * @{inheritdoc}
     */
    protected function getBundle()
    {
        return 'AppDemoBundle';
    } 
}