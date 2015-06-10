<?php

/**
 * JQueryDateType class
 *
 */
namespace App\BackendBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;

/**
 * Form type for displaying jquery datepicker
 *
 *
 */
class JQueryDateType extends AbstractType
{
    /**
     * {@inheritdoc}
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $defaultValues = array(
            'culture' => $this->getJQueryCulture(),
        );
        $resolver->setDefaults($defaultValues);
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getParent()
    {
        return 'genemu_jquerydate';
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName()
    {
        return 'app_backend_form_jquery_date_type';
    }

    /**
     * Get jQuery valid culture from current locale
     *
     * @return string
     */
    private function getJQueryCulture()
    {
        $locale = \Locale::getDefault();
        if (strpos($locale, '_') !== false) {
            list($localeJQuery, $locale) = explode("_", $locale);
        } else {
            $localeJQuery = $locale;
        }

        return $localeJQuery;
    }
}
