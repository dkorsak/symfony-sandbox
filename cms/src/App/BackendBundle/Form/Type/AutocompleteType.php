<?php
/**
 * AutocompleteType class
 *
 *
 */
namespace App\BackendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

/**
 * Form type for displaying widget with autocompleter
 *
 *
 */
class AutocompleteType extends AbstractType
{
    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getParent()
    {
        return 'genemu_jqueryautocompleter_entity';
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName()
    {
        return 'app_backend_form_autocomplete_type';
    }
}
