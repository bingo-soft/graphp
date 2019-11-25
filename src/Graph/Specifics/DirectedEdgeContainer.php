<?php

namespace Graphp\Graph\Specifics;

use Graphp\Edge\EdgeInterface;
use Graphp\Edge\EdgeContainerInterface;
use Graphp\Edge\EdgeSetFactoryInterface;
use Graphp\Edge\EdgeSet;
use Graphp\Vertex\VertexInterface;

/**
 * Class DirectedEdgeContainer
 *
 * @package Graphp\Graph\Specifics
 */
class DirectedEdgeContainer implements EdgeContainerInterface
{
    /**
     * Outgoing vertex edges
     *
     * @var array
     */
    private $outgoing;
    
    /**
     * Incoming vertex edges
     *
     * @var array
     */
    private $incoming;
    
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
     * @return EdgeSet
     */
    public function getOutgoing(): EdgeSet
    {
        return $this->outgoing;
    }
    
    /**
     * Get container incoming edges
     *
     * @return EdgeSet
     */
    public function getIncoming(): EdgeSet
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
                unset($this->incoming[$key]);
                break;
            }
        }
    }

    /**
     * Get the number of edges
     *
     * @return int
     */
    public function edgeCount(): int
    {
        return count($this->outgoing) + count($this->incoming);
    }
}
