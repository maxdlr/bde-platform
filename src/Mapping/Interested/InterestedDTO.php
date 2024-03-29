<?php

namespace App\Mapping\Interested;

use App\Entity\Interested;
use App\Mapping\DTO;
use App\Mapping\DTOInterface;
use Exception;

class InterestedDTO extends DTO implements DTOInterface
{
    // Data transfer class Database -> Object
    private array $from;

    public function config(array $from): static
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function process(): object
    {
        if (!assert(is_array($this->from)))
            throw new Exception('Wrong type, this is supposed to be an array');

        $interested = new Interested();

        $interested
            ->setId($this->from['id'])
            ->setEventId($this->from['event_id'])
            ->setUserId($this->from['user_id']);

        return $interested;
    }
}