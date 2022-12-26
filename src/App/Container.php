<?php

namespace Xhtkyy\HyperfTools\App;

use Hyperf\Di\Exception\NotFoundException;

class Container implements ContainerInterface {

    public function __construct(protected \Hyperf\Di\Container $container) {
    }

    public function get(string $id) {
        return $this->container->get($id);
    }

    public function has(string $id): bool {
        return $this->container->has($id);
    }

    /**
     * @param string $name
     * @param array $parameters
     * @return mixed
     * @throws NotFoundException
     */
    public function make(string $name, array $parameters = []): mixed {
        return $this->container->make($name, $parameters);
    }

    public function set(string $name, $entry) {
        $this->container->set($name, $entry);
    }

    public function unbind(string $name) {
        $this->container->unbind($name);
    }

    public function define(string $name, $definition) {
        $this->container->define($name, $definition);
    }
}