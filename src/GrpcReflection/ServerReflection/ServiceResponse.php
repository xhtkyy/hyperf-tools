<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: reflection.proto

namespace Xhtkyy\HyperfTools\GrpcReflection\ServerReflection;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * The information of a single service used by ListServiceResponse to answer
 * list_services request.
 *
 * Generated from protobuf message <code>grpc.reflection.v1alpha.ServiceResponse</code>
 */
class ServiceResponse extends \Google\Protobuf\Internal\Message
{
    /**
     * Full name of a registered service, including its package name. The format
     * is <package>.<service>
     *
     * Generated from protobuf field <code>string name = 1;</code>
     */
    protected $name = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $name
     *           Full name of a registered service, including its package name. The format
     *           is <package>.<service>
     * }
     */
    public function __construct($data = NULL) {
        \Xhtkyy\HyperfTools\GrpcReflection\GPBMetadata\Reflection::initOnce();
        parent::__construct($data);
    }

    /**
     * Full name of a registered service, including its package name. The format
     * is <package>.<service>
     *
     * Generated from protobuf field <code>string name = 1;</code>
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Full name of a registered service, including its package name. The format
     * is <package>.<service>
     *
     * Generated from protobuf field <code>string name = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setName($var)
    {
        GPBUtil::checkString($var, True);
        $this->name = $var;

        return $this;
    }

}

