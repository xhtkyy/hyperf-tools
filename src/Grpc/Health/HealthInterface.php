<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: protoc/health.proto

namespace Xhtkyy\HyperfTools\Grpc\Health;

/**
 * Protobuf type <code>grpc.health.v1.Health</code>
 */
interface HealthInterface
{
    /**
     * Method <code>check</code>
     *
     * @param HealthCheckRequest $request
     * @return HealthCheckResponse
     */
    public function check(HealthCheckRequest $request): HealthCheckResponse;

    /**
     * Method <code>watch</code>
     *
     * @param HealthCheckRequest $request
     * @return HealthCheckResponse
     */
    public function watch(HealthCheckRequest $request): HealthCheckResponse;

}

