<?php declare(strict_types=1);

namespace UzDevid\Conflux\Http\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use JetBrains\PhpStorm\NoReturn;
use JsonException;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Throwable;
use UzDevid\Conflux\Http\ConfluxHttp;
use UzDevid\Conflux\Http\ConfluxHttpInterface;
use UzDevid\Conflux\Http\Exception\ClientErrorException;
use UzDevid\Conflux\Http\Exception\ConfluxException;
use UzDevid\Conflux\Http\Exception\ServerErrorException;
use UzDevid\Conflux\Http\RequestHandler;
use UzDevid\Conflux\Http\Tests\Support\Profile\ProfileConfig;
use UzDevid\Conflux\Http\Tests\Support\Profile\Request\GetProfile;
use Yiisoft\EventDispatcher\Dispatcher\Dispatcher;
use Yiisoft\EventDispatcher\Provider\CompositeProvider;
use function is_null;

class ProfileIntegrationTest extends TestCase {
    /**
     * @param array $body
     * @param int $statusCode
     * @param array $headers
     * @param Throwable|null $exception
     * @return ConfluxHttp
     * @throws Exception
     * @throws JsonException
     */
    public function getConflux(array $body, int $statusCode = 200, array $headers = [], Throwable|null $exception = null): ConfluxHttpInterface {
        $mockClient = $this->createMock(Client::class);
        $mockResponse = new Response($statusCode, $headers, json_encode($body, JSON_THROW_ON_ERROR));
        $mockClient->method('request')->willReturn($mockResponse);

        if (!is_null($exception)) {
            $mockClient->method('request')->willThrowException($exception);
        }

        $eventDispatcher = new Dispatcher(new CompositeProvider());

        return new ConfluxHttp(new ProfileConfig($mockClient), new RequestHandler(), $eventDispatcher);
    }

    /**
     * @throws ClientErrorException
     * @throws ConfluxException
     * @throws Exception
     * @throws JsonException
     * @throws ServerErrorException
     */
    public function testIntegration(): void {
        $mockBody = ['success' => true, 'profile' => ['name' => 'John', 'surname' => 'Doe']];

        /** @var array $response */
        $response = $this
            ->getConflux($mockBody)
            ->withRequest(new GetProfile())->send();

        $this->assertEquals($response, $mockBody);
    }

    /**
     * @throws Exception
     * @throws ClientErrorException
     * @throws ConfluxException
     * @throws ServerErrorException
     * @throws JsonException
     */
    public function testServerError(): void {
        $this->expectException(ServerErrorException::class);

        /** @var array $response */
        $response = $this
            ->getConflux([], 500, [], new ServerException('Internal server error', new Request('get', 'fff'), new Response(500, [])))
            ->withRequest(new GetProfile())
            ->send();

        $this->assertEquals([], $response);
    }

    /**
     * @throws Exception
     * @throws ClientErrorException
     * @throws ConfluxException
     * @throws ServerErrorException
     * @throws JsonException
     */
    public function testClientError(): void {
        $this->expectException(ClientErrorException::class);

        /** @var array $response */
        $response = $this
            ->getConflux([], 403, [], new ClientException('Forbidden', new Request('get', 'fff'), new Response(403, [])))
            ->withRequest(new GetProfile())
            ->send();

        $this->assertEquals([], $response);
    }
}