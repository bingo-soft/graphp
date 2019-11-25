<?php

namespace Graphp\Edge;

use ArrayObject;

/**
 * Class EdgeSet
 *
 * @package Graphp\Edge
 */
class EdgeSet extends ArrayObject
{
    /**
     * Construct a set of edges
     *
     * @param array $input - array of edges
     */
    public function __construct(array $input = [])
    {
        parent::__construct($input, ArrayObject::ARRAY_AS_PROPS);
    }
    
    /**
     * Check if the set contains the edge
     *
     * @param EdgeInterface $edge - niddle
     */
    public function contains(EdgeInterface $edge): bool
    {
        return in_array($edge, $this->getArrayCopy());
    }
    
    /**
     * Remove the edge from the set
     *
     * @param EdgeInterface $edge - niddle
     */
    public function remove(EdgeInterface $edge): void
    {
        if (($id = array_search($edge, $this->getArrayCopy())) !== false) {
            unset($this[$id]);
        }
    }
}
