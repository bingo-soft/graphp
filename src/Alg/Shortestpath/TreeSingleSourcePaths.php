<?php

namespace Graphp\Alg\Shortestpath;

use Graphp\GraphInterface;
use Graphp\GraphPathInterface;
use Graphp\GraphUtils;
use Graphp\Vertex\VertexInterface;
use Graphp\Path\GraphWalk;
use Graphp\Alg\Interfaces\SingleSourcePathsInterface;

/**
 * Class TreeSingleSourcePaths
 *
 * @package Graphp\Alg\Shortestpath
 */
class TreeSingleSourcePaths implements SingleSourcePathsInterface
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
     * A map which keeps for each target vertex the predecessor edge and the total length of the
     * shortest path
     *
     * @var array
     */
    protected $map;

    /**
     * Construct a new instance
     *
     * @param GraphInterface $graph - the graph
     * @param VertexInterface $source - the source vertex
     * @param array $map - the map
     */
    public function __construct(GraphInterface $graph, VertexInterface $source, array $map)
    {
        $this->graph = $graph;
        $this->source = $source;
        $this->map = $map;
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
    public function getWeight(VertexInterface $targetVertex): ?float
    {
        $id = $targetVertex->getHash();
        if (array_key_exists($id, $this->map)) {
            return $this->map[$id][0];
        } elseif ($this->source->equals($targetVertex)) {
            return 0.0;
        }
        return INF;
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
        if ($this->source->equals($targetVertex)) {
            return GraphWalk::singletonWalk($this->graph, $this->source, 0.0);
        }

        $edgeList = [];

        $cur = $targetVertex;
        
        $id = $cur->getHash();
        if (!array_key_exists($id, $this->map) || $this->map[$id] == INF) {
            return null;
        }
        $p = $this->map[$id];

        $weight = 0.0;
        while (!is_null($p) && !$cur->equals($this->source)) {
            $edge = $p[1];
            if (is_null($edge)) {
                break;
            }
            array_unshift($edgeList, $edge);
            $weight += $this->graph->getEdgeWeight($edge);
            $cur = GraphUtils::getOppositeVertex($this->graph, $edge, $cur);

            $id = $cur->getHash();
            if (!array_key_exists($id, $this->map)) {
                break;
            }
            $p = $this->map[$id];
        }
        return new GraphWalk($this->graph, $this->source, $targetVertex, null, $edgeList, $weight);
    }
}
