<?php

namespace App\BackendBundle\Tests\Block;

use Symfony\Component\HttpFoundation\Response;
use App\BackendBundle\Block\MemcachedBlock;
use App\GeneralBundle\Tests\BasePHPUnitTest;

class MemcachedBlockTest extends BasePHPUnitTest
{
    /**
     * @var MemcachedBlock
     */
    private $memcachedBlock;

    public function setUp()
    {
        parent::setUp();
        $templating = $this->container->get('templating');
        $name = 'MEMCACHEDBLOCK';
        $memcachedStats = \Mockery::mock('App\\GeneralBundle\\Services\\Stats\\StatsInterface');
        $memcachedStats->shouldReceive('isConnected')->andReturn(true);
        $memcachedStats->shouldReceive('getMemoryAllocation')->andReturn(1000);
        $memcachedStats->shouldReceive('getUsedMemory')->andReturn(900);
        $memcachedStats->shouldReceive('getFreeMemory')->andReturn(100);
        $this->memcachedBlock = new MemcachedBlock($name, $templating, $memcachedStats);
    }

    public function testExecute()
    {
        $block = \Mockery::mock('Sonata\BlockBundle\Model\BlockInterface');
        $response = $this->memcachedBlock->execute($block);
        $this->assertTrue($response instanceof Response);
        // used memory in percentage
        $this->assertContains("90%", $response->getContent());
    }

    public function testGetCacheKeys()
    {
        $block = \Mockery::mock('Sonata\BlockBundle\Model\BlockInterface');
        $result = $this->memcachedBlock->getCacheKeys($block);
        $this->assertEquals(get_class($this->memcachedBlock), $result[0]);
    }
}
