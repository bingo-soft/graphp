<?php

namespace graphp\util;

/**
 * Interface SupplierInterface
 *
 * @package graphp\util
 */
interface SupplierInterface
{
    /**
     * Get the specific class type
     *
     * @return mixed
     */
    public function get();
}
