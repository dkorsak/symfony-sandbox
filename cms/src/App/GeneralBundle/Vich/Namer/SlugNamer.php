<?php

namespace App\GeneralBundle\Vich\Namer;

use Gedmo\Sluggable\Util\Urlizer;
use Vich\UploaderBundle\Naming\NamerInterface;

/**
 * Class for generate user frienly file name
 *
 *
 */
class SlugNamer implements NamerInterface
{
    /**
     * Generate file name
     * Object should has method getSlugFileName or getSlug
     * @see \Vich\UploaderBundle\Naming\NamerInterface::name()
     *
     * @param object $obj
     * @param string $file
     * @return string
     */
    public function name($obj, $field)
    {
        $file = call_user_func(array($obj, 'get' . $field));
        $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        if ("" != $ext) {
            $ext = '.' . $ext;
        }
        if (method_exists($obj, 'getSlug') && $obj->getSlug() != '') {
            return $obj->getSlug() . $ext;
        }

        if (method_exists($obj, 'getSlugFileName') && $obj->getSlugFileName() != '') {
            return Urlizer::urlize($obj->getSlugFileName()) . $ext;
        }

        return $file->getClientOriginalName();
    }
}
