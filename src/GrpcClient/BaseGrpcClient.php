<?php

namespace Xhtkyy\HyperfTools\GrpcClient;


use Google\Protobuf\Internal\Message;
use Hyperf\Context\Context;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Grpc\StatusCode;
use Hyperf\Tracer\SpanStarter;
use OpenTracing\Tracer;
use Swoole\Http2\Response;
use Xhtkyy\HyperfTools\App\ContainerInterface;
use const OpenTracing\Formats\TEXT_MAP;

class BaseGrpcClient {

    use SpanStarter;

    protected Tracer $tracer;

    protected ConfigInterface $config;

    protected string $hostname = "";
    protected bool $is_tracer = false;

    public function __construct(protected ContainerInterface $container) {
        $this->tracer = $this->container->get(Tracer::class);
        $this->config = $this->container->get(ConfigInterface::class);
    }

    public function _simpleRequest(string $method, Message $argument, array $deserialize, array $metadata = [], array $options = []): array {
        $trace_enable = $this->config->get("kyy_tools.trace", true);
        if ($trace_enable && $root = Context::get('tracer.root')) {
            $carrier = [];
            // Injects the context into the wire
            $this->tracer->inject(
                $root->getContext(),
                TEXT_MAP,
                $carrier
            );
            $metadata["tracer.carrier"] = json_encode($carrier);
        }

        $client = $this->container->get(GrpcClientManager::class);

        /**
         *
         * @var Response $resp
         */
        list($reply, $status, $resp) = $client->invoke($this->hostname, $method, $argument, $deserialize, $metadata, $options);
        // 判断是否需要追踪
        if ($trace_enable && $this->is_tracer) {
            $key  = "GRPC Client Response [RPC] {$method}";
            $span = $this->startSpan($key);
            $span->setTag('rpc.path', $method);
            if ($resp->headers) foreach ($resp->headers as $key => $value) {
                $span->setTag('rpc.headers' . '.' . $key, $value);
            }
            if ($status != StatusCode::OK) {
                $span->setTag('error', true);
            }
            $span->setTag('rpc.status', $status);
            $span->finish();
        }
        return [$reply, $status, $resp];
    }
}