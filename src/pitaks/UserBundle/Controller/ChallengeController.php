<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.26
 * Time: 12:02
 */

namespace pitaks\UserBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;

class ChallengeController extends Controller{
    /**
     * @param string $username
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ChallengePageAction($username)
    {
        $user = $this->get('fos_user.user_manager')->findUserByUsername($username);
        if(!$user)
        {
            throw $this->createNotFoundException(
                'No user found ' . $username
            );
        }
        if($username == $this->getUser()->getUsername())
        {
            return $this->redirectToRoute('fos_user_profile_show');
        }
        $tables = $this->getDoctrine()->getRepository('pitaksKickerBundle:Tables')->findAll();
        $friend = $this->get('fos_user.user_manager')->findUserByUsername($username);
        return $this->render('@User/Challenge/userChallengeView.html.twig', array(
                'tables' => $tables , 'user' => $friend
            )
        );
    }

    /**
     * @param string $username
     * @return Response
     */
    public function saveChallengeAction($username)
    {
        $date = $this->get('request')->request->get('dateValue');
        $tableId = $this->get('request')->request->get('tableId');
        $startValue =  $this->get('request')->request->get('startValue');
        $endValue =  $this->get('request')->request->get('endValue');
      //  $friendId =  $this->get('request')->request->get('friendId');
        $this->get('reservation_service')-> saveUserReservation($date, $tableId, $startValue,$endValue,$this->getUser(),$username);
        return new Response("Challenged ".$username);
    }



}