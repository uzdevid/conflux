<?php

namespace UzDevid\Conflux\Http\Request;

interface RequestHeadersInterface {
    /**
     * @return array
     */
    public function getHeaders(): array;
}