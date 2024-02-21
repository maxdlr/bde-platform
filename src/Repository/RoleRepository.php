<?php

namespace App\Repository;

use App\Enum\RoleEnum;

class RoleRepository
{
    public function findAll(): array
    {
        $rolesEnumCase = RoleEnum::cases();

        $roles = [];
        foreach ($rolesEnumCase as $role) {
            $roles[] = $role->value;
        }
        return $roles;
    }
}