<?php

namespace App\Attribute;

use Attribute;

#[Attribute]
class Entity
{
    public function __construct(
        private readonly string $repositoryClass,
    )
    {
    }

    public function getRepository(): string
    {
        return $this->repositoryClass;
    }
}