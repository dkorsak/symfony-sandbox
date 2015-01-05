<?php

/**
 * MediaCacheableListener class
 *
 *
 */
namespace App\GeneralBundle\EventListener;

use App\GeneralBundle\Mapping\Annotation\MediaCacheableField;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Common\EventSubscriber;
use App\GeneralBundle\Driver\AnnotationDriver;

/**
 * Media Cacheable Listener
 * Find entity with App\GeneralBundle\Mapping\Annotation\MediaCacheable annotation
 * and remove Liip image cache from all uploaded entity fields
 *
 */
class MediaCacheableListener implements EventSubscriber
{
    /**
     * Annotation driver
     *
     * @var AnnotationDriver
     */
    private $driver;

    /**
     * Cache manager
     *
     * @var CacheManager
     */
    private $imageCacheManager;

    /**
     * Constructor
     *
     * @param AnnotationDriver $driver
     * @param CacheManager     $imageCacheManager
     */
    public function __construct(AnnotationDriver $driver, CacheManager $imageCacheManager)
    {
        $this->driver = $driver;
        $this->imageCacheManager = $imageCacheManager;
    }

    /**
     * The events the listener is subscribed to.
     *
     * @return array The array of events.
     */
    public function getSubscribedEvents()
    {
        return array(
            'preUpdate',
            'postRemove',
        );
    }

    /**
     * PreUpdate entity event
     *
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $object = $args->getEntity();
        if ($this->isMediaCacheable($object)) {
            $realClass = \Doctrine\Common\Util\ClassUtils::getRealClass(get_class($object));
            $class = new \ReflectionClass($realClass);
            $mapping = $this->driver->readMediaCacheableFields($class);
            foreach ($mapping as $field) {
                $fieldName = $field->getPropertyName();
                $filePath = call_user_func(array($object, 'get'.ucfirst($field->getPathGetter())));
                if ($args->hasChangedField($fieldName)) {
                    $fileName = $args->getOldValue($fieldName);
                } else {
                    $fileName = call_user_func(array($object, 'get'.ucfirst($fieldName)));
                }
                $this->removeCache($field, $filePath, $fileName);
            }
        }
    }

    /**
     * PostRemove entity event
     *
     * @param LifecycleEventArgs $args
     */
    public function postRemove(LifecycleEventArgs $args)
    {
        $object = $args->getEntity();
        if ($this->isMediaCacheable($object)) {
            $class = new \ReflectionClass($object);
            $mapping = $this->driver->readMediaCacheableFields($class);
            foreach ($mapping as $field) {
                $fieldName = $field->getPropertyName();
                $filePath = call_user_func(array($object, 'get'.ucfirst($field->getPathGetter())));
                $fileName = call_user_func(array($object, 'get'.ucfirst($fieldName)));
                $this->removeCache($field, $filePath, $fileName);
            }
        }
    }

    /**
     * Remove file ceche
     *
     * @param MediaCacheableField $field
     * @param string              $filePath
     * @param string              $fileName
     */
    private function removeCache(MediaCacheableField $field, $filePath, $fileName)
    {
        if ("" == $fileName) {
            return;
        }
        foreach ($field->getFilters() as $filterName) {
            $this->imageCacheManager->remove($filePath.DIRECTORY_SEPARATOR.$fileName, $filterName);
        }
    }

    /**
     * Check if entity is annotated as media cacheable
     *
     * @param  object  $obj
     * @return boolean
     */
    private function isMediaCacheable($obj)
    {
        $realClass = \Doctrine\Common\Util\ClassUtils::getRealClass(get_class($obj));
        $class = new \ReflectionClass($realClass);

        return null !== $this->driver->readMediaCacheable($class);
    }
}
