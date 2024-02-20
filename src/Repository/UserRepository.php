<?php

namespace App\Repository;

use App\Mapping\User\UserDTO;
use App\Service\DB\Repository;

class UserRepository extends Repository
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'user';
        $this->dto = new UserDTO();
    }
}