<?php

namespace Graphp\Vertex;

/**
 * Interface VertexInterface
 *
 * @package Graphp\Vertex
 */
interface VertexInterface
{
    /**
     * Check if two vertices are equal
     *
     * @param VertexInterface $other - other vertex to be compared
     *
     * @return bool
     */
    public function equals(VertexInterface $other): bool;
    
    /**
     * Get vertex unique hash
     *
     * @return string
     */
    public function getHash(): string;

    /**
     * Get vertex value
     *
     * @return mixed
     */
    public function getValue();
}
