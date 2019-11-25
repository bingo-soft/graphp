<?php

namespace Graphp\Edge;

use Graphp\Vertex\VertexInterface;

/**
 * Class EdgeArraySetFactory
 *
 * @package Graphp\Edge
 */
class EdgeArraySetFactory implements EdgeSetFactoryInterface
{
    /**
     * Create a new edge set for a particular vertex
     *
     * @param VertexInterface $sourceVertex - the vertex for which the edge set is being created
     */
    public function createEdgeSet(VertexInterface $vertex): EdgeSet
    {
        return new EdgeSet();
    }
}
