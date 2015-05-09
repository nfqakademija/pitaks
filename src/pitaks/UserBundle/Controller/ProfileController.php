<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.25
 * Time: 16:23
 */

namespace pitaks\UserBundle\Controller;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use pitaks\UserBundle\Entity\LastReviews;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\ProfileController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileController extends BaseController
{

    public function registerAction(Request $request)
    {
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);
            echo "something";
            $revews = new LastReviews();
            $this->getDoctrine()->getManager()->persist($revews);
            $this->getDoctrine()->getManager()->flush();
            $user->setReviews($revews);
            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_registration_confirmed');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }

        return $this->render('FOSUserBundle:Registration:register.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function showAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->render('UserBundle:Profile:show.html.twig', array(
            'user' => $user
        ));
    }

    /*need to get all reservations*/
    public function userRegisteredReservationsAction()
    {
        $em = $this->getDoctrine();
        $user = $this->getUser();
        $username = $user->getUsername();
        $reservations = $em->getRepository('pitaksKickerBundle:RegisteredReservation')->findBy(array('userId' => $username), array('reservationStart' => 'ASC'));
        $userReservations = array();
        foreach ($reservations as $reservation) {
            $tableId = $em->getRepository('pitaksKickerBundle:Reservation')->
            findOneBy(array('registeredReservationId' => $reservation->getId()))->getTableId();
            $tableName = $em->getRepository('pitaksKickerBundle:Tables')->find($tableId)->getName();
            $record = array(
                "id" => $reservation->getId(),
                "tableName" => $tableName,
                "startDate" => $reservation->getReservationStart()->format('Y-m-d H:i'),
                "endDate" => $reservation->getReservationEnd()->format('Y-m-d H:i'),
                "tableId" => $tableId,
                "friend" => $reservation->getFriendId(),
                "confirmed" => $reservation->getIsConfirmed()
            );
            $userReservations[] = $record;
        }

        $reservations2 = $em->getRepository('pitaksKickerBundle:RegisteredReservation')->findBy(array('friendId' => $username, 'isConfirmed' => true), array('reservationStart' => 'ASC'));
        foreach ($reservations2 as $reservation) {
            $friend = $this->get('fos_user.user_manager')->findUserByUsername($reservation->getFriendId());
            $tableId = $em->getRepository('pitaksKickerBundle:Reservation')->
            findOneBy(array('registeredReservationId' => $reservation->getId()))->getTableId();
            $tableName = $em->getRepository('pitaksKickerBundle:Tables')->find($tableId)->getName();
            $record = array(
                "id" => $reservation->getId(),
                "tableName" => $tableName,
                "startDate" => $reservation->getReservationStart()->format('Y-m-d H:i'),
                "endDate" => $reservation->getReservationEnd()->format('Y-m-d H:i'),
                "tableId" => $tableId,
                "friend" => $reservation->getUserId(),
                "confirmed" => $reservation->getIsConfirmed()
            );
            $userReservations[] = $record;
        }
        return $this->render('@User/Reservations/userReservationsList.html.twig', array(
            'reservations' => $userReservations, 'user' => $user
        ));

    }

    /**
     * @return JsonResponse
     */
    public function deleteUserReservationAction()
    {
        $reservationId = $this->get('request')->request->get('reservationId');
        $this->get('reservation_service')->deleteRegisteredReservation($reservationId);
        return new JsonResponse("Reservation was deleted " . $reservationId);
    }


    public function userUnconfirmedRegisteredReservationsAction()
    {
        $em = $this->getDoctrine();
        $user = $this->getUser();
        $username = $user->getUsername();
        $reservations = $em->getRepository('pitaksKickerBundle:RegisteredReservation')->findBy(array('friendId' => $username, 'isConfirmed' => false));
        $userReservations = array();
        foreach ($reservations as $reservation) {
            $friend = $this->get('fos_user.user_manager')->findUserByUsername($reservation->getFriendId());
            $tableId = $em->getRepository('pitaksKickerBundle:Reservation')->
            findOneBy(array('registeredReservationId' => $reservation->getId()))->getTableId();
            $tableName = $em->getRepository('pitaksKickerBundle:Tables')->find($tableId)->getName();
            $record = array(
                "id" => $reservation->getId(),
                "tableName" => $tableName,
                "startDate" => $reservation->getReservationStart()->format('Y-m-d H:i'),
                "endDate" => $reservation->getReservationEnd()->format('Y-m-d H:i'),
                "tableId" => $tableId,
                "friend" => $reservation->getUserId()
            );
            $userReservations[] = $record;
        }
        $this->get('user_lastviews_service')->updateUserChallengeReviewData($user);
        return $this->render('@User/Reservations/userUncorfirmedReservationsList.html.twig', array(
            'reservations' => $userReservations, 'user' => $user
        ));
    }

    /**
     * @return JsonResponse
     */
    public function acceptUserReservationAction()
    {
        $reservationId = $this->get('request')->request->get('reservationId');
        $this->get('reservation_service')->acceptUnconfirmedRegisteredReservation($reservationId);
        return new JsonResponse("Reservation was accepted " . $reservationId);
    }

    public function upcomingUserReservations()
    {
        $userRes = $this->get('reservation_service')->upcomingUserReservations($this->getUser());
        $userTeamsRes = $this->get('reservation_service')->upcomingUserTeamsReservations($this->getUser());
        return $this->render('UserBundle:Reservations:upcomingUserReservations.html.twig',
            array( 'userReservations' => $userRes,
                    'userTeamsReservations' =>$userTeamsRes));
    }

}

