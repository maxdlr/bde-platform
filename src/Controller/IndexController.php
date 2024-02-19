<?php

namespace App\Controller;

use App\Attribute\Route;
use App\Repository\ProductRepository;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class IndexController extends AbstractController
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    #[Route('/', name: 'app_home', httpMethod: ['GET'])]
    public function home(): string
    {
        $welcomeMsg = 'Salut les bolos';

        return $this->twig->render('index/home.html.twig', [
            'welcomeMsg' => $welcomeMsg,
        ]);
    }
}