<?php

namespace AntonioKadid\WAPPKitCore\Tests\Reflection;

use AntonioKadid\WAPPKitCore\Reflection\ClosureInvoker;
use AntonioKadid\WAPPKitCore\Reflection\ConstructorInvoker;
use PHPUnit\Framework\TestCase;

/**
 * Class ConstructorTest
 *
 * @package AntonioKadid\WAPPKitCore\Tests\Reflection
 */
class ConstructorTest extends TestCase
{
    public function testConstructor()
    {
        $invoker = new ConstructorInvoker(TheSample::class);

        $result = $invoker->invoke(['number' => 5, 'text' => 'test']);
        $this->assertEquals('test_5', $result->process());
    }

    public function testConstructorWithClosure()
    {
        $invoker = new ClosureInvoker(
            function (TheSample $instance) {
                return $instance->process();
            });

        $result = $invoker->invoke(['number' => 5, 'text' => 'test']);
        $this->assertEquals('test_5', $result);
    }
}


class TheSample
{
    private $_number;
    private $_text;

    public function __construct(int $number, string $text)
    {
        $this->_number = $number;
        $this->_text = $text;
    }

    public function process()
    {
        return sprintf('%s_%d', $this->_text, $this->_number);
    }
}