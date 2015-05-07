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

    /**
     *
     */
    public function rssFeedAction()
    {
        $client = new Client();
        $response = $client->get('http://www.delfi.lt/rss/feeds/sports.xml');
        echo $response->getStatusCode();
// "200
       // echo $response->getBody();
        var_dump($response->getHeaders());
        echo $response->getHeader('content-type');
        return new Response("RSS");
    }
}
