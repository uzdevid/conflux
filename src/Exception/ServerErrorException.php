<?php
declare(strict_types=1);

namespace UzDevid\Conflux\Http\Exception;

use Exception;
use GuzzleHttp\Exception\ServerException;
use UzDevid\Conflux\Http\Request\RequestInterface;
use UzDevid\Conflux\Http\Response\ResponseInterface;

class ServerErrorException extends Exception {
    /**
     * @param ResponseInterface $response
     * @param RequestInterface $request
     * @param ServerException $previous
     */
    public function __construct(
        private readonly ResponseInterface $response,
        private readonly RequestInterface  $request,
        private readonly ServerException   $previous
    ) {
        parent::__construct($this->previous->getMessage(), $this->previous->getCode(), $this->previous);
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface {
        return $this->response;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface {
        return $this->request;
    }
}