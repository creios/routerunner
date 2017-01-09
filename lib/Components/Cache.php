<?php

namespace TimTegeler\Routerunner\Components;

use phpFastCache\Core\DriverAbstract;
use phpFastCache\Core\DriverInterface;

/**
 * Class Cache
 * @package TimTegeler\Routerunner
 */
class Cache
{

    /** @var DriverAbstract */
    private $cache;
    /** @var string */
    private $key = 'config';

    /**
     * Cache constructor.
     * @param DriverInterface $cache
     * @param string $key
     */
    public function __construct(DriverInterface $cache, $key)
    {
        $this->cache = $cache;
        $this->key = $key;
    }

    /**
     * @return int
     */
    public function filled()
    {
        return $this->cache->isExisting($this->key);
    }

    /**
     * @return mixed
     */
    public function read()
    {
        $cache = $this->cache->get($this->key);
        return unserialize($cache);
    }

    /**
     * @param $cache
     */
    public function write($cache)
    {
        $cache = serialize($cache);
        $this->cache->set($this->key, $cache);
    }

    /**
     * @return int
     */
    public function clear()
    {
        return $this->cache->delete($this->key);
    }

}