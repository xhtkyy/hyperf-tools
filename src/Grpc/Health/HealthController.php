<?php
declare(strict_types=1);
/**
 * @author   crayxn <https://github.com/crayxn>
 * @contact  crayxn@qq.com
 */

namespace Xhtkyy\HyperfTools\Grpc\Health;

use Hyperf\Contract\ConfigInterface;
use Xhtkyy\HyperfTools\Grpc\Exception\StreamException;
use Xhtkyy\HyperfTools\Grpc\Health\HealthCheckResponse\ServingStatus;
use Xhtkyy\HyperfTools\Grpc\Server\Response\Stream;

class HealthController implements HealthInterface
{
    public function __construct(protected ConfigInterface $config)
    {
    }

    public function check(HealthCheckRequest $request): HealthCheckResponse
    {
        $response = new HealthCheckResponse();
        $response->setStatus(ServingStatus::SERVING);
        return $response;
    }

    public function watch(HealthCheckRequest $request): HealthCheckResponse
    {
        $response = new HealthCheckResponse();
        $response->setStatus(ServingStatus::SERVING);

        $wait = $this->config->get("kyy_tools.health.wait", 300);
        try {
            $stream = new Stream();
            while (true) {
                if (!$stream->write($response)) {
                    break;
                };
                sleep($wait);
            }
            $stream->close();
        } catch (StreamException $exception) {
            // 兼容非Streaming模式
        }
        return $response;
    }

}