<?php

namespace App\Tests\Command;

use App\Command\RouteCommand;
use App\Service\RouteHandler;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class RouteCommandTest extends KernelTestCase
{
    public function test_command_found()
    {
        $kernel = $this->bootKernel();
        $application = new Application($kernel);

        $command = $application->find('routes:download');
        $this->assertInstanceOf(RouteCommand::class, $command);
    }

    public function test_execute()
    {
        $routeHandler = $this->getMockBuilder(RouteHandler::class)
            ->disableOriginalConstructor()
            ->getMock();

        $routeCommand = $this->getMockBuilder(RouteCommand::class)
            ->setConstructorArgs([$routeHandler])
            ->onlyMethods(['execute'])
            ->onlyMethods([])
            ->getMock();

        $routeHandler->expects($this->once())
            ->method('fetchRoutes');

        $commandTester = new CommandTester($routeCommand);

        $commandTester->execute([]);
    }
}