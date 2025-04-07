<?php

namespace UzDevid\Conflux\Http\Request;

interface ConvertableBodyInterface {
    /**
     * @param array $response
     * @return mixed
     */
    public function convert(array $response): mixed;
}