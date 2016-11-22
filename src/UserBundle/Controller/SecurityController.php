<?php
/**
 * Created by PhpStorm.
 * User: MARC LAZOLA TORRE
 * Date: 21/11/2016
 * Time: 16:48
 */

namespace UserBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    public function loginAction ()
    {

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('app_homepage');
        }

        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('UserBundle:Security:login.html.twig', array(
            'last_username'     => $lastUsername,
            'error'             => $error,
        ));
    }
}