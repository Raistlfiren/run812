<?php

namespace App\Tests\Http\RouteClient;

use App\Http\RouteClient\ClientException;
use App\Http\RouteClient\DataException;
use App\Http\RouteClient\RideWithGPSClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class RideWithGPSClientTest extends TestCase
{
    /**
     * @test
     */
    public function authenticate_test_success()
    {
        $mockResponseJson = file_get_contents(__DIR__ . '/../../Responses/Expected/authentication.json');
        $mockResponse = new MockResponse($mockResponseJson, [
            'http_code' => 200,
            'response_headers' => ['Content-Type: application/json'],
        ]);

        $httpClient = new MockHttpClient($mockResponse);

        $service = new RideWithGPSClient($httpClient, '', '', '');
        $service->authenticate();

        $this->assertSame('GET', $mockResponse->getRequestMethod());
        $options = $mockResponse->getRequestOptions();
        $this->assertSame($options['query'], ['email' => '', 'password' => '', 'apikey' => '']);
        $this->assertStringContainsString(RideWithGPSClient::API_URL . '/users/current.json', $mockResponse->getRequestUrl());
        $this->assertSame($service->getUserID(), '1234567');
        $this->assertSame($service->getAuthToken(), 'testToken');
        $this->assertEquals(1, $httpClient->getRequestsCount());
    }

    /**
     * @test
     */
    public function authenticate_test_has_auth_token()
    {
        $mockResponseJson = file_get_contents(__DIR__ . '/../../Responses/Expected/authentication.json');
        $mockResponse = new MockResponse($mockResponseJson, [
            'http_code' => 200,
            'response_headers' => ['Content-Type: application/json'],
        ]);

        $httpClient = new MockHttpClient($mockResponse);

        $service = new RideWithGPSClient($httpClient, '', '', '');
        $service->setAuthToken('testToken');
        $service->authenticate();

        $this->assertEquals(0, $httpClient->getRequestsCount());
        $this->assertEquals('testToken', $service->getAuthToken());
    }

    /**
     * @test
     */
    public function authenticate_test_failed_status_code()
    {
        $mockResponseJson = file_get_contents(__DIR__ . '/../../Responses/Expected/authentication.json');
        $mockResponse = new MockResponse($mockResponseJson, [
            'http_code' => 400,
            'response_headers' => ['Content-Type: application/json'],
        ]);

        $httpClient = new MockHttpClient($mockResponse);

        $this->expectException(ClientException::class);
        $this->expectExceptionMessage('Authenticate response returned invalid status code.');
        $service = new RideWithGPSClient($httpClient, '', '', '');
        $service->authenticate();
        $this->assertEquals(1, $httpClient->getRequestsCount());
    }

    /**
     * @test
     */
    public function authenticate_test_failed_data()
    {
        $mockResponseJson = file_get_contents(__DIR__ . '/../../Responses/Expected/authentication_failed.json');
        $mockResponse = new MockResponse($mockResponseJson, [
            'http_code' => 200,
            'response_headers' => ['Content-Type: application/json'],
        ]);

        $httpClient = new MockHttpClient($mockResponse);

        $this->expectException(DataException::class);
        $this->expectExceptionMessage('Authenticate response returned invalid data.');
        $service = new RideWithGPSClient($httpClient, '', '', '');
        $service->authenticate();
        $this->assertEquals(1, $httpClient->getRequestsCount());
    }

    /**
     * @test
     */
    public function fetch_all_test()
    {
        $mockResponseJson = file_get_contents(__DIR__ . '/../../Responses/Expected/fetch_all_routes.json');
        $mockResponse = new MockResponse($mockResponseJson, [
            'http_code' => 200,
            'response_headers' => ['Content-Type: application/json'],
        ]);

        $httpClient = new MockHttpClient($mockResponse);

        $service = new RideWithGPSClient($httpClient, '', '', '');
        $service->setAuthToken('testToken');
        $service->setUserID('1234567');
        $responseData = $service->fetchAllRoutes();

        $this->assertSame('GET', $mockResponse->getRequestMethod());
        $options = $mockResponse->getRequestOptions();
        $this->assertSame($options['query'], ['offset' => 0, 'limit' => 500, 'version' => 2, 'auth_token' => 'testToken', 'apikey' => '' ]);
        $this->assertStringContainsString(RideWithGPSClient::API_URL . '/users/'.$service->getUserID().'/routes.json', $mockResponse->getRequestUrl());
        $this->assertCount(2, $responseData);
        $this->assertEquals(1, $httpClient->getRequestsCount());
    }

    /**
     * @test
     */
    public function fetch_all_fail_status_code()
    {
        $mockResponseJson = file_get_contents(__DIR__ . '/../../Responses/Expected/fetch_all_routes.json');
        $mockResponse = new MockResponse($mockResponseJson, [
            'http_code' => 400,
            'response_headers' => ['Content-Type: application/json'],
        ]);

        $httpClient = new MockHttpClient($mockResponse);

        $this->expectException(ClientException::class);
        $this->expectExceptionMessage('Fetch all routes response returned invalid status code.');
        $service = new RideWithGPSClient($httpClient, '', '', '');
        $service->setAuthToken('testToken');
        $service->setUserID('1234567');
        $service->fetchAllRoutes();

        $this->assertEquals(1, $httpClient->getRequestsCount());
    }

    /**
     * @test
     */
    public function fetch_all_fail_data()
    {
        $mockResponseJson = file_get_contents(__DIR__ . '/../../Responses/Expected/fetch_all_routes_failed.json');
        $mockResponse = new MockResponse($mockResponseJson, [
            'http_code' => 200,
            'response_headers' => ['Content-Type: application/json'],
        ]);

        $httpClient = new MockHttpClient($mockResponse);

        $this->expectException(DataException::class);
        $this->expectExceptionMessage('Fetch all routes response returned invalid data.');
        $service = new RideWithGPSClient($httpClient, '', '', '');
        $service->setAuthToken('testToken');
        $service->setUserID('1234567');
        $service->fetchAllRoutes();

        $this->assertEquals(1, $httpClient->getRequestsCount());
    }

    /**
     * @test
     */
    public function fetch_single_route_test()
    {
        $mockResponseJson = file_get_contents(__DIR__ . '/../../Responses/Expected/fetch_individual_route.json');
        $mockResponse = new MockResponse($mockResponseJson, [
            'http_code' => 200,
            'response_headers' => ['Content-Type: application/json'],
        ]);

        $httpClient = new MockHttpClient($mockResponse);

        $service = new RideWithGPSClient($httpClient, '', '', '');
        $service->setAuthToken('testToken');
        $service->setUserID('1234567');

        $routeID = '138';
        $service->fetchRoute($routeID);

        $this->assertSame('GET', $mockResponse->getRequestMethod());
        $options = $mockResponse->getRequestOptions();
        $this->assertSame($options['query'], ['version' => 2, 'auth_token' => 'testToken', 'apikey' => '' ]);
        $this->assertStringContainsString(RideWithGPSClient::API_URL . '/routes/'.$routeID .'.json', $mockResponse->getRequestUrl());
        $this->assertEquals(1, $httpClient->getRequestsCount());
    }

    /**
     * @test
     */
    public function fetch_individual_route_fail_status_code()
    {
        $mockResponseJson = file_get_contents(__DIR__ . '/../../Responses/Expected/fetch_individual_route.json');
        $mockResponse = new MockResponse($mockResponseJson, [
            'http_code' => 400,
            'response_headers' => ['Content-Type: application/json'],
        ]);

        $httpClient = new MockHttpClient($mockResponse);

        $this->expectException(ClientException::class);
        $this->expectExceptionMessage('Fetch route response returned invalid status code.');
        $service = new RideWithGPSClient($httpClient, '', '', '');
        $service->setAuthToken('testToken');
        $service->setUserID('1234567');
        $service->fetchRoute('138');

        $this->assertEquals(1, $httpClient->getRequestsCount());
    }

    public function fetch_individual_route_fail_data()
    {
        $mockResponseJson = file_get_contents(__DIR__ . '/../../Responses/Expected/fetch_all_routes_failed.json');
        $mockResponse = new MockResponse($mockResponseJson, [
            'http_code' => 200,
            'response_headers' => ['Content-Type: application/json'],
        ]);

        $httpClient = new MockHttpClient($mockResponse);

        $this->expectException(DataException::class);
        $this->expectExceptionMessage('Fetch route response returned invalid data.');
        $service = new RideWithGPSClient($httpClient, '', '', '');
        $service->setAuthToken('testToken');
        $service->setUserID('1234567');
        $service->fetchRoute('138');

        $this->assertEquals(1, $httpClient->getRequestsCount());
    }

    /**
     * @test
     */
    public function fetch_thumbnail_test()
    {
        $mockResponseData = file_get_contents(__DIR__ . '/../../Responses/Expected/fetch_thumbnail.png');
        $mockResponse = new MockResponse($mockResponseData, [
            'http_code' => 200,
            'response_headers' => ['Content-Type: application/json'],
        ]);

        $httpClient = new MockHttpClient($mockResponse);

        $service = new RideWithGPSClient($httpClient, '', '', '');
        $service->setAuthToken('testToken');
        $service->setUserID('1234567');

        $routeID = '138';
        $service->fetchThumbnail($routeID);

        $this->assertSame('GET', $mockResponse->getRequestMethod());
        $options = $mockResponse->getRequestOptions();
        $this->assertSame($options['query'], ['version' => 2, 'auth_token' => 'testToken', 'apikey' => '' ]);
        $this->assertStringContainsString(RideWithGPSClient::API_URL . '/routes/'.$routeID .'/hover_preview.png', $mockResponse->getRequestUrl());
        $this->assertEquals(1, $httpClient->getRequestsCount());
    }

    /**
     * @test
     */
    public function fetch_thumbnail_fail_status_code()
    {
        $mockResponseData = file_get_contents(__DIR__ . '/../../Responses/Expected/fetch_thumbnail.png');
        $mockResponse = new MockResponse($mockResponseData, [
            'http_code' => 400,
            'response_headers' => ['Content-Type: application/json'],
        ]);

        $httpClient = new MockHttpClient($mockResponse);

        $this->expectException(ClientException::class);
        $this->expectExceptionMessage('Fetch thumbnail route response returned invalid status code.');
        $service = new RideWithGPSClient($httpClient, '', '', '');
        $service->setAuthToken('testToken');
        $service->setUserID('1234567');
        $service->fetchThumbnail('138');

        $this->assertEquals(1, $httpClient->getRequestsCount());
    }
}