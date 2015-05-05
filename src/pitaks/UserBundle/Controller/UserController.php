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

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showUsersAction()
    {
        return $this->render('UserBundle:User:index.html.twig');
    }

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
    public function userListAction(Request $request)
    {
        $name = $this->get('request')->request->get('word');
        $userResults = $this->get('fos_user.user_manager')->getUsersByWord($name);
        $statsService = $this->get('user_statistic_service');
        $results = array();

        foreach($userResults as $user)
        {
            $realUser = $this->get('fos_user.user_manager')->findUserByUsername($user['username']);
            $stats = $statsService->returnAllUserStatistic($realUser);
            $all=array(
                "stat" => $stats,
                "user" => $user
            );
            $results []= $all;
        }
        return $this->render('UserBundle:User:usersTableList.html.twig', array('users' => $results));
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