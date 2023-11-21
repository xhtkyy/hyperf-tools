<?php
return [
    // 服务注册
    "register" => [
        "server_name" => "grpc", // 对应服务名称 默认grpc
        "driver_name" => "nacos-grpc", // 支持 nacos、nacos-grpc、consul
        "algo" => "round-robin", // 负载算法 支持 random、round-robin、weighted-random、weighted-round-robin 默认round-robin
    ],
    "trace" => (bool)\Hyperf\Support\env("TRACER_ENABLE", true), //是否开启追踪
    "reflection" => [
        "enable" => \Hyperf\Support\env("REFLECTION_ENABLE", true), //是否开启服务反射 默认是true
        "path" => \Hyperf\Support\env("REFLECTION_PATH", BASE_PATH . "/app/Grpc/GPBMetadata"), //反射路径 指protoc生成的GPBMetadata文件路径
        "base_file" => []
    ],
    "service_alias" => []
];