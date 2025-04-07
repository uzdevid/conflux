<?php declare(strict_types=1);

namespace UzDevid\Conflux\Http\Parser;

use JsonException;
use UzDevid\Conflux\Http\Exception\ConfluxException;

trait JsonParser {
    /**
     * @param string $content
     * @return array
     * @throws ConfluxException
     */
    public function parse(string $content): array {
        try {
            return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new ConfluxException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}