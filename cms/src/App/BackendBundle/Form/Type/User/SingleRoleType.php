<?php

/**
 * SingleRoleType class
 *
 *
 */
namespace App\BackendBundle\Form\Type\User;

use App\GeneralBundle\Entity\User;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;

/**
 * Form widget for displaying select with user role
 *
 *
 */
class SingleRoleType extends AbstractType
{
    /**
     * {@inheritdoc}
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $defaultValues['choices'] = User::$userRoles;
        $defaultValues['empty_value'] = 'Please select';
        $defaultValues['data-placeholder'] = 'Please select';
        $resolver->setDefaults($defaultValues);
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getParent()
    {
        return 'choice';
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName()
    {
        return 'app_backend_form_user_single_role_type';
    }
}
