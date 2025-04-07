<?php declare(strict_types=1);

namespace UzDevid\Conflux\Http;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use UzDevid\Conflux\Http\Response\Response;
use UzDevid\Conflux\Http\Request\RequestBodyInterface;
use UzDevid\Conflux\Http\Request\RequestHeadersInterface;
use UzDevid\Conflux\Http\Request\RequestInterface;
use UzDevid\Conflux\Http\Request\RequestQueryInterface;

final class RequestHandler implements RequestHandlerInterface {
    private ConfigInterface $config;
    private RequestInterface $request;

    /**
     * @param RequestInterface $request
     * @return RequestHandlerInterface
     */
    public function withRequest(RequestInterface $request): RequestHandlerInterface {
        $new = clone $this;
        $new->request = $request;
        return $new;
    }

    /**
     * @param ConfigInterface $config
     * @return RequestHandlerInterface
     */
    public function withConfig(ConfigInterface $config): RequestHandlerInterface {
        $new = clone $this;
        $new->config = $config;
        return $new;
    }

    /**
     * @return Response
     * @throws GuzzleException
     * @throws ServerException
     * @throws ClientException
     */
    public function handle(): Response {
        $options = $this->createRequestOptions();

        $psrResponse = $this->config->getClient()->request($this->prepareMethod(), $this->prepareUrl(), $options);

        return new Response($psrResponse);
    }

    /**
     * @return array
     */
    private function createRequestOptions(): array {
        return [
            'headers' => $this->prepareHeaders(),
            'query' => $this->prepareQueries(),
            ...$this->prepareBody()
        ];
    }

    /**
     * @return string
     */
    private function prepareMethod(): string {
        if (is_string($this->request->getMethod())) {
            return $this->request->getMethod();
        }

        return $this->request->getMethod()->toString();
    }

    /**
     * @return array
     */
    private function prepareQueries(): array {
        if ($this->request instanceof RequestQueryInterface) {
            return $this->request->getQueryParams();
        }

        return [];
    }

    /**
     * @return string
     */
    private function prepareUrl(): string {
        if ($this->request instanceof RequestQueryInterface && !empty($this->request->getQueryPath())) {
            $queryPaths = $this->request->getQueryPath();
            return str_replace(array_keys($queryPaths), array_values($queryPaths), $this->request->getUrl());
        }

        return $this->request->getUrl();
    }

    /**
     * @return array
     */
    private function prepareHeaders(): array {
        $headers = $this->config->getDefaultHeaders();

        if ($this->request instanceof RequestHeadersInterface) {
            $headers = array_merge($headers, $this->request->getHeaders());
        }

        return $headers;
    }

    /**
     * @return array<string, array|string|null>
     */
    private function prepareBody(): array {
        if ($this->request instanceof RequestBodyInterface) {
            return [$this->request->getOption() => $this->request->getBody()];
        }

        return [];
    }
}