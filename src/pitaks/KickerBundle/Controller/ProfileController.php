<?php

namespace pitaks\KickerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfileController extends Controller
{
    public function indexAction()
    {
        return $this->render('pitaksKickerBundle:Profile:index.html.twig', array(
                // ...
            ));    }

    public function editAction()
    {
        return $this->render('pitaksKickerBundle:Profile:edit.html.twig', array(
                // ...
            ));    }

}
