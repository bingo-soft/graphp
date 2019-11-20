<?php

namespace graphp;

use graphp\vertex\VertexInterface;

/**
 * Interface GraphPathInterface
 *
 * @package graphp
 */
interface GraphPathInterface
{
    /**
     * Get the graph over which the path is defined
     *
     * @return GraphInterface
     */
    public function getGraph(): GraphInterface;

    /**
     * Get the start vertex in the path
     *
     * @return VertexInterface
     */
    public function getStartVertex(): VertexInterface;
    
    /**
     * Get the end vertex in the path
     *
     * @return VertexInterface
     */
    public function getEndVertex(): VertexInterface;

    /**
     * Get the edges making up the path
     *
     * @return array
     */
    public function getEdgeList(): array;
    
    /**
     * Get the vertices making up the path
     *
     * @return array
     */
    public function getVertexList(): array;

    /**
     * Get the weight of the path, which is typically the sum of the weights of the edges
     *
     * @return float
     */
    public function getWeight(): float;
    
    /**
     * Get the length of the path, measured in the number of edges
     *
     * @return int
     */
    public function getLength(): int;
}
