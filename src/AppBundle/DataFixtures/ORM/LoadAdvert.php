<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Advert;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Created by PhpStorm.
 * User: MARC LAZOLA TORRE
 * Date: 04/11/2016
 * Time: 13:26
 */
class LoadAdvert implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        $names = array(
            'Yoann',
            'Marc'
        );

        $titles = array(
            'Recherche medecin sur Amiens',
            'Recherche local sur Lyon'
        );

        $contents = array(
            "Bonjour, je m'appele Yoann et je suis un super docteur dans la bibi de cartier",
            "Bonjour, je recherche un endroit calme pour exercer mon talent"
        );


        for ($i = 0; $i < count($names); $i ++){
            $advert = new Advert();

            $advert
                ->setAuthor($names[$i])
                ->setTitle($titles[$i])
                ->setContent($contents[$i]);

            $manager->persist($advert);
        }

        $manager->flush();

    }
}