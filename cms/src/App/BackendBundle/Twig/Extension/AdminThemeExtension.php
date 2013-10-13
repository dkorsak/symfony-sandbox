<?php

namespace App\BackendBundle\Twig\Extension;

use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Twig extension class
 *
 */
class AdminThemeExtension extends \Twig_Extension
{
    /**
     * @var SecurityContextInterface
     */
    private $sc;

    /**
     * Constructor
     *
     * @param ObjectManager $om
     */
    public function __construct(SecurityContextInterface $sc)
    {
        $this->sc = $sc;
    }

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function getFunctions()
    {
        return array(
            'admin_theme' => new \Twig_Function_Method($this, 'getAdminTheme'),
        );
    }

    /**
     * Get GA code from config files
     *
     * @return string
     */
    public function getAdminTheme()
    {
        if ($this->sc->isGranted('IS_AUTHENTICATED_FULLY')) {
            $theme = $this->sc->getToken()->getUser()->getAdminTheme();
        }

        return empty($theme) ? 'default' : $theme;
    }

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function getName()
    {
        return 'app_backend_admin_theme_extension';
    }
}
