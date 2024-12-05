<?php

namespace Nodest\Framework\Routing;

use League\Container\Container;
use Nodest\Framework\Http\Request\Request;

interface RouterInterface
{
    public function dispatch(Request $request, Container $container): array;
}
