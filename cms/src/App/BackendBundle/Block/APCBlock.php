<?php

namespace App\BackendBundle\Block;

use App\GeneralBundle\Services\Stats\StatsInterface;
use Symfony\Component\Templating\EngineInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\HttpFoundation\Response;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Block\BaseBlockService;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class for displaying admin dashboard block
 * with APC stats
 *
 * @package App\Backend
 */
class APCBlock extends BaseBlockService
{
    /**
     * APCStats service
     *
     * @var ApcStats
     */
    private $apcStats;

    /**
     * Constructor
     *
     * @param string          $name
     * @param EngineInterface $templating
     * @param StatsInterface  $apcStats
     */
    public function __construct($name, EngineInterface $templating, StatsInterface $apcStats)
    {
        parent::__construct($name, $templating);
        $this->apcStats = $apcStats;
    }

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $block, Response $response = null)
    {
        $template = 'AppBackendBundle:Block:apc.block.html.twig';

        return $this->renderResponse($template, array('service' => $this->apcStats), $response);
    }

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
    }

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function getName()
    {
        return 'APC Block';
    }

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheKeys(BlockInterface $block)
    {
        return array(__CLASS__);
    }
}
