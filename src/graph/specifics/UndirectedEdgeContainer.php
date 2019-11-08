<?php

namespace graphp\graph\specifics;

use graphp\edge\EdgeInterface;
use graphp\edge\EdgeSetFactoryInterface;
use graphp\edge\EdgeContainerInterface;
use graphp\vertex\VertexInterface;

/**
 * Class UndirectedEdgeContainer
 *
 * @package graphp\graph\specifics
 */
final class UndirectedEdgeContainer implements EdgeContainerInterface
{
    /**
     * Container vertex edges
     *
     * @var array
     */
    private $vertexEdges = [];
    
    /**
     * Construct undirected edge container
     *
     * @param EdgeSetFactoryInterface $edgeSetFactory - the edge set factory
     * @param VertexInterface $vertex - the vertex
     */
    public function __construct(EdgeSetFactoryInterface $edgeSetFactory, VertexInterface $vertex)
    {
        $this->vertexEdges = $edgeSetFactory->createEdgeSet($vertex);
    }
    
    /**
     * Add an edge to the container
     *
     * @param EdgeInterface $edge - the edge to be added
     */
    public function addEdge(EdgeInterface $edge): void
    {
        $this->vertexEdges[] = $edge;
    }
    
    /**
     * Remove the edge from the container
     *
     * @param EdgeInterface $edge - the edge to be removed
     */
    public function removeEdge(EdgeInterface $edge): void
    {
        foreach ($this->vertexEdges as $key => $e) {
            if ($edge->equals($e)) {
                unset($this->vertexEdges[$key]);
                break;
            }
        }
    }
    
    /**
     * Get all container edges
     *
     * @return array
     */
    public function getEdges(): array
    {
        return $this->vertexEdges;
    }

    /**
     * Get the number of edges
     *
     * @return int
     */
    public function edgeCount(): int
    {
        return count($this->vertexEdges);
    }
}
