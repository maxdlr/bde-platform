<?php

namespace App\Routing;

class Route
{
    public function __construct(
        private string $uri,
        private string $name,
        private array  $httpMethod,
        private string $controllerClass,
        private string $controllerMethod
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

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getHttpMethod(): array
    {
        return $this->httpMethod;
    }

    public function getControllerClass(): string
    {
        return $this->controllerClass;
    }

    public function getControllerMethod(): string
    {
        return $this->controllerMethod;
    }


}