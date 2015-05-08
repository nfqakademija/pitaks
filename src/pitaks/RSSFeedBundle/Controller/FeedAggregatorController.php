<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.05.08
 * Time: 12:02
 */
namespace pitaks\RSSFeedBundle\Controller;
use pitaks\RSSFeedBundle\Entity\FeedProvider;
use pitaks\RSSFeedBundle\Form\Type\FeedProviderType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FeedAggregatorController extends Controller {
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public  function newFeedProviderAction(Request $request)
    {
        $feed_provider = new FeedProvider();
        $form = $this->createForm(new FeedProviderType(), $feed_provider);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $feed_provider->setLastUpdated(new \DateTime());
            $em->persist($feed_provider);
            $em->flush();
            return $this->redirectToRoute('listFeedProvider');
        }
        return $this->render('pitaksRSSFeedBundle:FeedAggregatorView:newFeedProvider.html.twig',
            array( 'form' => $form->createView()));
    }


    /**
     * @return Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $feed_providers = $em->getRepository('pitaksRSSFeedBundle:FeedProvider')->findAll();
        return $this->render(
            'pitaksRSSFeedBundle:FeedAggregatorView:listFeedProvider.html.twig',
            array('providers' => $feed_providers)
        );
    }
    /**
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function editFeedProviderAction($id, Request $request) {

        $em = $this->getDoctrine()->getManager();
        $provider = $em->getRepository('pitaksRSSFeedBundle:FeedProvider')->find($id);
        if (!$provider) {
            throw $this->createNotFoundException(
                'No provider found for id ' . $id
            );
        }
        $form = $this->createForm(new FeedProviderType(), $provider);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('listFeedProvider');
        }
        return $this->render('pitaksRSSFeedBundle:FeedAggregatorView:newFeedProvider.html.twig',
            array( 'form' => $form->createView()));
    }

    /**
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function deleteFeedProviderAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $provider = $em->getRepository('pitaksRSSFeedBundle:FeedProvider')->find($id);
        if (!$provider) {
            throw $this->createNotFoundException(
                'No provider found for id ' . $id
            );
        }
        $form = $this->createFormBuilder($provider)
            ->add('delete', 'submit')
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->remove($provider);
            $em->flush();
            return $this->redirectToRoute('listFeedProvider');
        }
        return $this->render('pitaksKickerBundle:Table:deleteTable.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}