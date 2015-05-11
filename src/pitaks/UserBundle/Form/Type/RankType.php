<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.05.10
 * Time: 18:56
 */

namespace pitaks\UserBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RankType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('win')
            ->add('scored')
            ->add('save', 'submit');
    }
    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "rank";
    }
}