<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.29
 * Time: 00:41
 */
namespace pitaks\TeamBundle\Controller;

use pitaks\TeamBundle\Entity\Team;
use pitaks\TeamBundle\Form\Type\TeamType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\User;

class TeamController extends Controller{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function TeamsViewAction()
    {
        $teams = $this->getDoctrine()->getRepository('pitaksTeamBundle:Team')->findAll();

        return $this->render(
            '@pitaksTeam/UserTeamsActionView/usersTeamAction',
            array('teams' => $teams)
        );
    }

    /**
     * @param integer $teamId
     * @return Response
     */
    public function OneTeamViewAction($teamId)
    {
        $team = $this->getDoctrine()->getRepository('pitaksTeamBundle:Team')->find($teamId);

        return $this->render(
            '@pitaksTeam/TeamViews/oneTeamView',
            array('team' => $team ,'users'=>$team->getUsers())
        );
    }


    /*VISADA PIRMAS TAS KURIS SUKURE PAKVIETE IR PNS*/
    /**
     * @param Request $request
     * @param $userId
     * @return Response
     */
    public function newTeamAction(Request $request,$userId)
    {
        $team = new Team();
        $friend = $this->get('fos_user.user_manager')->findUserById($userId);
        $form = $this->createForm(new TeamType(), $team);
        $form->handleRequest($request);
        $exits = $this->get('team_service')->checkIfTeamExits($userId,$this->getUser()->getId());
        if($userId == $this->getUser()->getId() )
        {
            //redirektinsim i kazkur kitur ir koki sms isvesim
           return new Response("Tokios komandaos neina sudaryt");
        }
        else if($form->isValid()) {
            // perform some action, such as saving the task to the database
            $team->setRegisteredDate(new \DateTime());
            $team->setConfirmed(false);
            $team->addUser($this->getUser());
            $team->addUser($friend);
            $friend->addTeam($team);
            $this->getUser()->addTeam($team);
            $em = $this->getDoctrine()->getManager();
            $em->persist($team);
            $em->flush();
            return $this->redirectToRoute('fos_user_profile_show');
        }
        return $this->render('pitaksTeamBundle:TeamViews:createNewTeam.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @return Response
     */
    public function showUserTeamsAction()
    {
        $teams = $this->getUser()->getTeams();
        return $this->render(
            '@pitaksTeam/UserTeamsActionView/usersTeamAction',
            array('teams' => $teams)
        );
    }

    /**
     * @return Response
     */
    public function showUsersInvitedTeamsAction()
    {
        $userId=$this->getUser()->getId();
        $teams = $this->getUser()->getTeams();
        $new = new \pitaks\UserBundle\Entity\User();
        $team2=$new->getTeams();
        foreach($team2 as $team)
        {
            if(!(new Team())->getConfirmed())
            {

            }
        }
        $teamAndUser = array();
        foreach($teams as $team)
        {
            $friend = $this->get('fos_user.user_manager')->findUserById($team->getUserId1());
            $a=array(
                "team"=>$team,
                "friend"=>$friend
            );
            $teamAndUser[]=$a;
        }
        return $this->render(
            'pitaksTeamBundle:TeamViews:allTeamsView.html.twig',
            array('teamAndFriend' => $teamAndUser)
        );
    }

    /**
     * @return Response
     */
    public function showUsersSuggestedTeamsAction()
    {
        $userId=$this->getUser()->getId();
        $teams = $this->getDoctrine()->getRepository('pitaksTeamBundle:Team')->getUsersSuggestedTeams($userId);
        $teamAndUser = array();
        foreach($teams as $team)
        {
            $friend = $this->get('fos_user.user_manager')->findUserById($team->getUserId2());
            $a=array(
                "team"=>$team,
                "friend"=>$friend
            );
            $teamAndUser[]=$a;
        }
        return $this->render(
            'pitaksTeamBundle:TeamViews:allTeamsView.html.twig',
            array('teamAndFriend' => $teamAndUser)
        );
    }

    public function deleteTeamAction($teamId,Request $request){

        $team = $this->getDoctrine()->getRepository('pitaksTeamBundle:Team')
            ->getUserTeamById($this->getUser()->getId(),$teamId);
        if (!$team) {
            throw $this->createNotFoundException(
                'No table found for id ' . $teamId
            );
        }
        $form = $this->createFormBuilder($team)
            ->add('delete', 'submit')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->remove($team);
            $this->getDoctrine()->getManager()->flush();
            return new Response('Team deleted successfully');
        }
        return $this->render('@pitaksTeam/UserTeamsActionView/deleteTeamActionView', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @param $teamId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function acceptTeamAction($teamId)
    {
        $this->get('team_service')->confirmTeam($teamId);
        // TODO need to change redirect t
        return $this->redirectToRoute('fos_user_profile_show');
    }
}