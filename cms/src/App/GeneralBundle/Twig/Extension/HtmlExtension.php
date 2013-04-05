<?php

namespace App\GeneralBundle\Twig\Extension;

use App\GeneralBundle\Utils\StringUtil;

class HtmlExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            'extract_current_controller' => new \Twig_Filter_Method($this, 'extractCurrentController'),
        );
    }

    /**
     * @param  string $controller
     * @return string
     */
    public function extractCurrentController($controller)
    {
        $details = StringUtil::extractDetailsFromControllerName($controller);

        return strtolower($details['bundle'] . ' ' . $details['controller'] . ' ' . $details['action']);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_general_html_extension';
    }
}
