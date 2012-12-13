<?php

namespace App\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomepageController extends Controller
{
    public function indexAction()
    {
        return $this->render('AppFrontendBundle:Homepage:index.html.twig');
    }
}
