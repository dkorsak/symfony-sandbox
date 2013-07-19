<?php

namespace App\BackendBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HiddenDateType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('day', 'hidden');
        $builder->add('month', 'hidden');
        $builder->add('year', 'hidden');
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $defaults = array(
        );
        $resolver->setDefaults($defaults);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'app_backend_form_hidden_date_type';
    }
}
