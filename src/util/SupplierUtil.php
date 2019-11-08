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
     * @return SupplierInterface
     */
    public static function createSupplier(string $className): SupplierInterface
    {
        return new Supplier($className);
    }

    /**
     * Create a default edge supplier
     *
     * @return DefaultEdge
     */
    public static function createDefaultEdgeSupplier(): SupplierInterface
    {
        return self::createSupplier(DefaultEdge::class);
    }

    /**
     * Create a default weighted edge supplier
     *
     * @return DefaultWeightedEdge
     */
    public static function createDefaultWeightedEdgeSupplier(): SupplierInterface
    {
        return self::createSupplier(DefaultWeightedEdge::class);
    }

    /**
     * Create an integer supplier
     *
     * @return SupplierInterface
     */
    public static function createIntegerSupplier(int $start = 0): SupplierInterface
    {
        return new IntegerSupplier($start);
    }

    /**
     * Create a string supplier
     *
     * @return SupplierInterface
     */
    public static function createStringSupplier(int $start = 0): SupplierInterface
    {
        return new StringSupplier($start);
    }
}
