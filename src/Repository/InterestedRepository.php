<?php

namespace App\Repository;

use App\Service\DB\Repository;

class InterestedRepository extends Repository
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'interested';
    }
}