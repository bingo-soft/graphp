<?php

namespace graphp\edge;

use graphp\vertex\VertexInterface;

/**
 * Class DefaultEdge
 *
 * @package graphp\edge
 */
class DefaultEdge implements EdgeInterface
{
    /**
     * The edge unique hash
     *
     * @var string
     */
    private $hash;

    /**
     * The edge source vertex
     *
     * @var VertexInterface
     */
    protected $sourceVertex;
    
    /**
     * The edge target vertex
     *
     * @var VertexInterface
     */
    protected $targetVertex;

    /**
     * Construct a new edge
     */
    public function __construct()
    {
        $this->hash = uniqid('', true);
    }

    /**
     * Check if two edges are equal
     *
     * @param EdgeInterface $other - other edge to be compared
     */
    public function equals(EdgeInterface $other): bool
    {
        return $this->hash == $other->getHash();
    }
    
    /**
     * Get the edge source vertex
     *
     * @return VertexInterface
     */
    protected function getSource(): VertexInterface
    {
        return $this->sourceVertex;
    }
    
    /**
     * Get the edge target vertex
     *
     * @return VertexInterface
     */
    protected function getTarget(): VertexInterface
    {
        return $this->targetVertex;
    }

    /**
     * Get the edge string representation
     *
     * @return string
     */
    public function __toString(): string
    {
        return "(" . $this->sourceVertex + " : " + $this->targetVertex + ")";
    }
}
