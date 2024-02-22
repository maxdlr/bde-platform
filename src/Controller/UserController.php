<?php

namespace App\Controller;

use App\Attribute\Route;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\RoleRepository;
use App\Service\Mail\MailManager;
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
            $mailManager = new MailManager;

            $stringCurrentDate = date('Y-m-d H:i:s');
            $dateCurrentDate = DateTime::createFromFormat('Y-m-d H:i:s', $stringCurrentDate);

            $user
                ->setFirstname($_POST['firstname'])
                ->setLastname($_POST['lastname'])
                ->setEmail($_POST['email'])
                ->setPassword($_POST['password'])
                ->setRoles("student")
                ->setIsVerified(false)
                ->setSignedUpOn($dateCurrentDate);
            $mailManager->sendValidateMail($_POST['email']);

            if ($userRepository->insertOne($user)) {
                $this->redirect('/admin/user/index');
            }
            else {
                var_dump("ok");
            }
        }

        $roles = $this->roleRepository->findAll();
        return $this->twig->render('user/user-new.html.twig', [
            'tags' => $roles,
        ]);
    }


    #[Route('/user/dashboard', name: 'app_user_dashboard', httpMethod: ['GET'])]
    public function dahsboard(): string
    {
        return $this->twig->render('user/index.html.twig', [
        ]);
    }

    #[Route('/user/validate/{id}', name: 'app_user_validate', httpMethod: ['GET'])]
    public function validate()
    {
        $token = substr($_SERVER['PATH_INFO'], strrpos($_SERVER['PATH_INFO'], "/") + 1);
        $mailManager = new MailManager();
        $mailManager->validationUser($token);

    }
}