<?php

namespace App\Controller;

use App\Attribute\Route;
use App\Repository\ProductRepository;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_home', httpMethod: ['GET'])]
    public function home(ProductRepository $productRepository): string
    {
        $products = $productRepository->findAll();
        $countOfProducts = count($products);

        $sumOfAllProductPrices = 0;
        foreach ($products as $product) {
            $sumOfAllProductPrices += $product->getPrice();
        }

        return $this->twig->render('index/home.html.twig', [
            'countOfProducts' => $countOfProducts,
            'sumOfAllProductPrices' => $sumOfAllProductPrices,
        ]);
    }

    #[Route('/contact', name: 'app_contact', httpMethod: ['GET'])]
    public function contact(): string
    {
        $content = 'Page Contact';

        return $this->twig->render('index/contact.html.twig', [
            'content' => $content
        ]);
    }
}