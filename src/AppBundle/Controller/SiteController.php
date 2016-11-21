<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Advert;
use AppBundle\Form\AdvertType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Debug\Exception\UndefinedFunctionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class SiteController extends Controller{

    public function indexAction(){

        //we select all the advert
        $advertRepository = $this->getDoctrine()->getRepository('AppBundle:Advert');
        $listAdvert = $advertRepository->findAll();

        return $this->render('AppBundle:Site:menu.html.twig ', array(
            'listAdvert' => $listAdvert,
        ));
    }

    public function createAccountAction ()
    {
        return $this->render('AppBundle:Default:index.html.twig');
    }

    public function showAccountAction ()
    {
        return $this->render('AppBundle:Default:index.html.twig');
    }

    public function viewAddAction ($id)
    {
        //we look for the advert in the DB with the ID '$id'
        $advertRepository = $this->getDoctrine()->getRepository('AppBundle:Advert');
        $advert = $advertRepository->find($id);

        //we check if the advert exist
        if ($advert == null){
            throw new NotFoundHttpException("The page with the id : " . $id . " dosen't exist");
        }

        return $this->render('AppBundle:Sit:viewAdd.html.twig', array(
            'advert'    => $advert,
        ));
    }

    public function newAddAction (Request $request)
    {
        $advert = new Advert();

        $form = $this->get('form.factory')->create(AdvertType::class, $advert);

        //if we receive a POST request => the use has sent the form so we sent his new page
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()){

            $em = $this->getDoctrine()->getManager();
            $em->persist($advert);
            $em->flush();

            return $this->redirectToRoute('app_viewAdd', array(
                'id' => $advert->getId(),
            ));
        }

        return $this->render('AppBundle:Sit:newAdvert.html.twig', array(
            'form'  =>  $form->createView(),
        ));
    }

    public function editAddAction ($id, Request $request)
    {
        //we look for the advert in the DB with the ID '$id'
        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository('AppBundle:Advert')->find($id);

        //we check if the id exist
        if ($advert == null){
            throw new NotFoundHttpException ("The page with the id : " . $id . " dosen't exist");
        }

        $form = $this->get('form.factory')->create(AdvertType::class, $advert);

        //if POST => the use try to edit the advert
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            //no persist because the advert exited already
            $em->flush();

            return $this->redirectToRoute('app_viewAdd', array(
                'id'    => $id,
            ));
        }

        return $this->render('@App/Sit/newAdvert.html.twig', array(       //TODO KIDJO : il faut rediriger la page
            'form'  => $form->createView(),
        ));
    }

    public function deleteAddAction (Request $request ,$id)
    {
        //we look for the advert in the DB with the ID '$id'
        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository('AppBundle:Advert')->find($id);

        //check if the advert exist
        if ($advert == null ){
            throw new NotFoundHttpException("The page with the id : " . $id . " dosen't exist");
        }

        $form = $this->get('form.factory')->create();

        //check if we receive a post query => the user want to delete the advert
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            //we remove the advert
            $em->remove($advert);
            $em->flush();

            //redirect to the home page
            return $this->redirectToRoute('app_homepage');
        }

        //if there is no POST method we return the delete page
        return $this->render('@App/Sit/delete.html.twig', array(
            'advert'    =>  $advert,
            'form'      =>  $form->createView(),
        ));
    }

}
