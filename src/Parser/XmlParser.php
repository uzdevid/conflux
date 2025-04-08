<?php declare(strict_types=1);

namespace UzDevid\Conflux\Http\Parser;

use JsonException;
use UzDevid\Conflux\Http\Exception\ConfluxException;

trait XmlParser {
    /**
     * @param string $content
     * @return array
     * @throws ConfluxException
     */
    public function parse(string $content): array {
        libxml_use_internal_errors(true);

        $xml = simplexml_load_string($content, "SimpleXMLElement", LIBXML_NOCDATA);
        if ($xml === false) {
            $errors = libxml_get_errors();
            $errorMessages = array_map(static fn($error) => $error->message, $errors);
            libxml_clear_errors();
            throw new ConfluxException("XML parsing error: " . implode(", ", $errorMessages));
        }

        try {
            $json = json_encode($xml, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new ConfluxException($e->getMessage(), $e->getCode(), $e);
        }

        try {
            $array = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new ConfluxException($e->getMessage(), $e->getCode(), $e);
        }

        return $array;
    }

}