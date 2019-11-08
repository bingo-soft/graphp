<?php

namespace graphp\graph\specifics;

use graphp\graph\GraphInterface;
use graphp\edge\EdgeInterface;
use graphp\edge\EdgeSetFactoryInterface;
use graphp\edge\EdgeArraySetFactory;
use graphp\vertex\VertexInterface;
use graphp\vertex\VertexMap;
use graphp\vertex\VertexSet;

/**
 * Class UndirectedSpecifics
 *
 * @package graphp\graph\specifics
 */
class UndirectedSpecifics implements SpecificsInterface
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
     * Construct a new undirected specifics
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
     * Add a vertex
     *
     * @param VertexInterface $vertex - vertex to be added
     */
    public function addVertex(VertexInterface $vertex): void
    {
        $this->vertexMap->put($vertex);
    }

    /**
     * Get the vertex set
     *
     * @return VertexSet
     */
    public function getVertexSet(): VertexSet
    {
        return $this->vertexMap->keySet();
    }

    /**
     * Get all edges connecting the source vertex to the target vertex
     *
     * @param VertexInterface $sourceVertex - source vertex
     * @param VertexInterface $targetVertex - target vertex
     *
     * @return array
     */
    public function getAllEdges(VertexInterface $sourceVertex, VertexInterface $targetVertex): array
    {
        $edges = [];
        
        if (
            $this->graph->containsVertex($sourceVertex)
            && $this->graph->containsVertex($targetVertex)
        ) {
            $edges = $this->getEdgeContainer($sourceVertex)->getEdges();
            
            foreach ($edges as $edge) {
                $equals = $this->isEqualStraightOrInverted($sourceVertex, $targetVertex, $edge);
                
                if ($equals) {
                    $edges[] = $edge;
                }
            }
        }
        
        return $edges;
    }

    /**
     * Get an edge connecting the source vertex to the target vertex
     *
     * @param VertexInterface $sourceVertex - source vertex
     * @param VertexInterface $targetVertex - target vertex
     *
     * @return null|EdgeInterface
     */
    public function getEdge(VertexInterface $sourceVertex, VertexInterface $targetVertex): ?EdgeInterface
    {
        if (
            $this->graph->containsVertex($sourceVertex)
            && $this->graph->containsVertex($targetVertex)
        ) {
            $edges = $this->getEdgeContainer($sourceVertex)->getEdges();
            
            foreach ($edges as $edge) {
                $equals = $this->isEqualStraightOrInverted($sourceVertex, $targetVertex, $edge);
                
                if ($equals) {
                    return $edge;
                }
            }
        }
        
        return null;
    }

    /**
     * Get all edges touching the specified vertex
     *
     * @param VertexInterface $vertex - the vertex for which a set of touching edges is to be returned
     *
     * @return array
     */
    public function edgesOf(VertexInterface $vertex): array
    {
        return $this->getEdgeContainer($vertex)->getEdges();
    }

    /**
     * Add the specified edge to the edge containers of its source and target vertices.
     *
     * @param EdgeInterface $edge - the edge to be added
     */
    public function addEdgeToTouchingVertices(EdgeInterface $edge): void
    {
        $sourceVertex = $this->graph->getEdgeSource($edge);
        $targetVertex = $this->graph->getEdgeTarget($edge);
        
        $this->getEdgeContainer($sourceVertex)->addEdge($edge);
        
        if (!$sourceVertex->equals($targetVertex)) {
            $this->getEdgeContainer($targetVertex)->addEdge($edge);
        }
    }

    /**
     * Remove the specified edge from the edge containers of its source and target vertices.
     *
     * @param EdgeInterface $edge - the edge to be removed
     */
    public function removeEdgeFromTouchingVertices(EdgeInterface $edge): void
    {
        $sourceVertex = $this->graph->getEdgeSource($edge);
        $targetVertex = $this->graph->getEdgeTarget($edge);
        
        $this->getEdgeContainer($sourceVertex)->removeEdge($edge);
        
        if (!$sourceVertex->equals($targetVertex)) {
            $this->getEdgeContainer($targetVertex)->removeEdge($edge);
        }
    }

    /**
     * Get all edges outgoing from the specified vertex
     *
     * @param VertexInterface $vertex - the vertex for which the list of outgoing edges to be returned
     *
     * @return array
     */
    public function outgoingEdgesOf(VertexInterface $vertex): array
    {
        return $this->getEdgeContainer($vertex)->getEdges();
    }

    /**
     * Get all edges incoming into the specified vertex
     *
     * @param VertexInterface $vertex - the vertex for which the list of incoming edges to be returned
     *
     * @return array
     */
    public function incomingEdgesOf(VertexInterface $vertex): array
    {
        return $this->getEdgeContainer($vertex)->getEdges();
    }

    /**
     * Get the degree of the specified vertex
     *
     * @param VertexInterface $vertex - the vertex whose degree is to be calculated
     *
     * @return int
     */
    public function degreeOf(VertexInterface $vertex): int
    {
        if ($this->graph->getType()->isAllowingSelfLoops()) {
            $degree = 0;
            $edges = $this->getEdgeContainer($vertex)->getEdges();

            foreach ($edges as $edge) {
                //if it is a loop, then count twice
                if ($graph->getEdgeSource($edge)->equals($this->graph->getEdgeTarget($edge))) {
                    $degree += 2;
                } else {
                    $degree += 1;
                }
            }

            return $degree;
        } else {
            $this->getEdgeContainer($vertex)->edgeCount();
        }
    }

    /**
     * Get the "in degree" of the specified vertex
     *
     * @param VertexInterface $vertex - the vertex whose in degree is to be calculated
     *
     * @return int
     */
    public function inDegreeOf(VertexInterface $vertex): int
    {
        return $this->degreeOf($vertex);
    }

    /**
     * Get the "out degree" of the specified vertex
     *
     * @param VertexInterface $vertex - the vertex whose out degree is to be calculated
     *
     * @return int
     */
    public function outDegreeOf(VertexInterface $vertex): int
    {
        return $this->degreeOf($vertex);
    }

    /**
     * Get the edge container for the specified vertex.
     *
     * @param VertexInterface $vertex - the vertex
     *
     * @return DirectedEdgeContainer
     */
    public function getEdgeContainer(VertexInterface $vertex): UndirectedEdgeContainer
    {
        $ec = $this->vertexMap->get($vertex);
        
        if (is_null($ec)) {
            $ec = new UndirectedEdgeContainer($this->edgeSetFactory, $vertex);
            $this->vertexMap->put($vertex, $ec);
        }
        
        return $ec;
    }

    /**
     * Check if both vertices are touching the edge
     *
     * @param VertexInterface $sourceVertex - source vertex
     * @param VertexInterface $targetVertex - target vertex
     * @param EdgeInterface $edge - the edge
     *
     * @return bool
     */
    private function isEqualStraightOrInverted(
        VertexInterface $sourceVertex,
        VertexInterface $targetVertex,
        EdgeInterface $edge
    ): bool {
        $straigt = $sourceVertex->equals($this->graph->getEdgeSource($edge))
                   && $targetVertex->equals($this->graph->getEdgeTarget($edge));
                   
        $inverted = $targetVertex->equals($this->graph->getEdgeSource($edge))
                   && $sourceVertex->equals($this->graph->getEdgeTarget($edge));
                   
        return $straigt || $inverted;
    }
}
