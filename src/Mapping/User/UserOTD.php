<?php 

namespace App\Mapping\User;

use App\Entity\User;
use App\Mapping\OTDInterface;
use Exception;

class UserOTD implements OTDInterface
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

        $passwordHash = password_hash($user->getPassword(), PASSWORD_DEFAULT);

        $arrayUser = [
            'name' => $user->getName(),
            'firstname' => $user->getFirstName(),
            'password' => $passwordHash,
            'email' => $user->getEmail(),
            'role' => $user->getRole(),
            'isVerified' => $user->getIsVerified(),
            'signedUpOn' => $user->getSignedUpOn()
        ];
        return $arrayUser;
    }
}