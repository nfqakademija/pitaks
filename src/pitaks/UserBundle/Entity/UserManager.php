<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.24
 * Time: 10:26
 */

namespace pitaks\UserBundle\Entity;



namespace pitaks\UserBundle\Entity;

use FOS\UserBundle\Doctrine\UserManager as BaseUserManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use FOS\UserBundle\Util\CanonicalizerInterface;
use Doctrine\ORM\EntityRepository;
use FOS\UserBundle\Model\UserInterface;

/**
 * This class extends the default fos user bundle doctrine usermanager to fit my own user entity.
 */
class UserManager extends BaseUserManager {

    /**
     *
     * @var EntityManager
     */
    protected $_em;

    public function __construct(EncoderFactoryInterface $encoderFactory, CanonicalizerInterface $usernameCanonicalizer,
                                CanonicalizerInterface $emailCanonicalizer, EntityManager $em, $class) {

        parent::__construct($encoderFactory, $usernameCanonicalizer, $emailCanonicalizer, $em, $class);
        $this->_em= $em;
    }

    /**
     * @param string $word
     * @return array
     */
   public function getUsersByWord($word){
       $query = $this->_em->createQueryBuilder();
       if(!empty($word)) {
           $query
               ->select('g')
               ->from("UserBundle:User", 'g')->
               where(
                   $query->expr()->like('g.username', ':name')
               )
               ->orderBy('g.username', 'ASC')
               ->setParameter('name', $word . '%');
       }else{
           $query
               ->select('g')
               ->from("UserBundle:User", 'g')
               ->orderBy('g.username', 'ASC');
       }
       return $query->getQuery()->getArrayResult();

   }

    /**
     * @param $word
     * @return array
     */
    public function getUsersNamesByFirstLetters($word){
        $query = $this->_em->createQueryBuilder();
        if(!empty($word)) {
            $query
                ->select('g.username')
                ->from("UserBundle:User", 'g')->
                where(
                    $query->expr()->like('g.username', ':name')
                )
                ->orderBy('g.username', 'ASC')
                ->setParameter('name', $word . '%');
        }else{
            $query
                ->select('g')
                ->from("UserBundle:User", 'g')
                ->orderBy('g.username', 'ASC');
        }
        return $query->getQuery()->getArrayResult();

    }

    /**
     * @param $id
     * @return UserInterface|object
     */
    public function findUserById($id)
    {
        return $this->findUserBy(array('id'=>$id));
    }
}