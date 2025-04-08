<p align="center">
    <a href="https://github.com/uzdevid" target="_blank">
        <img src="https://github.com/user-attachments/assets/e29daa5f-ac8f-47aa-b927-40400a6b5626" height="100px" alt="Yii">
    </a>
    <h1 align="center">Conflux HTTP</h1>
    <br>
</p>

[![Latest Stable Version](https://poser.pugx.org/uzdevid/conflux-http/v)](https://packagist.org/packages/uzdevid/conflux-http)
[![Total Downloads](https://poser.pugx.org/uzdevid/conflux-http/downloads)](https://packagist.org/packages/uzdevid/conflux-http)

Conflux HTTP — This is a flexible architecture for integration with external HTTP services, built on the basis of the PSR-18 and Guzzle. Each request is made out as a separate class that implements the necessary interfaces, providing readability, testability and expansion.

## Requirements

- PHP 8.3 or higher.

## Installation

The package could be installed with [Composer](https://getcomposer.org):

```shell
composer require uzdevid/conflux-http
```

## Quick start

### 1. Configuration

```php
class GithubConfig implements UzDevid\Conflux\Http\ConfigInterface {
    public function getClient(): ClientInterface {
        return new GuzzleHttp\Client;
    }

    public function getBaseUri(): string {
        return 'https://api.github.com';
    }

    public function getDefaultHeaders(): array {
        return ['Accept' => 'application/json'];
    }
}
```

### 2. Create request class

```php
use UzDevid\Conflux\Http\Request\RequestInterface;
use UzDevid\Conflux\Http\Request\RequestQueryInterface;
use UzDevid\Conflux\Http\Request\RequestBodyInterface;
use UzDevid\Conflux\Http\Request\ConvertableBodyInterface;
use UzDevid\Conflux\Http\Request\Method;
use UzDevid\Conflux\Http\Parser\JsonParser;

class GetUser implements RequestInterface, RequestQueryInterface, ConvertableBodyInterface {
    use JsonParser;
    
    public function getMethod(): Method {
        return Method::GET;
    }

    public function getUrl(): string {
        return '/users/{id}';
    }

    public function getQueryParams(): array {
        return [];
    }

    public function getQueryPath(): array {
        return ['{id}' => 123];
    }

    public function convert(array $response): UserDto {
        return new UserDto($response);
    }
}
```

### 3. Send request

```php
use UzDevid\Conflux\Http\ConfluxHttp;
use \Yiisoft\EventDispatcher\Dispatcher\Dispatcher;

$config = new GithubConfig();
$conflux = new ConfluxHttp($config, new RequestHandler(), new Dispatcher();
$response = $conflux->withRequest(new GetUser())->send(); // UserDto
```

---

## Implemented request interfaces

### RequestInterface

Mandatory interface, sets the method, path and parser answer:

```php
interface RequestInterface {
    public function getMethod(): Method|string;
    public function getUrl(): string;
    public function parse(string $content): array;
}
```

> You can use trait `Uzdevid\Conflux\Http\Parser\JsonParser` to pars the answers in JSON format

### RequestQueryInterface

If you need to send the query parameters or specify them to the URL:

```php
interface RequestQueryInterface {
    public function getQueryParams(): array;
    public function getQueryPath(): array;
}
```

### RequestBodyInterface

If you need send body (`POST`, `PUT` etc.):

```php
interface RequestBodyInterface {
    public function getOption(): Option; // Например, 'json', 'form_params'
    public function getBody(): array|string;
}
```

### RequestHeadersInterface

Custom headers:

```php
interface RequestHeadersInterface {
    public function getHeaders(): array;
}
```

### ConvertableBodyInterface

If after parsing the body needs to be converted into an object:

```php
interface ConvertableBodyInterface {
    public function convert(array $response): mixed;
}
```

---

## Example with POST request and body:

```php
class CreateUser implements RequestInterface, RequestBodyInterface {
    use JsonParser;
    
    public function getMethod(): Method {
        return Method::POST;
    }

    public function getUrl(): string {
        return '/users';
    }

    public function getOption(): Option|string {
        return 'json';
    }

    public function getBody(): array|string {
        return ['name' => 'John', 'email' => 'john@example.com'];
    }
}
```

---

### URL replacement
If the `getUrl()` contains the placeholders `{}`, they will be automatically replaced by values from `getQueryPath()`.

```text
getUrl(): '/users/{id}'
getQueryPath(): ['{id}' => 5]
```
Result: `GET /users/5`

---

## Handle events

You can subscribe to the events of `BeforeRequest`, `AfterRequest` and `OnThrow` using the `EventDispatcherInterface` from the package [yiisoft/event-dispatcher](https://github.com/yiisoft/event-dispatcher)

---

If you have suggestions or features, open it issue or write Pull Request ✨