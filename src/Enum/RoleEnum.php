<?php

namespace App\Enum;

use App\Attribute\AsEntity;
use App\Repository\RoleRepository;

#[AsEntity(repositoryClass: RoleRepository::class)]
enum RoleEnum: string
{
    case TAG_SOIREE = 'admin';
    case TAG_CULTURE = 'manager';
    case TAG_GAMING = 'student';
}
