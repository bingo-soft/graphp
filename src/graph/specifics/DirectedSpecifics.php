<?php

namespace graphp\graph\specifics;

use graphp\GraphInterface;
use graphp\edge\EdgeInterface;
use graphp\edge\EdgeSetFactoryInterface;
use graphp\edge\EdgeArraySetFactory;
use graphp\edge\EdgeSet;
use graphp\vertex\VertexInterface;
use graphp\vertex\VertexMap;
use graphp\vertex\VertexSet;

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
     * Add a vertex
     *
     * @param VertexInterface $vertex - vertex to be added
     */
    public function addVertex(VertexInterface $vertex): void
    {
        $this->vertexMap->put($vertex);
    }

    /**
     * Remove a vertex
     *
     * @param VertexInterface $vertex - vertex to be removed
     */
    public function removeVertex(VertexInterface $vertex): void
    {
        $this->vertexMap->remove($vertex);
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
     * @return EdgeSet
     */
    public function getAllEdges(VertexInterface $sourceVertex, VertexInterface $targetVertex): EdgeSet
    {
        $edges = new EdgeSet();
        
        if (
            $this->graph->containsVertex($sourceVertex)
            && $this->graph->containsVertex($targetVertex)
        ) {
            $allEdges = $this->getEdgeContainer($sourceVertex)->getOutgoing();
            
            foreach ($allEdges as $edge) {
                $equals = $this->graph->getEdgeTarget($edge)->equals($targetVertex);
                
                if ($equals && !$edges->contains($edge)) {
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
            $edges = $this->getEdgeContainer($sourceVertex)->getOutgoing();
            
            foreach ($edges as $edge) {
                $equals = $this->graph->getEdgeTarget($edge)->equals($targetVertex);
                
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
     * @return EdgeSet
     */
    public function edgesOf(VertexInterface $vertex): EdgeSet
    {
        $edges = $this->getEdgeContainer($vertex)->getOutgoing()->getArrayCopy();
        $edges = new EdgeSet(array_merge($edges, $this->getEdgeContainer($vertex)->getIncoming()->getArrayCopy()));
        
        //remove only one copy of self-loop
        if ($this->graph->getType()->isAllowingSelfLoops()) {
            $loops = array_unique($this->getAllEdges($vertex, $vertex)->getArrayCopy());
            
            foreach ($edges as $key => $edge) {
                if (($id = array_search($edge, $loops)) !== false) {
                    unset($edges[$key]);
                    unset($loops[$id]);
                }
            }
        }
        
        return $edges;
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
        
        $this->getEdgeContainer($sourceVertex)->addOutgoingEdge($edge);
        $this->getEdgeContainer($targetVertex)->addIncomingEdge($edge);
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
        
        $this->getEdgeContainer($sourceVertex)->removeOutgoingEdge($edge);
        $this->getEdgeContainer($targetVertex)->removeIncomingEdge($edge);
    }

    /**
     * Get all edges outgoing from the specified vertex
     *
     * @param VertexInterface $vertex - the vertex for which the list of outgoing edges to be returned
     *
     * @return EdgeSet
     */
    public function outgoingEdgesOf(VertexInterface $vertex): EdgeSet
    {
        return $this->getEdgeContainer($vertex)->getOutgoing();
    }

    /**
     * Get all edges incoming into the specified vertex
     *
     * @param VertexInterface $vertex - the vertex for which the list of incoming edges to be returned
     *
     * @return EdgeSet
     */
    public function incomingEdgesOf(VertexInterface $vertex): EdgeSet
    {
        return $this->getEdgeContainer($vertex)->getIncoming();
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
        return $this->inDegreeOf($vertex) + $this->outDegreeOf($vertex);
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
        return count($this->getEdgeContainer($vertex)->getIncoming());
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
        return count($this->getEdgeContainer($vertex)->getOutgoing());
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
