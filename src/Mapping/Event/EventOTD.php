<?php

namespace App\Mapping\Event;

use App\Entity\Event;
use App\Mapping\OTDInterface;

class EventOTD implements OTDInterface
{
    // Data transfer class Object -> Database
    private object $from;

    public function config(object $from): static
    {
        $this->from = $from;
        return $this;
    }

    public function process(): array
    {
        assert($this->from instanceof Event);

        $event = $this->from;

        return [
            'name' => $event->getName(),
            'description' => $event->getDescription(),
            'startDate' => $event->getStartDate()->format('Y-m-d H:i:s'),
            'endDate' => $event->getEndDate()->format('Y-m-d H:i:s'),
            'tag' => $event->getTag(),
            'capacity' => $event->getCapacity(),
            'owner_id' => $event->getOwnerId()
        ];
    }
}