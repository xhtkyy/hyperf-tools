<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: src/Casbin/proto/casbin.proto

namespace Xhtkyy\HyperfTools\Casbin\src\GrpcClient;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>casbin.PoliciesReq</code>
 */
class PoliciesReq extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>repeated string policies = 1;</code>
     */
    private $policies;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string[]|\Google\Protobuf\Internal\RepeatedField $policies
     * }
     */
    public function __construct($data = NULL) {
        \Xhtkyy\HyperfTools\Casbin\src\GrpcClient\GPBMetadata\Casbin::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>repeated string policies = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getPolicies()
    {
        return $this->policies;
    }

    /**
     * Generated from protobuf field <code>repeated string policies = 1;</code>
     * @param string[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setPolicies($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::STRING);
        $this->policies = $arr;

        return $this;
    }

}
