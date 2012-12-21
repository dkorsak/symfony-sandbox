<?php

namespace App\GeneralBundle\Services\Stats;

class ApcStats
{
    /**
     * @var \ArrayObject
     */
    private $memoryInfo;

    /**
     * Constructor
     */
    public function __construct()
    {
        if ($this->isConnected()) {
            $this->memoryInfo = new \ArrayObject(apc_sma_info());
        }
    }

    /**
     * @return boolean
     */
    public function isConnected()
    {
        return function_exists('apc_cache_info');
    }

    /**
     * @return \ArrayObject
     */
    public function getMemoryInfo()
    {
        return $this->memoryInfo;
    }

    /**
     * @return number
     */
    public function getMemoryAllocation()
    {
        return $this->getMemoryInfo()->offsetGet('seg_size') * $this->getMemoryInfo()->offsetGet('num_seg');
    }

    /**
     * @return number
     */
    public function getUsedMemory()
    {
        return $this->getMemoryAllocation() - $this->getFreeMemory();
    }

    /**
     * @return number
     */
    public function getFreeMemory()
    {
        return $this->getMemoryInfo()->offsetGet('avail_mem');
    }
}