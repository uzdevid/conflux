<?php

namespace UzDevid\Conflux\Http\Response;

interface ResponseInterface {
    /**
     * @return string
     */
    public function getBody(): string;

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function psr(): \Psr\Http\Message\ResponseInterface;
}