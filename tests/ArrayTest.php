<?php

namespace AntonioKadid\WAPPKitCore\Tests\Reflection;

use AntonioKadid\WAPPKitCore\Reflection\ClosureInvoker;
use AntonioKadid\WAPPKitCore\Reflection\Exceptions\InvalidParameterValueException;
use AntonioKadid\WAPPKitCore\Reflection\Exceptions\UnknownParameterTypeException;
use PHPUnit\Framework\TestCase;
use ReflectionException;

/**
 * Class ArrayTest
 *
 * @package AntonioKadid\WAPPKitCore\Tests\Reflection
 */
class ArrayTest extends TestCase
{
    /**
     * @throws InvalidParameterValueException
     * @throws UnknownParameterTypeException
     * @throws ReflectionException
     */
    public function testClosure()
    {
        $invoker = new ClosureInvoker(
            function (array $value) {
                return $value;
            });

        $result = $invoker->invoke(['value' => [5, 10, 15]]);
        $this->assertIsArray($result);
        $this->assertEquals(5, $result[0]);
        $this->assertEquals(10, $result[1]);
        $this->assertEquals(15, $result[2]);
    }
}