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

    protected function getUserConnected(): User
    {
        if (isset($_SESSION["user_connected"])){
            $userRepository = new UserRepository();
            $userConnected = $userRepository->findOneBy(['email' => $_SESSION["user_connected"]]);

            var_dump($userConnected); die;

            $this->setId($userConnected->getId());
            $this->setFirstname($userConnected->getFirstname());
            $this->setLastname($userConnected->getLastname());
            $this->setEmail($userConnected->getEmail());
            $this->setPassword($userConnected->getPassword());
            $this->setRoles($userConnected->getRoles());
            $this->setIsVerified($userConnected->getIsVerified());
            $this->setSignedUpOn($userConnected->getSignedUpOn());

            return $this;
        } else {
            return $this;
        }
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

    protected function truncate(string $string, int $charMax, string $suffix): string
    {
        $result = substr($string, 0, $charMax);
        return $result . $suffix;
    }
}