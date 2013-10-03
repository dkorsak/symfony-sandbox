<?php

/**
 * AnnotationDriver class
 *
 *
 */
namespace App\GeneralBundle\Driver;

use Doctrine\Common\Annotations\Reader;

/**
 * AnnotationDriver
 *
 */
class AnnotationDriver
{
    /**
     * Annotation reader
     *
     * @var Reader
     */
    private $reader;

    /**
     * Constructs a new instance of AnnotationDriver
     *
     * @param \Doctrine\Common\Annotations\Reader $reader The  annotation reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * Attempts to read the media cacheable annotation.
     *
     * @param  \ReflectionClass                                          $class The reflection class.
     * @return null|\App\GeneralBundle\Mapping\Annotation\MediaCacheable The annotation.
     */
    public function readMediaCacheable(\ReflectionClass $class)
    {
        return $this->reader
            ->getClassAnnotation($class, 'App\GeneralBundle\Mapping\Annotation\MediaCacheable');
    }

    /**
     * Attempts to read the media cacheable field annotations
     *
     * @param  \ReflectionClass $class The reflection class.
     * @return array            An array of media cacheable field annotations
     */
    public function readMediaCacheableFields(\ReflectionClass $class)
    {
        $fields = array();

        foreach ($class->getProperties() as $prop) {
            $field = $this->reader
                ->getPropertyAnnotation($prop, 'App\GeneralBundle\Mapping\Annotation\MediaCacheableField');

            if (null !== $field) {
                $field->setPropertyName($prop->getName());
                $fields[] = $field;
            }
        }

        return $fields;
    }

    /**
     * Get class property annotation
     *
     * @param  \ReflectionClass    $class
     * @param  string              $fieldName
     * @return MediaCacheableField
     */
    public function readCacheableField(\ReflectionClass $class, $fieldName)
    {
        try {
            $prop = $class->getProperty($fieldName);
            $field = $this->reader
                ->getPropertyAnnotation($prop, 'App\GeneralBundle\Mapping\Annotation\MediaCacheableField');

            return $field;
        } catch (\ReflectionException $e) {
            return null;
        }
    }
}
