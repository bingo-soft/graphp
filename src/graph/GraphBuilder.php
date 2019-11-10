<?php

namespace graphp\graph;

use graphp\edge\EdgeInterface;
use graphp\vertex\VertexInterface;

/**
 * Class GraphBuilder
 *
 * @package graphp\graph
 */
class GraphBuilder
{
    /**
     * The graph
     *
     * @var GraphInterface
     */
    private $graph;
    
    /**
     * Construct the graph
     *
     * @param GraphInterface $graph - the graph
     */
    public function __construct(GraphInterface $graph)
    {
        $this->graph = $graph;
    }

    /**
     * Add a vertex to the graph being built
     *
     * @param VertexInterface $vertex - the vertex
     */
    public function addVertex(VertexInterface $vertex): self
    {
        $this->graph->addVertex($vertex);
        return $this;
    }

    /**
     * Add vertices to the graph being built
     *
     * @param VertexInterface $vertex - the vertex
     */
    public function addVertices(array $vertices): self
    {
        foreach ($vertices as $vertex) {
            $this->addVertex($vertex);
        }
        return $this;
    }
}
