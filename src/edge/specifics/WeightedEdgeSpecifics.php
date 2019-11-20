<?php

namespace graphp\edge\specifics;

use graphp\edge\EdgeInterface;
use graphp\vertex\VertexInterface;

/**
 * Class WeightedEdgeSpecifics
 *
 * @package graphp\edge\specifics
 */
class WeightedEdgeSpecifics extends UniformEdgeSpecifics
{
    /**
     * Get the edge weight
     *
     * @param EdgeInterface $edge - the edge
     *
     * @return null|float
     */
    public function getEdgeWeight(EdgeInterface $edge): ?float
    {
        return $edge->getWeight();
    }
    
    /**
     * Set the edge weight
     *
     * @param EdgeInterface $edge - the edge
     * @param float $weight - the weight
     */
    public function setEdgeWeight(EdgeInterface $edge, ?float $weight = null): void
    {
        $edge->setWeight($weight);
    }
}
