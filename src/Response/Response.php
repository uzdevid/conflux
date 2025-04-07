<?php declare(strict_types=1);

namespace UzDevid\Conflux\Http\Response;

class Response implements ResponseInterface {
    private string $body;

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function __construct(
        private readonly \Psr\Http\Message\ResponseInterface $response
    ) {
    }

    /**
     * @return string
     */
    public function getBody(): string {
        return $this->body ?? ($this->body = $this->response->getBody()->getContents());
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function psr(): \Psr\Http\Message\ResponseInterface {
        return $this->response;
    }
}