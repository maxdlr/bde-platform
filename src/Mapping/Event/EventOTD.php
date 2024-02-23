<?php

namespace App\Mapping\Event;

use App\Entity\Event;
use App\Mapping\OTD;
use App\Mapping\OTDInterface;
use Exception;

class EventOTD extends OTD implements OTDInterface
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
    public function process(bool $encrypt = true): array
    {
        if (!assert($this->from instanceof Event))
            throw new Exception('Wrong type, this is supposed to be an Event object');

        $event = $this->from;

        return [
            'name' => $event->getName(),
            'description' => $event->getDescription(),
            'startDate' => $event->getStartDate()->format('Y-m-d H:i:s'),
            'endDate' => $event->getEndDate()->format('Y-m-d H:i:s'),
            'tag' => $event->getTag(),
            'capacity' => $event->getCapacity(),
            'owner_id' => $event->getOwnerId(),
            'fileSize' => $event->getFileSize(),
            'fileName' => $event->getFileName(),
        ];
    }
}