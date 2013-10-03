<?php

/**
 * DateRangeType class
 *
 */
namespace App\BackendBundle\Form\Type\Filter;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form widget for displaying filter date range
 *
 *
 */
class DateRangeType extends AbstractType
{
    /**
     * {@inheritDoc}
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
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
     * {@inheritdoc}
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('field_options' => array()));
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName()
    {
        return 'app_backend_form_filter_date_range_type';
    }
}
