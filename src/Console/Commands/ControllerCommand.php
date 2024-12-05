<?php

namespace Nodest\Framework\Console\Commands;

use Nodest\Framework\Console\CommandInterface;

class ControllerCommand implements CommandInterface
{
    private string $name = 'controller';

    public function __construct(
        private string $controllersPath
    ) {}

    public function execute(array $arguments = []): int
    {
        if (file_exists($this->controllersPath.$arguments['path'])) {
            echo 'Such a controller has already been created!'.PHP_EOL;
        } else {
            $parts = explode('/', $arguments['path']);
            $fileName = array_pop($parts);
            $partPath = '';
            foreach ($parts as $part) {
                if (! is_dir($this->controllersPath.$part)) {
                    $partPath = $partPath.$part.'/';
                    mkdir($this->controllersPath.$partPath);
                }
            }

            if (str_ends_with($fileName, '.php')) {
                $fullPath = $this->controllersPath.$partPath.$fileName;
                $fileName = pathinfo($fileName, PATHINFO_FILENAME);
                $filePath = str_replace('/', '\\', '/'.$partPath);
                $namespace = mb_substr($filePath, 0, mb_strlen($filePath) - 1);
                file_put_contents($fullPath, trim("
<?php

namespace App\Controllers$namespace;

use Nodest\Framework\Controller\AbstractController;

class $fileName extends AbstractController
{
}
                "));

            }

            echo "The controller has been successfully created: $arguments[path]".PHP_EOL;
        }

        return 0;
    }
}
