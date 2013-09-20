<?php

namespace App\BackendBundle\Form\Type\Filter;

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
        $fieldOptions = array_merge(
            array(
                'required' => false,
                'attr' => array('class' => 'medium-date')
            ),
            $options['field_options']
        );
        $builder->add('start', 'app_backend_form_jquery_date_type', $fieldOptions);
        $builder->add('end', 'app_backend_form_jquery_date_type', $fieldOptions);
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('field_options' => array()));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'app_backend_form_filter_date_range_type';
    }
}
