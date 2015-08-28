<?php

namespace App\GeneralBundle\Vich\Namer;

use Gedmo\Sluggable\Util\Urlizer;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\NamerInterface;

/**
 * Class for generate user friendly file name.
 */
class SlugNamer implements NamerInterface
{
    public function name($obj, PropertyMapping $mapping)
    {
        $file = $mapping->getFile($obj);
        $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        if ('' != $ext) {
            $ext = '.'.$ext;
        }
        if (method_exists($obj, 'getSlug') && $obj->getSlug() != '') {
            return $obj->getSlug().$ext;
        }

        if (method_exists($obj, 'getSlugFileName') && $obj->getSlugFileName() != '') {
            return Urlizer::urlize($obj->getSlugFileName()).$ext;
        }

        return $file->getClientOriginalName();
    }
}
