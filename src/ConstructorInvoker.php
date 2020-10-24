<?php


namespace AntonioKadid\WAPPKitCore\Reflection;

use ReflectionClass;
use ReflectionException;

/**
 * Class ConstructorInvoker
 *
 * @package AntonioKadid\WAPPKitCore\Reflection
 */
class ConstructorInvoker
    extends Invoker
    implements IInvoker
{
    private $_className;

    /**
     * ConstructorInvoker constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->_className = $name;
    }

    /**
     * @param string $name
     *
     * @return $this|IInvoker
     */
    public function setClass(string $name): IInvoker
    {
        $this->_className = $name;

        return $this;
    }

    /**
     * @param array $parameters
     * @param bool  $keyValuePairs
     *
     * @return mixed|object|null
     *
     * @throws Exceptions\InvalidParameterValueException
     * @throws Exceptions\UnknownParameterTypeException
     * @throws ReflectionException
     */
    public function invoke(array $parameters, bool $keyValuePairs = TRUE)
    {
        $this->_isDataKeyValuePairs = $keyValuePairs;

        if (!class_exists($this->_className, FALSE))
            return NULL;

        $class = new ReflectionClass($this->_className);
        if (!$class->isInstantiable())
            return NULL;

        $constructor = $class->getConstructor();

        if ($constructor == NULL || $constructor->getNumberOfParameters() === 0)
            return $class->newInstance();

        $constructorParameters = $constructor->getParameters();
        $parameters = $this->buildParameters($constructorParameters, $parameters);

        return $class->newInstanceArgs(
            $this->getInvokeArgs(
                $constructorParameters,
                $parameters));
    }
}