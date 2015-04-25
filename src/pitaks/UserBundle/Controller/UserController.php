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

        $lastReservationBox= $this->getDoctrine()->getRepository('pitaksKickerBundle:Reservation')
            ->findBy(array('tableId'=>4),array('reservationStart' => 'DESC'),1);
        if(count($lastReservationBox)>0)
        {
            $lastTime=$lastReservationBox[0]->getReservationStart();
        }
        else{
            $lastTime=new \DateTime();
        }
        //susikuriamas time
        $newtime = strtotime($lastTime->format('Y-m-d'));

        for($i = 1; $i <= 7; $i++)
        { $newtime= strtotime("+1 day", $newtime);
            echo date('Y-m-d H:i',$newtime);

        }
        var_export($lastTime);
      //  echo $lastTime->format('Y-m-d');
      //  $newtime= strtotime("+1 day", strtotime($lastTime->format('Y-m-d')));
       //  echo date('Y-m-d',$newtime);
        die;
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