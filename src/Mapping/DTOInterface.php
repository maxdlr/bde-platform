<?php

namespace App\Mapping;

interface DTOInterface
{
    public function config(array $from, object $to): static;

    public function process(): object;
}