<?php

namespace App\Mapping;

use App\Entity\Event;

class EventDTO extends DTO implements DTOInterface
{
    public function configure(): void
    {
        $this->from = [];
        $this->to = new Event();
    }

    public function process(): object
    {
        // TODO: Implement process() method.
    }


    // Data transfer class Database -> Object
}