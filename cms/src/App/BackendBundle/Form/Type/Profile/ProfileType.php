<?php

/**
 * ProfileType class
 *
 *
 */
namespace App\BackendBundle\Form\Type\Profile;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * User profile type
 *
 */
class ProfileType extends AbstractType
{
    /**
     * User class name
     *
     * @var string
     */
    private $class;

    /**
     * Constructor
     *
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * {@inheritdoc}
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', null, array('attr' => array('class' => 'span5')))
            ->add('lastname', null, array('attr' => array('class' => 'span5')))
            ->add('email', null, array('attr' => array('class' => 'span5')))
            ->add(
                'adminTheme',
                'choice',
                array(
                    'label' => 'Admin theme',
                    'required' => false,
                    'choices' => array(
                        null => 'Default',
                        'cerulean' => 'Cerulean',
                        'flatly' => 'Flatly',
                        'journal' => 'Journal',
                        'simplex' => 'Simplex',
                        'spacelab' => 'Spacelab',
                        'united' => 'United',
                    )
                )
            )
            ->add('oldPassword', 'password', array('required' => false, 'label' => 'Old password'))
            ->add('plainPassword', 'password', array('required' => false, 'label' => 'New password'))
            ->add('retypePassword', 'password', array('required' => false, 'label' => 'Retype password'));
    }

    /**
     * {@inheritdoc}
     *
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => $this->class,
                'translation_domain' => 'profile'
            )
        );
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName()
    {
        return 'app_backend_form_profile_profile_type';
    }
}
