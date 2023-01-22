<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DatabaseTestCase extends WebTestCase
{
    protected EntityManagerInterface $entityManager;
    protected KernelBrowser $client;
    protected AbstractDatabaseTool $databaseTool;
    protected $adminUrlGenerator;

    protected function setUp(): void
    {
        $client = self::createClient();

        $this->initDatabase($client);

        $this->entityManager = $client->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->adminUrlGenerator = $client->getContainer()->get(AdminUrlGenerator::class);

        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();

        $this->client = $client;
    }

    private function initDatabase(KernelBrowser $kernel): void
    {
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $metaData = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->updateSchema($metaData);
    }
}