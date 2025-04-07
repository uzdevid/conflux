<?php declare(strict_types=1);

namespace UzDevid\Conflux\Http\Event;

use Throwable;
use UzDevid\Conflux\Http\Request\RequestInterface;

class onThrow {
    /**
     * @param RequestInterface $request
     * @param Throwable $exception
     */
    public function __construct(
        public RequestInterface $request,
        public Throwable        $exception
    ) {
    }
}