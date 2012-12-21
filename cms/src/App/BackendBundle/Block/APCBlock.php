<?php

namespace App\BackendBundle\Block;

use Symfony\Component\Templating\EngineInterface;
use App\GeneralBundle\Services\Stats\ApcStats;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\HttpFoundation\Response;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Block\BaseBlockService;

class APCBlock extends BaseBlockService
{
    /**
     * @var ApcStats
     */
    private $apcStats;

    /**
     * Constructor
     * 
     * @param string $name
     * @param EngineInterface $templating
     * @param ApcStats $apcStats
     */
    public function __construct($name, EngineInterface $templating, ApcStats $apcStats)
    {
        parent::__construct($name, $templating);
        $this->apcStats = $apcStats;
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
        $response = $this->renderResponse('AppBackendBundle:Block:apc.block.html.twig', array('service' => $this->apcStats), $response);
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
        return 'APC Block';
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