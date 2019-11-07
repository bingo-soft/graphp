<?php

namespace graphp\vertex;

use ArrayObject;
use graphp\edge\EdgeContainerInterface;

/**
 * Class VertexMap
 *
 * @package graphp\vertex
 */
class VertexMap extends ArrayObject
{
    /**
     * Construct a vertex map. It maps vertices to corresponding edge containers
     *
     * @param array $input - array of vertex - edge container pairs
     */
    public function __construct(array $input = [])
    {
        parent::__construct($input, ArrayObject::ARRAY_AS_PROPS);
    }

    public function get(VertexInterface $vertex): ?EdgeContainerInterface
    {
        $id = $vertex->getHash();
        if (array_key_exists($id, $this->getArrayCopy())) {
            return $this->getArrayCopy()[$id];
        }
        return null;
    }

    public function put(VertexInterface $vertex, EdgeContainerInterface $ec): void
    {
        $id = $vertex->getHash();
        parent::offsetSet($id, $ec);
    }
}
