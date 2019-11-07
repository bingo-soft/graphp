<?php

namespace graphp\graph\specifics;

use graphp\edge\EdgeInterface;
use graphp\vertex\VertexInterface;

/**
 * Interface SpecificsInterface
 *
 * @package graphp\graph\specifics
 */
interface SpecificsInterface
{
    /**
     * Add a vertex
     *
     * @param VertexInterface $vertex - vertex to be added
     */
    public function addVertex(VertexInterface $vertex): void;
    
    /**
     * Get the vertex set
     *
     * @return array
     */
    public function getVertexSet(): array;
    
    /**
     * Get all edges connecting the source vertex to the target vertex
     *
     * @param VertexInterface $sourceVertex - source vertex
     * @param VertexInterface $targetVertex - target vertex
     *
     * @return array
     */
    public function getAllEdges(VertexInterface $sourceVertex, VertexInterface $targetVertex): array;
    
    /**
     * Get an edge connecting the source vertex to the target vertex
     *
     * @param VertexInterface $sourceVertex - source vertex
     * @param VertexInterface $targetVertex - target vertex
     *
     * @return null|EdgeInterface
     */
    public function getEdge(VertexInterface $sourceVertex, VertexInterface $targetVertex): ?EdgeInterface;
    
    /**
     * Get all edges touching the specified vertex
     *
     * @param VertexInterface $vertex - the vertex for which a set of touching edges is to be returned
     *
     * @return array
     */
    public function edgesOf(VertexInterface $vertex): array;
    
    /**
     * Add the specified edge to the edge containers of its source and target vertices.
     *
     * @param EdgeInterface $edge - the edge to be added
     */
    public function addEdgeToTouchingVertices(EdgeInterface $edge): void;
    
    /**
     * Remove the specified edge from the edge containers of its source and target vertices.
     *
     * @param EdgeInterface $edge - the edge to be removed
     */
    public function removeEdgeFromTouchingVertices(EdgeInterface $edge): void;
    
    /**
     * Get all edges outgoing from the specified vertex
     *
     * @param VertexInterface $vertex - the vertex for which the list of outgoing edges to be returned
     *
     * @return array
     */
    public function outgoingEdgesOf(VertexInterface $vertex): array;

    /**
     * Get all edges incoming into the specified vertex
     *
     * @param VertexInterface $vertex - the vertex for which the list of incoming edges to be returned
     *
     * @return array
     */
    public function incomingEdgesOf(VertexInterface $vertex): array;

    /**
     * Get the degree of the specified vertex
     *
     * @param VertexInterface $vertex - the vertex whose degree is to be calculated
     *
     * @return int
     */
    public function degreeOf(VertexInterface $vertex): int;

    /**
     * Get the "in degree" of the specified vertex
     *
     * @param VertexInterface $vertex - the vertex whose in degree is to be calculated
     *
     * @return int
     */
    public function inDegreeOf(VertexInterface $vertex): int;

    /**
     * Get the "out degree" of the specified vertex
     *
     * @param VertexInterface $vertex - the vertex whose out degree is to be calculated
     *
     * @return int
     */
    public function outDegreeOf(VertexInterface $vertex): int;
}
