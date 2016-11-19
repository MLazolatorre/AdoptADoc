<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Advert;
use AppBundle\Form\AdvertType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class SiteController extends Controller{

    public function indexAction(){

        //we select all the advert
        $advertRepository = $this->getDoctrine()->getRepository('AppBundle:Advert');
        $listAdvert = $advertRepository->findAll();

        //TODO renomer la page (default:index....)
        return $this->render('AppBundle:Default:index.html.twig', array(
            'listAdvert' => $listAdvert,
        ));
    }

    public function createAccountAction (){

        return $this->render('AppBundle:Default:index.html.twig');
    }

    public function showAccountAction (){

        return $this->render('AppBundle:Default:index.html.twig');
    }

    public function viewAddAction ($id)
    {
        //we select the advert id
        $advertRepository = $this->getDoctrine()->getRepository('AppBundle:Advert');
        $advert = $advertRepository->find($id);

        //we check if the advert exist
        if ($advert == null){
            throw new NotFoundHttpException('The page with the id : ' . $id . "dosen't exist");
        }

        //TODO renomer la page (default:index....)
        return $this->render('AppBundle:Default:index.html.twig', array(
            'advert'    => $advert,
        ));
    }

    public function editAddAction ()
    {

        return $this->render('AppBundle:Default:index.html.twig');
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

            $request->getSession()->getFlashBag()->add('notice', 'Advert saved.');

            return $this->redirectToRoute('app_viewAdd', array(
                'id' => $advert->getId(),
            ));
        }

        return $this->render('AppBundle:Default:index.html.twig');  //TODO return sur la bonne page
    }

    public function deleteAddAction ()
    {

        return $this->render('AppBundle:Default:index.html.twig');
    }

}
