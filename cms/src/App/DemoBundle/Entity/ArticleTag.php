<?php

namespace App\DemoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * App\DemoBundle\Entity\ArticleTag
 *
 * @ORM\Table(name="demo_article_tag")
 * @ORM\Entity()
 * @UniqueEntity(fields={"name"})
 */
class ArticleTag
{
    /**
     * @var integer
     * 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * 
     * @ORM\Column(name="name", type="string", length=255, nullable=false, unique=true)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var string
     * 
     * @ORM\Column(name="slug", type="string", length=255, nullable=false, unique=true)
     * @Gedmo\Slug(fields={"name"}, updatable=true)
     */
    protected $slug;

    /**
     * @var ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="Article", mappedBy="tags")
     */
    protected $articles;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ArticleTag
     */
    public function setName($name)
    {
        $this->name = $name;
        
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return ArticleTag
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        
        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add articles
     *
     * @param \App\DemoBundle\Entity\Article $articles
     * @return ArticleTag
     */
    public function addArticle(\App\DemoBundle\Entity\Article $articles)
    {
        $this->articles[] = $articles;
        
        return $this;
    }

    /**
     * Remove articles
     *
     * @param \App\DemoBundle\Entity\Article $articles
     */
    public function removeArticle(\App\DemoBundle\Entity\Article $articles)
    {
        $this->articles->removeElement($articles);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticles()
    {
        return $this->articles;
    }
}