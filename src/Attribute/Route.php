<?php

namespace App\Attribute;

use Attribute;

#[Attribute]
class Route
{
    public function __construct(
        private string $uri,
        private string $name,
        private array  $httpMethod,
    )
    {
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHttpMethod(): array
    {
        return $this->httpMethod;
    }
}