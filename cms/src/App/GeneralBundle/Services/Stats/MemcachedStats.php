<?php

namespace App\GeneralBundle\Services\Stats;

class MemcachedStats
{
    
    public function __construct()
    {
        $service = new \Memcached();
    }
}