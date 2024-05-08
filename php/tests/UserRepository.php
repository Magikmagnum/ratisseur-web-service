<?php

namespace App\Tests;

use App\Entity\User;
use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

class UserRepositoryTest extends KernelTestCase
{
    // protected AbstractDatabaseTool $databaseTool;
    protected DatabaseToolCollection $databaseTool;
    protected EntityManagerInterface $entityManager;

    public function setUp(): void
    {
        parent::setUp();
        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class);
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
    }

    // public function testCount(): void
    // {
    //     $this->databaseTool->loadAliceFixtureFiles([UserFixtures::class]);
    //     $users = $this->entityManager->getRepository(UserRepository::class)->count([]);
    //     $this->assertEquals(10, $users);

    //     // $routerService = static::getContainer()->get('router');
    //     // $myCustomService = static::getContainer()->get(CustomService::class);
    // }
}
