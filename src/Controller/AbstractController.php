<?php

namespace App\Controller;

use App\Entity\User;
use JetBrains\PhpStorm\NoReturn;
use Twig\Environment;

abstract class AbstractController
{
    public function __construct(protected readonly Environment $twig)
    {
    }

    #[NoReturn] protected function redirect(string $route): void
    {
        header("Location:" . $route);
        die();
    }

    #[NoReturn] protected function dd(mixed $expression): null
    {
        if (is_array($expression)) {
            foreach ($expression as $key => $exp) {
                var_dump($key . ' ---> ' . $exp);
            }
        } else {
            var_dump($expression);
        }
        exit();
    }

    protected function truncate(string $string, int $charMax, string $suffix): string
    {
        $result = substr($string, 0, $charMax);
        return $result . $suffix;
    }

    protected function getUser(): User
    {
        $this->dd($_SESSION);
    }
}