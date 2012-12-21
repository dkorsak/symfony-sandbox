<?php

namespace App\GeneralBundle\Services\Stats;

class MemcachedStats
{
    /**
     * @var \ArrayObject
     */
    private $stats;

    /**
     * @var boolean
     */
    private $connected;

    /**
     * Constructor
     * 
     * @param string $host
     * @param integer $port
     */
    public function __construct($host, $port)
    {
        $this->connected = false;
        if (!class_exists('Memcached')) {
            return;
        }
        $service = new \Memcached();
        
        if ($service->addServer($host, $port)) {
            $stats = $service->getStats();
            if (isset($stats[$host . ':' . $port])) {
                $this->stats = new \ArrayObject($stats[$host . ':' . $port]);
            }
            $this->connected = true;
        }
    }

    /**
     * @return boolean 
     */
    public function isConnected()
    {
        return $this->connected;
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
        return $this->getStats()->offsetGet('limit_maxbytes');
    }

    /**
     * @return number
     */
    public function getUsedMemory()
    {
        return $this->getStats()->offsetGet('bytes');
    }

    /**
     * @return number
     */
    public function getFreeMemory()
    {
        return $this->getMemoryAllocation() - $this->getUsedMemory();
    }
}