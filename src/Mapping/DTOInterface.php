<?php

namespace App\Mapping;

interface DTOInterface
{
    public function configure(): void;

    public function process(): object;
}