<?php declare(strict_types=1);

namespace UzDevid\Conflux\Http\Tests\Support\Profile;

use GuzzleHttp\ClientInterface;
use UzDevid\Conflux\Http\ConfigInterface;

readonly class ProfileConfig implements ConfigInterface {
    /**
     * @param ClientInterface $client
     */
    public function __construct(
        private ClientInterface $client
    ) {
    }

    /**
     * @return ClientInterface
     */
    public function getClient(): ClientInterface {
        return $this->client;
    }

    /**
     * @return string
     */
    public function getBaseUri(): string {
        return 'https://id.uzdevid.com';
    }

    /**
     * @return array
     */
    public function getDefaultHeaders(): array {
        return [];
    }
}