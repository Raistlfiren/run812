<?php

namespace App\Tests\Twig;

use App\Service\RouteCleaner;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Twig\TwigFilter;

class DirectionFilterTest extends KernelTestCase
{
    /**
     * @test
     */
    public function get_all_filters()
    {
        $kernel = self::bootKernel();

        $twig = $kernel->getContainer()->get('twig');
        $cleanTurnDirectionFilter = $twig->getFilter('cleanTurnDirection');
        $cleanTurnDirectionCallable = $cleanTurnDirectionFilter->getCallable();

        $this->assertInstanceOf(TwigFilter::class, $cleanTurnDirectionFilter);
        $this->assertEquals($cleanTurnDirectionCallable[0], RouteCleaner::class);
        $this->assertEquals($cleanTurnDirectionCallable[1], 'cleanTurnDirection');

        $cleanDirectionFilter = $twig->getFilter('cleanDirections');
        $cleanDirectionCallable = $cleanDirectionFilter->getCallable();

        $this->assertInstanceOf(TwigFilter::class, $cleanDirectionFilter);
        $this->assertEquals($cleanDirectionCallable[0], RouteCleaner::class);
        $this->assertEquals($cleanDirectionCallable[1], 'cleanDirections');
    }
}