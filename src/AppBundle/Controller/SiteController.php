<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Repository\AdvertRepository;

class SiteController extends Controller{

    public function indexAction(){

        $emAdvert = $this->getDoctrine()->getRepository('AppBundle:Advert');
        $listAdvert = $emAdvert->findAll();

        return $this->render('AppBundle:Default:index.html.twig', array(
            'listAdvert' => $listAdvert,
        ));
    }

    public function CreateAccountAction (){

        return $this->render('AppBundle:Default:index.html.twig');
    }

    public function showAccountAction (){

        return $this->render('AppBundle:Default:index.html.twig');
    }

    public function ViewAddAction (){

        return $this->render('AppBundle:Default:index.html.twig');
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
