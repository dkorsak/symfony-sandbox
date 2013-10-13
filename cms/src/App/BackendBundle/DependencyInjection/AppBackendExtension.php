<?php

/**
 * AppBackendExtension class
 *
 *
 */
namespace App\BackendBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AppBackendExtension extends Extension
{
    /**
     * {@inheritDoc}
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('controller_services.yml');
        $loader->load('services.yml');
        $loader->load('forms.yml');
        $loader->load('form_widgets.yml');
        $loader->load('blocks.yml');
        $loader->load('filters.yml');
        $loader->load('twig_extensions.yml');

        $container->setParameter(
            'twig.form.resources',
            array_merge(
                $container->getParameter('twig.form.resources'),
                array(
                    'AppBackendBundle:Form:ckeditor_widget.html.twig',
                    'AppBackendBundle:Form:autocomplete_widget.html.twig',
                    'AppBackendBundle:Form:jquery_date_widget.html.twig'
                )
            )
        );
    }
}
