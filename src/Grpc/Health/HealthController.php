<?php
declare(strict_types=1);
/**
 * @author   crayxn <https://github.com/crayxn>
 * @contact  crayxn@qq.com
 */

namespace Xhtkyy\HyperfTools\Grpc\Health;

use Xhtkyy\HyperfTools\Grpc\Health\HealthCheckResponse\ServingStatus;

class HealthController implements HealthInterface
{

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
        return $response;
    }

}