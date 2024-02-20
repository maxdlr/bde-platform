<?php

namespace App\Mapping;

interface DTOInterface
{
    public function config(array $from): static;

    public function process(): object;
}