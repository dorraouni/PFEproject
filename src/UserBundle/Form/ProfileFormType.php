<?php
// src/UserBundle/Form/ProfileFormType.php


namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom');
    
        $builder->add('prenom');
    }

public function getParent() 
{
    return 'FOS\UserBundle\Form\Type\ProfileFormType';
}

public function getBlockPrefix()
{
    return 'app_user_profile';
}

// For Symfony 2.x
public function getName()
{
    return $this->getBlockPrefix();
}

}

