<?php

/**
 * BaseAdmin class
 *
 *
 */
namespace App\BackendBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;

/**
 * Class for overwrite some of sonata admin class method
 *
 *
 */
class BaseAdmin extends Admin
{
    /**
     * Max paginator page links
     *
     * @var integer
     */
    protected $maxPageLinks = 10;

    /**
     * Get service from container
     *
     * @param  string $serviceName
     * @return object
     */
    protected function getService($serviceName)
    {
        return $this->getConfigurationPool()->getContainer()->get($serviceName);
    }

    /**
     * Get empty value for input type select
     *
     * @return string
     */
    protected function getEmptySelectValue()
    {
        return $this->translator->trans('Please select');
    }

    /**
     * Get datagrid actions
     *
     * @param  string $withShow
     * @return array
     */
    protected function getActions($withShow = false)
    {
        $actions = array(
            'show' => array('template' => 'AppBackendBundle:CRUD:list__action_show.html.twig'),
            'edit' => array('template' => 'AppBackendBundle:CRUD:list__action_edit.html.twig'),
            'delete' => array('template' => 'AppBackendBundle:CRUD:list__action_delete.html.twig'),
        );

        if (!$withShow) {
            unset($actions['show']);
        }

        return $actions;
    }
}
