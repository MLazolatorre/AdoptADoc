<?php
/**
 * Created by PhpStorm.
 * User: MARC LAZOLA TORRE
 * Date: 24/11/2016
 * Time: 15:29
 */

namespace UserBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use UserBundle\Form\ImageType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('lastName',   TextType::class)
            ->add('location',   TextType::class)
            ->add('speciality', TextType::class)
            ->add('image',      ImageType::class,array(
                'required' => false,
            ))
        ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'userbundle_registration';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}