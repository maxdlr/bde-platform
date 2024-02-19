<?php

namespace App\DependencyInjection;

use Override;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private array $services = [];
    #[Override] public function get(string $id)
    {
        if (!$this->has($id)) {
            throw new ServiceNotFoundException("Service $id not found.");
        }
        return $this->services[$id];
    }

    #[Override] public function has(string $id): bool
    {
        return array_key_exists($id, $this->services);
    }

    /**
     * @throws ServiceExistsException
     */
    public function set(string $id, object $instance): self
    {
        if ($this->has($id))
            throw new ServiceExistsException("Service $id already exists.");

        $this->services[$id] = $instance;

        return $this;
    }

}