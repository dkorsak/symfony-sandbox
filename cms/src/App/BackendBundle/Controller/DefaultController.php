<?php

namespace App\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function error403Action()
    {
        $params = array(
            'admin_pool' => $this->get('sonata.admin.pool'),
            'base_template' => $this->container->parameters['sonata.admin.configuration.templates']['layout'],
        );

        return $this->render('AppBackendBundle:Default:error403.html.twig', $params);
    }
}
