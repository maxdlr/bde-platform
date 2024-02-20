<?php

namespace App\Repository;

use App\Mapping\Event\EventDTO;
use App\Service\DB\Repository;

class EventRepository extends Repository
{
    private readonly EventDTO $eventDTO;

    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'event';
        $this->eventDTO = new EventDTO();
    }

    public function findAll(): null|array
    {
        $from = parent::findAll();

        $eventObjects = [];
        foreach ($from as $eventArray) {
            $eventObjects[] = $this->eventDTO->config($eventArray)->process();
        }

        return $eventObjects;
    }
}