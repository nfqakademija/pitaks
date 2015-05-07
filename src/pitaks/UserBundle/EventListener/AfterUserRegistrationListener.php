<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.05.07
 * Time: 17:54
 */
namespace pitaks\UserBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use pitaks\UserBundle\Entity\LastReviews;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class AfterUserRegistrationListener
{
    /**
     * @var $entityManager EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function onUserRegistered(FilterUserResponseEvent $event)
    {
        /**
         * @var $user \pitaks\UserBundle\Entity\User
         */
        $user = $event->getUser();

        // custom logika

        $reviews = new LastReviews();
        $user->setReviews( $reviews);
        $this->entityManager->persist($user);
        $this->entityManager->flush();


    }
}