<?php

namespace tests;

use ReflectionClass;

class Invoker
{
    protected $object;
    protected $method;

    /**
     * Invoker constructor.
     * @param $object
     * @param $methodName
     */
    public function __construct($object, $methodName)
    {
        $this->object = $object;
        $this->method = (new ReflectionClass($object))->getMethod($methodName);
        $this->method->setAccessible(true);
    }

    /**
     * @param array $args
     * @return mixed
     */
    public function invokeArgs(array $args)
    {
        return $this->method->invokeArgs($this->object, $args);
    }

    /**
     * @return mixed
     */
    public function invoke()
    {
        return $this->invokeArgs(func_get_args());
    }
}
