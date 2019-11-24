<?php

namespace Graphp\Graph\Types;

use Graphp\Graph\AbstractGraph;
use Graphp\Graph\Builder\GraphTypeBuilder;
use Graphp\Graph\Builder\GraphBuilder;
use Graphp\Util\SupplierUtil;
use Graphp\Util\SupplierInterface;

/**
 * Class DirectedPseudograph
 *
 * @package Graphp\Graph\Types
 */
class DirectedPseudograph extends AbstractGraph
{
    /**
     * Create a new default directed graph
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
                    ->allowSelfLoops(true)
                    ->allowMultipleEdges(true)
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
     * Create a default directed graph builder
     *
     * @param string $edgeClass - the edge class
     * @param SupplierInterface $edgeSupplier - the edge supplier
     *
     * @return GraphBuilder
     */
    public function createBuilder(?string $edgeClass = null, ?SupplierInterface $edgeSupplier = null): GraphBuilder
    {
        if (!is_null($edgeClass)) {
            return new GraphBuilder(new DirectedPseudograph($edgeClass));
        }
        return new GraphBuilder(new DirectedPseudograph(null, $edgeSupplier));
    }
}
