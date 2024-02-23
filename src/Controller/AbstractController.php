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

    protected function getUserConnected(): User|null|false
    {
        if (isset($_SESSION["user_connected"])) {
            $userRepository = new UserRepository();
            $userConnected = $userRepository->findOneBy(['email' => $_SESSION["user_connected"]]);

            return $userConnected ?? null;
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
        $user = $this->getUserConnected() ? $this->getUserConnected() : null;
        $url = $_SERVER['REQUEST_URI'];

        if (str_contains('/event/show', $url)) return true;


        if (!$user || $user === null) {
            $this->redirect('/user/login');
        }

        $allowedroutesForUser = Security::getAllowedRoutes(RoleEnum::tryFrom($user->getRoles()));

        if ($user->getRoles() === RoleEnum::ROLE_ADMIN->value) return true;

        $allowed = false;
        foreach ($allowedroutesForUser as $routes) {
            if (str_contains($routes, $url)) {
                $allowed = true;
            }
        }

        return $allowed;
    }

    protected function redirectIfForbidden()
    {
        if (!$this->isUserAllowedToRoute()) {
            $this->addFlash('danger', 'Zone controlée, veuillez contacter un admin.');
            $this->redirect('/user/login');
        }
    }
}