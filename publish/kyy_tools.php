<?php
return [
    // 服务注册
    "register" => [
        "server_name" => "grpc", // 对应服务名称 默认grpc
        "driver_name" => "nacos", // 支持 nacos、consul4grpc、consul
        "algo" => "round-robin", // 负载算法 支持 random、round-robin、weighted-random、weighted-round-robin 默认round-robin
    ],
    "trace" => (bool)env("TRACER_ENABLE", true), //是否开启追踪
    "reflection" => [
        "enable" => env("REFLECTION_ENABLE", true), //是否开启服务反射 默认是true
        "path" => env("REFLECTION_PATH", BASE_PATH . "/App/Grpc/GPBMetadata"), //反射路径 指protoc生成的GPBMetadata文件路径
        "base_file" => []
    ],
    "service_alias" => []
];