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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use pitaks\UserBundle\Entity\User;

class TeamController extends Controller{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function TeamsViewAction()
    {
        $teams = $this->getDoctrine()->getRepository('pitaksTeamBundle:Team')->findAll();

        return $this->render(
            'pitaksTeamBundle:TeamSearch:teamSerchResultView.html.twig',
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
            'pitaksTeamBundle:TeamViews:oneTeamView',
            array('team' => $team ,'users'=>$team->getUsers())
        );
    }

    public function TeamMenuAction($teamId)
    {
        //find user and check if he has access to this team
        $user= $this->getUser();
        $team = $this->getDoctrine()->getRepository('pitaksTeamBundle:Team')->find($teamId);
        if($team->getConfirmed() && $team->getUsers()->contains($user))
        {
            //if okay return view
            return $this->render(
                'pitaksTeamBundle:TeamViews:teamMenu.html.twig',
                array('team' => $team)
            );
        }
        else{
            //redirect to profile
            return $this->redirectToRoute('fos_user_profile_show');
        }

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
        if($userId == $this->getUser()->getId() || $exits == true)
        {
            //redirektinsim i kazkur kitur ir koki sms isvesim
            echo "Tokios komandaos neina sudaryt";
            return $this->redirectToRoute('show_users');
        }
        else if($form->isValid()) {
            // perform some action, such as saving the task to the database
            $team->setRegisteredDate(new \DateTime());
            $team->setConfirmed(false);
            $team->addUser($this->getUser());
            $team->addUser($friend);
            $team->setAuthor($this->getUser());
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
        $teamAndUser = array();
        foreach($teams as $team)
        {
            if($team->getConfirmed()){
            $friend = $this->get('team_service')->returnTeamFriend($this->getUser(),$team);
                $a = array(
                    "team" => $team,
                    "friend" => $friend
                );
                $teamAndUser[] = $a;}
        }
        return $this->render(
            'pitaksTeamBundle:TeamViews:allTeamsView.html.twig',
            array('teamAndFriend' => $teamAndUser)
        );
    }

    //parodo pakvietimus useriui
    /**
     * @return Response
     */
    public function showUsersInvitedTeamsAction()
    {
        //gauname userio koamnadas reikia ziureti jeigu pirmas komandos narys yra ne jis
        $teams = $this->getUser()->getTeams();
        $teamAndUser = array();
        foreach($teams as $team)
        {//ar creator
            if(!$team->getConfirmed() && $team->getAuthor() != $this->getUser()) {
                //draugas bus ne jis tj kitas
                $friend = $this->get('team_service')->returnTeamFriend($this->getUser(),$team);
                echo $friend->getId();
                $a = array(
                    "team" => $team,
                    "friend" => $friend
                );
                $teamAndUser[] = $a;
            }
        }
        return $this->render(
            '@pitaksTeam/UserTeamsActionView/suggestedTeamsViews',
            array('teamAndFriend' => $teamAndUser)
        );
    }
    /**
     * @return Response
     */
    public function showUsersSuggestedTeamsAction()
    {
        $teams= $this->getUser()->getCreatedTeams();
        $teamAndUser = array();
        foreach($teams as $team)
        {
            if(!$team->getConfirmed() ) {
            $friend = $this->get('team_service')->returnTeamFriend($this->getUser(),$team);
            $a=array(
                "team"=>$team,
                "friend"=>$friend
            );
            $teamAndUser[]=$a;
            }
        }
        $this->get('user_lastviews_service')->updateUserNewTeamsSuggestionData($this->getUser());
        return $this->render(
            'pitaksTeamBundle:TeamViews:allTeamsView.html.twig',
            array('teamAndFriend' => $teamAndUser)
        );
    }

    public function deleteTeamAction($teamId,Request $request){

        $team = $this->getDoctrine()->getRepository('pitaksTeamBundle:Team')->find($teamId);
        if (!$team || !$team->getUsers()->contains($this->getUser())) {
            throw $this->createNotFoundException(
                'No actions for this team' . $teamId
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
        // TODO need to change redirect to
        $team = $this->getDoctrine()->getRepository('pitaksTeamBundle:Team')->find($teamId);
        return $this->render('@pitaksTeam/UserTeamsActionView/acceptedTeamView', array(
            'team' =>$team
        ));
    }
//delete darysim
    /**
     * @param $teamId
     * @return Response
     */
    public function teamListsBySearchAction($teamId)
    {
        $name = $this->get('request')->request->get('word');
        $teams = $this->get('team_service')->returnAllTeamsNoUserTeams($name,$this->getUser());

        $myteam = $this->getDoctrine()->getRepository('pitaksTeamBundle:Team')->find($teamId);
       // $teams = $this->getDoctrine()->getRepository('pitaksTeamBundle:Team')->getTeamsByFirstLetters($name);
        return $this->render(
            'pitaksTeamBundle:TeamSearch:teamSerchResultView.html.twig',
            array('teams' => $teams, 'myTeam' => $myteam)
        );

    }

    /**
     * @param $teamId
     * @return Response
     */
    public function teamSearchViewAction($teamId)
    {
        return $this->render('pitaksTeamBundle:TeamSearch:teamSearchFormView.html.twig',
        array('myteamId'=>$teamId));
    }

    //return all teams name by name or everything if name field is emty
    public function teamsNamesListAction()
    {
        $name = $this->get('request')->request->get('name');
        $names = $this->get('team_service')->returnAllTeamsNames($name,$this->getUser());
        return new JsonResponse($names);
    }
}