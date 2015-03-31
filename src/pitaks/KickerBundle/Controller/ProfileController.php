<?php

namespace pitaks\KickerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfileController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('pitaksKickerBundle:Profile:index.html.twig', array('name' => $name));
    }

    public function editAction()
    {
        return $this->render('pitaksKickerBundle:Profile:edit.html.twig');
    }

}
