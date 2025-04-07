<?php

namespace UzDevid\Conflux\Http;

use GuzzleHttp\ClientInterface;

interface ConfigInterface {
    /**
     * @return ClientInterface
     */
    public function getClient(): ClientInterface;

    /**
     * @return string
     */
    public function getBaseUri(): string;

    /**
     * @return array<string, string>
     */
    public function getDefaultHeaders(): array;
}