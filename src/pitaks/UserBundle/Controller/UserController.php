<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.23
 * Time: 00:31
 */

namespace pitaks\UserBundle\Controller;

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
        $result = $this->get('fos_user.user_manager')->getUsersByWord($name);
        return $this->render('UserBundle:User:usersTableList.html.twig', array('users' => $result));
    }
}