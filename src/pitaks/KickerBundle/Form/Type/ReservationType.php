<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.04.05
 * Time: 15:17
 */

namespace pitaks\KickerBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ReservationType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reservationStart', 'datetime')
            ->add('reservationEnd', 'datetime')
            ->add('reservatioDuration', 'integer')
            ->add('save', 'submit');
    }

    public function getName()
    {
        return 'reservation';
    }

}