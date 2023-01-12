<?php

namespace App\Tests\Service;

use App\Service\RouteCleaner;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RouteCleanerTest extends KernelTestCase
{
    /**
     * @test
     */
    public function clean_turn_directions()
    {
        $value = RouteCleaner::cleanTurnDirection('Turn left onto Northwest Martin Luther King Jr Boulevard');

        $this->assertEquals('turn left onto northwest martin luther king jr boulevard', $value);
    }

    /**
     * @test
     * @dataProvider directionProvider
     */
    public function clean_directions(string $value, string $expected)
    {
        $value = RouteCleaner::cleanDirections($value);

        $this->assertEquals($expected, $value);
    }

    public function directionProvider(): array
    {
        return [
            [
                'Turn left onto Northwest Martin Luther King Jr Boulevard',
                'L Northwest Martin Luther King Jr Boulevard'
            ],
            [
                'Turn right onto North Fulton Avenue',
                'R North Fulton Avenue'
            ],
            [
                'Continue onto Kratzville Road',
                'Continue Kratzville Road'
            ],
            [
                'Turn slight right onto Old Post Road',
                'Slight R Old Post Road'
            ],
            [
                'Turn slight left onto Old Post Road',
                'Slight L Old Post Road'
            ],
            [
                'Turn sharp right onto Old Post Road',
                'Sharp R Old Post Road'
            ],
            [
                'Turn sharp left onto Old Post Road',
                'Sharp L Old Post Road'
            ],
        ];
    }
}