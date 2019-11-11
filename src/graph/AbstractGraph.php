<?php

namespace graphp\graph;

use InvalidArgumentException;
use graphp\graph\specifics\SpecificsInterface;
use graphp\graph\specifics\UndirectedSpecifics;
use graphp\graph\specifics\DirectedSpecifics;
use graphp\edge\EdgeInterface;
use graphp\edge\EdgeSet;
use graphp\edge\specifics\EdgeSpecificsInterface;
use graphp\edge\specifics\UniformEdgeSpecifics;
use graphp\edge\specifics\WeightedEdgeSpecifics;
use graphp\vertex\VertexInterface;
use graphp\vertex\VertexSet;
use graphp\util\SupplierInterface;

/**
 * Class AbstractGraph
 *
 * @package graphp\graph
 */
class AbstractGraph implements GraphInterface
{
    /**
     * The vertex supplier
     *
     * @var SupplierInterface
     */
    private $vertexSupplier;

    /**
     * The edge supplier
     *
     * @var SupplierInterface
     */
    private $edgeSupplier;

    /**
     * The graph type
     *
     * @var GraphTypeInterface
     */
    private $type;

    /**
     * The graph specifics
     *
     * @var SpecificsInterface
     */
    private $specifics;

    /**
     * The edge specifics
     *
     * @var EdgeSpecificsInterface
     */
    private $edgeSpecifics;

    /**
     * Construct a new graph
     *
     * @param SupplierInterface $vertexSupplier - the vertex supplier
     * @param SupplierInterface $edgeSupplier - the edge supplier
     * @param GraphTypeInterface $type - the graph type
     */
    protected function __construct(
        ?SupplierInterface $vertexSupplier = null,
        ?SupplierInterface $edgeSupplier = null,
        ?GraphTypeInterface $type = null
    ) {
        $this->vertexSupplier = $vertexSupplier;
        $this->edgeSupplier = $edgeSupplier;
        $this->type = $type;
        $this->specifics = $this->createSpecifics($type->isDirected());
        $this->edgeSpecifics = $this->createEdgeSpecifics($type->isWeighted());
    }

    /**
     * Get the graph specifics
     *
     * @param bool $isDirected - is directed graph?
     *
     * @return SpecificsInterface
     */
    public function createSpecifics(bool $isDirected): SpecificsInterface
    {
        return $isDirected ? new DirectedSpecifics($this) : new UndirectedSpecifics($this);
    }

    /**
     * Get the edge specifics
     *
     * @param bool $isWeighted - is weighted?
     *
     * @return EdgeSpecificsInterface
     */
    public function createEdgeSpecifics(bool $isWeighted): EdgeSpecificsInterface
    {
        return $isWeighted ? new WeightedEdgeSpecifics($this) : new UniformEdgeSpecifics($this);
    }

    /**
     * Get all edges connecting the source vertext to the target vertex
     *
     * @return EdgeSet
     */
    public function getAllEdges(VertexInterface $sourceVertex, VertexInterface $targetVertex): EdgeSet
    {
        return $this->specifics->getAllEdges($sourceVertex, $targetVertex);
    }

    /**
     * Get an edge connecting the source vertext to the target vertex
     *
     * @return null|EdgeInterface
     */
    public function getEdge(VertexInterface $sourceVertex, VertexInterface $targetVertex): ?EdgeInterface
    {
        return $this->specifics->getEdge($sourceVertex, $targetVertex);
    }

    /**
     * Get the vertex supplier that the graph uses whenever it needs to create new vertices
     *
     * @return null|SupplierInterface
     */
    public function getVertexSupplier(): ?SupplierInterface
    {
        return $this->vertexSupplier;
    }

    /**
     * Get the edge supplier that the graph uses whenever it needs to create new edges
     *
     * @return SupplierInterface
     */
    public function getEdgeSupplier(): SupplierInterface
    {
        return $this->edgeSupplier;
    }

    /**
     * Get the graph type
     *
     * @return GraphTypeInterface
     */
    public function getType(): GraphTypeInterface
    {
        return $this->type;
    }

