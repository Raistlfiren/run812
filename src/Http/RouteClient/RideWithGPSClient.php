<?php

namespace App\Http\RouteClient;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class RideWithGPSClient implements RouteClientInterface
{
    public const API_URL = 'https://ridewithgps.com';
    private HttpClientInterface $client;
    private string $rideWithGPSEmail;
    private string $rideWithGPSPassword;
    private string $rideWithGPSAPIKey;

    private string $authToken = '';
    private string $userID = '';

    public function __construct(
        HttpClientInterface $client,
        string $rideWithGPSEmail,
        string $rideWithGPSPassword,
        string $rideWithGPSAPIKey,
    )
    {
        $this->client = $client;
        $this->rideWithGPSEmail = $rideWithGPSEmail;
        $this->rideWithGPSPassword = $rideWithGPSPassword;
        $this->rideWithGPSAPIKey = $rideWithGPSAPIKey;
    }

    public function authenticate(): void
    {
        if (! empty($this->getAuthToken())) {
            return;
        }

        // Generate a unique token to download routes individually
        $response = $this->client->request(
            'GET',
            self::API_URL . '/users/current.json',
            [
                'query' => [
                    'email' => $this->rideWithGPSEmail,
                    'password' => $this->rideWithGPSPassword,
                    'apikey' => $this->rideWithGPSAPIKey
                ]
            ]
        );

        if (! $this->isResponseOK($response)) {
            throw new ClientException('Authenticate response returned invalid status code.');
        }

        $data = $response->toArray();

        if (
            $data &&
            array_key_exists('user', $data) &&
            array_key_exists('auth_token', $data['user']) &&
            array_key_exists('id', $data['user'])
        ) {
            $this->setAuthToken($data['user']['auth_token']);
            $this->setUserID($data['user']['id']);
        } else {
            throw new DataException('Authenticate response returned invalid data.');
        }

        return;
    }

    public function fetchAllRoutes(): array
    {
        $this->authenticate();

        $queryParameters = [
            'offset' => 0,
            'limit' => 500,
        ];

        // Fetch a list of all private routes from Ride With GPS
        $response = $this->client->request(
            'GET',
            self::API_URL . '/users/'.$this->getUserID().'/routes.json',
            [
                'query' => array_merge($queryParameters, $this->appendQueryParameters($this->getAuthToken()))
            ]
        );

        if (! $this->isResponseOK($response)) {
            throw new ClientException('Fetch all routes response returned invalid status code.');
        }

        $data = $response->toArray();

        if (! array_key_exists('results', $data) || count($data['results']) < 1) {
            throw new DataException('Fetch all routes response returned invalid data.');
        }

        return $data['results'];
    }

    public function fetchRoute(string $routeID): array
    {
        $this->authenticate();

        // Fetch the routes complete JSON to store in the database
        $response = $this->client->request(
            'GET',
            self::API_URL . '/routes/'.$routeID.'.json',
            [
                'query' => $this->appendQueryParameters($this->getAuthToken())
            ]
        );

        if (! $this->isResponseOK($response)) {
            throw new ClientException('Fetch route response returned invalid status code.');
        }

        $data = $response->toArray();

        if (! array_key_exists('type', $data) || ! array_key_exists('route', $data)) {
            throw new DataException('Fetch route response returned invalid data.');
        }

        return $data;
    }

    public function fetchThumbnail(string $routeID): string
    {
        $this->authenticate();

        // Fetch the static PNG image
        $response = $this->client->request(
            'GET',
            self::API_URL . '/routes/'.$routeID.'/hover_preview.png',
            [
                'query' => $this->appendQueryParameters($this->getAuthToken())
            ]
        );

        if (! $this->isResponseOK($response)) {
            throw new ClientException('Fetch thumbnail route response returned invalid status code.');
        }

        return $response->getContent();
    }

    protected function isResponseOK(ResponseInterface $response)
    {
        return $response->getStatusCode() === Response::HTTP_OK;
    }

    private function appendQueryParameters(string $authToken)
    {
        return [
            'version' => 2,
            'auth_token' => $authToken,
            'apikey' => $this->rideWithGPSAPIKey
        ];
    }

    /**
     * @return string
     */
    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    /**
     * @param string $authToken
     * @return RideWithGPSClient
     */
    public function setAuthToken(string $authToken): RideWithGPSClient
    {
        $this->authToken = $authToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserID(): string
    {
        return $this->userID;
    }

    /**
     * @param string $userID
     * @return RideWithGPSClient
     */
    public function setUserID(string $userID): RideWithGPSClient
    {
        $this->userID = $userID;
        return $this;
    }
}