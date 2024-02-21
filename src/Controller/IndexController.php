<?php

namespace App\Controller;

use App\Attribute\Route;
use App\Repository\ProductRepository;
use App\Service\EventFilter;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use App\Service\FilterManager;

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
        return $this->twig->render('index/home.html.twig', [
        ]);
    }
}