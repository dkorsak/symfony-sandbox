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
 * with memcache server stats
 *
 * @package App\Backend
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
     */
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $block, Response $response = null)
    {
        $template = 'AppBackendBundle:Block:memcached.block.html.twig';

        $response = $this->renderResponse($template, array('service' => $this->memcachedStats), $response);
        $response->setTtl($block->getSetting('ttl'));

        return $response;
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
