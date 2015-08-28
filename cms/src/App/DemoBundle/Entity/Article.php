<?php

namespace App\DemoBundle\Entity;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\GeneralBundle\Mapping\Annotation\MediaCacheable;
use App\GeneralBundle\Mapping\Annotation\MediaCacheableField;

/**
 * App\DemoBundle\Entity\Article.
 *
 * @ORM\Table(name="demo_article")
 * @ORM\Entity()
 * @Vich\Uploadable
 * @MediaCacheable
 */
class Article
{
    const IMAGE_UPLOAD_DIR = 'articles';

    /**
     * Primary key.
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Article title.
     *
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * Article content.
     *
     * @var string
     *
     * @ORM\Column(name="body", type="text", nullable=true)
     */
    private $body;

    /**
     * Publish date.
     *
     * @var \DateTime
     *
     * @ORM\Column(name="publish_date", type="datetime", nullable=false)
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    private $publishDate;

    /**
     * Is article published.
     *
     * @var bool
     *
     * @ORM\Column(name="publish", type="boolean", nullable=false)
     */
    private $publish;

    /**
     * Article slug.
     *
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=false, unique=true)
     * @Gedmo\Slug(fields={"title"}, updatable=false)
     */
    private $slug;

    /**
     * Image.
     *
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     * @MediaCacheableField(filters={"article_thumb"}, path_getter="imageUploadDir")
     */
    private $image;

    /**
     * Created at.
     *
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * Updated at.
     *
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * Category.
     *
     * @var ArticleCategory
     *
     * @ORM\ManyToOne(targetEntity="ArticleCategory",inversedBy="articles")
     * @ORM\JoinColumn(name="article_category_id", referencedColumnName="id", nullable=false, onDelete="RESTRICT")
     * @Assert\NotBlank()
     * @Assert\Type(type="App\DemoBundle\Entity\ArticleCategory")
     */
    private $articleCategory;

    /**
     * List of tags.
     *
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="ArticleTag", inversedBy="articles")
     * @ORM\JoinTable(name="demo_article_to_article_tag",
     *     joinColumns={
     *         @ORM\JoinColumn(name="article_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(name="tag_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     *     }
     * )
     */
    private $tags;

    /**
     * Uploaded image object.
     *
     * @var UploadedFile
     *
     * @Assert\Image()
     * @Vich\UploadableField(mapping="article_image", fileNameProperty="image")
     */
    private $uploadedImage;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->publishDate = new \DateTime();
        $this->publish = true;
        $this->tags = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle() ? $this->getTitle() : 'Article create';
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set body.
     *
     * @param string $body
     *
     * @return Article
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set publishDate.
     *
     * @param \DateTime $publishDate
     *
     * @return Article
     */
    public function setPublishDate($publishDate)
    {
        $this->publishDate = $publishDate;

        return $this;
    }

    /**
     * Get publishDate.
     *
     * @return \DateTime
     */
    public function getPublishDate()
    {
        return $this->publishDate;
    }

    /**
     * Set publish.
     *
     * @param bool $publish
     *
     * @return Article
     */
    public function setPublish($publish)
    {
        $this->publish = $publish;

        return $this;
    }

    /**
     * Get publish.
     *
     * @return bool
     */
    public function getPublish()
    {
        return $this->publish;
    }

    /**
     * Set slug.
     *
     * @param string $slug
     *
     * @return Article
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set image.
     *
     * @param string $image
     *
     * @return Article
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image.
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set created.
     *
     * @param \DateTime $created
     *
     * @return Article
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created.
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated.
     *
     * @param \DateTime $updated
     *
     * @return Article
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated.
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set articleCategory.
     *
     * @param \App\DemoBundle\Entity\ArticleCategory $articleCategory
     *
     * @return Article
     */
    public function setArticleCategory(\App\DemoBundle\Entity\ArticleCategory $articleCategory)
    {
        $this->articleCategory = $articleCategory;

        return $this;
    }

    /**
     * Get articleCategory.
     *
     * @return \App\DemoBundle\Entity\ArticleCategory
     */
    public function getArticleCategory()
    {
        return $this->articleCategory;
    }

    /**
     * Add tags.
     *
     * @param \App\DemoBundle\Entity\ArticleTag $tags
     *
     * @return Article
     */
    public function addTag(\App\DemoBundle\Entity\ArticleTag $tags)
    {
        $this->tags[] = $tags;

        return $this;
    }

    /**
     * Remove tags.
     *
     * @param \App\DemoBundle\Entity\ArticleTag $tags
     */
    public function removeTag(\App\DemoBundle\Entity\ArticleTag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set uploadedImage.
     *
     * @param UploadedFile $uploadedImage
     *
     * @return Article
     */
    public function setUploadedImage(File $uploadedImage = null)
    {
        $this->uploadedImage = $uploadedImage;
        // FIX Cannot Overwrite / Update Uploaded File
        // http://mossco.co.uk/symfony-2/vichuploaderbundle-how-to-fix-cannot-overwrite-update-uploaded-file/
        if ($uploadedImage instanceof File) {
            $this->setUpdated(new \DateTime());
        }

        return $this;
    }

    /**
     * Get uploadedImage.
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
        if ($this->getImage() != '') {
            return $this->getImageUploadDir().DIRECTORY_SEPARATOR.$this->getImage();
        }

        return '';
    }

    /**
     * @return string
     */
    public function getSlugFileName()
    {
        return $this->getTitle();
    }
}
