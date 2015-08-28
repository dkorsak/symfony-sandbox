<?php

namespace App\GeneralBundle\Tests\Resources\Entity;

use App\GeneralBundle\Mapping\Annotation\MediaCacheable;
use App\GeneralBundle\Mapping\Annotation\MediaCacheableField;

/**
 * Fake entity class for testing media cacheable
 * and media translatable doctrine event listeners.
 *
 * @MediaCacheable
 */
class TestMediaCacheableEntity
{
    /**
     * @var string
     *
     * @MediaCacheableField(filters={"top_filter1", "top_filter2"}, path_getter="topImagePath")
     */
    private $topImage;

    /**
     * @var string
     *
     * @MediaCacheableField(filters={"bottom_filter"}, path_getter="bottomImagePath")
     */
    private $bottomImage;

    /**
     * Constructor.
     *
     * @param string $topImage
     * @param string $bottomImage
     */
    public function __construct($topImage, $bottomImage)
    {
        $this->topImage = $topImage;
        $this->bottomImage = $bottomImage;
    }

    /**
     * @return string
     */
    public function getTopImage()
    {
        return $this->topImage;
    }

    /**
     * @return string
     */
    public function getBottomImage()
    {
        return $this->bottomImage;
    }

    /**
     * @return string
     */
    public function getTopImagePath()
    {
        return 'fake_upload_dir';
    }

    /**
     * @return string
     */
    public function getBottomImagePath()
    {
        return 'fake_upload_dir';
    }
}
