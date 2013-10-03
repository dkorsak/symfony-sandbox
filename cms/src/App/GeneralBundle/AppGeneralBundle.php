<?php

/**
 * AppGeneralBundle class
 *
 *
 */
namespace App\GeneralBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use App\GeneralBundle\DependencyInjection\CompilerPass\GeneralCompilerPass;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppGeneralBundle extends Bundle
{
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\HttpKernel\Bundle\Bundle::build()
     *
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new GeneralCompilerPass());
    }
}
