<?php

namespace pitaks\KickerBundle\Controller;

use GuzzleHttp\Client;
use pitaks\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use pitaks\KickerBundle\Entity\Tables;
use pitaks\KickerBundle\Form\Type\TableType;

class TableController extends Controller
{

    /**
     * @return Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tables = $em->getRepository('pitaksKickerBundle:Tables')->findAll();
        return $this->render(
            'pitaksKickerBundle:Table:index.html.twig',
            array('tables' => $tables)
        );
    }

    /**
     * @param Request $request
     * @return Response
     */
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

        return $this->render('pitaksKickerBundle:Table:createTable.html.twig', array(
            'form' => $form->createView(),));
    }

    /**
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function deleteAction($id, Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $table = $em->getRepository('pitaksKickerBundle:Tables')->find($id);
        if (!$table) {
            throw $this->createNotFoundException(
                'No table found for id ' . $id
            );
        }
        $form = $this->createFormBuilder($table)
            ->add('delete', 'submit')
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->remove($table);
            $em->flush();
            return new Response('Table deleted successfully');
        }
        return $this->render('pitaksKickerBundle:Table:deleteTable.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function ShowResultAction(){

        $client = new Client();
        $fromID = 96610;
        $lastID = 96815;

        $em = $this->getDoctrine()->getManager();
        $table = $em->getRepository('pitaksKickerBundle:Tables')->findById(1);

        $stalas = new Tables();

        $skaitliukas1 = 0;//$table->getResultFirst();
        $skaitliukas2 =0;//$table->getResultSecond();
        while($fromID<$lastID){
            $data = $client->get(
                "http://wonderwall.ox.nfq.lt/kickertable/api/v1/events?rows=".(100)."&from-id=".$fromID, ['auth' =>  ['nfq', 'labas']]
            );

            $rez = $data->json();
            $this->get('api_data')->handlerApiData($table,$rez['records']);
            $fromID += 100;
        }
        return new Response('<b> pirma: '.$skaitliukas1.' : '.$skaitliukas2.' antra </b> </br>
         DATA DROM TABLE: asdfasdf');

    }
}
