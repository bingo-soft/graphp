<?php

namespace graphp\graph\specifics;

use graphp\edge\EdgeInterface;
use graphp\edge\EdgeContainerInterface;
use graphp\edge\EdgeSetFactoryInterface;
use graphp\vertex\VertexInterface;

/**
 * Class DirectedEdgeContainer
 *
 * @package graphp\graph\specifics
 */
class DirectedEdgeContainer implements EdgeContainerInterface
{
    /**
     * Outgoing vertex edges
     *
     * @var array
     */
    private $outgoing = [];
    
    /**
     * Incoming vertex edges
     *
     * @var array
     */
    private $incoming = [];
    
    /**
     * Construct directed edge container
     *
     * @param EdgeSetFactoryInterface $edgeSetFactory - the edge set factory
     * @param VertexInterface $vertex - the vertex
     */
    public function __construct(EdgeSetFactoryInterface $edgeSetFactory, VertexInterface $vertex)
    {
        $this->outgoing = $edgeSetFactory->createEdgeSet($vertex);
        $this->incoming = $edgeSetFactory->createEdgeSet($vertex);
    }
    
    /**
     * Get container outgoing edges
     *
     * @return array
     */
    public function getOutgoing(): array
    {
        return $this->outgoing;
    }
    
    /**
     * Get container incoming edges
     *
     * @return array
     */
    public function getIncoming(): array
    {
        return $this->incoming;
    }
    
    /**
     * Add the outgoing edge
     *
     * @param EdgeInterface $edge - the edge to be added
     */
    public function addOutgoingEdge(EdgeInterface $edge): void
    {
        $this->outgoing[] = $edge;
    }
    
    /**
     * Add the incoming edge
     *
     * @param EdgeInterface $edge - the edge to be added
     */
    public function addIncomingEdge(EdgeInterface $edge): void
    {
        $this->incoming[] = $edge;
    }
    
    /**
     * Remove outgoing edge
     *
     * @param EdgeInterface $edge - the edge to be removed
     */
    public function removeOutgoingEdge(EdgeInterface $edge): void
    {
        foreach ($this->outgoing as $key => $e) {
            if ($edge->equals($e)) {
                unset($this->outgoing[$key]);
                break;
            }
        }
    }
    
    /**
     * Remove incoming edge
     *
     * @param EdgeInterface $edge - the edge to be removed
     */
    public function removeIncomingEdge(EdgeInterface $edge): void
    {
        foreach ($this->incoming as $key => $e) {
            if ($edge->equals($e)) {
                unset($this->outgoing[$key]);
                break;
            }
        }
    }
}
