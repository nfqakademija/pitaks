<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.05.06
 * Time: 23:18
 */

namespace pitaks\UserBundle\Controller;

use pitaks\KickerBundle\Entity\Document;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ImageController extends Controller{

    public function uploadAction(Request$request)
    {
        $image = $this->getDoctrine()->getRepository('pitaksKickerBundle:Document')->findBy(array('name' => $this->getUser()->getUsername()));
        if ($image) {
            return new Response("photo exits");
        } else {
            $document = new Document();
            $document->setName($this->getUser()->getUsername());
            $form = $this->createFormBuilder($document)
                ->add('file')
                ->add('save', 'submit', array('label' => 'save'))
                ->getForm();
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $this->getUser()->setImage($document);
                $em->persist($document);
                $em->flush();
                return $this->redirectToRoute('fos_user_profile_show');
            }
            return $this->render('@User/fileUnpload/fileUnpload.html.twig', array('form' => $form->createView()));
        }
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function deleteAction( Request $request)
    {
        $user = $this->getUser();
        $id =  $user->getImage()->getId();
        $em = $this->getDoctrine()->getManager();
        $image = $em->getRepository('pitaksKickerBundle:Document')->find($id);
        if (!$image) {
            throw $this->createNotFoundException(
                'No image found ' . $id
            );
        }
        $form = $this->createFormBuilder($image)
            ->add('delete', 'submit')
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $user->setImage(null);
            $em->remove($image);
            $em->flush();
            return $this->redirectToRoute('fos_user_profile_show');
        }
        return $this->render('@User/fileUnpload/deleteImage.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}