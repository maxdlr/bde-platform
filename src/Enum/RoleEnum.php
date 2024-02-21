<?php

namespace App\Enum;

use App\Attribute\AsEntity;
use App\Repository\RoleRepository;

#[AsEntity(repositoryClass: RoleRepository::class)]
enum RoleEnum: string
{
    case ROLE_ADMIN = 'admin';
    case ROLE_MANAGER = 'manager';
    case ROLE_STUDENT = 'student';
}
