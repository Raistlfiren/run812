<?php

namespace App\Service;

use App\Entity\Route;
use phpGPX\Models\GpxFile;
use phpGPX\Models\Metadata;
use phpGPX\Models\Point;
use phpGPX\Models\Segment;
use phpGPX\Models\Track;

class GPXHandler
{
    public static function createGPX(Route $route)
    {
        $json = $route->getJsonRoute()['route'];

        $gpxFile = new GpxFile();
        $gpxFile->metadata = new Metadata();
        $gpxFile->metadata->time = new \DateTime();
        $gpxFile->metadata->name = $route->getName();
        $gpxFile->metadata->description = $route->getDescription();
        $track = new Track();
        $track->type = 'RUN';
        $track->source = '812.run';
        $segment = new Segment();

        foreach ($json['track_points'] as $trackPoint) {
            $point = new Point(Point::TRACKPOINT);
            $point->latitude = $trackPoint['y'];
            $point->longitude = $trackPoint['x'];
            $point->elevation = $trackPoint['e'];
            $segment->points[] = $point;
        }

        $track->segments[] = $segment;
        $track->recalculateStats();
        $gpxFile->tracks[] = $track;

        return $gpxFile;
    }
}