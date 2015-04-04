<?php

namespace pitaks\KickerBundle\Controller;

use Doctrine\ORM\Mapping\Table;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use pitaks\KickerBundle\Entity\Tables;
use pitaks\KickerBundle\Form\Type\TableType;
use pitaks\KickerBundle\Entity\EventTable;

class TableController extends Controller
{
    /**
     * @Route("/index")
     * @Template()
     */
    public function indexAction()
    {
        return array(
                // ...
            );
    }

    public function setEventsIDAction()
    {
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository('pitaksKickerBundle:EventTable')->findALL();
        foreach ($events as $event) {
            $event->setTableId(1);
            $em->flush();
        }
        return new Response('<html><body>Pakeisti Duomenys</body></html>');
    }

    public function newAction(Request $request)
    {
        $table = new Tables();
        $form = $this->createForm(new TableType(), $table);
        $form->handleRequest($request);
        if ($form->isValid()) {
            // perform some action, such as saving the task to the database
            $em = $this->getDoctrine()->getManager();
            $em->persist($table);
            $em->flush();

            return new Response('<html><body>išsaugoti duomenys</body></html>');
        }
        return $this->render('pitaksKickerBundle:Table:createTable.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function editTableAction($id, Request $request) {

        $em = $this->getDoctrine()->getManager();
        $table = $em->getRepository('pitaksKickerBundle:Tables')->find($id);
        if (!$table) {
            throw $this->createNotFoundException(
                'No table found for id ' . $id
            );
        }
        $form = $this->createForm(new TableType(), $table);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();
            return new Response('News updated successfully');
        }

       // $build['form'] = $form->createView();
        return $this->render('pitaksKickerBundle:Table:createTable.html.twig', array(
            'form' => $form->createView(),));
    }



}
