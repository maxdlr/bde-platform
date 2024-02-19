<?php

namespace App\Controller;

use App\Attribute\Route;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Faker;
use JetBrains\PhpStorm\NoReturn;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'app_products', httpMethod: ['GET', 'POST'])]
    public function list(ProductRepository $productRepository, EntityManager $entityManager): string
    {
        $products = $productRepository->findAll();
        $messages = [];

        if (isset($_POST['newProductBtn']) && $_POST['newProductBtn'] == 'Créer') {

            foreach ($_POST as $formField) {
                if ($formField === '') {
                    $this->redirect('/product/new-fail');
                }
            }

            if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK) {

                $originalFileName = $_FILES['uploadedFile']['name'];
                $tmpFileName = $_FILES['uploadedFile']['tmp_name'];

                $fileNameCmps = explode(".", $originalFileName);
                $fileExtension = strtolower(end($fileNameCmps));

                $fileSize = $_FILES['uploadedFile']['size'];

                $newFileName = md5(time() . $tmpFileName) . '.' . $fileExtension;
                $allowedfileExtensions = array('jpg', 'gif', 'png', 'zip', 'txt', 'xls', 'doc');

                if (in_array($fileExtension, $allowedfileExtensions)) {
                    $uploadFileDir = __DIR__ . '/../../public/assets/images/';
                    $dest_path = $uploadFileDir . $newFileName;

                    move_uploaded_file($tmpFileName, $dest_path);

                    $product = new Product();
                    $product
                        ->setName($_POST['name'])
                        ->setDescription($_POST['description'])
                        ->setPrice($_POST['price'])
                        ->setFileName($newFileName)
                        ->setFileSize($fileSize);

                    $entityManager->persist($product);
                    $entityManager->flush();

                    $messages[] = ['type' => 'Succes !', 'content' => 'Produit ajouté avec brio'];

                } else {
                    $messages[] = ['type' => 'Echec...', 'content' => 'Mauvaise extension de fichier.'];
                }
            }
        }

        return $this->twig->render('product/list.html.twig', [
            'products' => $products,
            'messages' => $messages,
        ]);
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[NoReturn] #[Route('/product/fake10', name: 'app_product_fake10', httpMethod: ['GET', 'POST'])]
    public function fake10(EntityManager $entityManager): void
    {
        $faker = Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product
                ->setName($faker->domainWord)
                ->setDescription($faker->paragraph())
                ->setPrice($faker->randomFloat(3, 30, 500))
                ->setFileName('250x250.png')
                ->setFileSize($faker->randomFloat(2, 3, 10));

            $entityManager->persist($product);
        }
        $entityManager->flush();
        $this->redirect('/products');
    }

    /**
     * @throws ORMException
     */
    #[NoReturn] #[Route('/product/deleteAll', name: 'app_product_delete_all', httpMethod: ['GET', 'POST'])]
    public function deleteAll(ProductRepository $productRepository, EntityManager $entityManager): void
    {
        $products = $productRepository->findAll();

        foreach ($products as $product) {
            $entityManager->remove($product);
        }
        $entityManager->flush();
        $this->redirect('/products');
    }

}