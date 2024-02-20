<?php

namespace App\Mapping;

interface OTDInterface
{
    public function configure(): void;

    public function process(): array;
}