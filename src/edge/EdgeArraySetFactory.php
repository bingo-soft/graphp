<?php

namespace graphp\edge;

use graphp\vertex\VertexInterface;

/**
 * Class EdgeArraySetFactory
 *
 * @package graphp\edge
 */
class EdgeArraySetFactory implements EdgeSetFactoryInterface
{
    /**
     * Create a new edge set for a particular vertex
     *
     * @param VertexInterface $sourceVertex - the vertex for which the edge set is being created
     */
    public function createEdgeSet(VertexInterface $vertex): array
    {
        return [];
    }
}
