<?php

namespace App\Http\RouteClient;

interface RouteClientInterface
{
    public function authenticate(): void;

    public function fetchAllRoutes(): array;

    public function fetchRoute(string $routeID): array;

    public function fetchThumbnail(string $routeID): string;
}