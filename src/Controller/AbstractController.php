<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\RoleEnum;
use App\Repository\UserRepository;
use App\Service\Security;
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

    protected function getUserConnected(): User|false
    {
        if (isset($_SESSION["user_connected"])) {
            $userRepository = new UserRepository();
            $userConnected = $userRepository->findOneBy(['email' => $_SESSION["user_connected"]]);

            return $userConnected;
        } else {
            return false;
        }
    }

    public function addFlash(string $flashType, string $flashMessage): void
    {
        if (isset($_SESSION)) {

            $newFlash = ["type" => $flashType, "message" => $flashMessage];

            $_SESSION["flashbag"][] = $newFlash;
        }
    }

    public function clearFlashs(): void
    {
        unset($_SESSION["flashbag"]);
    }

    protected function truncate(string $string, int $charMax, string $suffix): string
    {
        $result = substr($string, 0, $charMax);
        return $result . $suffix;
    }

    protected function isUserAllowedToRoute(): bool
    {
        $user = $this->getUserConnected();

        if (!$user) {
            $this->redirect('/user/login');
        }

        $url = $_SERVER['REQUEST_URI'];
        $allowedroutesForUser = Security::getAllowedRoutes(RoleEnum::tryFrom($user->getRoles()));

        return in_array($url, $allowedroutesForUser);
    }
}