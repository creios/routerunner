<?php

namespace TimTegeler\Routerunner;

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
     */
    public static function useable()
    {
        return file_exists(self::$file) && is_readable(self::$file) && is_writeable(self::$file);
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