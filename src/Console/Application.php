<?php

namespace Nodest\Framework\Console;

use Psr\Container\ContainerInterface;

class Application
{
    public function __construct(
        private readonly ContainerInterface $container
    ) {}

    public function run(): int
    {
        $argv = $_SERVER['argv'];

        $commandName = $argv[1] ?? null;

        if (! isset($commandName)) {
            throw new ConsoleException('Invalid console command!');
        }

        $command = $this->container->get("console:$commandName");

        // options

        $argc = array_slice($argv, 2);

        $options = $this->parseOptions($argc);

        /**
         * @var CommandInterface $command
         */
        $command->execute($options);

        return 0;
    }

    private function parseOptions(array $argc): array
    {
        $options = [];

        foreach ($argc as $arg) {
            if (str_starts_with($arg, '--')) {
                $option = explode('=', substr($arg, 2));
                $options[$option[0]] = $option[1] ?? true;
            }
        }

        return $options;
    }
}
