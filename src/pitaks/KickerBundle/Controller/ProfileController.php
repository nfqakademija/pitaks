<?php

namespace pitaks\KickerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;
use pitaks\KickerBundle\Entity\EventTable;
use pitaks\KickerBundle\Entity\EventTableRepository;
use pitaks\KickerBundle\Entity\Tables;

class ProfileController extends Controller
{
    public function indexAction()
    {
        return $this->render('pitaksKickerBundle:Default:example.html.twig');
    }

    public function editAction()
    {
        return $this->render('pitaksKickerBundle:Profile:edit.html.twig');
    }

}
