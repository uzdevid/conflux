<?php

namespace UzDevid\Conflux\Http\Request;

interface RequestBodyInterface {
    /**
     * @return Option|string
     */
    public function getOption(): Option|string;
    
    /**
     * @return array|string
     */
    public function getBody(): array|string;
}