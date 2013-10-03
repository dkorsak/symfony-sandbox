<?php

/**
 * StatsInterface interface
 *
 *
 */
namespace App\GeneralBundle\Services\Stats;

/**
 * StatsInterface interface
 *
 *
 */
interface StatsInterface
{
    /**
     * Check if PHP extension is enabled and application can use stats
     *
     * @return boolean
     */
    public function isConnected();

    /**
     * @return number
     */
    public function getMemoryAllocation();

    /**
     * @return number
     */
    public function getUsedMemory();

    /**
     * @return number
     */
    public function getFreeMemory();
}
