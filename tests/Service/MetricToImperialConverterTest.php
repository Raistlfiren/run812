<?php

namespace App\Tests\Service;

use App\Service\MetricToImperialConverter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MetricToImperialConverterTest extends KernelTestCase
{
    /**
     * @test
     */
    public function convert_to_miles()
    {
        $value = MetricToImperialConverter::convertMetersToMiles(19312.1);

        $this->assertEquals(12, $value);
    }

    /**
     * @test
     */
    public function convert_to_feet()
    {
        $value = MetricToImperialConverter::convertMetersToFeet(100);

        $this->assertEquals(328, $value);
    }
}