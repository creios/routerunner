<?php

namespace TimTegeler\Routerunner\Util;

use TimTegeler\Routerunner\Exception\CacheException;

/**
 * Class Cache
 * @package TimTegeler\Routerunner
 */
class Cache
{

    /**
     * @var
     */
    public $file;

    /**
     * @return bool
     * @throws CacheException
     */
    public function useable()
    {
        self::exist();
        self::readable();
        self::writeable();
        return true;
    }

    /**
     * @return bool
     * @throws CacheException
     */
    public function exist()
    {
        if (file_exists($this->file)) return true;
        throw new CacheException(sprintf("File (%s) doesn't exist.", $this->file));
    }

    /**
     * @return bool
     * @throws CacheException
     */
    public function readable()
    {
        if (is_readable($this->file)) return true;
        throw new CacheException(sprintf("File (%s) isn't readable.", $this->file));
    }

    /**
     * @return bool
     * @throws CacheException
     */
    public function writeable()
    {
        if (is_writeable($this->file)) return true;
        throw new CacheException(sprintf("File (%s) isn't writeable.", $this->file));
    }

    /**
     * @return int
     */
    public function filled()
    {
        clearstatcache(True, $this->file);
        return filesize($this->file) > 0;
    }

    /**
     * @return mixed
     */
    public function read()
    {
        $cache = file_get_contents($this->file);
        return unserialize($cache);
    }

    /**
     * @param $cache
     */
    public function write($cache)
    {
        $cache = serialize($cache);
        file_put_contents($this->file, $cache);
    }

    /**
     * @return int
     */
    public function clear()
    {
        return file_put_contents($this->file, NULL);
    }

    /**
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

}