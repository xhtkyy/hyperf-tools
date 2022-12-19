<?php
return [
    "consul" => [
        "hostname"               => env("CONSUL_HOST"),
        "register_grpc_services" => [
            // 需要注册的服务名称
        ]
    ]
];