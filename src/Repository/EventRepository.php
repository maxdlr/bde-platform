<?php

namespace App\Repository;

use App\Mapping\Event\EventDTO;
use App\Mapping\Event\EventOTD;
use App\Service\DB\Repository;

class EventRepository extends Repository
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'event';
        $this->dto = new EventDTO();
        $this->otd = new EventOTD();
    }
}