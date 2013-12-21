<?php

/**
 * OPCacheBlock class
 *
 *
 */
namespace App\BackendBundle\Block;

use App\GeneralBundle\Services\Stats\StatsInterface;
use Symfony\Component\Templating\EngineInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\HttpFoundation\Response;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Block\BaseBlockService;

/**
 * Class for displaying admin dashboard block
 * with OPcache stats
 *
 * @package App\Backend
 */
class OPCacheBlock extends BaseBlockService
{
    /**
     * OPCache service
     *
     * @var OPCacheService
     */
    private $opCacheService;

    /**
     * Constructor
     *
     * @param string          $name
     * @param EngineInterface $templating
     * @param StatsInterface  $opCacheService
     */
    public function __construct($name, EngineInterface $templating, StatsInterface $opCacheService)
    {
        parent::__construct($name, $templating);
        $this->opCacheService = $opCacheService;
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
     * @param  BlockContextInterface $block
     * @param  Response              $response
     * @return Response
     */
    public function execute(BlockContextInterface $block, Response $response = null)
    {
        $template = 'AppBackendBundle:Block:opcache.block.html.twig';

        $response = $this->renderResponse($template, array('service' => $this->opCacheService), $response);
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
        return 'OPCache Block';
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
