<?php

namespace AntonioKadid\WAPPKitCore\Tests\Reflection;

use AntonioKadid\WAPPKitCore\Reflection\ClosureInvoker;
use AntonioKadid\WAPPKitCore\Reflection\Exceptions\InvalidParameterValueException;
use AntonioKadid\WAPPKitCore\Reflection\Exceptions\UnknownParameterTypeException;
use PHPUnit\Framework\TestCase;
use ReflectionException;

/**
 * Class NonKeyValuePairTest
 *
 * @package AntonioKadid\WAPPKitCore\Tests\Reflection
 */
class NonKeyValuePairTest extends TestCase
{
    /**
     * @throws InvalidParameterValueException
     * @throws UnknownParameterTypeException
     * @throws ReflectionException
     */
    public function testNonKeyValuePair()
    {
        $invoker = new ClosureInvoker(function (int $number, string $text, bool $value) {
            return $value ? sprintf('%s_%d', $text, $number) : sprintf('%d_%s', $number, $text);
        });

        $data = [1, 'test', TRUE];
        $result = $invoker->invoke($data, FALSE);
        $this->assertEquals('test_1', $result);

        $data = [1, 'test', FALSE];
        $result = $invoker->invoke($data, FALSE);
        $this->assertEquals('1_test', $result);

        $data = [1, 'test'];
        $result = $invoker->invoke($data, FALSE);
        $this->assertEquals('1_test', $result);
    }
}