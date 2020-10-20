<?php

namespace AntonioKadid\WAPPKitCore\Tests\Reflection;

use AntonioKadid\WAPPKitCore\Reflection\ClosureInvoker;
use AntonioKadid\WAPPKitCore\Reflection\Exceptions\InvalidParameterValueException;
use AntonioKadid\WAPPKitCore\Reflection\Exceptions\UnknownParameterTypeException;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Webmozart\Assert\Assert;

/**
 * Class CallableTest
 *
 * @package AntonioKadid\WAPPKitCore\Tests\Reflection
 */
class CallableTest extends TestCase
{
    /**
     * @throws InvalidParameterValueException
     * @throws UnknownParameterTypeException
     * @throws ReflectionException
     */
    public function testClosure()
    {
        $invoker = new ClosureInvoker(
            function (callable $value) {
                return call_user_func($value, 5, 'test');
            });

        $result = $invoker->invoke(['value' => [$this, 'theCallable']]);
        $this->assertEquals('test_5', $result);
    }

    public function theCallable(int $number, string $test)
    {
        return sprintf('%s_%d', $test, $number);
    }
}