<?php

/**
 * GeneralCompilerPass class.
 */
namespace App\GeneralBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class GeneralCompilerPass implements CompilerPassInterface
{
    /**
     * (non-PHPdoc).
     *
     * @see \Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface::process()
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        // disable caching routes for loading routes from database
        if (!$container->hasDefinition('router.default')) {
            return;
        }
        $router = $container->getDefinition('router.default');
        $configuration = $router->getArgument(2);
        $configuration['cache_dir'] = null;
        $router->replaceArgument(2, $configuration);
    }
}
