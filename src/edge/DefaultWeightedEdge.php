<?php

namespace graphp\edge;

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
     * @var mixed
     */
    protected $weight;

    /**
     * Get weight of the edge
     *
     * @return mixed
     */
    protected function getWeight()
    {
        return $this->weight;
    }
}
