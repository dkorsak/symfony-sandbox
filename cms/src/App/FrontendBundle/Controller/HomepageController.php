<?php

/**
 * HomepageController class
 *
 *
 */
namespace App\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controller for managing homepage actions
 *
 *
 */
class HomepageController extends Controller
{
    /**
     * Display homepage action
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('AppFrontendBundle:Homepage:index.html.twig');
    }
}
