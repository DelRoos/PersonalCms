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

    public function __construct(string $name, callable $callback, array $params)
    {
        $this->name = $name;
        $this->callback = $callback;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return callable
     */
    public function getCallback(): callable
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
