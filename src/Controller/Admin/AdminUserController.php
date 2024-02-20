<?php

namespace App\Controller\Admin;

use App\Attribute\Route;
use App\Controller\AbstractController;
use App\Entity\User;
use App\Mapping\User\UserOTD;
use App\Repository\UserRepository;
use Twig\Environment;

class AdminUserController extends AbstractController
{
    public function __construct(
        Environment                     $twig,
        private readonly UserRepository $userRepository,
    )
    {
        parent::__construct($twig);
    }

    #[Route('/admin/user/index', name: 'app_admin_user_edit', httpMethod: ['GET'])]
    public function index(): string
    {
        $eventOTD = new UserOTD();
        $userObjects = $this->userRepository->findAll();
        $users = array_map(fn(User $user): array => $eventOTD->config($user)->process(), $userObjects);

        return $this->twig->render('admin/index.html.twig', [
            'items' => $users,
            'entityName' => 'user'
        ]);
    }

    #[Route('/admin/user/edit', name: 'app_admin_user_edit', httpMethod: ['GET', 'POST'])]
    public function edit(): string
    {
        if (isset($_POST['editSubmit']) && $_POST['editSubmit'] == 'edit-user') {
            $userRepository = new UserRepository();
            $event = $userRepository->findOneBy(['id' => $_POST['idShow']]);
        }

        return $this->twig->render('admin/event/edit.html.twig');
    }
}