<?php

/**
 * HtmlExtension class
 *
 *
 */
namespace App\GeneralBundle\Twig\Extension;

use App\GeneralBundle\Utils\StringUtil;

class HtmlExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('extract_current_controller', array($this, 'extractCurrentController')),
        );
    }

    /**
     * @param  string $controller
     * @return string
     */
    public function extractCurrentController($controller)
    {
        $details = StringUtil::extractDetailsFromControllerName($controller);

        return strtolower($details['bundle'].' '.$details['controller'].' '.$details['action']);
    }

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function getName()
    {
        return 'app_general_html_extension';
    }
}
