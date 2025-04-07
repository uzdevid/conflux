<?php declare(strict_types=1);

namespace UzDevid\Conflux\Http\Event;

use UzDevid\Conflux\Http\Request\RequestInterface;
use UzDevid\Conflux\Http\Response\ResponseInterface;

class AfterRequest {
    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     */
    public function __construct(
        public RequestInterface  $request,
        public ResponseInterface $response
    ) {
    }
}