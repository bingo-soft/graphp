<?php

namespace Graphp\Edge\Specifics;

use Graphp\Edge\EdgeInterface;
use Graphp\Vertex\VertexInterface;

/**
 * Class WeightedEdgeSpecifics
 *
 * @package Graphp\Edge\Specifics
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
