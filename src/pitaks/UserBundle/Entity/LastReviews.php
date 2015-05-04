<?php

namespace pitaks\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * LastReviews
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="pitaks\UserBundle\Entity\LastReviewsRepository")
 */
class LastReviews
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="LastReservationReview", type="datetime")
     */
    private $lastReservationReview;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="LastTeamReview", type="datetime")
     */
    private $lastTeamReview;

    function __construct()
    {
        $this->lastTeamReview = new \DateTime();
        $this->lastReservationReview = new \DateTime();
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set lastReservationReview
     *
     * @param \DateTime $lastReservationReview
     * @return LastReviews
     */
    public function setLastReservationReview($lastReservationReview)
    {
        $this->lastReservationReview = $lastReservationReview;

        return $this;
    }

    /**
     * Get lastReservationReview
     *
     * @return \DateTime 
     */
    public function getLastReservationReview()
    {
        return $this->lastReservationReview;
    }

    /**
     * Set lastTeamReview
     *
     * @param \DateTime $lastTeamReview
     * @return LastReviews
     */
    public function setLastTeamReview($lastTeamReview)
    {
        $this->lastTeamReview = $lastTeamReview;

        return $this;
    }

    /**
     * Get lastTeamReview
     *
     * @return \DateTime 
     */
    public function getLastTeamReview()
    {
        return $this->lastTeamReview;
    }
}
