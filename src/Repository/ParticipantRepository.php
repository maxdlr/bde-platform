<?php

namespace App\Repository;

use App\Mapping\Participant\ParticipantDTO;
use App\Mapping\Participant\ParticipantOTD;
use App\Service\DB\Repository;

class ParticipantRepository extends Repository
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'participant';
        $this->dto = new ParticipantDTO();
        $this->otd = new ParticipantOTD();
    }
}