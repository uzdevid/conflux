<?php

namespace UzDevid\Conflux\Http;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use UzDevid\Conflux\Http\Response\Response;
use UzDevid\Conflux\Http\Request\RequestInterface;

interface RequestHandlerInterface {
    /**
     * @param RequestInterface $request
     * @return RequestHandlerInterface
     */
    public function withRequest(RequestInterface $request): RequestHandlerInterface;

    /**
     * @param ConfigInterface $config
     * @return RequestHandlerInterface
     */
    public function withConfig(ConfigInterface $config): RequestHandlerInterface;

    /**
     * @return Response
     * @throws GuzzleException
     * @throws ServerException
     * @throws ClientException
     */
    public function handle(): Response;
}