<?php

namespace App\Controller\Admin;

use App\Attribute\Route;
use App\Controller\AbstractController;
use App\Entity\User;
use App\Mapping\User\UserOTD;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Twig\Environment;

class AdminUserController extends AbstractController
{
    public function __construct(
        Environment                     $twig,
        private readonly UserRepository $userRepository,
        private readonly RoleRepository $roleRepository
    )
    {
        parent::__construct($twig);
    }

    #[Route('/admin/user/index', name: 'app_admin_user_index', httpMethod: ['GET'])]
    public function index(): string
    {
        $userObjects = $this->userRepository->findAll();
        $users = array_map(fn(User $user): array => $user->toArray(), $userObjects);

        return $this->twig->render('admin/index.html.twig', [
            'items' => $users,
            'entityName' => 'user'
        ]);
    }

    #[Route('/admin/user/edit/{id}', name: 'app_admin_user_edit', httpMethod: ['GET', 'POST'])]
    public function edit(int $idUser): string
    {
        $userRepository = new UserRepository();
        $user = $userRepository->findOneBy(['id' => $idUser]);


        if (isset($_POST['edit-user-submit']) && $_POST['edit-user-submit'] == 'edit-user') {

            $updatedUserArray = [
                'firstname' => $_POST['firstname'],
                'lastname' => $_POST['lastname'],
                'email' => $_POST['email'],
                'roles' => $_POST['role']
            ];

            if($_POST['isVerified'] === "on"){
                $updatedUserArray['isVerified'] = 1;
            } else {
                $updatedUserArray['isVerified'] = 0;
            }

            if ($userRepository->update($updatedUserArray, $user)) {
                $this->redirect('/admin/user/index');
            }
        } elseif (!is_null($user)) {
            $roles = $this->roleRepository->findAll();
            return $this->twig->render('admin/edit/user-edit.html.twig', [
                'item' => $user,
                'roles' => $roles
            ]);
        } else {
            return $this->twig->render('404.html.twig');
        }
    }

    #[Route('/admin/user/delete/{id}', name: 'app_admin_user_delete', httpMethod: ['POST'])]
    public function delete(int $idUser): void
    {
        $userRepository = new userRepository();
        $userToDelete = $userRepository->findOneBy(['id' => $idUser]);

        if ($userRepository->delete($userToDelete)) {
            $this->redirect('/admin/user/index');
        }
    }

}