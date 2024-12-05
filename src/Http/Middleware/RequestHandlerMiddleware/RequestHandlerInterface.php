<?php

namespace Nodest\Framework\Http\Middleware\RequestHandlerMiddleware;

use Nodest\Framework\Http\Request\Request;
use Nodest\Framework\Http\Response\Response;

interface RequestHandlerInterface
{
    public function handle(Request $request): Response;
}
