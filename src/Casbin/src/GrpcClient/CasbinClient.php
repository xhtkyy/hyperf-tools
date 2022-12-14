<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Xhtkyy\HyperfTools\Casbin\src\GrpcClient;

/**
 */
class CasbinClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserReq $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function getRolesForUser(\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserReq $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/casbin.Casbin/getRolesForUser',
        $argument,
        ['\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\RolesReply', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Xhtkyy\HyperfTools\Casbin\src\GrpcClient\RoleReq $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function getUsersForRole(\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\RoleReq $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/casbin.Casbin/getUsersForRole',
        $argument,
        ['\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UsersReply', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserRoleReq $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function hasRoleForUser(\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserRoleReq $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/casbin.Casbin/hasRoleForUser',
        $argument,
        ['\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\BoolReply', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserRoleReq $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function addRoleForUser(\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserRoleReq $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/casbin.Casbin/addRoleForUser',
        $argument,
        ['\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\BoolReply', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserRoleReq $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function deleteRoleForUser(\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserRoleReq $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/casbin.Casbin/deleteRoleForUser',
        $argument,
        ['\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\BoolReply', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserReq $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function deleteRolesForUser(\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserReq $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/casbin.Casbin/deleteRolesForUser',
        $argument,
        ['\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\BoolReply', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserReq $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function deleteUser(\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserReq $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/casbin.Casbin/deleteUser',
        $argument,
        ['\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\BoolReply', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Xhtkyy\HyperfTools\Casbin\src\GrpcClient\RoleReq $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function deleteRole(\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\RoleReq $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/casbin.Casbin/deleteRole',
        $argument,
        ['\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\BoolReply', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Xhtkyy\HyperfTools\Casbin\src\GrpcClient\Permissions $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function deletePermission(\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\Permissions $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/casbin.Casbin/deletePermission',
        $argument,
        ['\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\BoolReply', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Xhtkyy\HyperfTools\Casbin\src\GrpcClient\PermissionUserReq $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function addPermissionForUser(\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\PermissionUserReq $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/casbin.Casbin/addPermissionForUser',
        $argument,
        ['\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\BoolReply', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Xhtkyy\HyperfTools\Casbin\src\GrpcClient\PermissionUserReq $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function deletePermissionForUser(\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\PermissionUserReq $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/casbin.Casbin/deletePermissionForUser',
        $argument,
        ['\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\BoolReply', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserReq $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function deletePermissionsForUser(\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserReq $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/casbin.Casbin/deletePermissionsForUser',
        $argument,
        ['\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\BoolReply', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserReq $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function getPermissionsForUser(\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserReq $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/casbin.Casbin/getPermissionsForUser',
        $argument,
        ['\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\Permissions', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Xhtkyy\HyperfTools\Casbin\src\GrpcClient\PermissionUserReq $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function hasPermissionForUser(\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\PermissionUserReq $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/casbin.Casbin/hasPermissionForUser',
        $argument,
        ['\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\BoolReply', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserReq $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function getImplicitRolesForUser(\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserReq $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/casbin.Casbin/getImplicitRolesForUser',
        $argument,
        ['\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\RolesReply', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserReq $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function getImplicitPermissionsForUser(\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserReq $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/casbin.Casbin/getImplicitPermissionsForUser',
        $argument,
        ['\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\Permissions', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Xhtkyy\HyperfTools\Casbin\src\GrpcClient\Permissions $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function getImplicitUsersForPermission(\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\Permissions $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/casbin.Casbin/getImplicitUsersForPermission',
        $argument,
        ['\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UsersReply', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Xhtkyy\HyperfTools\Casbin\src\GrpcClient\RoleReq $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function getUsersForRoleInDomain(\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\RoleReq $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/casbin.Casbin/getUsersForRoleInDomain',
        $argument,
        ['\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UsersReply', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserReq $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function getRolesForUserInDomain(\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserReq $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/casbin.Casbin/getRolesForUserInDomain',
        $argument,
        ['\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\RolesReply', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserReq $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function getPermissionsForUserInDomain(\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserReq $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/casbin.Casbin/getPermissionsForUserInDomain',
        $argument,
        ['\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\Permissions', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserRoleReq $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function addRoleForUserInDomain(\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserRoleReq $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/casbin.Casbin/addRoleForUserInDomain',
        $argument,
        ['\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\BoolReply', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserRoleReq $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function deleteRoleForUserInDomain(\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserRoleReq $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/casbin.Casbin/deleteRoleForUserInDomain',
        $argument,
        ['\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\BoolReply', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Xhtkyy\HyperfTools\Casbin\src\GrpcClient\PoliciesReq $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function addPolicies(\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\PoliciesReq $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/casbin.Casbin/addPolicies',
        $argument,
        ['\Xhtkyy\HyperfTools\Casbin\src\GrpcClient\BoolReply', 'decode'],
        $metadata, $options);
    }

}
