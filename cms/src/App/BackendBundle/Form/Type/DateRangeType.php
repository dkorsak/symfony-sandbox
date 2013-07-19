<?php

namespace App\BackendBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DateRangeType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('start', 'app_backend_form_hidden_date_type');
        $builder->add('end', 'app_backend_form_hidden_date_type');
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $defaults = array(
            'field_options' => array()
        );
        $resolver->setDefaults($defaults);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'app_backend_form_date_range_type';
    }
}
