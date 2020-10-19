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
     *
     * @return mixed
     */
    function invoke(array $parameters);
}