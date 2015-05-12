<?php

namespace pitaks\CommentsBundle\Controller;

use pitaks\CommentsBundle\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// in order to use Doctrine you need these two
use AppBundle\Entity\Comment;
use Symfony\Component\HttpFoundation\Response;

// in order to handle response you need these two
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller
{
    /**
     * @param Request $reques
     * @param null $id
     * @return RedirectResponse|Response
     */
    public function indexAction(Request $request, $id = null)
    {
        if (!$table = $this->getTable($id)) {
            return $this->redirect($this->generateUrl('pitaks_kicker_homepage'));
        }

        $form = $this->getCommentForm();

        return $this->render('pitaksCommentsBundle:Default:index.html.twig', [
            'form' => $form->createView(),
            'list' => $this->getCommentsPagination($request),
        ]);
    }

    /**
     * @param Request $request
     * @param null $id
     * @return RedirectResponse|Response
     */
    public function commentAction(Request $request, $id = null)
    {
        if (!$table = $this->getTable($id)) {
            return $this->redirect($this->generateUrl('pitaks_kicker_homepage'));
        }

        $form = $this->getCommentForm();
        $user = $this->getUser();

        if ($form->isValid() && $user) {
            $this->getCommentRepository()->insert(
                $form->getData(),
                $this->getTable($id),
                $user
            );
            return $this->redirect($this->generateUrl('pitaks_comments_index', [
                'id' => $table->getId(),
            ]));
        }

        return $this->render('pitaksCommentsBundle:Default:index.html.twig', [
            'form' => $form->createView(),
            'list' => $this->getCommentsPagination($request),
        ]);
    }

    /**
     * @param null $id
     * @return null|\pitaks\KickerBundle\Entity\Tables
     */
    protected function getTable($id = null)
    {
        $tablesrepository = $this->getTableRepository();
        $table = $tablesrepository->findById($id);
        if ($table) {
            return $table;
        }

        return null;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return $this->get('doctrine.orm.entity_manager');
    }

    /**
     * @param Request $request
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    protected function getCommentsPagination(Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $this->getCommentRepository()->getListQuery(),
            $request->query->get('page', 1),
            10
        );

        return $pagination;
    }

    /**
     * @param array $data
     * @return \Symfony\Component\Form\Form
     */
    protected function getCommentForm(array $data = [])
    {
        $form = $this->createForm(new CommentType(), $data);

        $form->add('submit', 'submit');

        $form->handleRequest($this->getRequest());

        return $form;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getCommentRepository()
    {
        return $this->getEntityManager()->getRepository('pitaksCommentsBundle:Comment');
    }

    protected function getTableRepository()
    {
        return $this->getEntityManager()->getRepository('pitaksKickerBundle:Tables');
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->get('request');
    }
}
