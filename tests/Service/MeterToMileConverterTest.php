<?php

namespace App\Tests\Service;

use App\Service\MeterToMileConverter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MeterToMileConverterTest extends KernelTestCase
{
    /**
     * @test
     */
    public function convert_to_miles()
    {
        $value = MeterToMileConverter::convertToMiles(19312.1);

        $this->assertEquals(12, $value);
    }
}