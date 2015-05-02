<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.29
 * Time: 19:20
 */
namespace pitaks\TeamBundle\Services;
use Doctrine\ORM\EntityManager;
use pitaks\TeamBundle\Entity\Team;
use pitaks\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAware;

class TeamService extends ContainerAware {

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
     * @param integer $userId1
     * @param integer $userId2
     * @return bool
     */
    public function checkIfTeamExits($userId1, $userId2)
    {
        /*find if exits*/
        $user1 = $this->container->get('fos_user.user_manager')->findUserById($userId1)->getTeams();
        $user2 = $this->container->get('fos_user.user_manager')->findUserById($userId2)->getTeams();
        $exits = false;
        foreach($user1 as $team)
        {
            if($user2->contains($team))
                $exits=true;
        }
        return $exits;
    }

    /**
     * @param User $user1
     * @param User $user2
     * @return null
     */
    public function findTeamByTwoUser($user1, $user2)
    {
        $teams1 = $user1->getTeams();
        $teams2 =$user2->getTeams();

        foreach($teams1 as $team)
        {
            if($teams2->contains($team))
            {
                return $team;
            }
        }
            return null;
    }
    /**
     * @param integer $teamId
     */
    public function confirmTeam($teamId)
    {
        $team =$this->getEm()->getRepository('pitaksTeamBundle:Team')->find($teamId);
        $team->setConfirmed(true);
        $team->setConfirmedDate(new \DateTime());
        $this->getEm()->flush();
    }

    /**
     * @param integer $teamId
     */
    public function deleteTeam($teamId)
    {
        $team=$this->getEm()->getRepository('pitaksTeamBundle:Team')->find($teamId);
        $this->getEm()->remove($team);
        $this->getEm()->flush();
    }

    /**
     * @param User $you
     * @param Team $team
     * @return null
     */
    public function returnTeamFriend($you,$team)
    {
        //susirandam useri
        $users =$team->getUsers();
        foreach($users as $user)
        {
            if($user != $you)
                return $user;
        }
        return null;
    }


    /**
     * @param string $name
     * @return array
     */
    public function returnAllTeamsNames($name)
    {
        $teams = $this->getEm()->getRepository('pitaksTeamBundle:Team')->getTeamsByFirstLetters($name);
        $names = array();
        foreach($teams as $team)
        {
            $names[]=$team->getName();
        }
        return $names;
    }

    /**
     * @param string $name
     * @param User $user
     * @return array
     */
    public function returnAllTeamsNamesWithoutUser($name,$user)
    {
        $teams = $this->getEm()->getRepository('pitaksTeamBundle:Team')->getTeamsByFirstLetters($name);
        $names = array();
        foreach($teams as $team)
        {
            if(!$team->getUsers()->contains($user))
            {
                $names[]=$team->getName();
            }
        }
        return $names;
    }

    //not effective
    /**
     * @param string $name
     * @param User $user
     * @return array
     */
    public function returnAllTeamsNoUserTeams($name,$user)
    {
        $teams = $this->getEm()->getRepository('pitaksTeamBundle:Team')->getTeamsByFirstLetters($name);
        $teamsNo= array();
        foreach($teams as $team)
        {
            if(!$team->getUsers()->contains($user))
            {
                $teamsNo[]=$team;
            }
        }
        return $teamsNo;
    }


}