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
        // disable routing cache is configured by environment
        if (true != $container->getParameter('disable_routing_cache')) {
            $this->configureApacheRoutes($container);

            return;
        }
        // disable caching routes for loading routes from database
        if (!$container->hasDefinition('router.default')) {
            return;
        }
        $router = $container->getDefinition('router.default');
        $configuration = $router->getArgument(2);
        $configuration['cache_dir'] = null;
        $router->replaceArgument(2, $configuration);
    }

    /**
     * Connfigure read routing from htaccess file.
     *
     * @param ContainerBuilder $container
     */
    private function configureApacheRoutes(ContainerBuilder $container)
    {
        if (true != $container->getParameter('deploy.dump.routing')) {
            return;
        }
        $env = $container->getParameter('kernel.environment');
        if ('prod' != $env) {
            return;
        }
        $container->setParameter('router.options.matcher.cache_class', null);
        $container->setParameter('router.options.matcher_class', "Symfony\Component\Routing\Matcher\ApacheUrlMatcher");
    }
}
