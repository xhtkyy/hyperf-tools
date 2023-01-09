<?php

namespace Xhtkyy\HyperfTools\Casbin;

interface CasbinInterface {
    public function getRolesForUser(string $user, string $domain = ""): array;

    public function getUsersForRole(string $role, string $domain = ""): array;

    public function hasRoleForUser(string $user, string $role, string $domain = ""): bool;

    public function addRoleForUser(string $user, string $role, string $domain = ""): bool;

    public function deleteRoleForUser(string $user, string $role, string $domain = ""): bool;

    public function deleteRolesForUser(string $user, string $domain = ""): bool;

    public function deleteUser(string $user): bool;

    public function deleteRole(string $role): bool;

    public function deletePermission(string ...$permission): bool;

    public function addPermissionForUser(string $user, string $domain, string ...$permission): bool;

    public function deletePermissionForUser(string $user, string $domain, string ...$permission): bool;

    public function deletePermissionsForUser(string $user): bool;

    public function hasPermissionForUser(string $user, string $domain, string ...$permission): bool;

    public function getPermissionsForUser(string $user): array;

    public function getImplicitRolesForUser(string $user, string $domain = ""): array;

    public function getImplicitPermissionsForUser(string $user, string $domain = ""): array;

    public function getImplicitUsersForPermission(string ...$permission): array;

    public function getUsersForRoleInDomain(string $role, string $domain): array;

    public function getRolesForUserInDomain(string $user, string $domain): array;

    public function getPermissionsForUserInDomain(string $user, string $domain): array;

    public function addRoleForUserInDomain(string $user, string $role, string $domain): bool;

    public function deleteRoleForUserInDomain(string $user, string $role, string $domain): bool;

    public function addPolicies(array $policies): bool;

    public function addPermissionForUserInDomain(string $user, string $domain, string ...$permission): bool;

    public function deletePermissionForUserInDomain(string $user, string $domain, string ...$permission): bool;

    public function hasPermissionForUserInDomain(string $user, string $domain, string ...$permission): bool;

}