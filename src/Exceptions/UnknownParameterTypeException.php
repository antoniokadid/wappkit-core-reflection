<?php

namespace AntonioKadid\WAPPKitCore\Reflection\Exceptions;

use Exception;
use Throwable;

/**
 * Class UnknownParameterTypeException
 *
 * @package AntonioKadid\WAPPKitCore\Reflection\Exceptions
 */
class UnknownParameterTypeException extends Exception
{
    /** @var string */
    private $_parameterName;

    public function __construct(string $parameterName, string $message = '', int $code = 0, Throwable $previous = NULL)
    {
        parent::__construct($message, $code, $previous);

        $this->_parameterName = $parameterName;
    }

    /**
     * @return string
     */
    public function getParameterName(): string
    {
        return $this->_parameterName;
    }
}