    /**
     * Create a new edge in the graph. Return the newly created edge if added to the graph.
     *
     * @return EdgeInterface
     *
     * @throws InvalidArgumentException
     */
    public function addEdge(
        VertexInterface $sourceVertex,
        VertexInterface $targetVertex,
        ?EdgeInterface $edge = null
    ): ?EdgeInterface {
        $this->assertVertexExists($sourceVertex);
        $this->assertVertexExists($targetVertex);
        
        if (!$this->getType()->isAllowingMultipleEdges() && $this->containsEdge($sourceVertex, $targetVertex)) {
            return null;
        }
        
        if (!$this->getType()->isAllowingSelfLoops() && $sourceVertex->equals($targetVertex)) {
            throw new InvalidArgumentException("loops are not allowed");
        }
        
        $edgeSupplier = $this->edgeSupplier->get();

        if ($this->edgeSpecifics->add($edgeSupplier, $sourceVertex, $targetVertex)) {
            $this->specifics->addEdgeToTouchingVertices($edgeSupplier);
            return $edgeSupplier;
        }
                
        return null;
    }

    /**
     * Create and return a new vertex in the graph.
     *
     * @return null|VertexInterface
     */
    public function addVertex(VertexInterface $vertex): ?VertexInterface
    {
        if (!$this->containsVertex($vertex)) {
            $this->specifics->addVertex($vertex);
            return $vertex;
        }
        
        return null;
    }

    /**
     * Check if the graph contains the given edge, specified either by two vertices or by the edge itself
     *
     * @param VertexInterface $sourceVertex - the source vertex
     * @param VertexInterface $targetVertex - the target vertex
     * @param EdgeInterface $edge - the edge
     *
     * @return bool
     */
    public function containsEdge(
        ?VertexInterface $sourceVertex = null,
        ?VertexInterface $targetVertex = null,
        ?EdgeInterface $edge = null
    ): bool {
        if (!is_null($sourceVertex) && !is_null($targetVertex)) {
            return !is_null($this->getEdge($sourceVertex, $targetVertex));
        }
        return $this->edgeSpecifics->containsEdge($edge);
    }

    /**
     * Check if the graph contains the given vertex
     *
     * @return bool
     */
    public function containsVertex(VertexInterface $vertex): bool
    {
        return $this->specifics->getVertexSet()->contains($vertex);
    }

    /**
     * Get a set of all edges touching the specified vertex
     *
     * @param VertexInterface - the vertex
     *
     * @return EdgeSet
     */
    public function edgesOf(VertexInterface $vertex): EdgeSet
    {
        $this->assertVertexExists($vertex);
        return $this->specifics->edgesOf($vertex);
    }

    /**
     * Get a set of all edges incoming into the specified vertex
     *
     * @param VertexInterface $vertex - the vertex
     *
     * @return EdgeSet
     */
    public function incomingEdgesOf(VertexInterface $vertex): EdgeSet
    {
        $this->assertVertexExists($vertex);
        return $this->specifics->incomingEdgesOf($vertex);
    }

    /**
     * Get a set of all edges outgoing from the specified vertex
     *
     * @param VertexInterface $vertex - the vertex
     *
     * @return EdgeSet
     */
    public function outgoingEdgesOf(VertexInterface $vertex): EdgeSet
    {
        $this->assertVertexExists($vertex);
        return $this->specifics->outgoingEdgesOf($vertex);
    }

    /**
     * Remove all edges specified by two vertices or the the list of edges themselves.
     * Return true, if graph was changed
     *
     * @param VertexInterface $vertex - the source vertex
     * @param VertexInterface $vertex - the target vertex
     * @param array $edges - the edges
     *
     * @return bool
     */
    public function removeAllEdges(
        ?VertexInterface $sourceVertex = null,
        ?VertexInterface $targetVertex = null,
        array $edges = []
    ): bool {
        $changed = false;
        if (!is_null($sourceVertex) && !is_null($targetVertex)) {
            $edge = $this->getEdge($sourceVertex, $targetVertex);
            if (!is_null($edge)) {
                $this->specifics->removeEdgeFromTouchingVertices($edge);
                $this->edgeSpecifics->remove($edge);
                return true;
            }
        } else {
            foreach ($edges as $edge) {
                if ($this->containsEdge($edge)) {
                    $this->specifics->removeEdgeFromTouchingVertices($edge);
                    $this->edgeSpecifics->remove($edge);
                    $changed = true;
                }
            }
        }
        return $changed;
    }

