<?php

namespace TimTegeler\Routerunner\Components;

/**
 * Class Execution
 * @package TimTegeler\Routerunner\Components
 */
class Execution
{

    /** @var Call */
    private $call;
    /** @var array */
    private $parameters;
    /** @var string */
    private $reroutedPath;

    /**
     * Execution constructor.
     * @param Call $call
     * @param array $parameters
     */
    public function __construct(Call $call, array $parameters)
    {
        $this->call = $call;
        $this->parameters = $parameters;
    }

    /**
     * @return Call
     */
    public function getCall()
    {
        return $this->call;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param string $path
     * @return Execution
     */
    public function withReroutedPath($path)
    {
        $new = clone $this;
        $new->reroutedPath = $path;
        return $new;
    }

    /**
     * @return bool
     */
    public function hasRerouted()
    {
        return $this->reroutedPath !== null;
    }

    /**
     * @return string
     */
    public function getReroutedPath()
    {
        return $this->reroutedPath;
    }

}