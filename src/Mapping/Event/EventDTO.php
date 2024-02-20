<?php

namespace App\Mapping\Event;

use App\Entity\Event;
use App\Mapping\DTO;
use App\Mapping\DTOInterface;

class EventDTO extends DTO implements DTOInterface
{
    // Data transfer class Database -> Object

    public function config(array $from, object $to): static
    {
        $this->from = $from;
        $this->to = new Event();

        return $this;
    }

    public function process(): object
    {
        assert($this->to instanceof Event);
        assert(is_array($this->from));

        $event = $this->to;

        $event
            ->setName($this->from['name'])
            ->setDescription($this->from['description'])
            ->setStartDate($this->from['startDate'])
            ->setEndDate($this->from['endDate'])
            ->setTag($this->from['tag'])
            ->setCapacity($this->from['capacity'])
            ->setOwnerId($this->from['owner_id']);

        return $event;
    }
}