    /**
     * Remove all specified vertices contained in the graph.
     * Return true, if graph was changed
     *
     * @param array $vertices - the vertices
     *
     * @return bool
     */
    public function removeAllVertices(array $vertices = []): bool
    {
        $changed = false;
        foreach ($vertices as $vertex) {
            $changed = $this->removeVertex($vertex);
        }
        return $changed;
    }

    /**
     * Remove the specifeid edge from the graph.
     * Return the edge, if it was removed
     *
     * @param VertexInterface $sourceVertex - the source vertex
     * @param VertexInterface $targetVertex - the target vertex
     * @param EdgeInterface $edge - the edge
     *
     * @return null|EdgeInterface
     */
    public function removeEdge(
        ?VertexInterface $sourceVertex = null,
        ?VertexInterface $targetVertex = null,
        ?EdgeInterface $edge = null
    ): ?EdgeInterface {
        if (!is_null($sourceVertex) && !is_null($targetVertex)) {
            $edge = $this->getEdge($sourceVertex, $targetVertex);
            if (!is_null($edge)) {
                $this->specifics->removeEdgeFromTouchingVertices($edge);
                $this->edgeSpecifics->remove($edge);
                return $edge;
            }
        } elseif (!is_null($edge)) {
            $this->specifics->removeEdgeFromTouchingVertices($edge);
            $this->edgeSpecifics->remove($edge);
            return $edge;
        }
        return null;
    }

    /**
     * Remove the specifeid vertex from the graph.
     * Return the true, if it was removed
     *
     * @param VertexInterface $vertex - the vertex
     *
     * @return bool
     */
    public function removeVertex(VertexInterface $vertex): bool
    {
        if ($this->containsVertex($vertex)) {
            $edges = $this->edgesOf($vertex);
            $this->removeAllEdges(null, null, $edges);
            $this->specifics->removeVertex($vertex);
            return true;
        }
        return false;
    }

    /**
     * Get the set of edges contained in the graph
     *
     * @return EdgeSet
     */
    public function edgeSet(): EdgeSet
    {
        return $this->edgeSpecifics->getEdgeSet();
    }

    /**
     * Get the set of vertices contained in the graph
     *
     * @return VertexSet
     */
    public function vertexSet(): VertexSet
    {
        return $this->specifics->getVertexSet();
    }

    /**
     * Get the edge source vertex
     *
     * @param EdgeInterface $edge - the edge
     *
     * @return VertexInterface
     */
    public function getEdgeSource(EdgeInterface $edge): VertexInterface
    {
        return $this->edgeSpecifics->getEdgeSource($edge);
    }

    /**
     * Get the edge target vertex
     *
     * @param EdgeInterface $edge - the edge
     *
     * @return VertexInterface
     */
    public function getEdgeTarget(EdgeInterface $edge): VertexInterface
    {
        return $this->edgeSpecifics->getEdgeTarget($edge);
    }

    /**
     * Get the edge weight
     *
     * @param EdgeInterface $edge - the edge
     *
     * @return float
     */
    public function getEdgeWeight(EdgeInterface $edge): float
    {
        return $this->edgeSpecifics->getEdgeWeight($edge);
    }

    /**
     * Set the edge weight
     *
     * @param EdgeInterface $edge - the edge
     * @param float $weight - the edge weight
     */
    public function setEdgeWeight(EdgeInterface $edge, float $weight): void
    {
        $this->edgeSpecifics->setEdgeWeight($edge, $weight);
    }

    /**
     * Assert the the vertex exists
     *
     * @param VertexInterface - the vertex
     *
     * @return bool
     *
     * @throws InvalidArgumentException
     */
    private function assertVertexExists(VertexInterface $vertex): bool
    {
        if ($this->containsVertex($vertex)) {
            return true;
        } else {
            throw new InvalidArgumentException("no such vertex in graph");
        }
    }
}
