<?php

namespace graphp\graph\types;

use graphp\graph\AbstractGraph;
use graphp\graph\builder\GraphTypeBuilder;
use graphp\graph\builder\GraphBuilder;
use graphp\util\SupplierUtil;
use graphp\util\SupplierInterface;

/**
 * Class SimpleGraph
 *
 * @package graphp\graph\types
 */
class SimpleGraph extends AbstractGraph
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
        $builder = new GraphTypeBuilder();
        $graphType = $builder->undirected()
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
            return new GraphBuilder(new SimpleGraph($edgeClass));
        }
        return new GraphBuilder(new SimpleGraph(null, $edgeSupplier));
    }
}
