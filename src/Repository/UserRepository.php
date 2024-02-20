<?php

namespace App\Repository;

use App\Service\DB\Repository;

class UserRepository extends Repository
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'user';
    }
}