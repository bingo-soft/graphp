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
     * Get the edge hash
     *
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }
    
    /**
     * Get the edge source vertex
     *
     * @return VertexInterface
     */
    public function getSource(): VertexInterface
    {
        return $this->sourceVertex;
    }
    
    /**
     * Get the edge target vertex
     *
     * @return VertexInterface
     */
    public function getTarget(): VertexInterface
    {
        return $this->targetVertex;
    }

    /**
     * Set the edge source vertex
     *
     * @param VertexInterface $vertex - the source vertex
     */
    public function setSource(VertexInterface $vertex): void
    {
        $this->sourceVertex = $vertex;
    }
    
    /**
     * Set the edge target vertex
     *
     * @param VertexInterface $vertex - the target vertex
     */
    public function setTarget(VertexInterface $vertex): void
    {
        $this->targetVertex = $vertex;
    }

    /**
     * Get the edge string representation
     *
     * @return string
     */
    public function __toString(): string
    {
        return "(" . (string) $this->sourceVertex . " : " . (string) $this->targetVertex . ")";
    }
}
