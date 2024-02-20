<?php

namespace App\Mapping;

interface OTDInterface
{
    public function config(object $from): static;

    public function process(): array;
}