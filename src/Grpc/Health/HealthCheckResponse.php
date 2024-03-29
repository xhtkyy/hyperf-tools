<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: health.proto

namespace Xhtkyy\HyperfTools\Grpc\Health;

use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>grpc.health.v1.HealthCheckResponse</code>
 */
class HealthCheckResponse extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>.grpc.health.v1.HealthCheckResponse.ServingStatus status = 1;</code>
     */
    protected $status = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int $status
     * }
     */
    public function __construct($data = NULL) {
        \Xhtkyy\HyperfTools\Grpc\Health\Health::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>.grpc.health.v1.HealthCheckResponse.ServingStatus status = 1;</code>
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Generated from protobuf field <code>.grpc.health.v1.HealthCheckResponse.ServingStatus status = 1;</code>
     * @param int $var
     * @return $this
     */
    public function setStatus($var)
    {
        GPBUtil::checkEnum($var, HealthCheckResponse\ServingStatus::class);
        $this->status = $var;

        return $this;
    }

}

