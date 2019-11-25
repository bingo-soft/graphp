<?php

namespace Graphp\vertex;

use ArrayObject;
use Graphp\Edge\EdgeContainerInterface;

/**
 * Class VertexMap
 *
 * @package Graphp\Vertex
 */
class VertexMap extends ArrayObject
{
    private $vertices = [];

    /**
     * Construct a vertex map. It maps vertices to corresponding edge containers
     *
     * @param array $input - array of vertex - edge container pairs
     */
    public function __construct(array $input = [])
    {
        parent::__construct($input, ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * Get the edge container of the specified vertex
     *
     * @return null|EdgeContainerInterface
     */
    public function get(VertexInterface $vertex): ?EdgeContainerInterface
    {
        $id = $vertex->getHash();
        if (array_key_exists($id, $this->getArrayCopy())) {
            return $this->getArrayCopy()[$id];
        }
        return null;
    }

    /**
     * Put a new value to vertexmap
     *
     * @param VertexInterface $vertex - the vertex
     * @param EdgeContainerInterface $ec - the edge container
     */
    public function put(VertexInterface $vertex, ?EdgeContainerInterface $ec = null): void
    {
        $id = $vertex->getHash();
        parent::offsetSet($id, $ec);
        $this->vertices[$id] = $vertex;
    }

    /**
     * Remove a vertex from the vertexmap
     *
     * @param VertexInterface $vertex - niddle
     */
    public function remove(VertexInterface $vertex): void
    {
        $this->offsetUnset($vertex->getHash());
    }

    /**
     * Unset VertexMap value by key
     *
     * @param mixed $offset - array offset
     */
    public function offsetUnset($offset): void
    {
        $iter = $this->getIterator();
        $iter->offsetUnset($offset);
        unset($this->vertices[$offset]);
    }

    /**
     * Get array of vertices
     *
     * @return array
     */
    public function keySet(): VertexSet
    {
        return new VertexSet(array_values($this->vertices));
    }
}
