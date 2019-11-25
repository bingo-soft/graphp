<?php

namespace Graphp\Alg\Shortestpath;

use Exception;
use InvalidArgumentException;
use Heap\AddressableHeapInterface;
use Heap\Tree\PairingHeap;
use Graphp\GraphInterface;
use Graphp\GraphUtils;
use Graphp\Edge\EdgeInterface;
use Graphp\Vertex\VertexInterface;
use Graphp\Util\Supplier;
use Graphp\Alg\Interfaces\SingleSourcePathsInterface;

/**
 * Class DijkstraClosestFirstIterator
 *
 * @package Graphp\Alg\Shortestpath;
 */
class DijkstraClosestFirstIterator
{
    /**
     * The graph
     *
     * @var GraphInterface
     */
    private $graph;

    /**
     * The source vertex
     *
     * @var VertexInterface
     */
    private $source;

    /**
     * The radius
     *
     * @var float
     */
    private $radius;

    /**
     * Already seen vertices
     *
     * @var array
     */
    private $seen = [];

    /**
     * The underlying heap implementation
     *
     * @var AddressableHeapInterface
     */
    private $heap;

    /**
     * Create a new iterator for the specified graph.
     * If radius is specified, iteration will start at the source vertex and
     * will be limited to the subset of paths of weighted length less than or equal to the radius
     *
     * @param GraphInterface $graph - the graph
     * @param VertexInterface $source - the source vertex
     * @param float $radius - the limit on weighted path length
     * @param Supplier $heapSupplier - supplier of the preferable heap implementation
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        GraphInterface $graph,
        VertexInterface $source,
        ?float $radius = null,
        ?Supplier $heapSupplier = null
    ) {
        $this->graph = $graph;
        $this->source = $source;
        $this->radius = $radius ?? INF;
        if ($this->radius < 0.0) {
            throw new InvalidArgumentException("Radius must be non-negative");
        }
        $this->seen = [];
        $this->heap = is_null($heapSupplier) ? new PairingHeap() : $heapSupplier->get();
        $this->updateDistance($this->source, null, 0.0);
    }

    /**
     * Checks if iterator has next element to look up
     *
     * @return bool
     */
    public function hasNext(): bool
    {
        if ($this->heap->isEmpty()) {
            return false;
        }
        $vNode = $this->heap->findMin();
        $vDistance = $vNode->getKey();
        if ($this->radius < $vDistance) {
            $this->heap->clear();
            return false;
        }
        return true;
    }

    /**
     * Get the next vertex
     *
     * @return VertexInterface
     */
    public function next(): VertexInterface
    {
        if (!$this->hasNext()) {
            throw new Exception("No such element!");
        }

        // settle next node
        $vNode = $this->heap->deleteMin();
        $v = $vNode->getValue()[0];
        $vDistance = $vNode->getKey();
        
        // relax edges
        $edges = $this->graph->outgoingEdgesOf($v);
        foreach ($edges as $edge) {
            $u = GraphUtils::getOppositeVertex($this->graph, $edge, $v);
            $eWeight = $this->graph->getEdgeWeight($edge);
            if ($eWeight < 0.0) {
                throw new InvalidArgumentException("Negative edge weight not allowed");
            }
            $this->updateDistance($u, $edge, $vDistance + $eWeight);
        }

        return $v;
    }

    /**
     * Get the paths computed by the iterator
     *
     * @return SingleSourcePathsInterface
     */
    public function getPaths(): SingleSourcePathsInterface
    {
        return new TreeSingleSourcePaths($this->graph, $this->source, $this->getDistanceAndPredecessorMap());
    }

    /**
     * Get all paths using the traditional representation of the shortest path tree, which stores
     * for each vertex (a) the distance of the path from the source vertex and (b) the last edge
     * used to reach the vertex from the source vertex
     *
     * @return array
     */
    public function getDistanceAndPredecessorMap(): array
    {
        $distanceAndPredecessorMap = [];

        foreach ($this->seen as $vertexHash => $vNode) {
            $vDistance = $vNode->getKey();
            if ($this->radius < $vDistance) {
                continue;
            }
            $v = $vNode->getValue()[0];
            $distanceAndPredecessorMap[$vertexHash] = [$vDistance, $vNode->getValue()[1]];
        }

        return $distanceAndPredecessorMap;
    }

    /**
     * Update the distance to the vertex
     *
     * @param VertexInterface $v - the vertex
     * @param EdgeInterface $e - the predecessor edge
     * @param float $distance - the new distance
     */
    private function updateDistance(VertexInterface $v, ?EdgeInterface $e = null, float $distance): void
    {
        $id = $v->getHash();
        if (!array_key_exists($id, $this->seen)) {
            $node = $this->heap->insert($distance, [$v, $e]);
            $this->seen[$id] = $node;
        } else {
            $node = $this->seen[$id];
            if ($distance < $node->getKey()) {
                $node->decreaseKey($distance);
                $node->setValue([$node->getValue()[0], $e]);
            }
        }
    }
}
