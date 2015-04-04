<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.02
 * Time: 15:35
 */
namespace pitaks\KickerBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TableType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('ApiUrl', 'text')
            ->add('username', 'text')
            ->add('password', 'text')
            ->add('save', 'submit');
    }

    public function getName()
    {
        return 'table';
    }

}