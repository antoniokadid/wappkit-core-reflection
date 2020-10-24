<?php

namespace AntonioKadid\WAPPKitCore\Reflection;

/**
 * Interface IInvoker
 *
 * @package AntonioKadid\WAPPKitCore\Reflection
 */
interface IInvoker
{
    /**
     * @param array $parameters
     * @param bool  $keyValuePairs True, if the $parameters is a key-value pair array else False.
     *
     * @return mixed
     */
    function invoke(array $parameters, bool $keyValuePairs = TRUE);
}