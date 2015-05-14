<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.05.10
 * Time: 18:55
 */

namespace pitaks\UserBundle\Controller;


use pitaks\UserBundle\Entity\Rank;
use pitaks\UserBundle\Form\Type\RankType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RankController extends Controller{

    public  function newRankAction(Request $request)
    {
        $rank = new Rank();
        $form = $this->createForm(new RankType(), $rank);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($rank);
            $em->flush();
            return $this->redirectToRoute('listRank');
        }
        return $this->render('@User/Ranks/newRank.html.twig',
            array( 'form' => $form->createView()));
    }


    /**
     * @return Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine();
        $ranks = $em->getRepository('UserBundle:Rank')->findAll();
        return $this->render(
            '@User/Ranks/rankList.html.twig',
            array('ranks' => $ranks)
        );
    }
    /**
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function editRankAction($id, Request $request) {

        $em = $this->getDoctrine()->getManager();
        $rank = $em->getRepository('UserBundle:Rank')->find($id);
        if (!$rank) {
            throw $this->createNotFoundException(
                'No rank found for id ' . $id
            );
        }
        $form = $this->createForm(new RankType(), $rank);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('listRank');
        }
        return $this->render('@User/Ranks/newRank.html.twig',
            array( 'form' => $form->createView()));
    }

    /**
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function deleteRankAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $rank = $em->getRepository('UserBundle:Rank')->find($id);
        if (!$rank) {
            throw $this->createNotFoundException(
                'No rank found for id ' . $id
            );
        }
        $form = $this->createFormBuilder($rank )
            ->add('delete', 'submit')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            foreach($rank->getUsers() as $user)
            {
                $user->setRank(null);
            }
            $em->remove($rank );
            $em->flush();
            return $this->redirectToRoute('listRank');
        }
        return $this->render('@User/Ranks/deleteRank.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function showRanksAction()
    {
        $ranks=$this->getDoctrine()->getRepository('UserBundle:Rank')->getOrderedRanksDESC();
       return $this->render('UserBundle:Ranks:allRanks.html.twig',
            ['ranks' =>$ranks]);
    }

}