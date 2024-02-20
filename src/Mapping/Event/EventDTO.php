<?php

namespace App\Mapping\Event;

use App\Entity\Event;
use App\Mapping\DTOInterface;
use DateTime;
use Exception;

class EventDTO implements DTOInterface
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
        assert(is_array($this->from));

        $event = new Event();

        $event
            ->setName($this->from['name'])
            ->setDescription($this->from['description'])
            ->setStartDate(new DateTime($this->from['startDate']))
            ->setEndDate(new DateTime($this->from['endDate']))
            ->setTag($this->from['tag'])
            ->setCapacity($this->from['capacity'])
            ->setOwnerId($this->from['owner_id']);

        return $event;
    }
}