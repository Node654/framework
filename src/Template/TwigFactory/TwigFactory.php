<?php

namespace Nodest\Framework\Template\TwigFactory;

use Nodest\Framework\Session\SessionInterface;
use Nodest\Framework\SessionAuthenticated\SessionAuthInterface;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TwigFactory
{
    public function __construct(
        private string $viewsPath,
        private SessionInterface $session,
        private SessionAuthInterface $auth
    ) {}

    public function create()
    {
        $loader = new FilesystemLoader($this->viewsPath);
        $twig = new Environment($loader, [
            'debug' => true,
            'cache' => false,
        ]);
        $twig->addExtension(new DebugExtension);
        $twig->addFunction(new TwigFunction('session', [$this, 'getSession']));
        $twig->addFunction(new TwigFunction('auth', [$this, 'getAuth']));

        return $twig;
    }

    public function getSession()
    {
        return $this->session;
    }

    public function getAuth()
    {
        return $this->auth;
    }
}
