<?php

namespace App\BackendBundle\Tests\Block;

use Symfony\Component\HttpFoundation\Response;
use App\GeneralBundle\Tests\BasePHPUnitTest;
use App\BackendBundle\Block\OPCacheBlock;

class OPCacheBlockTest extends BasePHPUnitTest
{
    /**
     * @var OPCacheBlock
     */
    private $opcacheBlock;

    public function setUp()
    {
        parent::setUp();
        $templating = $this->container->get('templating');
        $name = 'OPCACHEBLOCK';
        $opCacheService = \Mockery::mock('App\\GeneralBundle\\Services\\Stats\\StatsInterface');
        $opCacheService->shouldReceive('isConnected')->andReturn(true);
        $opCacheService->shouldReceive('getMemoryAllocation')->andReturn(2000);
        $opCacheService->shouldReceive('getUsedMemory')->andReturn(900);
        $opCacheService->shouldReceive('getFreeMemory')->andReturn(1100);
        $this->opcacheBlock = new OPCacheBlock($name, $templating, $opCacheService);
    }

    public function testExecute()
    {
        $block = \Mockery::mock('Sonata\BlockBundle\Block\BlockContextInterface', array('getSetting' => null));
        $response = $this->opcacheBlock->execute($block);
        $this->assertTrue($response instanceof Response);
        // used memory in percentage
        $this->assertContains("45%", $response->getContent());
    }

    public function testGetCacheKeys()
    {
        $block = \Mockery::mock('Sonata\BlockBundle\Model\BlockInterface');
        $result = $this->opcacheBlock->getCacheKeys($block);
        $this->assertEquals(get_class($this->opcacheBlock), $result[0]);
    }
}
