<?php

namespace App\DemoBundle\Vich\Namer;

use Gedmo\Sluggable\Util\Urlizer;
use Vich\UploaderBundle\Naming\NamerInterface;

class ArticleImageNamer implements NamerInterface
{
    /**
     * {@inhertidoc}
     */
    public function name($obj, $field)
    {
        $file = call_user_func(array($obj, 'get' . $field));
        $name = $file->getClientOriginalName();
        $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        if ($ext != "" && $obj->getSlug() != '') {
            if ($obj->getSlug() != '') {
                $name = $obj->getSlug() . '.' . $ext;
            } else {
                $name = Urlizer::urlize($obj->getTitle()) . '.' . $ext;
            }
        }

        return $name;
    }
}