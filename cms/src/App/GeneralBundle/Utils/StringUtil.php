<?php

namespace App\GeneralBundle\Utils;

class StringUtil
{
    /**
     * Parse given controller name and return array with
     * controller namespace, bundle name, controller name, and action name
     *
     * @param  string $controller
     * @return string
     */
    public static function extractDetailsFromControllerName($controller)
    {
        $matches = array();

        preg_match('/(.*)\\\(.*)::(.*)/', $controller, $matches);
        $namespace = $matches[1];
        $controller = $matches[2];
        $action = $matches[3];

        preg_match('/(.*)\\\Controller/', $namespace, $matches);
        $bundle  = str_replace('\\', "", str_replace('Bundle\\', "", $matches[1]));

        return array('namespace' => $namespace, 'bundle' => $bundle, 'controller' => $controller, 'action' => $action);
    }

    /**
     * @return string
     */
    public static function generateRandomPassword()
    {
        return substr(md5(time().rand(1, 10000000)), 7, 8);
    }
}
