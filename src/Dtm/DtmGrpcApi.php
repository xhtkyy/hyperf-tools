<?php

namespace Xhtkyy\HyperfTools\Dtm;

use DtmClient\Api\ApiInterface;
use DtmClient\Api\RequestBranch;
use DtmClient\Constants\Operation;
use DtmClient\Constants\Protocol;
use DtmClient\Constants\Result;
use DtmClient\Exception\FailureException;
use DtmClient\Exception\OngingException;
use DtmClient\Exception\RequestException;
use DtmClient\Exception\UnsupportedException;
use DtmClient\Grpc\Message\DtmBranchRequest;
use DtmClient\Grpc\Message\DtmRequest;
use DtmClient\Grpc\Message\DtmTransOptions;
use DtmClient\TransContext;
use Hyperf\Contract\ConfigInterface;
use Xhtkyy\HyperfTools\GrpcClient\GrpcClientManager;

class DtmGrpcApi implements ApiInterface {
    protected ConfigInterface $config;

    protected GrpcClientManager $grpcClientManager;

    public function __construct(ConfigInterface $config, GrpcClientManager $grpcClientManager) {
        $this->config            = $config;
        $this->grpcClientManager = $grpcClientManager;
    }

    public function getProtocol(): string {
        return Protocol::GRPC;
    }

    public function generateGid(): string {
        $gidReply = $this->getDtmClient()->newGid();
        return $gidReply->getGid();
    }

    public function prepare(array $body) {
        $dtmRequest = $this->transferToRequest($body);
        $this->getDtmClient()->transCallDtm($dtmRequest, Operation::PREPARE);
    }

    public function submit(array $body): array {
        $dtmRequest = $this->transferToRequest($body);
        return $this->getDtmClient()->transCallDtm($dtmRequest, Operation::SUBMIT);
    }

    public function abort(array $body) {
        $dtmRequest = $this->transferToRequest($body);
        $this->getDtmClient()->transCallDtm($dtmRequest, Operation::ABORT);
    }

    public function registerBranch(array $body) {
        $dtmRequest = new DtmBranchRequest($body);
        $this->getDtmClient()->transCallDtm($dtmRequest, Operation::REGISTER_BRANCH);
    }

    /**
     * @throws UnsupportedException
     */
    public function query(array $body) {
        throw new UnsupportedException('Unsupported Query operation');
    }

    /**
     * @throws UnsupportedException
     */
    public function queryAll(array $body) {
        throw new UnsupportedException('Unsupported QueryAll operation');
    }

    /**
     * @param RequestBranch $requestBranch
     * @return array
     * @throws FailureException
     * @throws OngingException
     * @throws RequestException
     */
    public function transRequestBranch(RequestBranch $requestBranch): array {
        [$hostname, $method] = $this->parseHostnameAndMethod($requestBranch->url);
        try {
            $client = $this->grpcClientManager->getClient($hostname ?? '', $method);
        } catch (\Exception $e) {
            throw new FailureException();
        }
        TransContext::setDtm($client->getHostname());

        [$reply, $status, $response] = $client->invoke($method, $requestBranch->grpcArgument, $requestBranch->grpcDeserialize, $requestBranch->grpcMetadata, $requestBranch->grpcOptions);

        if (Result::ONGOING_STATUS == $status) {
            throw new OngingException();
        }
        if (Result::FAILURE_STATUS == $status) {
            throw new FailureException();
        }
        if ($status !== Result::OK_STATUS) {
            throw new RequestException($reply->serializeToString(), $status);
        }

        return [$reply, $status, $response];
    }

    protected function transferToRequest(array $body): DtmRequest {
        $request = new DtmRequest();
        isset($body['gid']) && $request->setGid($body['gid']);
        isset($body['trans_type']) && $request->setTransType($body['trans_type']);
        isset($body['custom_data']) && $request->setCustomedData($body['custom_data']);
        isset($body['bin_payloads']) && $request->setBinPayloads($body['bin_payloads']);
        isset($body['query_prepared']) && $request->setQueryPrepared($body['query_prepared']);
        isset($body['steps']) && $request->setSteps(json_encode($body['steps']));
        $dtmTransOptions = $this->transferToTransOptions($body);
        $dtmTransOptions && $request->setTransOptions($dtmTransOptions);
        return $request;
    }

    protected function transferToTransOptions(array $body): ?DtmTransOptions {
        $request = new DtmTransOptions();
        isset($body['wait_result']) && $request->setWaitResult($body['wait_result']);
        isset($body['timeout_to_fail']) && $request->setTimeoutToFail($body['timeout_to_fail']);
        isset($body['retry_interval']) && $request->setRetryInterval($body['retry_interval']);
        isset($body['passthrough_headers']) && $request->setPassthroughHeaders($body['passthrough_headers']);
        isset($body['branch_headers']) && $request->setBranchHeaders($body['branch_headers']);
        return $request;
    }

    protected function parseHostnameAndMethod(string $url): array {
        $path     = explode('/', $url);
        $hostname = $path[0];
        array_shift($path);
        $method = implode('/', $path);
        return [$hostname, $method];
    }

    protected function getDtmClient(): DtmGrpcClient {
        $server   = $this->config->get('dtm.server', '127.0.0.1');
        $port     = $this->config->get('dtm.port.grpc', 36790);
        $hostname = $server . ':' . $port;
        /**
         * @var DtmGrpcClient $client
         */
        try {
            $client = $this->grpcClientManager->getClient($hostname, "");
        } catch (\Exception $e) {
            return new DtmGrpcClient($hostname);
        }
        return $client;
    }
}