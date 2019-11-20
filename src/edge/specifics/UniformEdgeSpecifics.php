<?php

namespace graphp\edge\specifics;

use BadMethodCallException;
use graphp\GraphInterface;
use graphp\edge\EdgeInterface;
use graphp\edge\EdgeSet;
use graphp\vertex\VertexInterface;

/**
 * Class UniformEdgeSpecifics
 *
 * @package graphp\edge\specifics
 */
class UniformEdgeSpecifics implements EdgeSpecificsInterface
{
    /**
     * The edge set
     *
     * @var EdgeSet
     */
    protected $edgeSet;
    
    /**
     * Construct a new specifics
     */
    public function __construct()
    {
        $this->edgeSet = new EdgeSet();
    }
    
    /**
     * Check if the edge exists
     *
     * @param EdgeInterface $edge - the edge
     *
     * @return bool
     */
    public function containsEdge(EdgeInterface $edge): bool
    {
        return $this->edgeSet->contains($edge);
    }
    
     /**
     * Get the edge set
     *
     * @return EdgeSet
     */
    public function getEdgeSet(): EdgeSet
    {
        return $this->edgeSet;
    }
    
    /**
     * Remove the edge from the set
     *
     * @param EdgeInterface $edge - the edge
     */
    public function remove(EdgeInterface $edge): void
    {
        if ($this->edgeSet->contains($edge)) {
            $this->edgeSet->remove($edge);
        }
    }
    
    /**
     * Get the edge source vertex
     *
     * @param EdgeInterface $edge - the edge
     *
     * @return VertexInterface
     */
    public function getEdgeSource(EdgeInterface $edge): VertexInterface
    {
        return $edge->getSource();
    }
    
     /**
     * Get the edge target vertex
     *
     * @param EdgeInterface $edge - the edge
     *
     * @return VertexInterface
     */
    public function getEdgeTarget(EdgeInterface $edge): VertexInterface
    {
        return $edge->getTarget();
    }
    
    /**
     * Get the edge weight
     *
     * @param EdgeInterface $edge - the edge
     *
     * @return float
     */
    public function getEdgeWeight(EdgeInterface $edge): float
    {
        return GraphInterface::DEFAULT_EDGE_WEIGHT;
    }
    
    /**
     * Set the edge weight
     *
     * @param EdgeInterface $edge - the edge
     * @param float $weight - the weight
     *
     * @throws BadMethodCallException
     */
    public function setEdgeWeight(EdgeInterface $edge, float $weight): void
    {
        throw new BadMethodCallException("Method is not supported by this type of edge");
    }
    
    /**
     * Get the edge
     *
     * @param EdgeInterface $edge - the edge
     *
     * @return null|EdgeInterface
     */
    public function getEdge(EdgeInterface $edge): ?EdgeInterface
    {
        if (($id = array_search($edge, $this->edgeSet->getArrayCopy())) !== false) {
            return $this->edgeSet[$id];
        }
        return null;
    }
    
    /**
     * Add a new edge connecting the specified vertices
     * Return true, if edge was added
     *
     * @param EdgeInterface $edge - the edge
     * @param VertexInterface $sourceVertex - the source vertex
     * @param VertexInterface $sourceVertex - tge target vertex
     *
     * @return bool
     */
    public function add(EdgeInterface $edge, VertexInterface $sourceVertex, VertexInterface $targetVertex): bool
    {
        $edge->setSource($sourceVertex);
        $edge->setTarget($targetVertex);

        if (!$this->containsEdge($edge)) {
            $this->edgeSet[] = $edge;
            return true;
        }

        return false;
    }
}
