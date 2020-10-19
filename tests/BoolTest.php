<?php

namespace AntonioKadid\WAPPKitCore\Tests\Reflection;

use AntonioKadid\WAPPKitCore\Reflection\ClosureInvoker;
use AntonioKadid\WAPPKitCore\Reflection\Exceptions\InvalidParameterValueException;
use AntonioKadid\WAPPKitCore\Reflection\Exceptions\UnknownParameterTypeException;
use PHPUnit\Framework\TestCase;
use ReflectionException;

/**
 * Class BoolTest
 *
 * @package AntonioKadid\WAPPKitCore\Tests\Reflection
 */
class BoolTest extends TestCase
{
    /**
     * @throws InvalidParameterValueException
     * @throws UnknownParameterTypeException
     * @throws ReflectionException
     */
    public function testClosure()
    {
        $invoker = new ClosureInvoker(
            function (bool $value) {
                return $value;
            });

        $result = $invoker->invoke(['value' => TRUE]);
        $this->assertTrue($result);

        $result = $invoker->invoke(['value' => 1]);
        $this->assertTrue($result);

        $result = $invoker->invoke(['value' => '1']);
        $this->assertTrue($result);

        $result = $invoker->invoke(['value' => FALSE]);
        $this->assertFalse($result);

        $result = $invoker->invoke(['value' => 0]);
        $this->assertFalse($result);

        $result = $invoker->invoke(['value' => '0']);
        $this->assertFalse($result);
    }
}