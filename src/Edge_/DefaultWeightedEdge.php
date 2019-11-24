<?php

namespace Graphp\Edge;

use Graphp\GraphInterface;
use Graphp\Vertex\VertexInterface;

/**
 * Class DefaultWeightedEdge
 *
 * @package Graphp\Edge
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
     * @return null|float
     */
    public function getWeight(): ?float
    {
        return $this->weight;
    }

    /**
     * Set the weight of the edge
     *
     * @param float $weight - the edge weight
     */
    public function setWeight(?float $weight = null): void
    {
        $this->weight = $weight;
    }
}
