<?php

namespace Xhtkyy\HyperfTools\GrpcClient;


use Google\Protobuf\Internal\Message;
use Hyperf\Di\Annotation\Inject;
use Psr\Container\ContainerInterface;

class BaseGrpcClient {

    #[Inject]
    protected ContainerInterface $container;

    public function __construct(protected string $hostname = "") {
    }

    public function _simpleRequest(string $method, Message $argument, array $deserialize, array $metadata = [], array $options = []): array {
        $client = $this->container->get(GrpcClientManager::class);
        return $client->invoke($this->hostname, $method, $argument, $deserialize, $metadata, $options);
    }
}