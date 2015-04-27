<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.27
 * Time: 23:54
 */

namespace pitaks\KickerBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CommentType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comment', 'textarea')
            ->add('save','submit', array('label' => 'Comment'));
    }

    public function getName()
    {
        return 'comment';
    }
}