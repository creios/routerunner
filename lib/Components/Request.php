<?php

namespace TimTegeler\Routerunner\Components;

/**
 * Class Request
 * @package TimTegeler\Routerunner\Components
 */
class Request
{

    /** @var string */
    private $method;
    /** @var string */
    private $path;

    /**
     * Request constructor.
     * @param string $method
     * @param string $path
     */
    public function __construct($method, $path)
    {
        $this->method = $method;
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

}