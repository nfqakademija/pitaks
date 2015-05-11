<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.05.08
 * Time: 12:38
 */

namespace pitaks\UserBundle\Controller;


use pitaks\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller {

    public function indexAction()
    {
        /**@var User $user */
        $user = $this->getUser();
        if($user->hasRole('ROLE_ADMIN'))
        {
          return $this->render('@User/Admin/adminIndex.html.twig');
        }
        else{
            return $this->redirectToRoute('pitaks_kicker_homepage');
        }
    }

}