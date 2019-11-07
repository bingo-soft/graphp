<?php

namespace graphp\graph\specifics;

use graphp\graph\GraphInterface;
use graphp\edge\EdgeInterface;
use graphp\edge\EdgeSetFactoryInterface;
use graphp\edge\EdgeArraySetFactory;
use graphp\vertex\VertexInterface;
use graphp\vertex\VertexMap;

/**
 * Class DirectedSpecifics
 *
 * @package graphp\graph\specifics
 */
class DirectedSpecifics implements SpecificsInterface
{
    /**
     * The graph
     *
     * @var GraphInterface
     */
    protected $graph;

    /**
     * The vertex map
     *
     * @var VertexMap
     */
    protected $vertexMap;

    /**
     * The edge set factory
     *
     * @var EdgeSetFactoryInterface
     */
    protected $edgeSetFactory;

    /**
     * Construct a new directed specifics
     *
     * @param GraphInterface $graph - the graph for which these specifics are for
     * @param EdgeSetFactoryInterface $edgeSetFactory - the edge set factory, used by the graph
     */
    public function __construct(GraphInterface $graph, ?EdgeSetFactoryInterface $edgeSetFactory = null)
    {
        $this->graph = $graph;
        $this->vertexMap = new VertexMap();
        $this->edgeSetFactory = $edgeSetFactory ?? new EdgeArraySetFactory();
    }

    /**
     * Get the edge container for the specified vertex.
     *
     * @param VertexInterface $vertex - the vertex
     *
     * @return DirectedEdgeContainer
     */
    public function getEdgeContainer(VertexInterface $vertex): DirectedEdgeContainer
    {
        $ec = $this->vertexMap->get($vertex);
        
        if (is_null($ec)) {
            $ec = new DirectedEdgeContainer($this->edgeSetFactory, $vertex);
            $this->vertexMap->put($vertex, $ec);
        }
        
        return $ec;
    }
}
