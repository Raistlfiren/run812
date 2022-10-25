<?php

namespace App\Twig;

use App\Service\RouteCleaner;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DirectionFilter extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('cleanTurnDirection', [RouteCleaner::class, 'cleanTurnDirection']),
            new TwigFilter('cleanDirections', [RouteCleaner::class, 'cleanDirections']),
        ];
    }
}