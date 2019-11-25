<?php

namespace Graphp\Edge;

use Graphp\Vertex\VertexInterface;

/**
 * Interface EdgeSetFactoryInterface
 *
 * @package Graphp\Edge
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
