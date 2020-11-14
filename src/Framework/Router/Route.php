<?php

namespace Framework\Router;

/**
 * Represent the route
 */
class Route
{

    private $name;
    private $callback;
    private $params;

    public function __construct(string $name, $callback, array $params)
    {
        $this->name = $name;
        $this->callback = $callback;
        $this->params = $params;
    }

    /**
     * @return string name of the route
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return callable function with call if route is load
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * list of parameters
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
