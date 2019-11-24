<?php

namespace Graphp\Util;

/**
 * Interface SupplierInterface
 *
 * @package Graphp\Util
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
