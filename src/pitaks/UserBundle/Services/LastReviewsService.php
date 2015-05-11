<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.05.02
 * Time: 23:54
 */

namespace pitaks\UserBundle\Services;
use Doctrine\ORM\EntityManager;
use pitaks\TeamBundle\Entity\Team;
use pitaks\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Validator\Constraints\DateTime;

class LastReviewsService extends ContainerAware{

    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return EntityManager
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * @param EntityManager $em
     */
    public function setEm($em)
    {
        $this->em = $em;
    }

    /**
     * @param User $user
     * @return int
     */
    public function getUserChallengesCountFromLastVisit($user)
    {
        //gauname last visited data
        $lastChallengeReview = $user->getReviews()->getLastReservationReview();
        $date = $lastChallengeReview->format('Y:m:d H:i');
        $count = $this->getEm()->getRepository('pitaksKickerBundle:RegisteredReservation')
            ->findUserLastReservationFromData($user->getUsername(), $date);
        return $count;
    }

    /**
     * @param User $user
     */
    public function updateUserChallengeReviewData($user)
    {
        $user->getReviews()->setLastReservationReview(new \DateTime);
        $this->getEm()->flush();
    }

    /**
     * @param User $user
     * @return int
     */
    public function getUserNewTeamsSuggestionCount($user)
    {
        $count = 0;
        $teams =$this->container->get('team_service')->returnTeamsWhereUserIsInvited($user);
        foreach($teams as $team)
        {
            if( $team->getRegisteredDate() > $user->getReviews()->getLastTeamReview())
            {
                $count++;
            }
        }
        return $count;
    }
    /**
     * @param User $user
     */
    public function updateUserNewTeamsSuggestionData($user)
    {
        $user->getReviews()->setLastTeamReview(new \DateTime);
        $this->getEm()->flush();
    }

    /**
     * @param User $user
     * @return integer
     */
    public function getInvitedTeamsCount($user)
    {
        $count = 0;
        $teams =$user->getTeams();
        foreach($teams as $team)
        {
            /**@var Team $team */
            if($team->getAuthor()!=$user && $team->getConfirmed()==false)
                $count++;
        }
        return $count;
    }
    /**
     * @param User $user
     * @return integer
     */
    public function getInvitedRegistrationsForUserCount($user)
    {
       return count($this->em->getRepository('pitaksKickerBundle:RegisteredReservation')
            ->findBy(array('friendId' => $user->getUsername(),'isConfirmed'=>false)));
    }

    /**
     * @param User $user
     */
    public function updateRankDate($user)
    {
        $user->getReviews()->setLastRankUpdate(new \DateTime());
        $this->container->get('doctrine.orm.entity_manager')->flush();
    }
}