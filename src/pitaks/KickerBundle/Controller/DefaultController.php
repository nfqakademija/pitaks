<?php

namespace pitaks\KickerBundle\Controller;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tables = $em->getRepository('pitaksKickerBundle:Tables')->findAll();

        return $this->render('pitaksKickerBundle:Default:index.html.twig',
            array('tables' => $tables));
    }
}
