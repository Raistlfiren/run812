<?php

namespace App\Tests\Command;

use App\Command\UserCommand;
use App\Entity\User;
use App\Tests\DatabaseTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class UserCommandTest extends DatabaseTestCase
{
    public function test_command_found()
    {
        $kernel = $this->bootKernel();
        $application = new Application($kernel);

        $command = $application->find('user:create');
        $this->assertInstanceOf(UserCommand::class, $command);
    }

    public function test_execute()
    {
        $kernel = $this->bootKernel();
        $application = new Application($kernel);

        $command = $application->find('user:create');

        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'email' => 'test@test.com',
            'password' => 'test123'
        ]);

        $result = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['email' => 'test@test.com']);

        $this->assertInstanceOf(User::class, $result);
    }
}