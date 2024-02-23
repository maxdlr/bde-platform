<?php

namespace App\Attribute;

use Attribute;

#[Attribute]
readonly class AsEntity
{
    public function __construct(
        private string $repositoryClass,
    )
    {
    }

    public function getRepository(): string
    {
        return $this->repositoryClass;
    }
}