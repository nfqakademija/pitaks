<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.26
 * Time: 19:22
 */
namespace pitaks\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add your custom field
        $builder->add('name');
        $builder->add('lastName');
        $builder->add('cardId');
    }

    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'pitaks_user_registration';
    }
}