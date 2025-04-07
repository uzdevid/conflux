<?php

namespace UzDevid\Conflux\Http;

use UzDevid\Conflux\Http\Exception\ClientErrorException;
use UzDevid\Conflux\Http\Exception\ConfluxException;
use UzDevid\Conflux\Http\Exception\ServerErrorException;
use UzDevid\Conflux\Http\Request\RequestInterface;

interface ConfluxHttpInterface {
    /**
     * @param RequestInterface $request
     * @return ConfluxHttpInterface
     */
    public function withRequest(RequestInterface $request): ConfluxHttpInterface;

    /**
     * @return mixed
     * @throws ClientErrorException
     * @throws ServerErrorException
     * @throws ConfluxException
     */
    public function send(): mixed;
}