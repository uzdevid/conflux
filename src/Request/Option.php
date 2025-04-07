<?php

namespace UzDevid\Conflux\Http\Request;

enum Option {
    case JSON;
    case FORM_DATA;
    case FORM_URLENCODED;
}
