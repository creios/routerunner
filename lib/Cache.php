<?php

namespace TimTegeler\Routerunner;

class Cache
{

    public static $file;

    /**
     * @param string $file
     */
    public static function setFile($file)
    {
        self::$file = $file;
    }

    public static function exist()
    {
        return file_exists(self::$file);
    }

    public static function stuffed()
    {
        return (filesize(self::$file) > 0);
    }

    public static function writeable()
    {
        return self::exist() && is_writeable(self::$file);
    }

    public static function readable()
    {
        return self::exist() && is_readable(self::$file);
    }

    public static function load()
    {
        $cache = file_get_contents(self::$file);
        $routes = unserialize($cache);
        return $routes;
    }

    public static function save($routes)
    {
        $cache = serialize($routes);
        file_put_contents(self::$file, $cache);
    }

    public static function clear()
    {
        file_put_contents(self::$file, NULL);
    }
}