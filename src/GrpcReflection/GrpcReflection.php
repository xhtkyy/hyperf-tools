<?php

namespace Xhtkyy\HyperfTools\GrpcReflection;

use Hyperf\Contract\ConfigInterface;
use Hyperf\HttpServer\Router\DispatcherFactory;
use Hyperf\HttpServer\Router\Handler;
use Hyperf\Utils\Str;
use Xhtkyy\HyperfTools\App\ContainerInterface;
use Xhtkyy\HyperfTools\GrpcReflection\ServerReflection\FileDescriptorResponse;
use Xhtkyy\HyperfTools\GrpcReflection\ServerReflection\ListServiceResponse;
use Xhtkyy\HyperfTools\GrpcReflection\ServerReflection\ServerReflectionInterface;
use Xhtkyy\HyperfTools\GrpcReflection\ServerReflection\ServerReflectionRequest;
use Xhtkyy\HyperfTools\GrpcReflection\ServerReflection\ServerReflectionResponse;
use Xhtkyy\HyperfTools\GrpcReflection\ServerReflection\ServiceResponse;

class GrpcReflection implements ServerReflectionInterface {

    protected ConfigInterface $config;

    protected DispatcherFactory $dispatcherFactory;

    protected array $servers = [];
    protected array $files = [];

    public function __construct(protected ContainerInterface $container) {
        $this->config            = $this->container->get(ConfigInterface::class);
        $this->dispatcherFactory = $this->container->get(DispatcherFactory::class);
        //获取服务
        $this->servers = $this->servers();
    }

    /**
     * @param ServerReflectionRequest $request
     * @return ServerReflectionResponse
     */
    public function serverReflectionInfo(ServerReflectionRequest $request): ServerReflectionResponse {
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
                list($files, $symbol) = [[], $request->getFileContainingSymbol()];
                if (isset($this->servers[$symbol])) foreach ($this->servers[$symbol] as $filePath) {
                    $files[] = $this->protoFileContent($filePath);
                }
                // 设置到响应
                $resp->setFileDescriptorResponse(
                    (new FileDescriptorResponse())->setFileDescriptorProto($files)
                );
                break;
        }
        return $resp;
    }

    private function servers(): array {
        $routes   = $this->dispatcherFactory
            ->getRouter($this->config->get("kyy_tools.register.server_name", "grpc"))
            ->getData();
        $services = [];
        /**
         * @var Handler $handler
         */
        if (!empty($routes) && isset($routes[0]['POST'])) foreach ($routes[0]['POST'] as $handler) {
            $service = current(explode("/", trim($handler->route, "/")));
            if (!isset($services[$service])) {
                $files = $this->protoFilePaths($service);
                !empty($files) && $services[$service] = $files;
            }
        }
        return $services;
    }

    private function protoFilePaths(string $serverName): array {
        $protoFilePaths = [];
        $basePath       = $this->config->get("kyy_tools.reflection.path", "app/Grpc/GPBMetadata");
        foreach (explode(".", $serverName) as $item) {
            $file = $basePath . "/" . Str::studly($item) . ".php";
            if (!in_array($file, $protoFilePaths) && file_exists($file)) {
                $protoFilePaths[] = $file;
            }
        }
        return $protoFilePaths;
    }

    private function protoFileContent(string $filePath) {
        if (!isset($this->files[$filePath])) {
            // 读取
            $file = file_get_contents($filePath);
            // 获取proto生成的内容
            $start                  = strpos($file, "'", 121) + 1;
            $end                    = strpos($file, "'", $start);
            $file                   = substr($file, $start, $end - $start);
            $file                   = str_replace('\\\\', "\\", $file);
            $file                   = str_replace(substr($file, 1, 3), "", $file);
            $this->files[$filePath] = $file;
        }
        return $this->files[$filePath];
    }
}