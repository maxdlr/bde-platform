<?php

namespace App\Mapping\Participant;

use App\Entity\Participant;
use App\Mapping\OTD;
use App\Mapping\OTDInterface;
use Exception;

class ParticipantOTD extends OTD implements OTDInterface
{
    // Data transfer class Object -> Database
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
        if (!assert($this->from instanceof Participant))
            throw new Exception('Wrong type, this is supposed to be an Participant object');

        $participant = $this->from;

        return [
            'event_id' => $participant->getEventId(),
            'user_id' => $participant->getUserId()
        ];
    }
}