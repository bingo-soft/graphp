<?php

namespace Graphp\Alg\Interfaces;

use Graphp\Vertex\VertexInterface;
use Graphp\GraphInterface;
use Graphp\GraphPathInterface;

/**
 * Interface SingleSourcePathsInterface
 *
 * @package Graphp\Alg\Interfaces
 */
interface SingleSourcePathsInterface
{
    /**
     * Get the graph over which this set of paths is defined
     *
     * @return GraphInterface
     */
    public function getGraph(): GraphInterface;
    
    /**
     * Get the single source vertex
     *
     * @return GraphInterface
     */
    public function getSourceVertex(): VertexInterface;
    
    /**
     * Get the weight of the path from the source vertex to the target vertex
     *
     * @param VertexInterface $targetVertex - the target vertex
     *
     * @return float
     */
    public function getWeight(VertexInterface $targetVertex): ?float;
    
    /**
     * Return the path from the source vertex to the target vertex
     *
     * @param VertexInterface $targetVertex - the target vertex
     *
     * @return GraphPathInterface
     */
    public function getPath(VertexInterface $targetVertex): GraphPathInterface;
}
