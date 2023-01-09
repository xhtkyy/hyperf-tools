<?php

namespace Xhtkyy\HyperfTools\Casbin\src;

use Hyperf\Grpc\StatusCode;
use Xhtkyy\HyperfTools\Casbin\CasbinInterface;
use Xhtkyy\HyperfTools\Casbin\src\GrpcClient\BoolReply;
use Xhtkyy\HyperfTools\Casbin\src\GrpcClient\CasbinClient;
use Xhtkyy\HyperfTools\Casbin\src\GrpcClient\Permissions;
use Xhtkyy\HyperfTools\Casbin\src\GrpcClient\PermissionUserReq;
use Xhtkyy\HyperfTools\Casbin\src\GrpcClient\PoliciesReq;
use Xhtkyy\HyperfTools\Casbin\src\GrpcClient\RoleReq;
use Xhtkyy\HyperfTools\Casbin\src\GrpcClient\RolesReply;
use Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserReq;
use Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UserRoleReq;
use Xhtkyy\HyperfTools\Casbin\src\GrpcClient\UsersReply;

class Casbin implements CasbinInterface {

    public function __construct(protected CasbinClient $casbinClient) {
    }

    public function getRolesForUser(string $user, string $domain = ""): array {
        $userReq = (new UserReq)
            ->setUser($user)
            ->setDomain($domain);
        /**
         * @var RolesReply $reply
         */
        list($reply, $status) = $this->casbinClient->getRolesForUser($userReq);
        if ($status == StatusCode::OK) return iterator_to_array($reply->getRole());
        // todo log
        return [];
    }

    public function getUsersForRole(string $role, string $domain = ""): array {
        $roleReq = (new RoleReq())
            ->setRole($role)
            ->setDomain($domain);
        /**
         * @var UsersReply $reply
         */
        list($reply, $status) = $this->casbinClient->getUsersForRole($roleReq);
        if ($status == StatusCode::OK) return iterator_to_array($reply->getUser());
        // todo log
        return [];
    }

    public function hasRoleForUser(string $user, string $role, string $domain = ""): bool {
        $req = (new UserRoleReq())
            ->setRole($role)
            ->setUser($user)
            ->setDomain($domain);
        /**
         * @var BoolReply $reply
         */
        list($reply, $status) = $this->casbinClient->hasRoleForUser($req);
        if ($status == StatusCode::OK) return $reply->getResult();
        // todo log
        return false;
    }

    public function addRoleForUser(string $user, string $role, string $domain = ""): bool {
        $req = (new UserRoleReq())
            ->setRole($role)
            ->setUser($user)
            ->setDomain($domain);
        /**
         * @var BoolReply $reply
         */
        list($reply, $status) = $this->casbinClient->addRoleForUser($req);
        if ($status == StatusCode::OK) return $reply->getResult();
        // todo log
        return false;
    }

    public function deleteRoleForUser(string $user, string $role, string $domain = ""): bool {
        $req = (new UserRoleReq())
            ->setRole($role)
            ->setUser($user)
            ->setDomain($domain);
        /**
         * @var BoolReply $reply
         */
        list($reply, $status) = $this->casbinClient->deleteRoleForUser($req);
        if ($status == StatusCode::OK) return $reply->getResult();
        // todo log
        return false;
    }

    public function deleteRolesForUser(string $user, string $domain = ""): bool {
        $req = (new UserReq())
            ->setUser($user)
            ->setDomain($domain);
        /**
         * @var BoolReply $reply
         */
        list($reply, $status) = $this->casbinClient->deleteRolesForUser($req);
        if ($status == StatusCode::OK) return $reply->getResult();
        // todo log
        return false;
    }

    public function deleteUser(string $user): bool {
        $req = (new UserReq())->setUser($user);
        /**
         * @var BoolReply $reply
         */
        list($reply, $status) = $this->casbinClient->deleteUser($req);
        if ($status == StatusCode::OK) return $reply->getResult();
        // todo log
        return false;
    }

    public function deleteRole(string $role): bool {
        $req = (new RoleReq())->setRole($role);
        /**
         * @var BoolReply $reply
         */
        list($reply, $status) = $this->casbinClient->deleteRole($req);
        if ($status == StatusCode::OK) return $reply->getResult();
        // todo log
        return false;
    }

    public function deletePermission(string ...$permission): bool {
        $req = (new Permissions())->setPermission($permission);
        /**
         * @var BoolReply $reply
         */
        list($reply, $status) = $this->casbinClient->deletePermission($req);
        if ($status == StatusCode::OK) return $reply->getResult();
        // todo log
        return false;
    }

    public function addPermissionForUser(string $user, string ...$permission): bool {
        $req = (new PermissionUserReq())
            ->setUser($user)
            ->setPermission($permission);
        /**
         * @var BoolReply $reply
         */
        list($reply, $status) = $this->casbinClient->addPermissionForUser($req);
        if ($status == StatusCode::OK) return $reply->getResult();
        // todo log
        return false;
    }

    public function deletePermissionForUser(string $user, string ...$permission): bool {
        $req = (new PermissionUserReq())
            ->setUser($user)
            ->setPermission($permission);
        /**
         * @var BoolReply $reply
         */
        list($reply, $status) = $this->casbinClient->deletePermissionForUser($req);
        if ($status == StatusCode::OK) return $reply->getResult();
        // todo log
        return false;
    }

    public function deletePermissionsForUser(string $user): bool {
        $req = (new UserReq())->setUser($user);
        /**
         * @var BoolReply $reply
         */
        list($reply, $status) = $this->casbinClient->deletePermissionsForUser($req);
        if ($status == StatusCode::OK) return $reply->getResult();
        // todo log
        return false;
    }

