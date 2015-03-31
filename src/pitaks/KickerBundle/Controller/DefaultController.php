<?php

namespace pitaks\KickerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('pitaksKickerBundle:Default:index.html.twig');
    }
}
