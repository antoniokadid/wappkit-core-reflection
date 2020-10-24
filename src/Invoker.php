<?php

namespace AntonioKadid\WAPPKitCore\Reflection;

use AntonioKadid\WAPPKitCore\Reflection\Exceptions\InvalidParameterValueException;
use AntonioKadid\WAPPKitCore\Reflection\Exceptions\UnknownParameterTypeException;
use ReflectionException;
use ReflectionNamedType;
use ReflectionParameter;

/**
 * Class Invoker
 *
 * @package AntonioKadid\WAPPKitCore\Reflection
 */
class Invoker
{
    protected $_isDataKeyValuePairs;

    /**
     * @param array $reflectionParameters
     * @param array $data
     *
     * @return array
     *
     * @throws InvalidParameterValueException
     * @throws ReflectionException
     * @throws UnknownParameterTypeException
     */
    protected function getInvokeArgs(array $reflectionParameters, array $data): array
    {
        $result = [];

        /** @var ReflectionParameter $reflectionParameter */
        foreach ($reflectionParameters as $reflectionParameter) {
            $parameterName = $reflectionParameter->getName();

            if (($class = $reflectionParameter->getClass()) != NULL) {
                $result[] = $this->getValueForClassParameter($reflectionParameter, $class->getName(), $data);
                continue;
            }

            if ($reflectionParameter->isArray() && is_array($data[$parameterName])) {
                $result[] = $data[$parameterName];
                continue;
            }

            if ($reflectionParameter->isCallable() && is_callable($data[$parameterName])) {
                $result[] = $data[$parameterName];
                continue;
            }

            if (!array_key_exists($parameterName, $data) ||
                ($reflectionParameter->isCallable() && !is_callable($data[$parameterName]))) {
                if ($reflectionParameter->isOptional() && $reflectionParameter->isDefaultValueAvailable())
                    $result[] = $reflectionParameter->getDefaultValue();
                else if ($reflectionParameter->allowsNull())
                    $result[] = NULL;

                throw new InvalidParameterValueException($parameterName, sprintf('Invalid value for parameter %s.', $parameterName));
            }

            $result[] = !$reflectionParameter->hasType() ?
                $data[$parameterName] :
                $this->getValueForTypedParameter($reflectionParameter, $data);
        }

        return $result;
    }

    /**
     * @param ReflectionParameter[] $reflectionParameters
     * @param array                 $values
     *
     * @return array
     */
    protected function buildParameters(array $reflectionParameters, array $values): array
    {
        if ($this->_isDataKeyValuePairs)
            return $values;

        $keys = array_map(
            function (ReflectionParameter $parameter) {
                return $parameter->getName();
            },
            $reflectionParameters);

        $values = array_pad($values, count($keys), NULL);

        return array_combine($keys, $values);
    }

    /**
     * @param ReflectionParameter $parameter
     * @param array               $data
     *
     * @return array|bool|float|int|mixed|string|null
     *
     * @throws InvalidParameterValueException
     * @throws ReflectionException
     * @throws UnknownParameterTypeException
     */
    private function getValueForTypedParameter(ReflectionParameter $parameter, array $data)
    {
        $parameterName = $parameter->getName();
        $parameterType = $parameter->getType();
        $parameterValue = $data[$parameterName];

        if (!($parameterType instanceof ReflectionNamedType))
            throw new UnknownParameterTypeException($parameterName, sprintf('Unknown type for parameter %s', $parameterName));

        $parameterTypeName = $parameterType->getName();

        switch (strtolower($parameterTypeName)) {
            case 'string':
            {
                return strval($parameterValue);
            }
            case 'bool':
            {
                return boolval($parameterValue);
            }
            case 'int':
            {
                return intval($parameterValue);
            }
            case 'float':
            {
                return floatval($parameterValue);
            }
            default:
            {
                throw new InvalidParameterValueException($parameterName, sprintf('Invalid value for parameter %s.', $parameterName));
            }
        }
    }

    /**
     * @param ReflectionParameter $parameter
     * @param string              $className
     * @param array               $data
     *
     * @return mixed|object
     *
     * @throws InvalidParameterValueException
     * @throws ReflectionException
     * @throws UnknownParameterTypeException
     */
    private function getValueForClassParameter(ReflectionParameter $parameter, string $className, array $data)
    {
        $invoker = new ConstructorInvoker($className);
        $instance = $invoker->invoke($data, $this->_isDataKeyValuePairs);

        if ($instance === NULL) {
            if ($parameter->isOptional() && $parameter->isDefaultValueAvailable())
                return $parameter->getDefaultValue();

            throw new UnknownParameterTypeException($parameter->getName(), sprintf('Unknown type for parameter %s', $parameter->getName()));
        }

        return $instance;
    }

    /**
     * @param ReflectionParameter $parameter
     * @param callable            $callable
     * @param array               $data
     *
     * @return mixed
     *
     * @throws InvalidParameterValueException
     * @throws ReflectionException
     * @throws UnknownParameterTypeException
     */
    private function getValueForCallableParameter(ReflectionParameter $parameter, callable $callable, array $data)
    {
        $invoker = new CallableInvoker($callable);
        return $invoker->invoke($data, $this->_isDataKeyValuePairs);
    }
}