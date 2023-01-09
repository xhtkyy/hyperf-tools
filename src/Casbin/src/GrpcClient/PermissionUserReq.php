<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: src/Casbin/proto/casbin.proto

namespace Xhtkyy\HyperfTools\Casbin\src\GrpcClient;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>casbin.PermissionUserReq</code>
 */
class PermissionUserReq extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string user = 1;</code>
     */
    protected $user = '';
    /**
     * Generated from protobuf field <code>string domain = 2;</code>
     */
    protected $domain = '';
    /**
     * Generated from protobuf field <code>repeated string permission = 3;</code>
     */
    private $permission;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $user
     *     @type string $domain
     *     @type string[]|\Google\Protobuf\Internal\RepeatedField $permission
     * }
     */
    public function __construct($data = NULL) {
        \Xhtkyy\HyperfTools\Casbin\src\GrpcClient\GPBMetadata\Casbin::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string user = 1;</code>
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Generated from protobuf field <code>string user = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setUser($var)
    {
        GPBUtil::checkString($var, True);
        $this->user = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string domain = 2;</code>
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Generated from protobuf field <code>string domain = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setDomain($var)
    {
        GPBUtil::checkString($var, True);
        $this->domain = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>repeated string permission = 3;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * Generated from protobuf field <code>repeated string permission = 3;</code>
     * @param string[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setPermission($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::STRING);
        $this->permission = $arr;

        return $this;
    }

}

