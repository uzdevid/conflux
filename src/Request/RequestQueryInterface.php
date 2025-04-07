<?php

namespace UzDevid\Conflux\Http\Request;

interface RequestQueryInterface {
    /**
     * @return array
     */
    public function getQueryParams(): array;

    /**
     * @return array
     */
    public function getQueryPath(): array;
}