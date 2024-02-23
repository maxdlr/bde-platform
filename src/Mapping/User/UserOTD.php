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
    public function process(bool $encrypt = true): array
    {
        if (!assert($this->from instanceof User))
            throw new Exception('Wrong type, this is supposed to be an User object');

        $user = $this->from;

        $hashedPassword = $encrypt ? $this->hashPassword($user->getPassword()) : $user->getPassword();

        return [
            'lastname' => $user->getLastname(),
            'firstname' => $user->getFirstName(),
            'password' => $hashedPassword,
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'isVerified' => +$user->getIsVerified(),
            'signedUpOn' => $user->getSignedUpOn()->format('Y-m-d H:i:s')
        ];
    }

    private function hashPassword(string $plainPassword)
    {
        return password_hash($plainPassword, PASSWORD_DEFAULT);
    }
}