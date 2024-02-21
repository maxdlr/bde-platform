<?php

namespace App\Mapping\User;

use App\Entity\User;
use App\Mapping\DTO;
use App\Mapping\DTOInterface;
use Exception;

class UserDTO extends DTO implements DTOInterface
{
    private array $from;

    public function config(array $from): static
    {
        $this->from = $from;
        return $this;
    }

    public function process(): object
    {
        if (!assert(is_array($this->from)))
            throw new Exception('Wrong type, this is supposed to be an array');

        $user = new User;

        $user
            ->setId($this->from['id'])
            ->setFirstname($this->from['firstname'])
            ->setName($this->from['name'])
            ->setEmail($this->from['email'])
            ->setPassword($this->from['password'])
            ->setRole($this->from['role'])
            ->setIsVerified($this->from['isVerified'])
            ->setSignedUpOn($this->from['signedUpOn']);

        return $user;
    }
}