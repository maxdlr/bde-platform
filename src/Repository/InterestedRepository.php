<?php

namespace App\Repository;

use App\Mapping\Interested\InterestedDTO;
use App\Mapping\Interested\InterestedOTD;
use App\Service\DB\Repository;

class InterestedRepository extends Repository
{

    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'interested';
        $this->dto = new InterestedDTO();
        $this->otd = new InterestedOTD();
    }
}