<?php

/**
 * MemcachedBlock class
 *
 *
 */
namespace App\BackendBundle\Block;

use App\GeneralBundle\Services\Stats\StatsInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;

/**
 * Class for displaying admin dashboard block
 * with memcache server stats
 *
 *
 */
class MemcachedBlock extends BaseBlockService
{
    /**
     * Mamcached stats service
     *
     * @var MemcachedStats
     */
    private $memcachedStats;

    /**
     * Constructor
     *
     * @param string          $name
     * @param EngineInterface $templating
     * @param StatsInterface  $memcachedStats
     */
    public function __construct($name, EngineInterface $templating, StatsInterface $memcachedStats)
    {
        parent::__construct($name, $templating);
        $this->memcachedStats = $memcachedStats;
    }

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     * @param ErrorElement   $errorElement
     * @param BlockInterface $block
     */
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
    }

    /**
     * {@inheritdoc}
     * @param  Response $response
     * @return Response
     */
    public function execute(BlockContextInterface $block, Response $response = null)
    {
        $template = 'AppBackendBundle:Block:base.stats.block.html.twig';

        $params = array(
            'service' => $this->memcachedStats,
            'title' => 'Memcache server info',
            'warning' => 'Memcached is not enabled'
        );

        $response = $this->renderResponse($template, $params, $response);
        $response->setTtl($block->getSetting('ttl'));

        return $response;
    }

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     * @param FormMapper     $formMapper
     * @param BlockInterface $block
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
    }

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     * @return string
     */
    public function getName()
    {
        return 'Memcached Block';
    }

    /**
     * {@inheritdoc}
     * @return string
     */
    public function getCacheKeys(BlockInterface $block)
    {
        return array(__CLASS__);
    }
}
