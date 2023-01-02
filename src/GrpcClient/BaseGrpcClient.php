<?php

namespace Xhtkyy\HyperfTools\GrpcClient;


use Google\Protobuf\Internal\Message;
use Hyperf\Context\Context;
use Hyperf\Di\Annotation\Inject;
use OpenTracing\Tracer;
use Xhtkyy\HyperfTools\App\ContainerInterface;
use const OpenTracing\Formats\TEXT_MAP;

class BaseGrpcClient {

    #[Inject]
    protected ContainerInterface $container;

    #[Inject]
    protected Tracer $tracer;

    public function __construct(protected string $hostname = "") {
    }

    public function _simpleRequest(string $method, Message $argument, array $deserialize, array $metadata = [], array $options = []): array {

        if ($span = Context::get('tracer.root')) {
            $carrier = [];
            // Injects the context into the wire
            $this->tracer->inject(
                $span->getContext(),
                TEXT_MAP,
                $carrier
            );
            $metadata["tracer.carrier"] = json_encode($carrier);
        }

        $client = $this->container->get(GrpcClientManager::class);
        return $client->invoke($this->hostname, $method, $argument, $deserialize, $metadata, $options);
    }
}