<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Advert;
use AppBundle\Entity\Application;
use AppBundle\Form\AdvertType;
use AppBundle\Form\ApplicationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use UserBundle\Entity\User;
use UserBundle\Form\UserType;


class SiteController extends Controller
{

    public function indexAction()
    {

        //we select all the advert
        $advertRepository = $this->getDoctrine()->getRepository('AppBundle:Advert');
        $listAdvert = $advertRepository->findAll();


        return $this->render('@App/Site/index.html.twig', array(

            'listAdvert' => $listAdvert,
        ));
    }

    public function createAccountAction(Request $request)
    {

        $user = new User();

        $form = $this->get('form.factory')->create(UserType::class, $user);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            //we check in JS if password == confirm password
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('@User/Security/create_account.html.twig', array(
            'form' => $form->createView(),

        ));

    }

    public function showAccountAction($id)
    {
        return $this->render('AppBundle:Default:index.html.twig');
    }


    public function viewAdAction(Request $request, $id)
    {
        //we look for the advert in the DB with the ID '$id'
        $advertRepository = $this->getDoctrine()->getRepository('AppBundle:Advert');
        $advert = $advertRepository->find($id);

        //we check if the advert exist
        if ($advert == null) {
            throw new NotFoundHttpException("The page with the id : " . $id . " dosen't exist");
        }

        $application = new Application($advert);

        $form = $this->get('form.factory')->create(ApplicationType::class, $application);
        $em = $this->getDoctrine()->getManager();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->persist($application);
            $em->flush();
        }

        //we find all the application
        $listApplication = $em->getRepository('AppBundle:Application')->findAll();

        $advertApplication = array();

        foreach ($listApplication as $application) {
            if ($application->getAdvert() == $advert) {
                $advertApplication[] = $application;
            }
        }

        return $this->render('AppBundle:Site:viewAd.html.twig', array(
            'advert'    => $advert,
            'form'      => $form->createView(),
            'listApplication'   => $advertApplication,
        ));
    }

    public function newAdAction (Request $request)
    {
        $advert = new Advert();

        $form = $this->get('form.factory')->create(AdvertType::class, $advert);

        //if we receive a POST request => the use has sent the form so we sent his new page
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($advert);
            $em->flush();

            return $this->redirectToRoute('app_viewAd', array(
                'id' => $advert->getId(),
            ));
        }


        return $this->render('AppBundle:Site:newAdvert.html.twig', array(
            'form'  =>  $form->createView(),
        ));
    }

    public function editAdAction($id, Request $request)
    {
        //we look for the advert in the DB with the ID '$id'
        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository('AppBundle:Advert')->find($id);

        //we check if the id exist
        if ($advert == null) {
            throw new NotFoundHttpException ("The page with the id : " . $id . " dosen't exist");
        }

        $form = $this->get('form.factory')->create(AdvertType::class, $advert);

        //if POST => the use try to edit the advert
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            //no persist because the advert exited already
            $em->flush();

            return $this->redirectToRoute('app_viewAd', array(

                'id'    => $id,
            ));
        }

        return $this->render('@App/Site/editAd.html.twig', array(
            'form'  => $form->createView(),
        ));
    }

    public function deleteAdAction (Request $request ,$id)

    {
        //we look for the advert in the DB with the ID '$id'
        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository('AppBundle:Advert')->find($id);

        //check if the advert exist
        if ($advert == null) {
            throw new NotFoundHttpException("The page with the id : " . $id . " dosen't exist");
        }

        $form = $this->get('form.factory')->create();

        //check if we receive a post query => the user want to delete the advert
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            //we remove the advert
            $em->remove($advert);
            $em->flush();

            //redirect to the home page
            return $this->redirectToRoute('app_homepage');
        }

        //if there is no POST method we return the delete page
        return $this->render('@App/Site/deleteAd.html.twig', array(
            'advert'    =>  $advert,
            'form'      =>  $form->createView(),

        ));
    }

}
