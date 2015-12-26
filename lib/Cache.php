<?php

namespace TimTegeler\Routerunner;

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
    public static $file;

    /**
     * @return bool
     * @throws CacheException
     */
    public static function exist()
    {
        if (file_exists(self::$file)) return true;
        throw new CacheException(sprintf("File (%s) doesn't exist.", self::$file));
    }

    /**
     * @return bool
     * @throws CacheException
     */
    public static function readable()
    {
        if (is_readable(self::$file)) return true;
        throw new CacheException(sprintf("File (%s) isn't readable.", self::$file));
    }

    /**
     * @return bool
     * @throws CacheException
     */
    public static function writeable()
    {
        if (is_writeable(self::$file)) return true;
        throw new CacheException(sprintf("File (%s) isn't writeable.", self::$file));
    }

    /**
     * @return bool
     * @throws CacheException
     */
    public static function useable()
    {
        self::exist();
        self::readable();
        self::writeable();
        return true;
    }

    /**
     * @return int
     */
    public static function filled()
    {
        clearstatcache(True, self::$file);
        return filesize(self::$file) > 0;
    }

    /**
     * @return mixed
     */
    public static function read()
    {
        $cache = file_get_contents(self::$file);
        return unserialize($cache);
    }

    /**
     * @param $cache
     */
    public static function write($cache)
    {
        $cache = serialize($cache);
        file_put_contents(self::$file, $cache);
    }

    /**
     *
     */
    public static function clear()
    {
        file_put_contents(self::$file, NULL);
    }

    /**
     * @param string $file
     */
    public static function setFile($file)
    {
        self::$file = $file;
    }

}