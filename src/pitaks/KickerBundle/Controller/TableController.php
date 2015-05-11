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
            return new Response('<div class="btn btn-danger" style="padding: 50px">You have voted.Your score was: '.$Rating->getRating().'</div>' );
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
        if($game->getUser1Team1())
        $user11 = $this->get('fos_user.user_manager')->findUserByCardId( $game->getUser1Team1());
        else $user11=null;
        if($game->getUser1Team1())
        $user12 = $this->get('fos_user.user_manager')->findUserByCardId( $game->getUser2Team1());
        else $user12=null;
        if($game->getUser1Team1())
        $user21 = $this->get('fos_user.user_manager')->findUserByCardId( $game->getUser1Team2());
        else $user21=null;
        if($game->getUser1Team1())
        $user22 = $this->get('fos_user.user_manager')->findUserByCardId( $game->getUser2Team2());
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
}
