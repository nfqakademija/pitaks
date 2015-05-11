<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.05.11
 * Time: 13:38
 */

namespace pitaks\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileFormType extends AbstractType
{
    public function getParent()
    {
        return 'fos_user_profile';
    }

    public function getName()
    {
        return 'pitaks_user_profile';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add your custom field
        $builder->add('name')
        ->add('lastName')
        ->add('cardId')
        ->remove('username');
    }
}