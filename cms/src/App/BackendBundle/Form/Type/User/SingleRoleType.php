<?php

namespace App\BackendBundle\Form\Type\User;

use App\GeneralBundle\Entity\User;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;

class SingleRoleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $defaultValues['choices'] = User::$userRoles;
        $defaultValues['empty_value'] = 'Please select';
        $resolver->setDefaults($defaultValues);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'choice';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_backend_form_user_single_role_type';
    }
}
