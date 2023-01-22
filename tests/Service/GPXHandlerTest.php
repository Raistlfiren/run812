<?php

namespace App\Tests\Service;

use App\Entity\Route;
use App\Service\GPXHandler;
use PHPUnit\Framework\TestCase;

class GPXHandlerTest extends TestCase
{
    public function test_gpx_handler()
    {
        $route = new Route();
        $route->setName('Test');
        $route->setDescription('This is a description');

        $route->setJsonRoute(['route' => ['track_points' => [
            ['x' => 123, 'y' => 456, 'e' =>12],
            ['x' => 123, 'y' => 456, 'e' =>12],
            ['x' => 123, 'y' => 456, 'e' =>12],
            ['x' => 123, 'y' => 456, 'e' =>12],
            ['x' => 123, 'y' => 456, 'e' =>12]
        ]]]);

        $gpxOutput = GPXHandler::createGPX($route)->toArray();

        $this->assertCount(3, $gpxOutput['metadata']);
        $this->assertEquals('Test', $gpxOutput['metadata']['name']);
        $this->assertEquals('This is a description', $gpxOutput['metadata']['desc']);

        $this->assertCount(5, $gpxOutput['tracks'][0]['trkseg'][0]['points']);
    }
}