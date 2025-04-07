<?php declare(strict_types=1);

namespace UzDevid\Conflux\Http\Tests\Support\Profile\Request;

use UzDevid\Conflux\Http\Parser\JsonParser;
use UzDevid\Conflux\Http\Request\Method;
use UzDevid\Conflux\Http\Request\RequestInterface;

class GetProfile implements RequestInterface {
    use JsonParser;

    /**
     * @return Method
     */
    public function getMethod(): Method {
        return Method::GET;
    }

    /**
     * @return string
     */
    public function getUrl(): string {
        return '/me';
    }
}