<?php

namespace App\BackendBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;

class JQueryDateType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $defaultValues = array(
            'culture' => $this->getJQueryCulture()
        );
        $resolver->setDefaults($defaultValues);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'genemu_jquerydate';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_backend_form_jquery_date_type';
    }

    /**
     * Get JQuery valid culture from current locale
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