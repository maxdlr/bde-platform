<?php

namespace App\Controller;

use App\Attribute\Route;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\RoleRepository;
use Twig\Environment;
use DateTime;

class UserController extends AbstractController
{
    public function __construct(
        Environment                      $twig,
        private readonly UserRepository $userRepository,
        private readonly RoleRepository $roleRepository
    )
    {
        parent::__construct($twig);
    }


    #[Route('/user/new', name: 'app_user_new', httpMethod: ['GET', 'POST'])]
    public function new(): string
    {
        if (isset($_POST['new-user-submit']) && $_POST['new-user-submit'] == 'new-user') {
            $user = new User();
            $userRepository = new UserRepository();

            $stringCurrentDate = date('Y-m-d H:i:s');
            $dateCurrentDate = DateTime::createFromFormat('Y-m-d H:i:s', $stringCurrentDate);

            $user
                ->setFirstname($_POST['firstname'])
                ->setLastname($_POST['lastname'])
                ->setEmail($_POST['email'])
                ->setPassword($_POST['password'])
                ->setRoles("student")
                ->setIsVerified(1)
                ->setSignedUpOn($dateCurrentDate);

            if ($userRepository->insertOne($user)) {
                $this->redirect('/admin/user/index');
            }
        }

        $roles = $this->roleRepository->findAll();
        return $this->twig->render('user/user-new.html.twig', [
            'tags' => $roles,
        ]);
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