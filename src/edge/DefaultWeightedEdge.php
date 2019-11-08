<?php

namespace graphp\edge;

use graphp\graph\GraphInterface;
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
     * @var double
     */
    protected $weight = GraphInterface::DEFAULT_EDGE_WEIGHT;

    /**
     * Get weight of the edge
     *
     * @return mixed
     */
    protected function getWeight(): double
    {
        return $this->weight;
    }
}
