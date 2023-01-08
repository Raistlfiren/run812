<?php

namespace App\Service;

use App\Entity\Route;
use GeoJson\BoundingBox;
use GeoJson\Feature\Feature;
use GeoJson\Feature\FeatureCollection;
use GeoJson\Geometry\LineString;
use GeoJson\Geometry\Point;

class GeoJsonConverter
{
    public static function convertRoute(Route $route)
    {
        $line = [];
        $json = $route->getJsonRoute()['route'];

        foreach ($json['track_points'] as $trackPoint) {
            $line[] = [$trackPoint['x'], $trackPoint['y']];
        }

        $boundingBox = $json['bounding_box'];

        $track = new LineString($line);
        $box = new BoundingBox([$boundingBox[0]['lat'], $boundingBox[0]['lng'], $boundingBox[1]['lat'], $boundingBox[1]['lng']]);
        $trackFeature = new Feature($track, null, null, $box);

        $endPoint = new Point([$json['last_lng'], $json['last_lat']]);
        $startPoint = new Point([$json['first_lng'], $json['first_lat']]);
        return new FeatureCollection([$trackFeature, (new Feature($startPoint, ['name' => 'start'])), (new Feature($endPoint, ['name' => 'end']))]);
    }
}