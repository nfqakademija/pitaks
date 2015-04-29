<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.29
 * Time: 19:20
 */
namespace pitaks\TeamBundle\Services;
use Doctrine\ORM\EntityManager;
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
        $firstOption =$this->getEm()->getRepository('pitaksTeamBundle:Team')->findBy(array('userId1'=>$userId1, 'userId2'=>$userId2));
        $secondOption = $this->getEm()->getRepository('pitaksTeamBundle:Team')->findBy(array('userId1'=>$userId2, 'userId2'=>$userId1));
        if($firstOption !=null|| $secondOption!=null)
        {
            return true;
        }
        return false;
    }

    /**
     * @param integer $teamId
     */
    public function confirmTeam($teamId)
    {
        $team =$this->getEm()->getRepository('pitaksTeamBundle:Team')->find($teamId);
        $team->setConfirmed(true);
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
     * @param integer $userId
     * @return array
     */
    public function returnUserTeams($userId)
    {
        return $this->getEm()->getRepository('pitaksTeamBundle:Team')->getAllUserTeam($userId);
    }
}