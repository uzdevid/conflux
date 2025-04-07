<?php
declare(strict_types=1);

namespace UzDevid\Conflux\Http\Event;

use UzDevid\Conflux\Http\Request\RequestInterface;

class BeforeRequest {
    /**
     * @param RequestInterface $request
     */
    public function __construct(
        public RequestInterface $request
    ) {
    }
}