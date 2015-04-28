<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.27
 * Time: 22:53
 */

namespace pitaks\KickerBundle\Controller;

use pitaks\KickerBundle\Entity\Comment;
use pitaks\KickerBundle\Form\Type\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;

class CommentController extends Controller{

    public function newCommentAction(Request $request,$tableId)
    {
        $comment = new Comment();
        $form = $this->createForm(new CommentType(), $comment);
        $form->handleRequest($request);

        if ($form->isValid()) {
            // perform some action, such as saving the task to the database
            $comment->setDate(new \DateTime());
            $table = $this->getDoctrine()->getRepository('pitaksKickerBundle:Tables')->find($tableId);
            $comment->setTableId($table);
            $comment->setUserId($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return new Response("Success");
        }

        return $this->render('pitaksKickerBundle:Comments:createComment.html.twig', array(
            'form' => $form->createView(),
        ));

    }

}