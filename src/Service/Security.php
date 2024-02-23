<?php

namespace App\Service;

use App\Enum\RoleEnum;

class Security
{
    static public function getAllowedRoutes(RoleEnum $roleEnum): array
    {
        $role = $roleEnum;

        return match ($role) {
            RoleEnum::ROLE_MANAGER => [
                '/admin/event/index',
                '/admin/dashboard',
                '/admin/event/new',
                '/admin/event/edit',
                '/admin/event/delete',
                '/admin/user/index',
                ...self::getAllowedRoutes(RoleEnum::ROLE_STUDENT)
            ],
            RoleEnum::ROLE_STUDENT => [
                '/user/new',
                '/user/login',
                '/user/logout',
                '/user/dashboard',
                '/user/validate',
                '/event/show',
                '/event/new/interested',
                '/event/delete/interested',
                '/event/new/participant',
                '/event/delete/participant',
            ],
        };
    }
}