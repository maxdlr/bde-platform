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

        $arrayUser = [
            'name' => $user->getName(),
            'firstname' => $user->getFirstName(),
            'password' => $user->getPassword(),
            'email' => $user->getEmail(),
            'role' => $user->getRole(),
            'isVerified' => $user->isVerified(),
            'signedUpOn' => $user->getSignedUpDate()
        ];
        return $arrayUser;
    }
}