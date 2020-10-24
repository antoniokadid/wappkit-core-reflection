<?php

namespace AntonioKadid\WAPPKitCore\Reflection;

use Closure;

/**
 * Class CallableInvoker
 *
 * @package AntonioKadid\WAPPKitCore\Reflection
 */
class CallableInvoker
    extends ClosureInvoker
{
    /**
     * CallableInvoker constructor.
     *
     * @param callable $callable
     */
    public function __construct(callable $callable)
    {
        parent::__construct(Closure::fromCallable($callable));
    }

    /**
     * @param callable $callable
     */
    public function setCallable(callable $callable): void
    {
        $this->setClosure(Closure::fromCallable($callable));
    }
}