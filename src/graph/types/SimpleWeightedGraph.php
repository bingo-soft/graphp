<?php

namespace graphp\graph\types;

use graphp\graph\AbstractGraph;
use graphp\graph\builder\GraphTypeBuilder;
use graphp\util\SupplierUtil;

/**
 * Class SimpleWeightedGraph
 *
 * @package graphp\graph\types
 */
class SimpleWeightedGraph extends SimpleGraph
{
    /**
     * Create a new simple graph
     *
     * @param string $edgeClass - the edge class
     * @param SupplierInterface $vertexSupplier - the vertex supplier
     * @param SupplierInterface $edgeSupplier - the edge supplier
     * @param bool $weighted - if the graph is weighted
     */
    public function __construct(
        ?string $edgeClass = null,
        ?SupplierInterface $vertexSupplier = null,
        ?SupplierInterface $edgeSupplier = null,
        ?bool $weighted = null
    ) {
        parent::__construct(
            $edgeClass,
            $vertexSupplier,
            $edgeSupplier,
            true
        );
    }

    /**
     * Create a simple graph builder
     *
     * @param string $edgeClass - the edge class
     * @param SupplierInterface $edgeSupplier - the edge supplier
     *
     * @return GraphBuilder
     */
    public function createBuilder(?string $edgeClass = null, ?SupplierInterface $edgeSupplier = null): GraphBuilder
    {
        if (!is_null($edgeClass)) {
            return new GraphBuilder(new SimpleWeightedGraph($edgeClass));
        }
        return new GraphBuilder(new SimpleWeightedGraph(null, $edgeSupplier));
    }
}
