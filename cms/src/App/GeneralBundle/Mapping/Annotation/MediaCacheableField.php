<?php

/**
 * MediaCacheableField class
 *
 *
 */
namespace App\GeneralBundle\Mapping\Annotation;

/**
 * Media Cacheable Field
 *
 * @Annotation
 *
 */
class MediaCacheableField
{
    /**
     * @var array
     */
    private $filters;

    /**
     * @var string
     */
    private $propertyName;

    /**
     * @var string
     */
    private $pathGetter;

    /**
     * Constructs a new instance of MediaCacheableField
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
        if (isset($options['filters'])) {
            $this->filters = $options['filters'];
        } else {
            throw new \InvalidArgumentException('The "filters" attribute of MediaCacheableField is required.');
        }
        if (isset($options['path_getter'])) {
            $this->pathGetter = $options['path_getter'];
        } else {
            throw new \InvalidArgumentException('The "path_getter" attribute of MediaCacheableField is required.');
        }
    }

    /**
     * Get filters.
     *
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Set filters
     *
     * @param array $filters
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    /**
     * Get the property name
     *
     * @return string The property name.
     */
    public function getPropertyName()
    {
        return $this->propertyName;
    }

    /**
     * Set the property name
     *
     * @param $propertyName The property name.
     */
    public function setPropertyName($propertyName)
    {
        $this->propertyName = $propertyName;
    }

    /**
     * Get path getter
     *
     * @return string
     */
    public function getPathGetter()
    {
        return $this->pathGetter;
    }

    /**
     * Set path getter
     *
     * @param string $pathGetter
     */
    public function setPathGetter($pathGetter)
    {
        $this->pathGetter = $pathGetter;
    }
}
