<?php

namespace App\Mapping\Interested;

use App\Entity\Interested;
use App\Mapping\OTD;
use App\Mapping\OTDInterface;
use Exception;

class InterestedOTD extends OTD implements OTDInterface
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
        if (!assert($this->from instanceof Interested))
            throw new Exception('Wrong type, this is supposed to be an Interested object');

        $interested = $this->from;

        return [
            'event_id' => $interested->getEventId(),
            'user_id' => $interested->getUserId()
        ];
    }
}