<?php

namespace App\Service;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class EnvironmentManager
{
    public function getTemplateEngine(): Environment
    {
        $loader = new FilesystemLoader(__DIR__ . '/../../templates');
        return new Environment($loader, [
            'cache' => __DIR__ . '/../../var/cache',
            'debug' => $_ENV['APP_ENV'] === 'dev'
        ]);
    }
}