<?php

namespace Nodest\Framework\Http\Middleware\AuthenticatedMiddleware;

use Nodest\Framework\Http\Middleware\MiddlewareInterface;
use Nodest\Framework\Http\Middleware\RequestHandlerMiddleware\RequestHandlerInterface;
use Nodest\Framework\Http\Request\Request;
use Nodest\Framework\Http\Response\RedirectResponse;
use Nodest\Framework\Http\Response\Response;
use Nodest\Framework\Session\SessionInterface;
use Nodest\Framework\SessionAuthenticated\SessionAuthInterface;

class Authenticated implements MiddlewareInterface
{
    public function __construct(
        private SessionAuthInterface $auth,
        private SessionInterface $session
    ) {}

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $this->session->start();

        if (! $this->auth->check()) {
            $this->session->setFlash('errorAuth', 'To get started, you need to sign in to your account.');

            return new RedirectResponse('/user/login');
        }

        return $handler->handle($request);
    }
}
