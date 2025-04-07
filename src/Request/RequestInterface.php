<?php

namespace UzDevid\Conflux\Http\Request;

use UzDevid\Conflux\Http\Exception\ConfluxException;

interface RequestInterface {
    /**
     * @return Method|string
     */
    public function getMethod(): Method|string;

    /**
     * @return string
     */
    public function getUrl(): string;

    /**
     * @param string $content
     * @return array
     * @throws ConfluxException
     */
    public function parse(string $content): array;
}