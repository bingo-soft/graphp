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
}
