<?php

namespace App\DemoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * App\DemoBundle\Entity\ArticleCategory
 *
 * @ORM\Table(name="demo_article_category")
 * @ORM\Entity()
 */
class ArticleCategory
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
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
     * @ORM\OneToMany(targetEntity="Article", mappedBy="articleCategory")
     */
    protected $articles;

    /**
     * Constructor
     * 
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
        return $this->name;
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
     * @return ArticleCategory
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
     * @return ArticleCategory
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
     * @param \App\DemoBundle\Entity\ArticleCategory $articles
     * @return ArticleCategory
     */
    public function addArticle(\App\DemoBundle\Entity\ArticleCategory $articles)
    {
        $this->articles[] = $articles;
        
        return $this;
    }

    /**
     * Remove articles
     *
     * @param \App\DemoBundle\Entity\ArticleCategory $articles
     */
    public function removeArticle(\App\DemoBundle\Entity\ArticleCategory $articles)
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