    public function hasPermissionForUser(string $user, string ...$permission): bool {
        $req = (new PermissionUserReq())
            ->setUser($user)
            ->setPermission($permission);
        /**
         * @var BoolReply $reply
         */
        list($reply, $status) = $this->casbinClient->hasPermissionForUser($req);
        if ($status == StatusCode::OK) return $reply->getResult();
        // todo log
        return false;
    }

    public function getPermissionsForUser(string $user): array {
        $req = (new UserReq())->setUser($user);
        /**
         * @var Permissions $reply
         */
        list($reply, $status) = $this->casbinClient->getPermissionsForUser($req);
        if ($status == StatusCode::OK) return iterator_to_array($reply->getPermission());
        // todo log
        return [];
    }

    public function getImplicitRolesForUser(string $user, string $domain = ""): array {
        $req = (new UserReq())
            ->setDomain($domain)
            ->setUser($user);
        /**
         * @var RolesReply $reply
         */
        list($reply, $status) = $this->casbinClient->getImplicitRolesForUser($req);
        if ($status == StatusCode::OK) return iterator_to_array($reply->getRole());
        // todo log
        return [];
    }

    public function getImplicitPermissionsForUser(string $user, string $domain = ""): array {
        $req = (new UserReq())
            ->setDomain($domain)
            ->setUser($user);
        /**
         * @var Permissions $reply
         */
        list($reply, $status) = $this->casbinClient->getImplicitPermissionsForUser($req);
        if ($status == StatusCode::OK) return iterator_to_array($reply->getPermission());
        // todo log
        return [];
    }

    public function getImplicitUsersForPermission(string ...$permission): array {
        $req = (new Permissions())
            ->setPermission($permission);
        /**
         * @var UsersReply $reply
         */
        list($reply, $status) = $this->casbinClient->getImplicitUsersForPermission($req);
        if ($status == StatusCode::OK) return iterator_to_array($reply->getUser());
        // todo log
        return [];
    }

    public function getUsersForRoleInDomain(string $role, string $domain): array {
        $req = (new RoleReq())
            ->setRole($role)
            ->setDomain($domain);
        /**
         * @var UsersReply $reply
         */
        list($reply, $status) = $this->casbinClient->getUsersForRoleInDomain($req);
        if ($status == StatusCode::OK) return iterator_to_array($reply->getUser());
        // todo log
        return [];
    }

    public function getRolesForUserInDomain(string $user, string $domain): array {
        $req = (new UserReq())
            ->setUser($user)
            ->setDomain($domain);
        /**
         * @var RolesReply $reply
         */
        list($reply, $status) = $this->casbinClient->getRolesForUserInDomain($req);
        if ($status == StatusCode::OK) return iterator_to_array($reply->getRole());
        // todo log
        return [];
    }

    public function getPermissionsForUserInDomain(string $user, string $domain): array {
        $req = (new UserReq())
            ->setUser($user)
            ->setDomain($domain);
        /**
         * @var Permissions $reply
         */
        list($reply, $status) = $this->casbinClient->getPermissionsForUserInDomain($req);
        if ($status == StatusCode::OK) return iterator_to_array($reply->getPermission());
        // todo log
        return [];
    }

    public function addRoleForUserInDomain(string $user, string $role, string $domain): bool {
        $req = (new UserRoleReq())
            ->setUser($user)
            ->setRole($role)
            ->setDomain($domain);
        /**
         * @var BoolReply $reply
         */
        list($reply, $status) = $this->casbinClient->addRoleForUserInDomain($req);
        if ($status == StatusCode::OK) return $reply->getResult();
        // todo log
        return false;
    }

    public function deleteRoleForUserInDomain(string $user, string $role, string $domain): bool {
        $req = (new UserRoleReq())
            ->setUser($user)
            ->setRole($role)
            ->setDomain($domain);
        /**
         * @var BoolReply $reply
         */
        list($reply, $status) = $this->casbinClient->deleteRoleForUserInDomain($req);
        if ($status == StatusCode::OK) return $reply->getResult();
        // todo log
        return false;
    }

    public function addPolicies(array $policies): bool {
        $req = (new PoliciesReq())
            ->setPolicies($policies);
        /**
         * @var BoolReply $reply
         */
        list($reply, $status) = $this->casbinClient->addPolicies($req);
        if ($status == StatusCode::OK) return $reply->getResult();
        // todo log
        return false;
    }

    public function addPermissionForUserInDomain(string $user, string $domain, string ...$permission): bool {
        $req = (new PermissionUserReq())
            ->setUser($user)
            ->setDomain($domain)
            ->setPermission($permission);
        /**
         * @var BoolReply $reply
         */
        list($reply, $status) = $this->casbinClient->addPermissionForUser($req);
        if ($status == StatusCode::OK) return $reply->getResult();
        // todo log
        return false;
    }

    public function deletePermissionForUserInDomain(string $user, string $domain, string ...$permission): bool {
        $req = (new PermissionUserReq())
            ->setUser($user)
            ->setDomain($domain)
            ->setPermission($permission);
        /**
         * @var BoolReply $reply
         */
        list($reply, $status) = $this->casbinClient->deletePermissionForUser($req);
        if ($status == StatusCode::OK) return $reply->getResult();
        // todo log
        return false;
    }

    public function hasPermissionForUserInDomain(string $user, string $domain, string ...$permission): bool {
        $req = (new PermissionUserReq())
            ->setUser($user)
            ->setDomain($domain)
            ->setPermission($permission);
        /**
         * @var BoolReply $reply
         */
        list($reply, $status) = $this->casbinClient->hasPermissionForUser($req);
        if ($status == StatusCode::OK) return $reply->getResult();
        // todo log
        return false;
    }
}