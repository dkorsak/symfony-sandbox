<?php

namespace App\BackendBundle\Block;

use Symfony\Component\Templating\EngineInterface;
use App\GeneralBundle\Services\Stats\MemcachedStats;
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
     * @param string $name
     * @param EngineInterface $templating
     * @param MemcachedStats $apcStats
     */
    public function __construct($name, EngineInterface $templating, MemcachedStats $memcachedStats)
    {
        parent::__construct($name, $templating);
        $this->memcachedStats = $memcachedStats;
    }

    /**
     * {@inheritdoc}
     */
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockInterface $block, Response $response = null)
    {
        $response = $this->renderResponse('AppBackendBundle:Block:memcached.block.html.twig', array('service' => $this->memcachedStats), $response);
        $response->setTtl(10);
        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Memcached Block';
    }

    /**
     * {@inheritdoc}
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