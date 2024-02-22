<?php

namespace App\Service;

use App\Enum\RoleEnum;

class Security
{
    static public function getAllowedRoutes(RoleEnum $roleEnum): array
    {
        $role = $roleEnum;

        return match ($role) {
            RoleEnum::ROLE_ADMIN => [
                '/admin',
                '/admin/dashboard',
                '/admin/event/index',
                '/admin/event/new',
                '/admin/event/edit',
                '/admin/event/delete',
                '/admin/user/index',
                '/admin/user/new',
                '/admin/user/edit',
                '/admin/user/delete'
            ],
            RoleEnum::ROLE_MANAGER => [
                '/admin',
                '/admin/dashboard',
                '/admin/event/index',
                '/admin/event/new',
                '/admin/event/edit',
                '/admin/event/delete',
                '/admin/user/index'
            ],
            RoleEnum::ROLE_STUDENT => [],
        };
    }
}