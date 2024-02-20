<?php

namespace App\Mapping;

use App\Entity\User;
use App\Mapping\DTOInterface;
use Exception;

class UserDTO implements DTOInterface
{
    private array $from;

    public function config(array $from): static
    {
        $this->from = $from;
        return $this;
    }

    public function process():object
    {
        if (!assert(is_array($this->from)))
            throw new Exception('Wrong type, this is supposed to be an array');

        $user = new User;

        $user
            ->setFirstname($this->from['firstname'])
            ->setName($this->from['name'])
            ->setEmail($this->from['email'])
            ->setPassword($this->from['password'])
            ->setRole($this->from['role'])
            ->setIsVerified($this->from['isVerified'])
            ->setSignedUpDate($this->from['signedUpDate']);
            
        return $user;
    }
}