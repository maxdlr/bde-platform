<?php

namespace App\Mapping\Participant;

use App\Entity\Participant;
use App\Mapping\DTO;
use App\Mapping\DTOInterface;
use Exception;

class ParticipantDTO extends DTO implements DTOInterface
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

        $participant = new Participant();

        $participant
            ->setId($this->from['id'])
            ->setEventId($this->from['event_id'])
            ->setUserId($this->from['user_id']);

        return $participant;
    }
}