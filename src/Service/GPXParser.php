<?php

namespace App\Service;

use Exception;
use phpGPX\phpGPX;
use Point;
use Route;

class GPXParser
{
    public function parse($gpx)
    {
        $track = [];

        $route = new Route();
        if ($this->validateFile($gpx)) {
            $file = phpGPX::load($gpx);

            foreach ($file->tracks as $track)
            {
                // Statistics for whole track
                $statistics = $track->stats->toArray();

                $route->setName($track->name);
                $route->setDistance($statistics['distance']/1609);
                foreach ($track->getPoints() as $trackPoint) {
                    $point = new Point();
                    $point->setLatitude($trackPoint->latitude);
                    $point->setLongitude($trackPoint->longitude);
                    $point->setElevation($trackPoint->elevation);
                    $route->addPoint($point);
                }
            }

            return $route;
        }



        throw new Exception('Please provide a valid file path.');
    }

    public function validateFile($gpx)
    {
        if (file_exists($gpx)) {
            return true;
        }

        return false;
    }
}