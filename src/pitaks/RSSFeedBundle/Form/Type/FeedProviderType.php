<?php
/**
 * Created by PhpStorm.
 * User: Aurimas
 * Date: 2015.05.08
 * Time: 12:07
 */

namespace pitaks\RSSFeedBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FeedProviderType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('rss_url', 'text')
            ->add('save', 'submit');
    }

    public function getName()
    {
        return 'feedProvider';
    }


}