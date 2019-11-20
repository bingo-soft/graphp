<?php

namespace graphp\edge;

use graphp\GraphInterface;
use graphp\vertex\VertexInterface;

/**
 * Class DefaultWeightedEdge
 *
 * @package graphp\edge
 */
class DefaultWeightedEdge extends DefaultEdge
{
    /**
     * Weight of the edge
     *
     * @var float
     */
    protected $weight = GraphInterface::DEFAULT_EDGE_WEIGHT;

    /**
     * Get weight of the edge
     *
     * @return mixed
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * Set the weight of the edge
     *
     * @param float $weight - the edge weight
     */
    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }
}
