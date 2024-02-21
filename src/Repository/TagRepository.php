<?php

namespace App\Repository;

use App\Enum\TagEnum;

class TagRepository
{
    public function findAll(): array
    {
        $tagsEnumCase = TagEnum::cases();

        $tags = [];
        foreach ($tagsEnumCase as $tag) {
            $tags[] = $tag->value;
        }
        return $tags;
    }
}