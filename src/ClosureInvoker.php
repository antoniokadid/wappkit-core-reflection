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
     * @param bool  $keyValuePairs True, if the $parameters is a key-value pair array else False.
     *
     * @return mixed
     *
     * @throws Exceptions\InvalidParameterValueException
     * @throws Exceptions\UnknownParameterTypeException
     * @throws ReflectionException
     */
    public function invoke(array $parameters = [], bool $keyValuePairs = TRUE)
    {
        $this->_isDataKeyValuePairs = $keyValuePairs;

        $reflectionFunction = new ReflectionFunction($this->_closure);
        if ($reflectionFunction->getNumberOfParameters() === 0)
            return $reflectionFunction->invoke();

        $reflectionParameters = $reflectionFunction->getParameters();
        $parameters = $this->buildParameters($reflectionParameters, $parameters);

        return $reflectionFunction->invokeArgs(
            $this->getInvokeArgs(
                $reflectionParameters,
                $parameters));
    }
}