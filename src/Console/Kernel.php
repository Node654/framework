<?php

namespace Nodest\Framework\Console;

use Psr\Container\ContainerInterface;

class Kernel
{
    public function __construct(
        private ContainerInterface $container,
        private Application $application
    ) {}

    public function handle(): int
    {
        $this->registerCommands();

        $status = $this->application->run();

        return $status;
    }

    private function registerCommands()
    {
        $commandFiles = new \DirectoryIterator(__DIR__.'/Commands');
        $namespace = $this->container->get('framework-console-commands-namespaces');
        foreach ($commandFiles as $commandFile) {
            if (! $commandFile->isFile()) {
                continue;
            }

            $command = $namespace.pathinfo($commandFile->getFilename(), PATHINFO_FILENAME);

            if (is_subclass_of($command, CommandInterface::class)) {
                $name = (new \ReflectionClass($command))
                    ->getProperty('name')
                    ->getDefaultValue();
                $this->container->add("console:$name", $command);
            }
        }
    }
}
