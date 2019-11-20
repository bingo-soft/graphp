<?php

namespace graphp\graph\types;

use graphp\graph\AbstractGraph;
use graphp\graph\builder\GraphTypeBuilder;
use graphp\util\SupplierUtil;

/**
 * Class SimpleDirectedGraph
 *
 * @package graphp\graph\types
 */
class SimpleDirectedGraph extends AbstractGraph
{
    /**
     * Create a new simple directed graph
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
        $builder = new GraphTypeBuilder();
        $graphType = $builder->directed()
                    ->allowSelfLoops(false)
                    ->allowMultipleEdges(false)
                    ->weighted($weighted)
                    ->build();
        if (!is_null($edgeClass)) {
            $util = new SupplierUtil();
            $edgeSupplier = $util->createSupplier($edgeClass);
            parent::__construct(
                $vertexSupplier,
                $edgeSupplier,
                $graphType
            );
        } else {
            parent::__construct(
                $vertexSupplier,
                $edgeSupplier,
                $graphType
            );
        }
    }

    /**
     * Create a simple directed graph builder
     *
     * @param string $edgeClass - the edge class
     * @param SupplierInterface $edgeSupplier - the edge supplier
     *
     * @return GraphBuilder
     */
    public function createBuilder(?string $edgeClass = null, ?SupplierInterface $edgeSupplier = null): GraphBuilder
    {
        if (!is_null($edgeClass)) {
            return new GraphBuilder(new SimpleDirectedGraph($edgeClass));
        }
        return new GraphBuilder(new SimpleDirectedGraph(null, $edgeSupplier));
    }
}