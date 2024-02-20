<?php

namespace App\Repository;

use App\Service\DB\Repository;

class EventRepository extends Repository
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'event';
    }
}