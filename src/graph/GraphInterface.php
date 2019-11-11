<?php

namespace graphp\graph;

use graphp\edge\EdgeInterface;
use graphp\edge\EdgeSetFactoryInterface;
use graphp\edge\EdgeSet;
use graphp\vertex\VertexInterface;
use graphp\vertex\VertexSet;
use graphp\util\SupplierInterface;

/**
 * Interface GraphInterface
 *
 * @package graphp\graph
 */
interface GraphInterface
{
    /**
     * Default edge weight
     *
     * @var float
     */
    public const DEFAULT_EDGE_WEIGHT = 1.0;

    /**
     * Get all edges connecting the source vertext to the target vertex
     *
     * @return EdgeSet
     */
    public function getAllEdges(VertexInterface $sourceVertex, VertexInterface $targetVertex): EdgeSet;
    
    /**
     * Get an edge connecting the source vertext to the target vertex
     *
     * @return null|EdgeInterface
     */
    public function getEdge(VertexInterface $sourceVertex, VertexInterface $targetVertex): ?EdgeInterface;
    
    /**
     * Get the vertex supplier that the graph uses whenever it needs to create new vertices
     *
     * @return null|SupplierInterface
     */
    public function getVertexSupplier(): ?SupplierInterface;

    /**
     * Get the edge supplier that the graph uses whenever it needs to create new edges
     *
     * @return SupplierInterface
     */
    public function getEdgeSupplier(): SupplierInterface;
    
    /**
     * Create a new edge in the graph. Return the newly created edge if added to the graph.
     *
     * @return null|EdgeInterface
     */
    public function addEdge(
        VertexInterface $sourceVertex,
        VertexInterface $targetVertex,
        ?EdgeInterface $edge = null
    ): ?EdgeInterface;
    
    /**
     * Create and return a new vertex in the graph.
     *
     * @return null|VertexInterface
     */
    public function addVertex(VertexInterface $vertex): ?VertexInterface;
    
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
    ): bool;
    
    /**
     * Check if the graph contains the given vertex
     *
     * @return bool
     */
    public function containsVertex(VertexInterface $vertex): bool;
    
    /**
     * Get a set of all edges touching the specified vertex
     *
     * @param VertexInterface - the vertex
     *
     * @return EdgeSet
     */
    public function edgesOf(VertexInterface $vertex): EdgeSet;
    
    /**
     * Get a set of all edges incoming into the specified vertex
     *
     * @param VertexInterface $vertex - the vertex
     *
     * @return EdgeSet
     */
    public function incomingEdgesOf(VertexInterface $vertex): EdgeSet;

    /**
     * Get a set of all edges outgoing from the specified vertex
     *
     * @param VertexInterface $vertex - the vertex
     *
     * @return EdgeSet
     */
    public function outgoingEdgesOf(VertexInterface $vertex): EdgeSet;
    
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
    ): bool;
    
    /**
     * Remove all specified vertices contained in the graph.
     * Return true, if graph was changed
     *
     * @param array $vertices - the vertices
     *
     * @return bool
     */
    public function removeAllVertices(array $vertices = []): bool;
    
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
    ): ?EdgeInterface;
    
    /**
     * Remove the specifeid vertex from the graph.
     * Return the true, if it was removed
     *
     * @param VertexInterface $vertex - the vertex
     *
     * @return bool
     */
    public function removeVertex(VertexInterface $vertex): bool;
    
    /**
     * Get the set of edges contained in the graph
     *
     * @return EdgeSet
     */
    public function edgeSet(): EdgeSet;

    /**
     * Get the set of vertices contained in the graph
     *
     * @return VertexSet
     */
    public function vertexSet(): VertexSet;
    
    /**
     * Get the edge source vertex
     *
     * @param EdgeInterface $edge - the edge
     *
     * @return VertexInterface
     */
    public function getEdgeSource(EdgeInterface $edge): VertexInterface;
    
    /**
     * Get the edge target vertex
     *
     * @param EdgeInterface $edge - the edge
     *
     * @return VertexInterface
     */
    public function getEdgeTarget(EdgeInterface $edge): VertexInterface;
    
    /**
     * Get the edge weight
     *
     * @param EdgeInterface $edge - the edge
     *
     * @return float
     */
    public function getEdgeWeight(EdgeInterface $edge): float;
    
    /**
     * Set the edge weight
     *
     * @param EdgeInterface $edge - the edge
     * @param float $weight - the edge weight
     */
    public function setEdgeWeight(EdgeInterface $edge, float $weight): void;
}
