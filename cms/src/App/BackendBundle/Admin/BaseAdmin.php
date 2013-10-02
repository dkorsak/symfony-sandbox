<?php

namespace App\BackendBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;

class BaseAdmin extends Admin
{
    /**
     * @var integer
     */
    protected $maxPageLinks = 10;

    /**
     * Get securityContext
     *
     * @return SecurityContextInterface
     */
    public function getSecurityContext()
    {
        return $this->getConfigurationPool()->getContainer()->get('security.context');
    }

    /**
     * Get twig
     *
     * @return Twig_Environment
     */
    public function getTwig()
    {
        return $this->getConfigurationPool()->getContainer()->get('twig');
    }

    /**
     * @return string
     */
    protected function getEmptySelectValue()
    {
        return $this->translator->trans('Please select');
    }
}
