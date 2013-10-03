<?php

/**
 * DefaultController class
 *
 *
 */
namespace App\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Default controller
 *
 *
 */
class DefaultController extends Controller
{
    /**
     * Execute permission denied action
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function error403Action()
    {
        $params = array(
            'admin_pool' => $this->get('sonata.admin.pool'),
            'base_template' => $this->container->parameters['sonata.admin.configuration.templates']['layout'],
        );

        return $this->render('AppBackendBundle:Default:error403.html.twig', $params);
    }
}
