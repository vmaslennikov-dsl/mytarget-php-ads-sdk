<?php

namespace MyTarget\Transport\Middleware;

use MyTarget\Transport\HttpTransport;
use MyTarget\Transport\Middleware\Impl\TerminatingMiddleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpMiddlewareStack
{
    /**
     * @var \SplStack|HttpMiddleware[]
     */
    protected $middlewares;

    /**
     * @var HttpTransport
     */
    protected $http;

    /**
     * @param \SplStack|HttpMiddleware[] $middlewares
     * @param HttpTransport $http
     */
    public function __construct(\SplStack $middlewares, HttpTransport $http)
    {
        $this->middlewares = clone $middlewares;
        $this->http = $http;
    }

    /**
     * @param RequestInterface $request
     * @param array|null $context
     *
     * @return ResponseInterface
     */
    public function request(RequestInterface $request, array $context = null)
    {
        $middlewares = clone $this->middlewares;
        $stack = new HttpMiddlewareStack($middlewares, $this->http);

        return $stack->pop()->request($request, $stack, $context);
    }

    /**
     * @return HttpMiddleware
     */
    private function pop()
    {
        return $this->middlewares->isEmpty() ? new TerminatingMiddleware($this->http) : $this->middlewares->pop();
    }
}
