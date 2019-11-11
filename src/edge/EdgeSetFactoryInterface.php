<?php

namespace graphp\edge;

use graphp\vertex\VertexInterface;

/**
 * Interface EdgeSetFactoryInterface
 *
 * @package graphp\edge
 */
interface EdgeSetFactoryInterface
{
    /**
     * Create a new edge set for a particular vertex
     *
     * @param VertexInterface $sourceVertex - the vertex for which the edge set is being created
     */
    public function createEdgeSet(VertexInterface $sourceVertex): EdgeSet;
}
