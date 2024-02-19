<?php

namespace App\Repository;

use App\DB\Repository;

class EventRepository extends Repository
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'event';
    }
}