<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.23
 * Time: 00:31
 */

namespace pitaks\UserBundle\Controller;

use pitaks\KickerBundle\Entity\TableRate;
use pitaks\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

class UserController extends Controller{


    public function autocompleteUserNamesAction(Request $request)
    {
        $name = $this->get('request')->request->get('word');
        $result=$this->get('fos_user.user_manager')->getUsersNamesByFirstLetters($name);
        $results=array();
        foreach($result as $value)
        {
            $results[]=$value['username'];
        }
        return new JsonResponse($results);
    }


    public function userListSearchAction(Request $request)
    {
        $form = $this->getCommentForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $date = $form->getData();
            return $this->redirectToRoute('userslist',['text'=>$date['name']]);
        }
        return $this->render($this->generateUrl('userslist',['text'=>""]));
    }

    protected function getCommentForm()
    {
        $form = $this->createFormBuilder()
            ->add('name', 'text',array(
                'required' => false,
            ))
            ->add('save', 'submit', array('label' => 'search'))
            ->getForm();
        return $form;
    }

    public function userListAction(Request $request, $text)
    {
        $userResults = $this->get('fos_user.user_manager')->getUsersByWord($text);
        $form = $this->getCommentForm();
        if ($userResults) {
            $resultsAll = $this->getUserWithStats($userResults);
            $paginator = $this->get('knp_paginator');
            $results = $paginator->paginate(
                $resultsAll,
                $request->query->get('page', 1),
                5/*limit per page*/
            );
            return $this->render('UserBundle:User:usersTableList.html.twig',
                array(
                    'users' => $results,
                    'form' => $form->createView(),
                )
            );
        }
    }

    /**
     *
     */
    protected function getUserWithStats($users)
    {
        $resultsAll=array();
        foreach($users as $user)
        {

            /** @var  User $user */
            $stats = $this->get('user_statistic_service')->returnAllUserStatistic($user);
            $rank =null;
            if($user->getRank())
                $rank =$user->getRank()->getName();
            if($user->getImage())
                $image = $user->getImage()->getImageAddress();
            else
                $image = 'images/anonymous.png';
            $all=array(
                "plusMinus" => $stats->getPlusMinusBalance(),
                "win" => $stats->getGamesWon(),
                "scored" => $stats->getPointsScored(),
                "userImage" =>  $image,
                "username" => $user->getUsername(),
                "userId" => $user->getId(),
                "name"=>$user->getName(),
                "rank"=>$rank
            );
            $resultsAll []= $all;
        }
        return $resultsAll;
    }



    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userTableRatesListAction()
    {
        $user =$this->getUser();
        $rating =$user->getTablesRating();
        return $this->render('UserBundle:UserTableRate:userTableRating.html.twig',
            array('ratings' => $rating)
        );
    }
}