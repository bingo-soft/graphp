<?php

namespace Graphp\Alg\Interfaces;

use Graphp\Vertex\VertexInterface;
use Graphp\GraphPathInterface;

/**
 * Interface ShortestPathAlgorithmInterface
 *
 * @package Graphp\Alg\Interfaces
 */
interface ShortestPathAlgorithmInterface
{
    /**
     * Get the shortest path from the source vertex to the target vertex.
     *
     * @param VertexInterface $sourceVertex - the source vertex
     * @param VertexInterface $targetVertex - the target vertex
     *
     * @return GraphPathInterface
     */
    public function getPath(VertexInterface $sourceVertex, VertexInterface $targetVertex): GraphPathInterface;
    
    /**
     * Get the weight of the path from the source vertex to the target vertex.
     *
     * @param VertexInterface $sourceVertex - the source vertex
     * @param VertexInterface $targetVertex - the target vertex
     *
     * @return float
     */
    public function getPathWeight(VertexInterface $sourceVertex, VertexInterface $targetVertex): ?float;
    
    /**
     * Compute all shortest paths starting from a single source vertex.
     *
     * @param VertexInterface $sourceVertex - the source vertex
     *
     * @return SingleSourcePathsInterface
     */
    public function getPaths(VertexInterface $sourceVertex): SingleSourcePathsInterface;
}
