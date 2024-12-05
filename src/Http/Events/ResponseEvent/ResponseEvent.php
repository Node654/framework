<?php

namespace Nodest\Framework\Http\Events\ResponseEvent;

use Nodest\Framework\Event\Event;
use Nodest\Framework\Http\Request\Request;
use Nodest\Framework\Http\Response\Response;

class ResponseEvent extends Event
{
    public function __construct(
        private readonly Request $request,
        private readonly Response $response
    ) {}

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}
