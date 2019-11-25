<?php

namespace Graphp\Alg\Shortestpath;

use Graphp\GraphInterface;
use Graphp\GraphPathInterface;
use Graphp\GraphUtils;
use Graphp\Vertex\VertexInterface;
use Graphp\Path\GraphWalk;
use Graphp\Alg\Interfaces\SingleSourcePathsInterface;

/**
 * Class ListSingleSourcePaths
 *
 * @package Graphp\Alg\Shortestpath
 */
class ListSingleSourcePaths implements SingleSourcePathsInterface
{
    /**
     * The graph
     *
     * @var GraphInterface
     */
    protected $graph;

    /**
     * The source vertex
     *
     * @var VertexInterface
     */
    protected $source;

    /**
     * One path per vertex
     *
     * @var array
     */
    protected $paths = [];

    /**
     * Construct a new instance
     *
     * @param GraphInterface $graph - the graph
     * @param VertexInterface $source - the source vertex
     * @param array $paths - one path per target vertex
     */
    public function __construct(GraphInterface $graph, VertexInterface $source, array $paths = [])
    {
        $this->graph = $graph;
        $this->source = $source;
        $this->paths = $paths;
    }

    /**
     * Get the graph over which this set of paths is defined
     *
     * @return GraphInterface
     */
    public function getGraph(): GraphInterface
    {
        return $this->graph;
    }

    /**
     * Get the single source vertex
     *
     * @return GraphInterface
     */
    public function getSourceVertex(): VertexInterface
    {
        return $this->source;
    }

    /**
     * Get the weight of the path from the source vertex to the target vertex
     *
     * @param VertexInterface $targetVertex - the target vertex
     *
     * @return float
     */
    public function getWeight(VertexInterface $targetVertex): float
    {
        $id = $targetVertex->getHash();
        if (!array_key_exists($id, $this->paths)) {
            if ($this->source->equals($targetVertex)) {
                return 0.0;
            }
            return INF;
        }
        return $this->paths[$id]->getWeight();
    }

    /**
     * Get the path from the source vertex to the target vertex
     *
     * @param VertexInterface $targetVertex - the target vertex
     *
     * @return null|GraphPathInterface
     */
    public function getPath(VertexInterface $targetVertex): ?GraphPathInterface
    {
        $id = $targetVertex->getHash();
        if (!array_key_exists($id, $this->paths)) {
            if ($this->source->equals($targetVertex)) {
                return GraphWalk::singletonWalk($this->graph, $source, 0.0);
            }
            return null;
        }
        return $this->paths[$id];
    }
}
