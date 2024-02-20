<?php

namespace App\Repository;

use App\Enum\TagEnum;

class TagRepository
{
    public function findAll(): array
    {
        $tagsObject = TagEnum::cases();

        $tags = [];
        foreach ($tagsObject as $tag) {
            $tags[] = $tag->value;
        }
        return $tags;
    }
}