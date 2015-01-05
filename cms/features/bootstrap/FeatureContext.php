<?php

use \Behat\MinkExtension\Context\MinkContext;
use \Behat\Symfony2Extension\Context\KernelAwareContext;
use \Symfony\Component\HttpKernel\KernelInterface;

class FeatureContext extends MinkContext implements KernelAwareContext
{
    private $kernel;

    /**
     * Sets Kernel instance.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Returns HttpKernel instance.
     *
     * @return KernelInterface
     */
    public function getKernel()
    {
        return $this->kernel;
    }

    /**
     * Returns HttpKernel service container.
     *
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->kernel->getContainer();
    }
}
