<?php

namespace Graphp\Alg\Shortestpath;

use InvalidArgumentException;
use Graphp\GraphPathInterface;
use Graphp\GraphInterface;
use Graphp\Vertex\VertexInterface;
use Graphp\Path\GraphWalk;
use Graphp\Alg\Interfaces\ShortestPathAlgorithmInterface;
use Graphp\Alg\Interfaces\SingleSourcePathsInterface;

/**
 * Class AbstractShortestPathAlgorithm
 *
 * @package Graphp\Alg\Shortestpath
 */
abstract class AbstractShortestPathAlgorithm implements ShortestPathAlgorithmInterface
{
    /**
     * The graph
     *
     * @var GraphInterface
     */
    protected $graph;

    /**
     * Construct a new instance of the algorithm for the given graph
     *
     * @param GraphInterface $graph - the graph
     */
    public function __construct(GraphInterface $graph)
    {
        $this->graph = $graph;
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
            throw new InvalidArgumentException("graph must contain the source vertex");
        }

        $paths = [];
        $vertices = $this->graph->vertexSet();
        foreach ($vertices as $vertex) {
            $paths[$vertex->getHash()] = $this->getPath($source, $vertex);
        }
        return new ListSingleSourcePaths($this->graph, $source, $paths);
    }

    /**
     * Get the path from the source vertex to the target vertex
     *
     * @param VertexInterface $source - the source vertex
     * @param VertexInterface $sink - the target vertex
     *
     * @return null|GraphPathInterface
     */
    abstract public function getPath(VertexInterface $source, VertexInterface $sink): ?GraphPathInterface;

    /**
     * Get the weight of the path from the source vertex to the target vertex.
     *
     * @param VertexInterface $sourceVertex - the source vertex
     * @param VertexInterface $targetVertex - the target vertex
     *
     * @return float
     */
    public function getPathWeight(VertexInterface $source, VertexInterface $sink): float
    {
        $p = $this->getPath($source, $sink);
        if (is_null($p)) {
            return INF;
        }
        return $p->getWeight();
    }

    /**
     * Create emppty path from the source vertex to the target vertex.
     *
     * @param VertexInterface $sourceVertex - the source vertex
     * @param VertexInterface $targetVertex - the target vertex
     *
     * @return null|GraphPathInterface
     */
    protected function createEmptyPath(VertexInterface $source, VertexInterface $sink): ?GraphPathInterface
    {
        if ($source->equals($sink)) {
            return GraphWalk::singletonWalk($this->graph, $source, 0.0);
        }
        return null;
    }
}
