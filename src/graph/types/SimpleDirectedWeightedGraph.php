<?php

namespace graphp\graph\types;

use graphp\graph\AbstractGraph;
use graphp\graph\builder\GraphBuilder;
use graphp\util\SupplierUtil;
use graphp\util\SupplierInterface;

/**
 * Class SimpleDirectedWeightedGraph
 *
 * @package graphp\graph\types
 */
class SimpleDirectedWeightedGraph extends SimpleDirectedGraph
{
    /**
     * Create a new simple directed weighted graph
     *
     * @param string $edgeClass - the edge class
     * @param SupplierInterface $vertexSupplier - the vertex supplier
     * @param SupplierInterface $edgeSupplier - the edge supplier
     * @param bool $weighted - if the graph is weighted
     */
    public function __construct(
        ?string $edgeClass = null,
        ?SupplierInterface $vertexSupplier = null,
        ?SupplierInterface $edgeSupplier = null
    ) {
        parent::__construct(
            $edgeClass,
            $vertexSupplier,
            $edgeSupplier,
            true
        );
    }

    /**
     * Create a simple directed weighted graph builder
     *
     * @param string $edgeClass - the edge class
     * @param SupplierInterface $edgeSupplier - the edge supplier
     *
     * @return GraphBuilder
     */
    public function createBuilder(?string $edgeClass = null, ?SupplierInterface $edgeSupplier = null): GraphBuilder
    {
        if (!is_null($edgeClass)) {
            return new GraphBuilder(new SimpleDirectedWeightedGraph($edgeClass));
        }
        return new GraphBuilder(new SimpleDirectedWeightedGraph(null, $edgeSupplier));
    }
}
