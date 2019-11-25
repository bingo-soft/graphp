<?php

namespace Graphp\Util;

/**
 * Class Supplier
 *
 * @package Graphp\Util
 */
class Supplier implements SupplierInterface
{
    /**
     * Name of the class, whose instance will be provided
     *
     * @var string
     */
    private $className;

    /**
     * Construct a new supplier
     *
     * @param string $className - the class name
     */
    public function __construct(string $className)
    {
        $this->className = $className;
    }

    /**
     * Get the specified class instance
     *
     * @return mixed
     */
    public function get()
    {
        return new $this->className();
    }
}
