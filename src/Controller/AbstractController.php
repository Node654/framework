<?php

namespace Nodest\Framework\Controller;

use Nodest\Framework\Http\Request\Request;
use Nodest\Framework\Http\Response\Response;
use Psr\Container\ContainerInterface;

abstract class AbstractController
{
    protected ?ContainerInterface $container = null;

    protected Request $request;

    public function setContainer(?ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function render(string $view, array $params = [], ?Response $response = null)
    {
        $content = $this->container->get('twig')->render('/pages/'.$view, $params);

        $response ??= new Response;

        $response->setContent($content);

        return $response;
    }
}
