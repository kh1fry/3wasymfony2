<?php

namespace Wa\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('WaFrontBundle:Default:index.html.twig');
    }
}
