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
        header("Location:".$route);
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

    protected function getUser(): User
    {
        $this->dd($_SESSION);
    }

    public function addFlash(string $flashType, string $flashMessage):void{
        if(isset($_SESSION)){

            $newFlash = ["type" => $flashType, "message" => $flashMessage];

            $_SESSION["flashbag"][] = $newFlash;
        }
    }

    public function clearFlashs():void{
        unset($_SESSION["flashbag"]);
    }
}