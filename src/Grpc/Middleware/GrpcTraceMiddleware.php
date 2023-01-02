<?php

namespace Xhtkyy\HyperfTools\Grpc\Middleware;

use Hyperf\Grpc\StatusCode;
use Hyperf\HttpMessage\Server\Response;
use Hyperf\Rpc\Context;
use Hyperf\Tracer\SpanStarter;
use Hyperf\Tracer\SpanTagManager;
use OpenTracing\Tracer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Xhtkyy\HyperfTools\App\ContainerInterface;
use const OpenTracing\Formats\TEXT_MAP;
use \Throwable;

class GrpcTraceMiddleware implements MiddlewareInterface {

    use SpanStarter;

    private Tracer $tracer;

    private SpanTagManager $spanTagManager;

    private Context $context;

    public function __construct(private ContainerInterface $container) {
        $this->tracer         = $container->get(Tracer::class);
        $this->spanTagManager = $container->get(SpanTagManager::class);
        $this->context        = $container->get(Context::class);
    }

    /**
     * @throws Throwable
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        $option = [];
        // 判断存在传递的父节点
        if ($request->hasHeader("tracer.carrier")) {
            $carrier     = json_decode($request->getHeaderLine("tracer.carrier"));
            $spanContext = $this->tracer->extract(TEXT_MAP, $carrier);
            if ($spanContext) {
                $option['child_of'] = $spanContext;
            }
        }

        $path = $request->getUri()->getPath();
        $key  = "GRPC Request [RPC] {$path}";
        $span = $this->startSpan($key, $option);
        $span->setTag('rpc.path', $path);
        foreach ($request->getHeaders() as $key => $value) {
            $span->setTag('rpc.headers' . '.' . $key, implode(', ', $value));
        }
        try {
            /**
             * @var Response $response
             */
            $response = $handler->handle($request);
            $status   = $response->getTrailer("grpc-status");
            if ($status != StatusCode::OK) {
                $span->setTag('error', true);
            }
            $span->setTag('rpc.status', $status);
            $span->setTag('rpc.message', $response->getTrailer("grpc-message"));
        } catch (Throwable $e) {
            $span->setTag('error', true);
            $span->log(['message', $e->getMessage(), 'code' => $e->getCode(), 'stacktrace' => $e->getTraceAsString()]);
            throw $e;
        } finally {
            //提交
            $span->finish();
            $this->tracer->flush();
        }
        return $response;
    }
}