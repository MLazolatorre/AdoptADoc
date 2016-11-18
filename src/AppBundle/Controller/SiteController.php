<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Repository\AdvertRepository;
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

    public function EditAddAction (){

        return $this->render('AppBundle:Default:index.html.twig');
    }

    public function NewAddAction (){

        return $this->render('AppBundle:Default:index.html.twig');
    }

    public function DeleteAddAction (){

        return $this->render('AppBundle:Default:index.html.twig');
    }

}
