<?php

use App\Providers\EventServiceProvider;
use App\Services\UserService\UserService;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Tools\DsnParser;
use Dotenv\Dotenv;
use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Nodest\Framework\Console\Application;
use Nodest\Framework\Console\Commands\ControllerCommand;
use Nodest\Framework\Console\Commands\MigrateCommand;
use Nodest\Framework\Console\Kernel as ConsoleKernel;
use Nodest\Framework\Controller\AbstractController;
use Nodest\Framework\Dbal\ConnectionFactory;
use Nodest\Framework\Event\EventDispatcher\EventDispatcher;
use Nodest\Framework\Event\ListenerProvider\ListenerProvider;
use Nodest\Framework\Http\Kernel;
use Nodest\Framework\Http\Middleware\ExtractRouteMiddleware\ExtractRoute;
use Nodest\Framework\Http\Middleware\RequestHandlerMiddleware\RequestHandler;
use Nodest\Framework\Http\Middleware\RequestHandlerMiddleware\RequestHandlerInterface;
use Nodest\Framework\Http\Middleware\RouterDispatchMiddleware\RouterDispatch;
use Nodest\Framework\Providers\ServiceProviderInterface;
use Nodest\Framework\Routing\Router;
use Nodest\Framework\Routing\RouterInterface;
use Nodest\Framework\Session\Session;
use Nodest\Framework\Session\SessionInterface;
use Nodest\Framework\SessionAuthenticated\SessionAuthentication;
use Nodest\Framework\SessionAuthenticated\SessionAuthInterface;
use Nodest\Framework\Template\TwigFactory\TwigFactory;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

$routes = require_once dirname(__DIR__).'/routes/web.php';
$views = dirname(__DIR__).'/views';
$appEnv = $_ENV['APP_ENV'] ?? 'local';
$migrationsPath = dirname(__DIR__).'/database/migrations';
$databaseUrl = 'pdo-mysql://root:123456@mysql:3306/php_framework?charset=utf8mb4';
$controllersPath = dirname(__DIR__).'/app/Controllers/';
$basePath = dirname(__DIR__);
$parser = new DsnParser;
$connectionParams = $parser->parse($databaseUrl);

$dotenv = Dotenv::createImmutable($basePath);
$dotenv->load();

$container = new Container;

$container->delegate(
    new ReflectionContainer(true)
);

$container->add('base-path', new StringArgument($basePath));

$container->addShared(SessionInterface::class, Session::class);

$container->addShared('twig-factory', TwigFactory::class)
    ->addArguments([new StringArgument($views), SessionInterface::class, SessionAuthInterface::class]);
$container->addShared('twig', function () use ($container) {
    return $container->get('twig-factory')->create();
});
$container->inflector(AbstractController::class)->invokeMethod('setContainer', [$container]);
$container->add('APP_ENV', new StringArgument($appEnv));

$container->add(RouterInterface::class, Router::class);

$container->add(RequestHandlerInterface::class, RequestHandler::class)->addArgument($container);

$container->addShared(Kernel::class)
    ->addArguments([$container, RequestHandlerInterface::class, EventDispatcher::class]);

$container->add(ConnectionFactory::class)
    ->addArgument(new ArrayArgument($connectionParams));

$container->addShared(Connection::class, function () use ($container): Connection {
    return $container->get(ConnectionFactory::class)->create();
});

$container->addShared(RouterDispatch::class)->addArguments([RouterInterface::class, $container]);

$container->add(Application::class)
    ->addArgument($container);

$container->add('framework-console-commands-namespaces', new StringArgument('Nodest\\Framework\\Console\\Commands\\'));

$container->add(SessionAuthInterface::class, SessionAuthentication::class)
    ->addArguments([
        UserService::class,
        SessionInterface::class,
    ]);

$container->add(ExtractRoute::class)
    ->addArgument($routes);

$container->addShared(ListenerProviderInterface::class, ListenerProvider::class);

$container->addShared(EventDispatcherInterface::class, EventDispatcher::class)->addArgument(ListenerProviderInterface::class);

$container->addShared(ConsoleKernel::class)
    ->addArgument($container)
    ->addArgument(Application::class);

$container->add('console:migrate', MigrateCommand::class)
    ->addArgument(Connection::class)
    ->addArgument(new StringArgument($migrationsPath));

$container->add('console:controller', ControllerCommand::class)
    ->addArgument(new StringArgument($controllersPath));

return $container;
