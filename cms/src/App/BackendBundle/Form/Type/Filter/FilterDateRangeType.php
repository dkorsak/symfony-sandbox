<?php

namespace App\BackendBundle\Form\Type\Filter;

use Sonata\AdminBundle\Form\Type\Filter\DateRangeType;
use Symfony\Component\Form\FormBuilderInterface;

class FilterDateRangeType extends DateRangeType
{
    /**
     * Build form
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->remove('value')
            ->add(
                'value',
                'app_backend_form_filter_date_range_type',
                array('field_options' => $options['field_options'])
            );
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'app_backend_form_filter_filter_date_range_type';
    }
}
