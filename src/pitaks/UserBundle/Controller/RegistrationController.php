<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.05.03
 * Time: 20:54
 */

namespace pitaks\UserBundle\Controller;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use pitaks\UserBundle\Entity\LastReviews;
use pitaks\UserBundle\Entity\User;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends BaseController {

        public function registerAction(Request $request)
        {

            $response = parent::registerAction($request);
            $user=$this->getUser();
            $reviews = new LastReviews();
            $user->setReviews( $reviews);
            $this->getDoctrine()->getManager()->persist( $reviews);
            $this->getDoctrine()->getManager()->flush();
            echo"jonas";
            return $response;

        }




}