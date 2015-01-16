<?php

use Symfony\CS\FixerInterface;

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->notName('.php_cs')
    ->notName('composer.*')
    ->notName('phpunit.xml*')
    ->notName('*.phar')
    ->notName('autoload.php')
    ->notName('AppCache.php')
    ->notName('AppKernel.php')
    ->notName('SymfonyRequirements.php')
    ->exclude('vendor')
    ->exclude('bin')
    ->exclude('app/cache')
    ->exclude('app/logs')
    ->exclude('App/BackendBundle/Resources/public')
    //->exclude('App/FrontendBundle/Resources/public')
    //->exclude('App/GeneralBundle/Resources/public')
    ->in(__DIR__)
;

return Symfony\CS\Config\Config::create()
    ->finder($finder)
;