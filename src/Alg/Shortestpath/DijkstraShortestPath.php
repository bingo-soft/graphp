<?php

namespace Graphp\Alg\Shortestpath;

use InvalidArgumentException;
use Heap\AddressableHeapInterface;
use Heap\Tree\PairingHeap;
use Graphp\GraphPathInterface;
use Graphp\GraphInterface;
use Graphp\Vertex\VertexInterface;
use Graphp\Util\Supplier;
use Graphp\Alg\Interfaces\SingleSourcePathsInterface;

/**
 * Class DijkstraShortestPath
 *
 * @package Graphp\Alg\Shortestpath
 */
class DijkstraShortestPath extends AbstractShortestPathAlgorithm
{
    /**
     * The radius
     *
     * @var float
     */
    private $radius;

    /**
     * The heap supplier
     *
     * @var Supplier
     */
    private $heapSupplier;

    /**
     * Construct a new instance of the algorithm for the given graph
     *
     * @param GraphInterface $graph - the graph
     * @param float $radius - the radius
     * @param Supplier $heapSupplier - the heap supplier
     */
    public function __construct(GraphInterface $graph, float $radius = null, ?Supplier $heapSupplier = null)
    {
        parent::__construct($graph);
        
        if (!is_null($radius) && $radius < 0) {
            throw new InvalidArgumentException("Radius must be non-negative");
        }
        
        $this->radius = $radius ?? INF;
        $this->heapSupplier = $heapSupplier ?? new Supplier(PairingHeap::class);
    }

    /**
     * Find path between two vertices, if it exists.
     *
     * @param GraphInterface $graph - the graph
     * @param VertexInterface $source - the source vertex
     * @param VertexInterface $sink - the target vertex
     *
     * @return null|GraphPathInterface
     */
    public static function findPathBetween(
        GraphInterface $graph,
        VertexInterface $source,
        VertexInterface $sink
    ): ?GraphPathInterface {
        return (new DijkstraShortestPath($graph))->getPath($source, $sink);
    }

    /**
     * Get the path from the source vertex to the target vertex
     *
     * @param VertexInterface $targetVertex - the source vertex
     * @param VertexInterface $sink - the target vertex
     *
     * @return null|GraphPathInterface
     */
    public function getPath(VertexInterface $source, VertexInterface $sink): ?GraphPathInterface
    {
        if (!$this->graph->containsVertex($source)) {
            throw new InvalidArgumentException("Graph must contain the source vertex");
        }
        if (!$this->graph->containsVertex($sink)) {
            throw new InvalidArgumentException("Graph must contain the target vertex");
        }
        if ($source->equals($sink)) {
            return $this->createEmptyPath($source, $sink);
        }

        $it = new DijkstraClosestFirstIterator($this->graph, $source, $this->radius, $this->heapSupplier);

        while ($it->hasNext()) {
            $vertex = $it->next();
            if ($vertex->equals($sink)) {
                break;
            }
        }

        return $it->getPaths()->getPath($sink);
    }

    /**
     * Compute all shortest paths starting from a single source vertex.
     *
     * @param VertexInterface $sourceVertex - the source vertex
     *
     * @return SingleSourcePathsInterface
     */
    public function getPaths(VertexInterface $source): SingleSourcePathsInterface
    {
        if (!$this->graph->containsVertex($source)) {
            throw new InvalidArgumentException("Graph must contain the source vertex");
        }

        $it = new DijkstraClosestFirstIterator($this->graph, $source, $this->radius, $this->heapSupplier);

        while ($it->hasNext()) {
            $it->next();
        }

        return $it->getPaths();
    }
}
