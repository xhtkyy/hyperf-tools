<?php

namespace Xhtkyy\HyperfTools\App;

interface ContainerInterface {
    public function get(string $id);

    public function has(string $id): bool;

    public function make(string $name, array $parameters = []);

    public function set(string $name, $entry);

    public function unbind(string $name);

    public function define(string $name, $definition);
}