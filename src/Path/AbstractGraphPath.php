<?php

namespace Graphp\Path;

use ArrayObject;
use Graphp\GraphUtils;
use Graphp\GraphInterface;
use Graphp\GraphPathInterface;
use Graphp\Vertex\VertexInterface;

/**
 * Class AbstractGraphPath
 *
 * @package Graphp\Path
 */
abstract class AbstractGraphPath implements GraphPathInterface
{
    /**
     * The graph over which the path is defined
     *
     * @var GraphInterface
     */
    protected $graph;

    /**
     * The start vertex in the path
     *
     * @var VertexInterface
     */
    protected $startVertex;

    /**
     * The end vertex in the path
     *
     * @var VertexInterface
     */
    protected $endVertex;

    /**
     * The weight of the path
     *
     * @var float
     */
    protected $weight;

    /**
     * Get the graph over which the path is defined
     *
     * @return GraphInterface
     */
    public function getGraph(): GraphInterface
    {
        return $this->graph;
    }

    /**
     * Get the start vertex in the path
     *
     * @return VertexInterface
     */
    public function getStartVertex(): VertexInterface
    {
        return $this->startVertex;
    }
    
    /**
     * Get the end vertex in the path
     *
     * @return VertexInterface
     */
    public function getEndVertex(): VertexInterface
    {
        return $this->endVertex;
    }

    /**
     * Get the edges making up the path
     *
     * @return array
     */
    public function getEdgeList(): array
    {
        $vertexList = $this->getVertexList();
        if (count($vertexList) < 2) {
            return [];
        }

        $graph = $this->getGraph();
        $edgeList = [];

        $vertexIterator = (new ArrayObject($vertexList))->getIterator();
        $sourceVertex = $vertexIterator->current();
        $vertexIterator->next();
        while ($vertexIterator->valid()) {
            $targetVertex = $vertexIterator->current();
            $edgeList[] = $graph->getEdge($sourceVertex, $targetVertex);
            $sourceVertex = $targetVertex;
            $vertexIterator->next();
            $sourceVertex = $targetVertex;
        }
        return $edgeList;
    }
    
    /**
     * Get the vertices making up the path
     *
     * @return array
     */
    public function getVertexList(): array
    {
        $edgeList = $this->getEdgeList();

        if (empty($edgeList)) {
            $startVertex = $this->getStartVertex();
            if (!is_null($startVertex) && $startVertex->equals($this->getEndVertex())) {
                return [$startVertex];
            }
            return [];
        }

        $graph = $this->getGraph();
        $vertexList = [];
        $vertex = $this->getStartVertex();
        $vertexList[] = $vertex;
        foreach ($edgeList as $edge) {
            $vertex = GraphUtils::getOppositeVertex($graph, $edge, $vertex);
            $vertexList[] = $vertex;
        }
        return $vertexList;
    }

    /**
     * Get the weight of the path, which is typically the sum of the weights of the edges
     *
     * @return float
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * Set the weight of the path
     *
     * @param float $weight - the weight of the path
     */
    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }
    
    /**
     * Get the length of the path, measured in the number of edges
     *
     * @return int
     */
    public function getLength(): int
    {
        return count($this->getEdgeList());
    }
}
