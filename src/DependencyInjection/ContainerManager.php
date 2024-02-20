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
use Twig\Extension\DebugExtension;
use Twig\TwigFilter;

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
        $repositoriesFQCN = $this->getEntityRepositoriesFQCN($this->getEntityFileNames(), $this->getEnumFileNames());
        $repositoryObjects = $this->getEntityRepositoryObjects($repositoriesFQCN);

        $this->twig->addExtension(new DebugExtension());

        try {
            $container
                ->set(Environment::class, $this->twig)
                ->set(EntityManager::class, $this->entityManager);

            for ($i = 0; $i < count($repositoriesFQCN); $i++) {
                $newRepo = new $repositoryObjects[$i]($this->entityManager);
                $container->set($repositoriesFQCN[$i], $newRepo);
            }

            return $container;

        } catch (ServiceExistsException|Exception $e) {
            var_dump($e);
            exit();
        }
    }

    /**
     * @throws Exception
     */
    private function getEntityRepositoriesFQCN(array $entityFileNames, array $enumFileNames): array
    {
        $repositoryFQCNs = [];
        foreach ($entityFileNames as $name) {
            $entityInfo = new ReflectionClass("App\Entity\\" . $name);
            $entityClassAttribute = $entityInfo->getAttributes('App\Attribute\AsEntity')[0];
            ['repositoryClass' => $repositoryFQCNs[]] = $entityClassAttribute->getArguments();
        }

        foreach ($enumFileNames as $name) {
            $entityInfo = new ReflectionClass("App\Enum\\" . $name);
            $entityClassAttribute = $entityInfo->getAttributes('App\Attribute\AsEntity')[0];
            ['repositoryClass' => $repositoryFQCNs[]] = $entityClassAttribute->getArguments();
        }

        return $repositoryFQCNs;
    }

    /**
     * @throws Exception
     */
    private function getEntityFileNames(): array
    {
        return $this->attributeManager->getPhpFileNamesFromDir(
            __DIR__ . '/../Entity'
        );
    }

    private function getEnumFileNames(): array
    {
        return $this->attributeManager->getPhpFileNamesFromDir(
            __DIR__ . '/../Enum'
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