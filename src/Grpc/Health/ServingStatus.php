<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: protoc/health.proto

namespace Xhtkyy\HyperfTools\Grpc\Health;

use UnexpectedValueException;

/**
 * Protobuf type <code>grpc.health.v1.ServingStatus</code>
 */
class ServingStatus
{
    /**
     * Generated from protobuf enum <code>UNKNOWN = 0;</code>
     */
    const UNKNOWN = 0;
    /**
     * Generated from protobuf enum <code>SERVING = 1;</code>
     */
    const SERVING = 1;
    /**
     * Generated from protobuf enum <code>NOT_SERVING = 2;</code>
     */
    const NOT_SERVING = 2;
    /**
     * Used only by the Watch method.
     *
     * Generated from protobuf enum <code>SERVICE_UNKNOWN = 3;</code>
     */
    const SERVICE_UNKNOWN = 3;

    private static $valueToName = [
        self::UNKNOWN => 'UNKNOWN',
        self::SERVING => 'SERVING',
        self::NOT_SERVING => 'NOT_SERVING',
        self::SERVICE_UNKNOWN => 'SERVICE_UNKNOWN',
    ];

    public static function name($value)
    {
        if (!isset(self::$valueToName[$value])) {
            throw new UnexpectedValueException(sprintf(
                    'Enum %s has no name defined for value %s', __CLASS__, $value));
        }
        return self::$valueToName[$value];
    }


    public static function value($name)
    {
        $const = __CLASS__ . '::' . strtoupper($name);
        if (!defined($const)) {
            throw new UnexpectedValueException(sprintf(
                    'Enum %s has no value defined for name %s', __CLASS__, $name));
        }
        return constant($const);
    }
}

