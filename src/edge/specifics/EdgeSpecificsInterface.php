<?php

namespace graphp\edge\specifics;

use graphp\edge\EdgeInterface;
use graphp\edge\EdgeSet;
use graphp\vertex\VertexInterface;

/**
 * Interface EdgeSpecificsInterface
 *
 * @package graphp\edge\specifics
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
     * @return double
     */
    public function getEdgeWeight(EdgeInterface $edge): double;
    
    /**
     * Set the edge weight
     *
     * @param EdgeInterface $edge - the edge
     * @param double $weight - the weight
     */
    public function setEdgeWeight(EdgeInterface $edge, double $weight): void;
}
