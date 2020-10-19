<?php

namespace AntonioKadid\WAPPKitCore\Reflection;

use Closure;
use ReflectionException;
use ReflectionFunction;

/**
 * Class ClosureInvoker
 *
 * @package AntonioKadid\WAPPKitCore\Reflection
 */
class ClosureInvoker
    extends Invoker
    implements IInvoker
{
    /** @var Closure */
    private $_closure;

    /**
     * ClosureInvoker constructor.
     *
     * @param Closure $closure
     */
    public function __construct(Closure $closure)
    {
        $this->setClosure($closure);
    }

    /**
     * @param Closure $closure
     *
     * @return $this|IInvoker
     */
    public function setClosure(Closure $closure): IInvoker
    {
        $this->_closure = $closure;

        return $this;
    }

    /**
     * @param array $parameters
     *
     * @return mixed
     *
     * @throws Exceptions\InvalidParameterValueException
     * @throws Exceptions\UnknownParameterTypeException
     * @throws ReflectionException
     */
    public function invoke(array $parameters = [])
    {
        $reflectionFunction = new ReflectionFunction($this->_closure);
        if ($reflectionFunction->getNumberOfParameters() === 0)
            return $reflectionFunction->invoke();

        return $reflectionFunction->invokeArgs(self::getInvokeArgs($reflectionFunction->getParameters(), $parameters));
    }
}