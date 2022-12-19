<?php

namespace Xhtkyy\HyperfTools\Consul\Listener;

use Xhtkyy\HyperfTools\Consul\ConsulDriver;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\BootApplication;
use Hyperf\ServiceGovernance\DriverManager;

class RegisterConsul4GrpcDriverListener implements ListenerInterface {
    public function __construct(protected DriverManager $driverManager)
    {
    }

    public function listen(): array
    {
        return [
            BootApplication::class,
        ];
    }

    public function process(object $event): void
    {
        $this->driverManager->register('consul4grpc', make(ConsulDriver::class));
    }
}