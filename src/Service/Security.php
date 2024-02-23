<?php

namespace App\Service;

use App\Enum\RoleEnum;

class Security
{
    static public function getForbiddenRoutes(RoleEnum $roleEnum): array
    {
        $role = $roleEnum;

        return match ($role) {
            RoleEnum::ROLE_MANAGER => [
                '/admin/user/edit',
                '/admin/user/new',
                '/admin/user/delete',
            ],
            RoleEnum::ROLE_STUDENT => [
                '/admin',
            ],
        };
    }
}