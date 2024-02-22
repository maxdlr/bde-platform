<?php

namespace App\Controller;

use App\Attribute\Route;
use App\Entity\User;
use App\Repository\UserRepository;
use Twig\Environment;

class UserController extends AbstractController
{
    public function __construct(
        Environment                     $twig,
        private readonly UserRepository $userRepository
    )
    {
        parent::__construct($twig);
    }

    #[Route('/user/login', name: 'app_user_login', httpMethod: ['GET', 'POST'])]
    public function connection(): string
    {
        $this->clearFlashs();

        if (isset($_POST['connect-user-submit']) && $_POST['connect-user-submit'] == 'connect-user') {

            $userRepository = new UserRepository();

            $user = $userRepository->findOneBy(['email' => $_POST['email']]);
            if(!is_null($user)){
                $verifyHashPassword = password_verify($_POST['password'], $user->getPassword());
                if($verifyHashPassword === true){
                    $_SESSION["user_connected"] = $user->getEmail();

                    $this->redirect('/');
                    exit();
                } else {
                    $this->addFlash("danger", "Le mot de passe saisi ne correspond pas à l'adresse mail");
                }
            }else {
                $this->addFlash("danger", "L'adresse mail saisie ne correspond à aucun compte");
            }

            return $this->twig->render('user/login.html.twig', [
                'flashbag' => $_SESSION["flashbag"]
            ]);
        }

        return $this->twig->render('user/login.html.twig', []);
    }

    #[Route('/user/logout', name: 'app_user_logout', httpMethod: ['GET'])]
    public function disconnection(): string
    {
        session_destroy();

        header("Location: /");
        exit();
    }

    #[Route('/user/dashboard', name: 'app_user_dashboard', httpMethod: ['GET'])]
    public function dahsboard(): string
    {


        return $this->twig->render('user/index.html.twig', [
        ]);
    }
}