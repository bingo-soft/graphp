<?php

namespace graphp\vertex;

/**
 * Class Vertex
 *
 * @package graphp\vertex
 */
class Vertex implements VertexInterface
{
    /**
     * The vertex unique hash
     *
     * @var string
     */
    private $hash;

    /**
     * The vertex value
     *
     * @var mixed
     */
    private $value;
    
    /**
     * Construct a new vertex
     *
     * @param mixed $value - value stored in vertex
     */
    public function __construct($value)
    {
        $this->value = $value;
        $this->hash = uniqid('', true);
    }
    
    /**
     * Check if two vertices are equal
     *
     * @param VertexInterface $other - other vertex to be compared
     */
    public function equals(VertexInterface $other): bool
    {
        return $this->hash == $other->getHash();
    }
    
    /**
     * Get the vertex hash
     *
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }
    
    /**
     * Get the vertex value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
    
    /**
     * Get the vertex string representation
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }
}