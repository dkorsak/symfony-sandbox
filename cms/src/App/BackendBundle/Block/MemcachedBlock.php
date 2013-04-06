<?php

namespace App\BackendBundle\Block;

use App\GeneralBundle\Services\Stats\StatsInterface;
use Symfony\Component\Templating\EngineInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\HttpFoundation\Response;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Block\BaseBlockService;

class MemcachedBlock extends BaseBlockService
{
    /**
     * @var MemcachedStats
     */
    private $memcachedStats;

    /**
     * Constructor
     *
     * @param string          $name
     * @param EngineInterface $templating
     * @param StatsInterface  $apcStats
     */
    public function __construct($name, EngineInterface $templating, StatsInterface $memcachedStats)
    {
        parent::__construct($name, $templating);
        $this->memcachedStats = $memcachedStats;
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
    public function execute(BlockInterface $block, Response $response = null)
    {
        $template = 'AppBackendBundle:Block:memcached.block.html.twig';

        return $this->renderResponse($template, array('service' => $this->memcachedStats), $response);
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
        return 'Memcached Block';
    }

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function getDefaultSettings()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheKeys(BlockInterface $block)
    {
        return array(__CLASS__);
    }
}
