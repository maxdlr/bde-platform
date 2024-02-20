<?php

namespace App\Mapping;

interface OTDInterface
{
    public function config(): void;

    public function process(): array;
}