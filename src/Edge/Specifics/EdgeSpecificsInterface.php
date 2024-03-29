<?php

namespace Graphp\Edge\Specifics;

use Graphp\Edge\EdgeInterface;
use Graphp\Edge\EdgeSet;
use Graphp\Vertex\VertexInterface;

/**
 * Interface EdgeSpecificsInterface
 *
 * @package Graphp\Edge\Specifics
 */
interface EdgeSpecificsInterface
{
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
     * Add a new edge connecting the specified vertices
     * Return true, if edge was added
     *
     * @param EdgeInterface $edge - the edge
     * @param VertexInterface $sourceVertex - the source vertex
     * @param VertexInterface $sourceVertex - tge target vertex
     *
     * @return bool
     */
    public function add(EdgeInterface $edge, VertexInterface $sourceVertex, VertexInterface $targetVertex): bool;
    
    /**
     * Check if the edge exists
     *
     * @param EdgeInterface $edge - the edge
     *
     * @return bool
     */
    public function containsEdge(EdgeInterface $edge): bool;
    
    /**
     * Get the edge set
     *
     * @return EdgeSet
     */
    public function getEdgeSet(): EdgeSet;
    
    /**
     * Remove the edge from the set
     *
     * @param EdgeInterface $edge - the edge
     */
    public function remove(EdgeInterface $edge): void;
    
    /**
     * Get the edge weight
     *
     * @param EdgeInterface $edge - the edge
     *
     * @return null|float
     */
    public function getEdgeWeight(EdgeInterface $edge): ?float;
    
    /**
     * Set the edge weight
     *
     * @param EdgeInterface $edge - the edge
     * @param float $weight - the weight
     */
    public function setEdgeWeight(EdgeInterface $edge, ?float $weight = null): void;
}
