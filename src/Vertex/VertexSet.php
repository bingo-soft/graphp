<?php

namespace Graphp\Vertex;

use ArrayObject;

/**
 * Class VertexSet
 *
 * @package Graphp\Vertex
 */
class VertexSet extends ArrayObject
{
    /**
     * Construct a set of vertices
     *
     * @param array $input - array of vertices
     */
    public function __construct(array $input = [])
    {
        parent::__construct($input, ArrayObject::ARRAY_AS_PROPS);
    }
    
    /**
     * Check if the set contains the vertex
     *
     * @param VertexInterface $vertex - niddle
     */
    public function contains(VertexInterface $vertex): bool
    {
        return in_array($vertex, $this->getArrayCopy());
    }
    
    /**
     * Remove the vertex from the set
     *
     * @param VertexInterface $vertex - niddle
     */
    public function remove(VertexInterface $vertex): void
    {
        if (($id = array_search($vertex, $this->getArrayCopy())) !== false) {
            unset($this[$id]);
        }
    }
}
