<?php

namespace Graphp\Graph\Types;

use Graphp\Graph\Builder\GraphBuilder;
use Graphp\Util\SupplierUtil;
use Graphp\Util\SupplierInterface;

/**
 * Class DirectedWeightedPseudograph
 *
 * @package Graphp\Graph\Types
 */
class DirectedWeightedPseudograph extends DirectedPseudograph
{
    /**
     * Create a new simple directed weighted graph
     *
     * @param string $edgeClass - the edge class
     * @param SupplierInterface $vertexSupplier - the vertex supplier
     * @param SupplierInterface $edgeSupplier - the edge supplier
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
            return new GraphBuilder(new DirectedWeightedPseudograph($edgeClass));
        }
        return new GraphBuilder(new DirectedWeightedPseudograph(null, $edgeSupplier));
    }
}
