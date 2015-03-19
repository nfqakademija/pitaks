<?php

namespace pitaks\KickerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('pitaksKickerBundle:Default:index.html.twig', array('name' => $name));
    }
}
