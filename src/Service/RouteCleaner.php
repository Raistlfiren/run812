<?php

namespace App\Service;

class RouteCleaner
{
    public const TURN_DIRECTIONS = [
        'left' => 'L',
        'right' => 'R',
        'sharp right' => 'SR',
        'sharp left' => 'SL',
        'straight' => 'S',
        'slight right' => 'SR',
        'slight left' => 'SL'
    ];

    public const CLEAN_WORDS = [
        'turn' => 'Turn',
        'onto' => 'Onto',
        'left' => 'Left',
        'right' => 'Right',
        'sharp' => 'Sharp'
    ];

    public const CLEAN_WORDS_REGEX_OLD = '/turn|onto|left|right|sharp/i';
    public const CLEAN_WORDS_REGEX = '/turn\s|onto\s/i';

    public static function cleanTurnDirection($turnDirection)
    {
        $turnDirection = strtolower($turnDirection);

        return self::TURN_DIRECTIONS[$turnDirection] ?? $turnDirection;
    }

    public static function cleanDirections($directions)
    {
        $phrase = preg_replace(self::CLEAN_WORDS_REGEX, '', $directions);

        return ucfirst(str_replace(array_keys(self::TURN_DIRECTIONS), array_values(self::TURN_DIRECTIONS), $phrase));
    }
}