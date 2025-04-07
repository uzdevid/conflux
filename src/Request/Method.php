<?php

namespace UzDevid\Conflux\Http\Request;

enum Method {
    case POST;
    case GET;
    case PUT;
    case PATCH;
    case DELETE;

    /**
     * @return string
     */
    public function toString(): string {
        return $this->name;
    }
}
