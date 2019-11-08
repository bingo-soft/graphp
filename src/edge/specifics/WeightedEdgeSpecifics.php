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
     * @return double
     */
    public function getEdgeWeight(EdgeInterface $edge): double
    {
        return $edge->getWeight();
    }
    
    /**
     * Set the edge weight
     *
     * @param EdgeInterface $edge - the edge
     * @param double $weight - the weight
     */
    public function setEdgeWeight(EdgeInterface $edge, double $weight): void
    {
        $edge->setWeight($weight);
    }
}
