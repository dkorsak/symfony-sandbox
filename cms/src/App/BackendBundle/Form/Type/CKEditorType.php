<?php

/**
 * CKEditorType class.
 */
namespace App\BackendBundle\Form\Type;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;

/**
 * CKEditor form widget.
 */
class CKEditorType extends AbstractType
{
    /**
     * {@inheritdoc}
     *
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['custom_config'] = $options['custom_config'];
        $view->vars['language'] = $options['language'];
    }

    /**
     * {@inheritdoc}
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $defaultValues = array(
            'custom_config' => 'bundles/appbackend/js/ckeditor_config.js',
            'language' => $this->getCKEditorCulture(),
            'required' => false,
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
        return 'textarea';
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName()
    {
        return 'app_backend_form_ckeditor_type';
    }

    /**
     * Get CKEditor valid culture from current locale.
     *
     * @return string
     */
    private function getCKEditorCulture()
    {
        $locale = \Locale::getDefault();
        if (strpos($locale, '_') !== false) {
            list($localeCKEditor, $locale) = explode('_', $locale);
        } else {
            $localeCKEditor = $locale;
        }

        return $localeCKEditor;
    }
}
