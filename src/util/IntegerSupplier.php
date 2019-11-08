<?php

namespace graphp\util;

/**
 * Class IntegerSupplier
 *
 * @package graphp\util
 */
class IntegerSupplier implements SupplierInterface
{
    /**
     * Supplier value
     *
     * @var int
     */
    private static $i = 0;

    /**
     * Construct supplier value
     *
     * @param int $start - starting value
     */
    public function __construct(int $start)
    {
        self::$i = $start;
    }

    /**
     * Get supplier value
     *
     * @return int
     */
    public function get(): int
    {
        return self::$i++;
    }
}
