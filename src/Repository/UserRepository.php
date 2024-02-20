<?php

namespace App\Repository;

use App\Service\DB\Repository;

class UserRepository extends Repository
{
    private readonly UserDTO $userDTO;

    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'user';
    }

    public function findAll(): null|array
    {
        $from = parent::findAll();

        $userObjects = [];
        foreach ($from as $userArray) {
            $userObjects[] = $this->userDTO->config($userArray)->process();
        }

        return $userObjects;
    }
}