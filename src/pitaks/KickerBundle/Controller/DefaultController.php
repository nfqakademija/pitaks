<?php

namespace pitaks\KickerBundle\Controller;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $tables = $em->getRepository('pitaksKickerBundle:Tables')->findAll();
        $pagination=$this->get('rss_feed_getter_service')->rssFeedPagination($request);
        return $this->render('pitaksKickerBundle:Default:index.html.twig',
            array('tables' => $tables,'pagination' => $pagination));
    }
}
