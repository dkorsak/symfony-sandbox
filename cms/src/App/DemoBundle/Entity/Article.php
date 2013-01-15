<?php

namespace App\DemoBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * App\DemoBundle\Entity\Article
 *
 * @ORM\Table(name="demo_article")
 * @ORM\Entity()
 * @Vich\Uploadable
 */
class Article
{
    const IMAGE_UPLOAD_DIR = 'articles';

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
     * @Assert\NotBlank()
     */
    protected $title;

    /**
     * @var string
     * 
     * @ORM\Column(name="body", type="text", nullable=false)
     * @Assert\NotBlank()
     */
    protected $body;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="publish_date", type="datetime", nullable=false)
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    protected $publishDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="publish", type="boolean", nullable=false)
     */
    protected $publish;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=false, unique=true)
     * @Gedmo\Slug(fields={"title"}, updatable=false)
     */
    protected $slug;

    /**
     * @var string
     * 
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    protected $image;

    /**
     * @var ArticleCategory
     * 
     * @ORM\ManyToOne(targetEntity="ArticleCategory",inversedBy="articles")
     * @ORM\JoinColumn(name="article_category_id", referencedColumnName="id", nullable=false, onDelete="RESTRICT")
     * @Assert\NotBlank()
     * @Assert\Type(type="App\DemoBundle\Entity\ArticleCategory")
     */
    protected $articleCategory;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="ArticleTag", inversedBy="articles")
     * @ORM\JoinTable(name="demo_article_to_article_tag",
     *     joinColumns={@ORM\JoinColumn(name="article_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)}
     * )
     */
    protected $tags;

    /**
     * @var UploadedFile
     * 
     * @Assert\Image()
     * @Vich\UploadableField(mapping="article_image", fileNameProperty="image")
     */
    protected $uploadedImage;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->publishDate = new \DateTime();
        $this->publish = true;
        $this->tags = new ArrayCollection();
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
     * Set slug
     *
     * @param string $slug
     * @return Article
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
     * Set image
     *
     * @param string $image
     * @return Article
     */
    public function setImage($image)
    {
        $this->image = $image;
        
        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
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

    /**
     * Add tags
     *
     * @param \App\DemoBundle\Entity\ArticleTag $tags
     * @return Article
     */
    public function addTag(\App\DemoBundle\Entity\ArticleTag $tags)
    {
        $this->tags[] = $tags;
        
        return $this;
    }

    /**
     * Remove tags
     *
     * @param \App\DemoBundle\Entity\ArticleTag $tags
     */
    public function removeTag(\App\DemoBundle\Entity\ArticleTag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set uploadedImage
     * 
     * @param UploadedFile $uploadedImage
     * @return Article
     */
    public function setUploadedImage(UploadedFile $uploadedImage = null)
    {
        $this->uploadedImage = $uploadedImage;
        
        return $this;
    }

    /**
     * Get uploadedImage
     * 
     * @return UploadedFile
     */
    public function getUploadedImage()
    {
        return $this->uploadedImage;
    }

    /**
     * @return string
     */
    public function getImageUploadDir()
    {
        return self::IMAGE_UPLOAD_DIR;
    }

    /**
     * @return string
     */
    public function getFullImagePath()
    {
        if ($this->getImage() != "") {
            return $this->getImageUploadDir() . DIRECTORY_SEPARATOR . $this->getImage();
        }

        return "";
    }
}