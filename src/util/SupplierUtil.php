<?php

namespace graphp\util;

use graphp\edge\DefaultEdge;
use graphp\edge\DefaultWeightedEdge;

/**
 * Class SupplierUtil
 *
 * @package graphp\util
 */
class SupplierUtil
{
    /**
     * Supplier constants
     */
    public const DEFAULT_EDGE_SUPPLIER = DefaultEdge::class;
    public const DEFAULT_WEIGHTED_EDGE_SUPPLIER = DefaultWeightedEdge::class;

    /**
     * Create an edge supplier
     *
     * @param string $className - edge class name
     *
     * @return DefaultEdge|DefaultWeightedEdge
     */
    public static function createSupplier(string $className)
    {
        return new $className();
    }

    /**
     * Create a default edge supplier
     *
     * @return DefaultEdge
     */
    public static function createDefaultEdgeSupplier(): DefaultEdge
    {
        return self::createSupplier(DefaultEdge::class);
    }

     /**
     * Create a default weighted edge supplier
     *
     * @return DefaultWeightedEdge
     */
    public static function createDefaultWeightedEdgeSupplier(): DefaultWeightedEdge
    {
        return self::createSupplier(DefaultWeightedEdge::class);
    }
}
