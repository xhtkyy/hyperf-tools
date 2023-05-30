<?php
// GENERATED CODE -- DO NOT EDIT!

// Original file comments:
// Copyright 2015 The gRPC Authors
// 
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
// 
//     http://www.apache.org/licenses/LICENSE-2.0
// 
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.
//
// The canonical version of this proto can be found at
// https://github.com/grpc/grpc-proto/blob/master/grpc/health/v1/health.proto
//
namespace Xhtkyy\HyperfTools\Grpc\Health;

/**
 */
class HealthClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * If the requested service is unknown, the call will fail with status
     * NOT_FOUND.
     * @param \Xhtkyy\HyperfTools\Grpc\Health\HealthCheckRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function Check(\Xhtkyy\HyperfTools\Grpc\Health\HealthCheckRequest $argument,
                                                                             $metadata = [], $options = []) {
        return $this->_simpleRequest('/grpc.health.v1.Health/Check',
        $argument,
        ['\Xhtkyy\HyperfTools\Grpc\Health\HealthCheckResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * Performs a watch for the serving status of the requested service.
     * The server will immediately send back a message indicating the current
     * serving status.  It will then subsequently send a new message whenever
     * the service's serving status changes.
     * 
     * If the requested service is unknown when the call is received, the
     * server will send a message setting the serving status to
     * SERVICE_UNKNOWN but will *not* terminate the call.  If at some
     * future point, the serving status of the service becomes known, the
     * server will send a new message with the service's serving status.
     * 
     * If the call terminates with status UNIMPLEMENTED, then clients
     * should assume this method is not supported and should not retry the
     * call.  If the call terminates with any other status (including OK),
     * clients should retry the call with appropriate exponential backoff.
     * @param \Xhtkyy\HyperfTools\Grpc\Health\HealthCheckRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\ServerStreamingCall
     */
    public function Watch(\Xhtkyy\HyperfTools\Grpc\Health\HealthCheckRequest $argument,
                                                                             $metadata = [], $options = []) {
        return $this->_serverStreamRequest('/grpc.health.v1.Health/Watch',
        $argument,
        ['\Xhtkyy\HyperfTools\Grpc\Health\HealthCheckResponse', 'decode'],
        $metadata, $options);
    }

}
