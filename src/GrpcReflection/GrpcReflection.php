<?php

namespace Xhtkyy\HyperfTools\GrpcReflection;

use Google\Protobuf\Internal\DescriptorPool;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Grpc\StatusCode;
use Hyperf\GrpcServer\Exception\GrpcException;
use Hyperf\HttpServer\Router\DispatcherFactory;
use Hyperf\HttpServer\Router\Handler;
use Xhtkyy\HyperfTools\App\ContainerInterface;
use Xhtkyy\HyperfTools\GrpcReflection\ServerReflection\FileDescriptorResponse;
use Xhtkyy\HyperfTools\GrpcReflection\ServerReflection\ListServiceResponse;
use Xhtkyy\HyperfTools\GrpcReflection\ServerReflection\ServerReflectionInterface;
use Xhtkyy\HyperfTools\GrpcReflection\ServerReflection\ServerReflectionRequest;
use Xhtkyy\HyperfTools\GrpcReflection\ServerReflection\ServerReflectionResponse;
use Xhtkyy\HyperfTools\GrpcReflection\ServerReflection\ServiceResponse;

class GrpcReflection implements ServerReflectionInterface
{

    protected ConfigInterface $config;

    protected DispatcherFactory $dispatcherFactory;

    protected array $servers = [];
    protected array $files = [];

    protected array $googleProto = [];

    public function __construct(protected ContainerInterface $container)
    {
        $this->config = $this->container->get(ConfigInterface::class);
        $this->dispatcherFactory = $this->container->get(DispatcherFactory::class);
        //获取服务
        $this->servers = $this->servers();
        $this->googleProto = [
            'google/protobuf/struct.proto',
            'google/protobuf/empty.proto',
            'google/protobuf/any.proto',
            'google/protobuf/timestamp.proto',
            'google/protobuf/duration.proto'
        ];
    }

    /**
     * @param ServerReflectionRequest $request
     * @return ServerReflectionResponse
     */
    public function serverReflectionInfo(ServerReflectionRequest $request): ServerReflectionResponse
    {
        // get gpb class pool
        $descriptorPool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();
        // new response
        $resp = new ServerReflectionResponse();
        $resp->setOriginalRequest($request);
        switch ($request->getMessageRequest()) {
            case "list_services":
                $servers = [];
                foreach ($this->servers as $server => $files) {
                    $servers[] = (new ServiceResponse())->setName($server);
                }
                $resp->setListServicesResponse(
                    (new ListServiceResponse())->setService($servers)
                );
                break;
            case "file_containing_symbol":
                $symbol = $request->getFileContainingSymbol();
                $file = $descriptorPool->getContentByServerName($symbol);
                if (!empty($file)) {
                    // set file
                    $resp->setFileDescriptorResponse(
                        (new FileDescriptorResponse())->setFileDescriptorProto(array_merge(
                                [$file], $this->googleProto($descriptorPool) //with Google proto
                            )
                        )
                    );
                }
                break;
            case "file_by_filename":
                $fileName = $request->getFileByFilename();
                $file = $descriptorPool->getContentByProtoName($fileName);
                if (!empty($fileName)) {
                    $resp->setFileDescriptorResponse(
                        (new FileDescriptorResponse())->setFileDescriptorProto([$file])
                    );
                    break;
                }
                throw new GrpcException("{$fileName} not found", StatusCode::NOT_FOUND);
        }
        return $resp;
    }

    /**
     * get google proto
     * @param DescriptorPool $descriptorPool
     * @return array
     */
    private function googleProto(DescriptorPool $descriptorPool): array
    {
        $temp = [];
        foreach ($this->googleProto as $proto) {
            if ($file = $descriptorPool->getContentByProtoName($proto)) $temp[] = $file;
        }
        return $temp;
    }

    /**
     * get server by router
     * @return array
     */
    private function servers(): array
    {
        $routes = $this->dispatcherFactory
            ->getRouter($this->config->get("kyy_tools.register.server_name", "grpc"))
            ->getData();
        $services = [];
        /**
         * @var Handler $handler
         */
        if (!empty($routes) && isset($routes[0]['POST'])) foreach ($routes[0]['POST'] as $handler) {
            $service = current(explode("/", trim($handler->route, "/")));
            if (!isset($services[$service])) {
                $services[$service] = '';
            }
        }
        return $services;
    }
}