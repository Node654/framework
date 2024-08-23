<?php

namespace Nodest\Framework\Tests;

use Nodest\Framework\Container\Container;
use Nodest\Framework\Container\Exceptions\ContainerException;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function test_getting_service_from_container()
    {
        $container = new Container;
        $container->add('nodest-code-one', One::class);
        $this->assertInstanceOf(One::class, $container->get('nodest-code-one'));
    }

    public function test_examination_container_exception()
    {
        $container = new Container;
        $this->expectException(ContainerException::class);
        $container->add('nodest-code-one');
    }

    public function test_add_service_to_container()
    {
        $container = new Container;
        $container->add('nodest-code-two', Two::class);
        $this->assertTrue($container->has('nodest-code-two'));
    }

    public function test_has_service_returns_true_when_service_exists()
    {
        $container = new Container;
        $container->add('nodest-code-two', Two::class);
        $this->assertTrue($container->has('nodest-code-two'));
        $this->assertFalse($container->has('no-class'));
    }

    public function test_has_method()
    {
        $container = new Container;
        $container->add('nodest-code-two', Two::class);
        $this->assertTrue($container->has('nodest-code-two'));
        $this->assertFalse($container->has('no-class'));
    }

    public function test_recursively_autowired()
    {
        $container = new Container;
        $container->add('nodest-code-one', One::class);
        $one = $container->get('nodest-code-one');
        $two = $one->getTwo();
        $this->assertInstanceOf(Tree::class, $two->getTree());
        $this->assertInstanceOf(Pho::class, $two->getPho());
    }
}
