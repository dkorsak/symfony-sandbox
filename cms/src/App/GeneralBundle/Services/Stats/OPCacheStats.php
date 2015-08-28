<?php

/**
 * MemcachedStats class.
 */
namespace App\GeneralBundle\Services\Stats;

/**
 * Service for reading opcache stats - requires min PHP 5.5.5.
 */
class OPCacheStats implements StatsInterface
{
    /**
     * OPCache stats.
     *
     * @var \ArrayObject
     */
    private $stats;

    /**
     * Constructor.
     */
    public function __construct()
    {
        if (!$this->isConnected()) {
            return;
        }
        $this->stats = new \ArrayObject(opcache_get_status());
    }

    /**
     * {@inheritdoc}
     */
    public function isConnected()
    {
        return function_exists('opcache_get_status') && 'cli' !== php_sapi_name();
    }

    /**
     * @return \ArrayObject
     */
    public function getStats()
    {
        return $this->stats;
    }

    /**
     * @return number
     */
    public function getMemoryAllocation()
    {
        return $this->getUsedMemory() + $this->getFreeMemory();
    }

    /**
     * @return number
     */
    public function getUsedMemory()
    {
        $usage = $this->getStats()->offsetGet('memory_usage');

        return $usage['used_memory'];
    }

    /**
     * @return number
     */
    public function getFreeMemory()
    {
        $usage = $this->getStats()->offsetGet('memory_usage');

        return $usage['free_memory'];
    }
}
