<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: protoc/reflection.proto

namespace Xhtkyy\HyperfTools\GrpcReflection\ServerReflection;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * A list of extension numbers sent by the server answering
 * all_extension_numbers_of_type request.
 *
 * Generated from protobuf message <code>grpc.reflection.v1alpha.ExtensionNumberResponse</code>
 */
class ExtensionNumberResponse extends \Google\Protobuf\Internal\Message
{
    /**
     * Full name of the base type, including the package name. The format
     * is <package>.<type>
     *
     * Generated from protobuf field <code>string base_type_name = 1;</code>
     */
    protected $base_type_name = '';
    /**
     * Generated from protobuf field <code>repeated int32 extension_number = 2;</code>
     */
    private $extension_number;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $base_type_name
     *           Full name of the base type, including the package name. The format
     *           is <package>.<type>
     *     @type int[]|\Google\Protobuf\Internal\RepeatedField $extension_number
     * }
     */
    public function __construct($data = NULL) {
        \Xhtkyy\HyperfTools\GrpcReflection\GPBMetadata\Reflection::initOnce();
        parent::__construct($data);
    }

    /**
     * Full name of the base type, including the package name. The format
     * is <package>.<type>
     *
     * Generated from protobuf field <code>string base_type_name = 1;</code>
     * @return string
     */
    public function getBaseTypeName()
    {
        return $this->base_type_name;
    }

    /**
     * Full name of the base type, including the package name. The format
     * is <package>.<type>
     *
     * Generated from protobuf field <code>string base_type_name = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setBaseTypeName($var)
    {
        GPBUtil::checkString($var, True);
        $this->base_type_name = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>repeated int32 extension_number = 2;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getExtensionNumber()
    {
        return $this->extension_number;
    }

    /**
     * Generated from protobuf field <code>repeated int32 extension_number = 2;</code>
     * @param int[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setExtensionNumber($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::INT32);
        $this->extension_number = $arr;

        return $this;
    }

}

