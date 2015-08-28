<?php

namespace App\BackendBundle\Tests\Block;

use Symfony\Component\HttpFoundation\Response;
use App\BackendBundle\Block\APCBlock;
use App\GeneralBundle\Tests\BasePHPUnitTest;

class APCBlockTest extends BasePHPUnitTest
{
    /**
     * @var APCBlock
     */
    private $apcBlock;

    public function setUp()
    {
        parent::setUp();
        $templating = $this->container->get('templating');
        $name = 'APCBLOCK';
        $apcStats = \Mockery::mock('App\\GeneralBundle\\Services\\Stats\\StatsInterface');
        $apcStats->shouldReceive('isConnected')->andReturn(true);
        $apcStats->shouldReceive('getMemoryAllocation')->andReturn(2000);
        $apcStats->shouldReceive('getUsedMemory')->andReturn(910);
        $apcStats->shouldReceive('getFreeMemory')->andReturn(120);
        $this->apcBlock = new APCBlock($name, $templating, $apcStats);
    }

    public function testExecute()
    {
        $block = \Mockery::mock('Sonata\BlockBundle\Block\BlockContextInterface', array('getSetting' => null));
        $response = $this->apcBlock->execute($block);
        $this->assertTrue($response instanceof Response);
        // used memory in percentage
        $this->assertContains('45.5%', $response->getContent());
    }

    public function testGetCacheKeys()
    {
        $block = \Mockery::mock('Sonata\BlockBundle\Model\BlockInterface');
        $result = $this->apcBlock->getCacheKeys($block);
        $this->assertEquals(get_class($this->apcBlock), $result[0]);
    }
}
