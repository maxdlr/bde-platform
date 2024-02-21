<?php

namespace App\Mapping\User;

use App\Entity\User;
use App\Mapping\OTD;
use App\Mapping\OTDInterface;
use Exception;

class UserOTD extends OTD implements OTDInterface
{
    private object $from;

    public function config(object $from): static
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function process(): array
    {
        if (!assert($this->from instanceof User))
            throw new Exception('Wrong type, this is supposed to be an User object');

        $user = $this->from;

        $arrayUser = [
            'lastname' => $user->getLastname(),
            'firstname' => $user->getFirstName(),
            'password' => $user->getPassword(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'isVerified' => $user->isVerified(),
            'signedUpOn' => $user->getSignedUpOn()
        ];
        return $arrayUser;
    }
}