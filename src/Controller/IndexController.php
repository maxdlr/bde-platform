<?php

namespace App\Controller;

use App\Attribute\Route;
use App\Entity\User;
use App\Repository\EventRepository;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class IndexController extends AbstractController
{
    public function __construct(
        Environment                      $twig,
        private readonly EventRepository $eventRepository,
    )
    {
        parent::__construct($twig);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    #[Route('/', name: 'app_home', httpMethod: ['GET'])]
    public function home(): string
    {
        var_dump($_SESSION);
        if(isset($_SESSION)){
            $connectedUser = new User();
            $connectedUser->getUserConnected();
        } else {
            $connectedUser = null;
        }

        $events = $this->eventRepository->findAll();
        return $this->twig->render('index/home.html.twig', [
            'events' => $events,
            'connectedUser' => $connectedUser
        ]);
    }
}