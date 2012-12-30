<?php

namespace App\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * App\DemoBundle\Entity\Article
 *
 * @ORM\Table(name="demo_article")
 * @ORM\Entity()
 */
class Article
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
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    protected $title;

    /**
     * @var string
     * 
     * @ORM\Column(name="body", type="text", nullable=false)
     */
    protected $body;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="publish_date", type="datetime", nullable=false)
     */
    protected $publishDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="publish", type="boolean", nullable=false)
     */
    protected $publish;

    /**
     * @var integer
     * 
     * @ORM\ManyToOne(targetEntity="ArticleCategory")
     * @ORM\JoinColumn(name="article_category_id", referencedColumnName="id", nullable=false, onDelete="RESTRICT")
     */
    protected $articleCategory;

    /**
     * Constructor
     * 
     */
    public function __construct()
    {
        $this->publishDate = new \DateTime();
        $this->publish = true;
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
     * Set title
     *
     * @param string $title
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Article
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set publishDate
     *
     * @param \DateTime $publishDate
     * @return Article
     */
    public function setPublishDate($publishDate)
    {
        $this->publishDate = $publishDate;
        return $this;
    }

    /**
     * Get publishDate
     *
     * @return \DateTime 
     */
    public function getPublishDate()
    {
        return $this->publishDate;
    }

    /**
     * Set publish
     *
     * @param boolean $publish
     * @return Article
     */
    public function setPublish($publish)
    {
        $this->publish = $publish;
        return $this;
    }

    /**
     * Get publish
     *
     * @return boolean
     */
    public function getPublish()
    {
        return $this->publish;
    }

    /**
     * Set articleCategory
     *
     * @param \App\DemoBundle\Entity\ArticleCategory $articleCategory
     * @return Article
     */
    public function setArticleCategory(\App\DemoBundle\Entity\ArticleCategory $articleCategory)
    {
        $this->articleCategory = $articleCategory;
        return $this;
    }

    /**
     * Get articleCategory
     *
     * @return \App\DemoBundle\Entity\ArticleCategory 
     */
    public function getArticleCategory()
    {
        return $this->articleCategory;
    }
}