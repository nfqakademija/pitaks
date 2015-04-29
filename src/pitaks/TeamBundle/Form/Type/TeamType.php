<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.29
 * Time: 16:25
 */
namespace pitaks\TeamBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TeamType extends  AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('save','submit', array('label' => 'create Team'));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'team';
    }
}