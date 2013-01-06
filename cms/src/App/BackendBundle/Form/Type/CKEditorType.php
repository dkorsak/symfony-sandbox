<?php

namespace App\BackendBundle\Form\Type;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;

class CKEditorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->set('custom_config', $options['custom_config']);
        $view->set('language', $options['language']);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $defaultValues = array(
            'custom_config' => 'bundles/appbackend/js/ckeditor_config.js',
            'language' => $this->getCKEditorCulture(),
            'required' => false
        );
        $resolver->setDefaults($defaultValues);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'textarea';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app_backend_form_ckeditor_type';
    }

    /**
     * Get CKEditor valid culture from current locale
     * 
     * @return string
     */
    private function getCKEditorCulture()
    {
        $locale = \Locale::getDefault();
        if (strpos($locale, '_') !== false) {
            list($localeCKEditor, $locale) = explode("_", $locale);
        } else {
            $localeCKEditor = $locale;
        }
        
        return $localeCKEditor;
    }
}