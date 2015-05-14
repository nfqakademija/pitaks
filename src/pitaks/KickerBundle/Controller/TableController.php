<?php

namespace pitaks\KickerBundle\Controller;

use pitaks\KickerBundle\Entity\Game;
use pitaks\KickerBundle\Entity\TableRate;
use pitaks\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use pitaks\KickerBundle\Entity\Tables;
use pitaks\KickerBundle\Form\Type\TableType;
use pitaks\UserBundle\Entity\Rank;

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

    /**
     * @return Response
     */
    public function rateTableViewAction(){
        $tableId = $this->get('request')->request->get('tableId');
        $table = $this->getDoctrine()->getRepository('pitaksKickerBundle:Tables')->find($tableId);
        $user = $this->getUser();
        $user->getUsername();
        //need to check if registration exits
       $Rating = $this->getDoctrine()->getRepository('pitaksKickerBundle:TableRate')->findOneBy(array('username'=>$this->getUser()->getId(),'tableId'=>$tableId));
       if($Rating == null)
        return $this->render(
            '@pitaksKicker/Default/tableRateModal.html.twig',
            array('table' => $table)
        );
        else{
            return new Response('<h3>You have voted.Your score was: '.$Rating->getRating().'</h3>' );
        }

    }

    /**
     * @param integer $tableId
     * @return Response
     */
    public function saveTableRateAction($tableId){
        //get data from form tableId and user id and score
        $rating = $this->get('request')->request->get('rating');
        $TableRate = new TableRate();
        $em = $this->getDoctrine()->getManager();
        $table = $em->getRepository('pitaksKickerBundle:Tables')->find($tableId);
        $TableRate->setRating($rating);
        $TableRate->setTableId($table);
        $TableRate->setUsername(  $this->getUser());
        $em->persist($TableRate);
        $em->flush();
        return new Response("RATE WAS ADDED");
    }

    public function showTableInfoAction($tableId)
    {
        /*rodysim komentaru forma ir visus komentarus*/
        /*trumpa info apie table*/
        $em =$this->getDoctrine()->getManager();
        $table = $em->getRepository('pitaksKickerBundle:Tables')->find($tableId);
        $comments = $em->getRepository('pitaksKickerBundle:Comment')->findBy(array('tableId'=>$tableId));
    }

    /**
     * @param integer $tableId
     * @return Response
     */
    public function showTableResultsAction($tableId)
    {
        $em = $this->getDoctrine()->getManager();
        $table = $em->getRepository('pitaksKickerBundle:Tables')->find($tableId);
        $tableStatus = $this->get('api_data')->getTableStatusFromApi($table);
        $game = $this->getDoctrine()->getManager()->getRepository('pitaksKickerBundle:Game')->
        getLastGame($table->getId());
        if(!is_null($game->getUser1Team1()))
            $user11 = $this->getUserRepository()->findUserByCardId( $game->getUser1Team1());
        else $user11=null;
        if(!is_null($game->getUser2Team1()))
            $user12 = $this->getUserRepository()->findUserByCardId( $game->getUser2Team1());
        else $user12=null;
        if(!is_null($game->getUser1Team2()))
            $user21 = $this->getUserRepository()->findUserByCardId( $game->getUser1Team2());
        else $user21=null;
        if(!is_null($game->getUser2Team2()) )
            $user22 = $this->getUserRepository()->findUserByCardId( $game->getUser2Team2());
        else $user22=null;
            $team1 = $this->get('team_service')->findTeamByTwoUser($user11,$user12);
            $team2 = $this->get('team_service')->findTeamByTwoUser($user21,$user22);
        return $this->render('pitaksKickerBundle:Table:tableResultReview.html.twig',array(
            'game' => $game,
            'tableStatus' => $tableStatus,
            'user11' => $user11,
            'user12' => $user12,
            'user21' => $user21,
            'user22' => $user22,
            'team1' => $team1,
            'team2' =>$team2,
        ));
    }
    public function bestPlayersForTableAction($tableId)
    {
        $table = $this->getDoctrine()->getRepository('pitaksKickerBundle:Tables')->find($tableId);
        if (!$table) {
            throw $this->createNotFoundException(
                'No table found for id ' . $tableId
            );
        }
        $usersStatistic = $this->getDoctrine()->getRepository('UserBundle:UserTableStatistic')
            ->findBy(array('tableId'=>$tableId), array('gamesWin' => 'DESC', 'pointsScored'=> 'DESC'),5 );
        return $this->render('pitaksKickerBundle:Table:topUserForTable.html.twig', array('users'=>$usersStatistic));
    }

    /**
     * @return \pitaks\KickerBundle\Entity\TablesRepository
     */
    protected function getTableRepository()
    {
        return $this->getDoctrine()->getRepository('pitaksKickerBundle:Tables');
    }
    /**
     * @param null $id
     * @return null|\pitaks\KickerBundle\Entity\Tables
     */
    protected function getTable($id = null)
    {
        $tablesrepository = $this->getTableRepository();
        $table = $tablesrepository->findById($id);
        if ($table) {
            return $table;
        }
        return null;
    }

    /**
     * @return \pitaks\KickerBundle\Entity\GameRepository
     */
    protected function getGameRepository()
    {
        return $this->getDoctrine()->getRepository('pitaksKickerBundle:Game');
    }

    /**
     * @param $tableId
     * @return Response
     */
    public function showTableGamesAction($tableId,Request $request)
    {
       $table = $this->getTable($tableId);
        if(!$table)
        {
            throw $this->createNotFoundException(
                'No table found for id ' . $tableId
            );
        }
        $games = $this->getGameRepository()->getGamesForTableWhereResult($tableId);
        $results = $this->gamesList($games);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $results,
            $request->query->get('page', 1)/*page number*/,
            5/*limit per page*/
        );
        return  $this->render('pitaksKickerBundle:Default:tableGames.html.twig',
            array('pagination'=>$pagination, 'table'=>$table));

    }

    /**
     * @return \pitaks\UserBundle\Entity\UserManager
     */
    protected function getUserRepository()
    {
        return $this->get('fos_user.user_manager');
    }

    /**
     * @param null $cardId
     * @return \FOS\UserBundle\Model\UserInterface|null|object
     */
    protected function getUserByCard($cardId = null)
    {
        $userRepository = $this->getUserRepository();
        if($cardId) {
            $user = $userRepository->findUserByCardId($cardId);
            if ($user) {
                return $user;
            }
        }
        return null;
    }

    /**
     * @param $games
     * @return array
     */
    protected function gamesList($games)
    {
        $results=array();
        foreach($games as $game)
        {/** @var Game $game */
            $user11=$this->getUserByCard($game->getUser1Team1());
            $user12=$this->getUserByCard($game->getUser1Team2());
            $user21=$this->getUserByCard($game->getUser2Team1());
            $user22=$this->getUserByCard($game->getUser2Team2());
            $row=array(
                'user11'=>$user11,
                'user12'=>$user12,
                'user21'=>$user21,
                'user22'=>$user22,
                'begin'=> date('Y-m-d H:i',  $game->getBeginTime()),
                'end'=>date('Y-m-d H:i',  $game->getLastTime()),
                'duration'=> date('Y-m-d H:i', $game->getLastTime()-$game->getBeginTime()),
                'score1'=>$game->getScoreTeam1(),
                'score2'=>$game->getScoreTeam2()
            );
            $results[]=$row;
        }
        return $results;
    }
}
