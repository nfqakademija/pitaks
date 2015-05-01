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
        $user = new User();
        $user->getTeams();
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

}