<?php

namespace Nodest\Framework\Routing;

use Nodest\Framework\Http\Request;

interface RouterInterface
{
    public function dispatch(Request $request);
}
