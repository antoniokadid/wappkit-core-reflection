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
    protected static function getInvokeArgs(array $reflectionParameters, array $data): array
    {
        $result = [];

        foreach ($reflectionParameters as $reflectionParameter) {
            $parameterName = $reflectionParameter->getName();

            if (!array_key_exists($parameterName, $data)) {
                if ($reflectionParameter->isOptional() && $reflectionParameter->isDefaultValueAvailable())
                    $result[] = $reflectionParameter->getDefaultValue();
                else if ($reflectionParameter->allowsNull())
                    $result[] = NULL;

                throw new InvalidParameterValueException($parameterName, sprintf('Invalid value for parameter %s.', $parameterName));
            }

            if (!$reflectionParameter->hasType()) {
                $result[] = $data[$parameterName];
                continue;
            }

            if ($reflectionParameter->isArray() && is_callable($data[$parameterName])) {
                $invoker = new CallableInvoker($data[$parameterName]);
                $result[] = $invoker->invoke($data);
                continue;
            }

            if ($reflectionParameter->isCallable() && is_array($data[$parameterName])) {
                $result[] = $data[$parameterName];
                continue;
            }

            $result[] = self::getValueForTypedParameter($reflectionParameter, $data);
        }

        return $result;
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
    private static function getValueForTypedParameter(ReflectionParameter $parameter, array $data)
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
                if (!class_exists($parameterTypeName))
                    throw new UnknownParameterTypeException($parameterName, sprintf('Unknown type for parameter %s', $parameterName));

                $injectedClass = $parameter->getClass();
                if ($injectedClass == NULL || !$injectedClass->isInstantiable()) {
                    if ($parameter->isOptional() && $parameter->isDefaultValueAvailable())
                        return $parameter->getDefaultValue();

                    throw new UnknownParameterTypeException($parameterName, sprintf('Unknown type for parameter %s', $parameterName));
                }

                $injectedConstructor = $injectedClass->getConstructor();
                if ($injectedConstructor == NULL || $injectedConstructor->getNumberOfParameters() === 0)
                    return $injectedClass->newInstance([]);

                return $injectedClass->newInstanceArgs(self::getInvokeArgs($injectedConstructor->getParameters(), $data));
            }
        }
    }
}