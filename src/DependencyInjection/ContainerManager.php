<?php

namespace App\DependencyInjection;

use App\Attribute\AttributeManager;
use App\Service\DB\DatabaseManager;
use App\Service\DB\EntityManager;
use App\Service\EnvironmentManager;
use Exception;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use Twig\Environment;

class ContainerManager
{
    private AttributeManager $attributeManager;
    private DatabaseManager $databaseManager;
    private EntityManager $entityManager;
    private EnvironmentManager $environmentManager;
    private Environment $twig;

    public function __construct()
    {
        $this->attributeManager = new AttributeManager();
        $this->databaseManager = new DatabaseManager();
        $this->entityManager = $this->databaseManager->getEntityManager();
        $this->environmentManager = new EnvironmentManager();
        $this->twig = $this->environmentManager->getTemplateEngine();
    }

    /**
     * @throws Exception
     */
    public function buildContainer(): ContainerInterface
    {
        $container = new Container();
//        $repositoriesFQCN = $this->getEntityRepositoriesFQCN($this->getEntityFileNames());
//        $repositoryObjects = $this->getEntityRepositoryObjects($repositoriesFQCN);

        try {
            $container
                ->set(Environment::class, $this->twig)
                ->set(EntityManager::class, $this->entityManager);

//            for ($i = 0; $i < count($repositoriesFQCN); $i++) {
//                $newRepo = new $repositoryObjects[$i]($this->entityManager);
//                $container->set($repositoriesFQCN[$i], $newRepo);
//            }

            return $container;

        } catch (ServiceExistsException|Exception $e) {
            var_dump($e);
            exit();
        }
    }

//    /**
//     * @throws Exception
//     */
//    private function getEntityRepositoriesFQCN(array $entityFileNames): array
//    {
//        $repositoryFQCNs = [];
//        foreach ($entityFileNames as $name) {
//            $entityInfo = new ReflectionClass("App\Entity\\" . $name);
//            $entityClassAttribute = $entityInfo->getAttributes('Doctrine\ORM\Mapping\Entity')[0];
//            ['repositoryClass' => $repositoryFQCNs[]] = $entityClassAttribute->getArguments();
//        }
//
//        return $repositoryFQCNs;
//    }

    /**
     * @throws Exception
     */
    private function getEntityFileNames(): array
    {
        return $this->attributeManager->getPhpFileNamesFromDir(
            __DIR__ . '/../Entity'
        );
    }

    /**
     * @throws Exception
     */
    private function getEntityRepositoryObjects($repositoryFQCNs): array
    {
        $repositoryOjects = [];

        foreach ($repositoryFQCNs as $fqcn) {
            $entityInfo = new ReflectionClass($fqcn);
            $repositoryOjects[] = $entityInfo->newInstanceWithoutConstructor();
        }
        return $repositoryOjects;
    }
}