<?php declare(strict_types=1);

namespace UzDevid\Conflux\Http\Event;

use Throwable;
use UzDevid\Conflux\Http\Request\RequestInterface;
use UzDevid\Conflux\Http\Response\ResponseInterface;

class OnThrow {
    /**
     * @param RequestInterface $request
     * @param ResponseInterface|null $response
     * @param Throwable $exception
     */
    public function __construct(
        public RequestInterface $request,
        public ResponseInterface|null $response,
        public Throwable        $exception
    ) {
    }
}