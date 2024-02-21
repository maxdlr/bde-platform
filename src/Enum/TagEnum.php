<?php

namespace App\Enum;

use App\Attribute\AsEntity;
use App\Repository\TagRepository;

#[AsEntity(repositoryClass: TagRepository::class)]
enum TagEnum: string
{
    case TAG_SOIREE = 'soirée';
    case TAG_CULTURE = 'culture';
    case TAG_GAMING = 'gaming';
    case TAG_SEJOUR = 'séjour';
    case TAG_SPORT = 'sport';
    case TAG_ATELIER = 'atelier';
    case TAG_HACKATHON = 'hackathon';
}
