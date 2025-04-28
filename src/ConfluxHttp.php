<?php declare(strict_types=1);

namespace UzDevid\Conflux\Http;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use Psr\EventDispatcher\EventDispatcherInterface;
use UzDevid\Conflux\Http\Event\AfterRequest;
use UzDevid\Conflux\Http\Event\BeforeRequest;
use UzDevid\Conflux\Http\Event\OnThrow;
use UzDevid\Conflux\Http\Exception\ClientErrorException;
use UzDevid\Conflux\Http\Exception\ConfluxException;
use UzDevid\Conflux\Http\Exception\ServerErrorException;
use UzDevid\Conflux\Http\Request\ConvertableBodyInterface;
use UzDevid\Conflux\Http\Request\RequestInterface;
use UzDevid\Conflux\Http\Response\Response;

final class ConfluxHttp implements ConfluxHttpInterface {
    private RequestInterface $request;

    /**
     * @param ConfigInterface $config
     * @param RequestHandlerInterface $requestHandler
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        private readonly ConfigInterface          $config,
        private readonly RequestHandlerInterface  $requestHandler,
        private readonly EventDispatcherInterface $eventDispatcher
    ) {
    }

    /**
     * @param RequestInterface $request
     * @return ConfluxHttp
     */
    public function withRequest(RequestInterface $request): ConfluxHttpInterface {
        $new = clone $this;
        $new->request = $request;
        return $new;
    }

    /**
     * @return mixed
     * @throws ClientErrorException
     * @throws ConfluxException
     * @throws ServerErrorException
     */
    public function send(): mixed {
        $this->eventDispatcher->dispatch(new BeforeRequest($this->request));

        try {
            $response = $this->requestHandler->withConfig($this->config)->withRequest($this->request)->handle();
        } catch (ClientException $exception) {
            $throw = new ClientErrorException(new Response($exception->getResponse()), $this->request, $exception);
            $this->eventDispatcher->dispatch(new OnThrow($this->request, $throw));
            throw $throw;
        } catch (ServerException $exception) {
            $throw = new ServerErrorException(new Response($exception->getResponse()), $this->request, $exception);
            $this->eventDispatcher->dispatch(new OnThrow($this->request, $throw));
            throw $throw;
        } catch (GuzzleException $exception) {
            $throw = new ConfluxException($exception->getMessage(), $exception->getCode(), $exception);
            $this->eventDispatcher->dispatch(new OnThrow($this->request, $throw));
            throw $throw;
        }

        $this->eventDispatcher->dispatch(new AfterRequest($this->request, $response));

        $parsedBody = $this->request->parse($response->getBody());

        if ($this->request instanceof ConvertableBodyInterface) {
            return $this->request->convert($parsedBody);
        }

        return $parsedBody;
    }
}